<?php

namespace App\Exports;

use App\Models\MtopApplication;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use phpDocumentor\Reflection\Types\Collection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MTOPRenewalExport implements FromCollection, WithHeadings, WithColumnFormatting, WithStyles
{

    public function __construct($mtop_applications)
    {
        $this->mtop_applications = $mtop_applications;
    }

    public function collection() {
        return $this->mtop_applications;
    }


    public function headings(): array
    {
        return [
            ['LIST OF FOR RENEWALS'],
            ['Application Id','MTFRB #','Application Date','Body Number','Make Type','Engine Motor #','Chassis #','Plate #','Operator','Address', 'Barangay', 'Date Issued', 'Valid Until', 'OR #', 'Amount'],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2:N2')->getFont()->setBold(true);
        $total_num_rows = count($this->mtop_applications) + 3;
        $total_num_rows_test = $total_num_rows - 1;
        $sheet->setCellValue('A' . $total_num_rows, 'Total:');
        $sheet->setCellValue('O' . $total_num_rows, '=SUM(O3:O' . $total_num_rows_test. ')');
    }


}
