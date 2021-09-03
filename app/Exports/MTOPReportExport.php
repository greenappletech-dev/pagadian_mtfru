<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MTOPReportExport implements FromView, WithStyles, WithColumnFormatting
{

    private $array;
    private $type;
    private $from;
    private $to;

    public function __construct($array, $type, $from, $to)
    {
        $this->array = $array;
        $this->type = $type;
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {

        if($this->type == 2)
        {
            return view('excel.xls_old_new', ['array' => $this->array]);
        }

        return view('excel.mtop_detailed_report', ['array' => $this->array, 'from' => $this->from, 'to' => $this->to]);
    }

    public function styles(Worksheet $sheet)
    {
        if($this->type == 1)
        {

            $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)->setAutoSize('true');

            }

            $sheet->getStyle('A1:G1')->getFont()->setBold(true);
            $sheet->getStyle('A2:G2')->getFont()->setBold(true);
            $sheet->getStyle('A:F')->getAlignment()->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));
            $sheet->getStyle('G')->getAlignment()->applyFromArray(array('horizontal' => 'right', 'vertical' => 'center'));
        }


        if($this->type == 2)
        {
            $cells = ['A', 'B', 'C', 'D'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)->setAutoSize('true');

            }

            $sheet->getStyle('A1:G1')->getFont()->setBold(true);
            $sheet->getStyle('A:G')->getAlignment()->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));
            $sheet->getStyle('A')->getAlignment()->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));
        }

    }

    public function columnFormats(): array
    {
        if($this->type == 1)
        {
            return [
                'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ];
        }

    }

}
