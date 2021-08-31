<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FVRRenewalExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($fvr_applications)
    {
        $this->fvr_applications = $fvr_applications;
    }

    public function collection() {
        return $this->fvr_applications;
    }


    public function headings(): array
    {
        return [
            ['LIST OF FOR RENEWALS'],
            ['Application Date','Barangay','Operator','Body Number','Boat Description','Issue Date','Validity Date','Approve Date','OR #','Total'],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G','H', 'I', 'J'];

        foreach($cells as $cell) {
            $sheet->getColumnDimension($cell)->setAutoSize('true');
        }

        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2:J2')->getFont()->setBold(true);
        $total_num_rows = count($this->fvr_applications) + 3;
        $total_num_rows_test = $total_num_rows - 1;
        $sheet->setCellValue('A' . $total_num_rows, 'Total:');
        $sheet->setCellValue('J' . $total_num_rows, '=SUM(J3:J' . $total_num_rows_test. ')');
    }
}
