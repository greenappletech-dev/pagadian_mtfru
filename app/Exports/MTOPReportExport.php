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
    private $barangay;

    public function __construct($array, $type, $from, $to, $barangay)
    {
        $this->array = $array;
        $this->type = $type;
        $this->from = $from;
        $this->to = $to;
        $this->barangay = $barangay == null ? null : $barangay->brgy_desc;
    }

    public function view(): View
    {

        if($this->type == 1)
        {
            return view('excel.xls_mtop_detailed_report',
            [
                'array' => $this->array,
                'from' => $this->from,
                'to' => $this->to,
                'barangay' => $this->barangay
            ]);
        }

        if($this->type == 2)
        {
            return view('excel.xls_old_new',
            [
                'array' => $this->array
            ]);
        }

        if($this->type == 3)
        {
            return view('excel.xls_mtop_summary_new_franchise',
            [
                'array' => $this->array,
                'from' => $this->from,
                'to' => $this->to,
                'barangay' => $this->barangay
            ]);
        }

        if($this->type == 4)
        {
            return view('excel.xls_mtop_new_franchise',
            [
                'array' => $this->array,
                'from' => $this->from,
                'to' => $this->to,
                'barangay' => $this->barangay
            ]);
        }

        if($this->type == 5)
        {
            return view('excel.xls_mtop_accomplishment_report',
            [
                'array' => $this->array,
                'from' => $this->from,
                'to' => $this->to,
            ]);
        }

    }

    public function styles(Worksheet $sheet)
    {
        if($this->type == 1)
        {

            $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)
                    ->setAutoSize('true');

            }

            $sheet->getStyle('A1:G1')
            ->getFont()
            ->setBold(true);

            $sheet->getStyle('A2:G2')
            ->getFont()
            ->setBold(true);

            $sheet->getStyle('A:F')
            ->getAlignment()
            ->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));

            $sheet->getStyle('G')
            ->getAlignment()
            ->applyFromArray(array('horizontal' => 'right', 'vertical' => 'center'));
        }

        if($this->type == 2)
        {
            $cells = ['A', 'B', 'C', 'D'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)
                ->setAutoSize('true');

            }

            $sheet->getStyle('A1:G1')
            ->getFont()
            ->setBold(true);

            $sheet->getStyle('A:G')
            ->getAlignment()
            ->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));

            $sheet->getStyle('A')
            ->getAlignment()
            ->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));
        }

        if($this->type == 3)
        {
            $cells = ['A', 'B', 'C', 'D', 'E'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)
                ->setAutoSize('true');

            }

            $sheet->getStyle('A1:E1')
            ->getFont()
            ->setBold(true);

            $sheet->getStyle('A2:E2')
            ->getFont()
            ->setBold(true);

            $sheet->getStyle('A:E')
            ->getAlignment()
            ->applyFromArray(array('horizontal' => 'center', 'vertical' => 'center'));

            $sheet->getStyle('A')
            ->getAlignment()
            ->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));
        }

        if($this->type == 4)
        {
            $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

            foreach($cells as $cell)
            {
                $sheet->getColumnDimension($cell)
                ->setAutoSize('true');
            }

            $sheet->getStyle('A1:J1')
            ->getFont()
            ->setBold(true);

            $sheet->getStyle('A2:J2')
            ->getFont()
            ->setBold(true);
        }

        if($this->type == 5)
        {

            $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

            foreach($cells as $cell)
            {

                $sheet->getColumnDimension($cell)
                    ->setAutoSize('true');

            }

            $sheet->getStyle('A1:G1')
                ->getFont()
                ->setBold(true);

            $sheet->getStyle('A2:G2')
                ->getFont()
                ->setBold(true);

            $sheet->getStyle('A:F')
                ->getAlignment()
                ->applyFromArray(array('horizontal' => 'left', 'vertical' => 'center'));

            $sheet->getStyle('G')
                ->getAlignment()
                ->applyFromArray(array('horizontal' => 'right', 'vertical' => 'center'));
        }
    }

    public function columnFormats(): array
    {
        $arr = [2, 3, 4, 5];

        if($this->type == 1)
        {
            return [
                'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            ];
        }

        if(in_array($this->type, $arr))
        {
            return [];
        }

    }

}
