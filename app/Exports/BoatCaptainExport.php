<?php

namespace App\Exports;

use App\Models\BoatCaptain;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BoatCaptainExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {
        $this->captain = new BoatCaptain();
    }

    public function collection()
    {
        return $this->captain->fetchDataToExport();
    }
    public function headings(): array
    {
        return [
            'Body Number',
            'Banca Name',
            'Operator',
            'Boat Captain',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $cells = ['A', 'B', 'C', 'D'];

        foreach($cells as $cell) {
            $sheet->getColumnDimension($cell)->setAutoSize('true');
        }

        $sheet->getStyle('A1:D1')->getAlignment()->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));
    }
}
