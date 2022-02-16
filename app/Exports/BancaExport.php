<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BancaExport implements FromArray, WithHeadings, WithStyles
{

    private $export;

    public function __construct(array $bancas)
    {
        $this->export = $bancas;
    }


    public function headings(): array
    {
        return [
            'Operator',
            'Body Number',
            'Boat Name',
            'Boat Color',
            'Manning Crew',
            'Fishing Gear',
            'Boat Type',
            'Length',
            'Width',
            'Depth',
            'Gross Tonnage',
            'Net Tonnage',
            'Make/Type',
            'Engine Serial No',
            'Horsepower',
            'Cylinder'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $cells = ['A','B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];

        foreach($cells as $cell) {
            $sheet->getColumnDimension($cell)->setAutoSize('true');
        }

        $sheet->getStyle('A1:P1')->getAlignment()->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));
    }
}
