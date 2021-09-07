<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChargesExport implements FromView, WithStyles
{

    private $data;

    public function __construct($data)
    {
       $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.xls_mtop_charges', ['data' => $this->data]);
    }

    public function styles(Worksheet $sheet) {
        $cells = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach($cells as $cell)
        {
            $sheet->getColumnDimension($cell)->setAutoSize('true');
        }

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
    }
}
