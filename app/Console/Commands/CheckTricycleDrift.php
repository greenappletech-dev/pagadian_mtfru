<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * The tricycle master is only updated by MtopApplicationController@approve.
 * Anything that sets an application to approved by another route - a direct
 * database edit, an import - leaves the master holding the previous unit,
 * which then blocks that engine or chassis from ever being registered again.
 *
 * This command finds those records. It only reads.
 */
class CheckTricycleDrift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mtop:check-drift
                            {--only= : Limit the listing to "real" or "typo"}
                            {--csv= : Also write the full listing to this file}
                            {--quiet-if-clean : Print nothing when there is no drift}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report tricycle records whose engine or chassis no longer matches their latest approved application';

    /**
     * A serial this close to the one on the application is a keying mistake
     * rather than a different unit, and must be checked against the papers.
     */
    private const TYPO_DISTANCE = 2;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rows = $this->fetchDrift();
        $quiet = $this->option('quiet-if-clean');

        if (empty($rows)) {
            if (!$quiet) {
                $this->info('No drift found. Every tricycle master matches its latest approved application.');
                $this->reportBypass();
            }
            return 0;
        }

        $rows = $this->classify($rows);

        $real = array_filter($rows, function ($r) { return $r['kind'] === 'real'; });
        $typo = array_filter($rows, function ($r) { return $r['kind'] === 'typo'; });
        $clashing = array_filter($rows, function ($r) { return $r['collides']; });

        $this->warn(sprintf('%d tricycle record(s) out of sync with their latest approved application.', count($rows)));
        $this->line(sprintf('  %d look like a real change of unit', count($real)));
        $this->line(sprintf('  %d differ by %d character(s) or less - likely a keying mistake, check the papers',
            count($typo), self::TYPO_DISTANCE));

        if (count($clashing)) {
            $this->error(sprintf('  %d cannot be corrected automatically: another tricycle already holds the incoming serial', count($clashing)));
        }

        $this->newLine();
        $this->table(
            ['Tricycle', 'Body', 'Kind', 'Master engine', 'Application engine', 'App', 'Date', 'Approved', 'Clash', 'Operator'],
            array_map(function ($r) {
                return [
                    $r['tricycle_id'], $r['body_number'], strtoupper($r['kind']),
                    $r['master_engine'], $r['app_engine'],
                    $r['app_id'], $r['transact_date'],
                    $r['approve_date'] ?: 'MISSING',
                    $r['collides'] ? 'YES' : '',
                    mb_substr((string) $r['operator_name'], 0, 26),
                ];
            }, $this->filtered($rows))
        );

        if ($path = $this->option('csv')) {
            $this->writeCsv($path, $rows);
            $this->info('Full listing written to ' . $path);
        }

        $this->newLine();
        $this->reportBypass();
        $this->reportPaidWithoutApproval();
        $this->reportDuplicates();

        return 1;
    }

    /**
     * Tricycles whose master engine differs from the newest approved
     * application that changed the unit.
     */
    private function fetchDrift()
    {
        return DB::select("
            with latest as (
                select distinct on (m.tricycle_id)
                       m.tricycle_id, m.id as app_id, m.transact_date, m.transact_type,
                       m.engine_motor_no, m.chassis_no, m.approve_date, m.taxpayer_id
                from mtop_applications m
                where m.transact_type like '%3%'
                  and m.status = 4
                  and m.engine_motor_no is not null
                  and trim(m.engine_motor_no) <> ''
                order by m.tricycle_id, m.transact_date desc, m.id desc
            )
            select t.id as tricycle_id, t.body_number,
                   t.engine_motor_no as master_engine, t.chassis_no as master_chassis,
                   l.app_id, l.transact_date, l.transact_type, l.approve_date,
                   l.engine_motor_no as app_engine, l.chassis_no as app_chassis,
                   tp.full_name as operator_name
            from latest l
            join tricycles t on t.id = l.tricycle_id
            left join taxpayer tp on tp.id = l.taxpayer_id
            where upper(trim(l.engine_motor_no)) <> upper(trim(t.engine_motor_no))
            order by l.transact_date desc
        ");
    }

    /**
     * Tag each row as a real change of unit or a probable typo, and flag any
     * whose incoming serial is already held by a different tricycle.
     */
    private function classify($rows)
    {
        $out = [];

        foreach ($rows as $row) {
            $row = (array) $row;

            $distance = levenshtein(
                strtoupper(trim($row['master_engine'])),
                strtoupper(trim($row['app_engine']))
            );

            $row['kind'] = $distance <= self::TYPO_DISTANCE ? 'typo' : 'real';
            $row['distance'] = $distance;
            $row['collides'] = DB::table('tricycles')
                ->where('id', '<>', $row['tricycle_id'])
                ->whereRaw('upper(trim(engine_motor_no)) = ?', [strtoupper(trim($row['app_engine']))])
                ->exists();

            $out[] = $row;
        }

        return $out;
    }

    private function filtered($rows)
    {
        $only = $this->option('only');

        if (!$only) {
            return $rows;
        }

        return array_filter($rows, function ($r) use ($only) {
            return $r['kind'] === strtolower($only);
        });
    }

    /**
     * An approved application with no approval date did not come through the
     * Approve button, which is the only thing that updates the master.
     */
    private function reportBypass()
    {
        $missing = DB::table('mtop_applications')
            ->where('status', 4)->whereNull('approve_date')->count();

        if (!$missing) {
            return;
        }

        $total = DB::table('mtop_applications')->where('status', 4)->count();

        $this->warn(sprintf(
            '%d of %d approved applications have no approval date, so they did not pass through the Approve button.',
            $missing, $total
        ));
        $this->line('  These are where future drift comes from. Records approved outside the system never update the tricycle master.');
    }

    /**
     * Applications the cashier has already collected on but which were never
     * approved. This is drift before it happens: the moment one of these is
     * marked approved by hand, the master is left holding the previous unit.
     */
    private function reportPaidWithoutApproval()
    {
        $unapproved = DB::select("
            select m.id, m.body_number, m.transact_date, m.transact_type, m.status
            from mtop_applications m
            join colhdr c on c.mtop_application_id = m.id
            where c.canc_date is null and m.status <> 4
            group by m.id, m.body_number, m.transact_date, m.transact_type, m.status
            order by m.transact_date desc
        ");

        if (empty($unapproved)) {
            return;
        }

        $changeUnit = array_filter($unapproved, function ($a) {
            return strpos((string) $a->transact_type, '3') !== false;
        });

        $this->newLine();
        $this->warn(sprintf('%d application(s) have been paid but never approved.', count($unapproved)));

        if (count($changeUnit)) {
            $this->error(sprintf('  %d of them are change units and will drift the master if approved by hand:', count($changeUnit)));

            $this->table(
                ['App', 'Body', 'Date', 'Type', 'Status'],
                array_map(function ($a) {
                    return [$a->id, $a->body_number, $a->transact_date, $a->transact_type, $a->status];
                }, $changeUnit)
            );
        }
    }

    /**
     * Serials held by more than one tricycle. These block re-registration and
     * must be resolved before a unique index can be added.
     */
    private function reportDuplicates()
    {
        foreach (['engine_motor_no', 'chassis_no', 'plate_no'] as $column) {
            $groups = DB::select("
                select {$column} as value, count(*) as total
                from tricycles
                where {$column} is not null and trim({$column}) <> ''
                group by {$column}
                having count(*) > 1
            ");

            foreach ($groups as $group) {
                $ids = DB::table('tricycles')->where($column, $group->value)
                    ->pluck('body_number')->implode(', ');

                $this->warn(sprintf('Duplicate %s "%s" held by %d tricycles (body numbers: %s)',
                    $column, $group->value, $group->total, $ids));
            }
        }
    }

    private function writeCsv($path, $rows)
    {
        $handle = fopen($path, 'w');

        fputcsv($handle, [
            'tricycle_id', 'body_number', 'kind', 'distance',
            'master_engine', 'app_engine', 'master_chassis', 'app_chassis',
            'app_id', 'transact_date', 'transact_type', 'approve_date',
            'collides', 'operator_name',
        ]);

        foreach ($rows as $r) {
            fputcsv($handle, [
                $r['tricycle_id'], $r['body_number'], $r['kind'], $r['distance'],
                $r['master_engine'], $r['app_engine'], $r['master_chassis'], $r['app_chassis'],
                $r['app_id'], $r['transact_date'], $r['transact_type'], $r['approve_date'],
                $r['collides'] ? 'yes' : 'no', $r['operator_name'],
            ]);
        }

        fclose($handle);
    }
}
