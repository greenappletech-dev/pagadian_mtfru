<?php

namespace App\Exports;

use App\Models\Driver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class DriverExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $driver = new Driver();
        return $driver->fetchDataForReport();
    }

    public function headings(): array
    {
        return [
            'Body Number',
            'Drivers License No',
            'Drivers Name',
            'Address',
            'Operators Name',
            'Make Type',
            'Engine Motor No',
            'Chassis No',
            'Plate No',
            'Date Added',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $cells = ['A','B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach($cells as $cell) {
            $sheet->getColumnDimension($cell)->setAutoSize('true');
        }
    }

}
