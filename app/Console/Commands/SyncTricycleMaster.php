<?php

namespace App\Console\Commands;

use App\Http\Controllers\MtopApplicationController;
use App\Models\Tricycle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Repairs tricycle masters left behind by an approval that never went through
 * the Approve button. Reports on the same records as mtop:check-drift.
 *
 * The actual write goes through MtopApplicationController@updateTricycleDetails
 * rather than a copy of it, so a record repaired here ends up in exactly the
 * state a proper approval would have produced, including its history row.
 *
 * The approval date is deliberately left alone. Stamping today's date on a
 * transaction from a previous year would replace one wrong record with another.
 */
class SyncTricycleMaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mtop:sync-master
                            {--tricycle=* : Repair only these tricycle ids}
                            {--include-typo : Also repair records that differ by a character or two}
                            {--apply : Write the changes. Without this the command only reports}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bring tricycle masters back in line with their latest approved application';

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
        $rows = $this->candidates();

        if (empty($rows)) {
            $this->info('Nothing to repair.');
            return 0;
        }

        $apply = $this->option('apply');

        $this->line($apply
            ? 'Applying repairs.'
            : 'Dry run. Nothing will be written. Add --apply to commit these changes.');
        $this->newLine();

        $repaired = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            $reason = $this->skipReason($row);

            if ($reason) {
                $this->line(sprintf('  <fg=yellow>skip</> tri %-6s body %-8s %s', $row->tricycle_id, $row->body_number, $reason));
                $skipped++;
                continue;
            }

            $this->line(sprintf('  <fg=green>%s</> tri %-6s body %-8s %s -> %s   (app #%s)',
                $apply ? 'sync' : 'would', $row->tricycle_id, $row->body_number,
                $row->master_engine, $row->app_engine, $row->app_id));

            if ($apply) {
                $this->repair($row);
            }

            $repaired++;
        }

        $this->newLine();
        $this->info(sprintf('%s %d record(s), skipped %d.',
            $apply ? 'Repaired' : 'Would repair', $repaired, $skipped));

        if (!$apply && $repaired) {
            $this->line('Re-run with --apply once the values above have been confirmed against the papers.');
        }

        return 0;
    }

    /**
     * Why this record cannot be repaired automatically, or null if it can.
     */
    private function skipReason($row)
    {
        $distance = levenshtein(
            strtoupper(trim($row->master_engine)),
            strtoupper(trim($row->app_engine))
        );

        if ($distance <= self::TYPO_DISTANCE && !$this->option('include-typo')) {
            return sprintf('differs by %d character(s) - check the papers, then use --include-typo', $distance);
        }

        $held = Tricycle::where('id', '<>', $row->tricycle_id)
            ->whereRaw('upper(trim(engine_motor_no)) = ?', [strtoupper(trim($row->app_engine))])
            ->value('body_number');

        if ($held) {
            return sprintf('engine %s is already held by body %s', $row->app_engine, $held);
        }

        return null;
    }

    /**
     * Route the write through the same method an approval uses, so the record
     * and its history row come out identical to a properly approved one.
     */
    private function repair($row)
    {
        DB::transaction(function () use ($row) {
            (new MtopApplicationController())->updateTricycleDetails([
                'tricycle_id'     => $row->tricycle_id,
                'operator_id'     => $row->taxpayer_id,
                'make_type'       => $row->app_make_type,
                'engine_motor_no' => $row->app_engine,
                'chassis_no'      => $row->app_chassis,
                'plate_no'        => $row->app_plate_no,
            ], $row->app_id);

            /* the master should also point at the application it now reflects */
            Tricycle::where('id', $row->tricycle_id)
                ->update(['mtop_application_id' => $row->app_id]);
        });
    }

    private function candidates()
    {
        $ids = $this->option('tricycle');

        $rows = DB::select("
            with latest as (
                select distinct on (m.tricycle_id)
                       m.tricycle_id, m.id as app_id, m.transact_date, m.transact_type, m.taxpayer_id,
                       m.engine_motor_no, m.chassis_no, m.plate_no, m.make_type
                from mtop_applications m
                where m.transact_type like '%3%'
                  and m.status = 4
                  and m.engine_motor_no is not null
                  and trim(m.engine_motor_no) <> ''
                order by m.tricycle_id, m.transact_date desc, m.id desc
            )
            select t.id as tricycle_id, t.body_number,
                   t.engine_motor_no as master_engine,
                   l.app_id, l.transact_date, l.taxpayer_id,
                   l.engine_motor_no as app_engine, l.chassis_no as app_chassis,
                   l.plate_no as app_plate_no, l.make_type as app_make_type
            from latest l
            join tricycles t on t.id = l.tricycle_id
            where upper(trim(l.engine_motor_no)) <> upper(trim(t.engine_motor_no))
            order by l.transact_date desc
        ");

        if (empty($ids)) {
            return $rows;
        }

        return array_values(array_filter($rows, function ($r) use ($ids) {
            return in_array((string) $r->tricycle_id, $ids, true);
        }));
    }
}
