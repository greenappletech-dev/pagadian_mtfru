<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MTFRUReportController extends Controller
{
    public function old_new() {
        return view('report.old_new');
    }

}
