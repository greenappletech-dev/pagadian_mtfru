<?php

namespace App\Http\Controllers;

use App\Exports\MTOPRenewalExport;
use App\Models\Barangay;
use App\Models\Charge;
use App\Models\MtopApplication;
use App\Models\MtopApplicationCharge;
use App\Models\Taxpayer;
use App\Models\Tricycle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;

class MtopViewRenewalController extends Controller
{

    public function __construct()
    {
        $this->mtop_applications = new MtopApplication();
        $this->tricycles = new Tricycle();
        $this->barangays = new Barangay();
        $this->operators = new Taxpayer();
        $this->charges = new Charge();
        $this->mtop_applications_charge = new MtopApplicationCharge();
    }

    public function index() {
        return view('mtfru.mtop_view_renewal');
    }

    public function getdata() {
        $barangays = $this->barangays->fetchData();
        return response()->json(['barangays' => $barangays],200);
    }

    public function filter($from, $to, $barangay_id) {
        $mtop_applications = $this->mtop_applications->fetchFilteredDataRenewal($from, $to, $barangay_id);
        return response()->json(['mtop_applications' => $mtop_applications]);
    }

    public function export($from, $to, $barangay_id) {
        $mtop_applications = $this->mtop_applications->fetchFilteredDataRenewal($from, $to, $barangay_id);
        $excel_export = new MTOPRenewalExport($mtop_applications);
        return Excel::download($excel_export, 'list_for_renewals_from_' . $from . '_to_' . $to . '.xlsx');
    }

}
