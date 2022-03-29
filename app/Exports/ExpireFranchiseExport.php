<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExpireFranchiseExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
//        dd($data);
    }


    public function view(): View
    {
        $expired = $this->data;
        return view('excel.xls_expired_franchise', compact('expired'));
    }
}
