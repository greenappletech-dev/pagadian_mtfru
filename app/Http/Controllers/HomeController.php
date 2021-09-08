<?php

namespace App\Http\Controllers;

use App\Exports\SampleExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Tricycle;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use Excel;

class HomeController extends Controller
{

    public function index() {
        return view('home');
    }

}
