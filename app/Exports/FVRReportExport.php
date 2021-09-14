<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FVRReportExport implements FromView, WithStyles, WithColumnFormatting
{

    private $report;
    private $from;
    private $to;
    private $barangay;

   public function __construct($report, $from, $to, $barangay)
   {
       $this->report = $report;
       $this->from = $from;
       $this->to = $to;
       $this->barangay = $barangay == null ? null : $barangay->brgy_desc;
   }

   public function view(): View
   {
       return view('excel.xls_fvr_collection_report', [
           'generated_report' => $this->report,
           'from' => $this->from,
           'to' => $this->to,
           'barangay' => $this->barangay
       ]);
   }

    public function styles(Worksheet $sheet)
    {
        $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        foreach($cells as $cell)
        {

            $sheet->getColumnDimension($cell)
                ->setAutoSize('true');

        }

        $sheet->getStyle('A1:K1')
            ->getFont()
            ->setBold(true);

        $sheet->getStyle('A2:K2')
            ->getFont()
            ->setBold(true);

    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }



}
