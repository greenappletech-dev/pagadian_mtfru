<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Tagging an OR used to send the line item's id where the OR's id was
 * expected, so it could write to a different operator's collection row while
 * still reporting success. This looks for collections that may have been
 * attached to the wrong application, by that fault or by hand.
 *
 * Nothing here is proof on its own. A relative or an association officer
 * paying on someone's behalf looks the same as a mis-tag from the data, so
 * treat the output as a list to check against the receipts.
 */
class CheckOrTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mtop:check-or-tags
                            {--since= : Only look at collections from this date onward (YYYY-MM-DD)}
                            {--csv= : Write the full listing to this file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Look for ORs that may be tagged to the wrong MTOP application';

    /** Words that carry no identifying signal. */
    private const NOISE = ['JR', 'SR', 'III', 'II', 'IV', 'MR', 'MRS', 'MS', 'DE', 'DELA', 'DEL', 'LA', 'DELOS'];

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
        $since = $this->option('since');

        $rows = $this->taggedCollections($since);
        $this->line(sprintf('Examined %d tagged collection(s)%s.',
            count($rows), $since ? ' from ' . $since : ''));

        $unrelated = $this->unrelatedNames($rows);
        $early = $this->paidBeforeFiled($since);
        $multiple = $this->severalPerApplication($since);

        $this->newLine();

        if ($unrelated) {
            $this->warn(sprintf('%d collection(s) share no name with the operator on the application.', count($unrelated)));
            $this->line('  A representative paying looks the same as a mis-tag, so check these against the receipts.');
            $this->newLine();
            $this->table(
                ['App', 'Body', 'OR', 'Paid', 'Payer on the OR', 'Operator on the application'],
                array_map(function ($r) {
                    return [$r->app_id, $r->body_number, $r->or_number, $r->trnx_date,
                        mb_substr($r->or_name, 0, 30), mb_substr($r->operator_name, 0, 30)];
                }, $unrelated)
            );
        } else {
            $this->info('Every tagged collection shares a name with its operator.');
        }

        if ($early) {
            $this->newLine();
            $this->warn(sprintf('%d collection(s) are dated before the application was filed.', count($early)));
            $this->line('  Usually a back-dated application rather than a mis-tag, but a large gap is worth a look.');
            $this->table(
                ['App', 'Body', 'OR', 'Paid', 'Application filed', 'Days earlier'],
                array_map(function ($r) {
                    return [$r->app_id, $r->body_number, $r->or_number, $r->trnx_date, $r->transact_date, $r->days_early];
                }, array_slice($early, 0, 20))
            );

            if (count($early) > 20) {
                $this->line(sprintf('  ...and %d more. Use --csv to see them all.', count($early) - 20));
            }
        }

        if ($multiple) {
            $this->newLine();
            $this->warn(sprintf('%d application(s) carry more than one live OR.', count($multiple)));
            $this->table(['App', 'ORs', 'Numbers'], array_map(function ($r) {
                return [$r->mtop_application_id, $r->total, $r->numbers];
            }, $multiple));
        }

        if ($path = $this->option('csv')) {
            $this->writeCsv($path, $unrelated, $early);
            $this->info('Listing written to ' . $path);
        }

        return ($unrelated || $early || $multiple) ? 1 : 0;
    }

    private function taggedCollections($since)
    {
        $query = DB::table('colhdr as c')
            ->join('mtop_applications as m', 'm.id', 'c.mtop_application_id')
            ->leftJoin('taxpayer as tp', 'tp.id', 'm.taxpayer_id')
            ->where('c.mtop_application_id', '>', 0)
            ->whereNull('c.canc_date')
            ->select('c.id', 'c.or_number', 'c.full_name as or_name', 'c.trnx_date',
                'm.id as app_id', 'm.transact_date', 'm.body_number', 'tp.full_name as operator_name');

        if ($since) {
            $query->whereDate('c.trnx_date', '>=', $since);
        }

        return $query->get()->all();
    }

    /**
     * Names are written surname first in one place and given name first in the
     * other, and the payer often carries the body number, so compare the set of
     * words rather than the string.
     */
    private function nameWords($name)
    {
        $name = strtoupper((string) $name);
        $name = preg_replace('/[0-9]+/', ' ', $name);
        $name = preg_replace('/[^A-ZÑ ]/u', ' ', $name);

        $words = preg_split('/\s+/', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_filter($words, function ($w) {
            return mb_strlen($w) > 1 && !in_array($w, self::NOISE, true);
        }));
    }

    private function unrelatedNames($rows)
    {
        $out = [];

        foreach ($rows as $row) {
            $payer = $this->nameWords($row->or_name);
            $operator = $this->nameWords($row->operator_name);

            if (!$payer || !$operator) {
                continue;
            }

            if (empty(array_intersect($payer, $operator))) {
                $out[] = $row;
            }
        }

        return $out;
    }

    private function paidBeforeFiled($since)
    {
        $query = DB::table('colhdr as c')
            ->join('mtop_applications as m', 'm.id', 'c.mtop_application_id')
            ->where('c.mtop_application_id', '>', 0)
            ->whereNull('c.canc_date')
            ->whereColumn('c.trnx_date', '<', 'm.transact_date')
            ->select('c.or_number', 'c.trnx_date', 'm.id as app_id', 'm.body_number', 'm.transact_date',
                DB::raw('(m.transact_date - c.trnx_date) as days_early'))
            ->orderByDesc(DB::raw('(m.transact_date - c.trnx_date)'));

        if ($since) {
            $query->whereDate('c.trnx_date', '>=', $since);
        }

        return $query->get()->all();
    }

    private function severalPerApplication($since)
    {
        $query = DB::table('colhdr')
            ->where('mtop_application_id', '>', 0)
            ->whereNull('canc_date')
            ->groupBy('mtop_application_id')
            ->havingRaw('count(*) > 1')
            ->select('mtop_application_id',
                DB::raw('count(*) as total'),
                DB::raw("string_agg(or_number, ', ') as numbers"));

        if ($since) {
            $query->whereDate('trnx_date', '>=', $since);
        }

        return $query->get()->all();
    }

    private function writeCsv($path, $unrelated, $early)
    {
        $handle = fopen($path, 'w');
        fputcsv($handle, ['signal', 'app_id', 'body_number', 'or_number', 'trnx_date', 'transact_date', 'or_payer', 'operator']);

        foreach ($unrelated as $r) {
            fputcsv($handle, ['unrelated_name', $r->app_id, $r->body_number, $r->or_number,
                $r->trnx_date, $r->transact_date, $r->or_name, $r->operator_name]);
        }

        foreach ($early as $r) {
            fputcsv($handle, ['paid_before_filed', $r->app_id, $r->body_number, $r->or_number,
                $r->trnx_date, $r->transact_date, '', '']);
        }

        fclose($handle);
    }
}
