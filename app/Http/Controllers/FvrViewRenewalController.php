<?php

namespace App\Http\Controllers;

use App\Exports\FVRRenewalExport;
use App\Models\Barangay;
use App\Models\FvrApplication;
use Illuminate\Http\Request;
use Excel;

class FvrViewRenewalController extends Controller
{
    /**
     * @var Barangay
     */
    private $barangays;
    private $fvr_application;

    public function __construct()
    {
        $this->barangays = new Barangay();
        $this->fvr_application = new FvrApplication();
    }

    public function index() {
        return view('fvr.fvr_view_renewal');
    }

    public function getdata() {
        $barangays = $this->barangays->fetchData();
        return response()->json(['barangays' => $barangays],200);
    }

    public function filter($from, $to, $barangay_id) {
        $fvr_applications = $this->fvr_application->fetchFilteredDataRenewal($from, $to, $barangay_id);
        return response()->json(['fvr_applications' => $fvr_applications]);
    }

    public function export($from, $to, $barangay_id) {
        $fvr_applications = $this->fvr_application->fetchFilteredToExport($from, $to, $barangay_id);
        $export = new FVRRenewalExport($fvr_applications);
        return Excel::download($export, 'banca_renewals_' . date('mdy') . '.xlsx');
    }

}
