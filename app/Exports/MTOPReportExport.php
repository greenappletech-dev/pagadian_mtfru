<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MTOPReportExport implements FromView, WithStyles
{

    private $array;
    private $type;

    public function __construct($array, $type)
    {
        $this->array = $array;
        $this->type = $type;
    }

    public function view(): View
    {

        if($this->type == 2)
        {
            return view('excel.xls_old_new', ['array' => $this->array]);
        }

        return view('excel.mtop_detailed_report', ['array' => $this->array]);
    }

    public function styles(Worksheet $sheet)
    {
        if($this->type == 1)
        {

            $cells = ['A', 'B', 'C', 'D', 'E'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)->setAutoSize('true');

            }

            $sheet->getStyle('A1:E1')->getFont()->setBold(true);
            $sheet->getStyle('A:E')->getAlignment()->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));
        }


        if($this->type == 2)
        {
            $cells = ['A', 'B', 'C', 'D'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)->setAutoSize('true');

            }

            $sheet->getStyle('A1:D1')->getFont()->setBold(true);
            $sheet->getStyle('A:D')->getAlignment()->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));
            $sheet->getStyle('A')->getAlignment()->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));
        }

    }

}
