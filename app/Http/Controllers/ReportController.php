<?php

namespace App\Http\Controllers;

use App\Exports\MasterListExport;
use App\Models\MtopApplication;
use App\Models\MtopApplicationCharge;
use App\Models\Tricycle;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Excel;

class ReportController extends Controller
{

    private $mtop_applications;

    public function __construct()
    {
        $this->mtop_applications = new MtopApplication();
    }

    public function master_list() {
        return view('report.master_list');
    }

    public function master_list_getdata() {

        /*
            Create a array
            to iterate body numbers from
            1 to the last latest body number
            based on the system parameters
        */
        $body_numbers = array();
        $applications = array();

        $system_parameters = DB::table('m99')->select('body_number_to')->first();

        $start_number = 1;

        /* get length of the string to count the zeroes will be added at the beginning of the number when it is less that the length of the current setting */
        $get_fix_length = strlen($system_parameters->body_number_to);

        /* incase of number has 0 on the beginning */
        $last_number_as_int = (int)$system_parameters->body_number_to;

        /* loop the number so we can get the iteration per number. so we can get the record one by one and insert it on a array */

        for($i = $start_number; $i <= $last_number_as_int; $i++) {

            /* get the length of the $i so we can put how many zeros is needed to matched the current settings */
            $get_length = strlen($i);
            $zeros = '';

            for($length = $get_length; $length < $get_fix_length; $length++) {
                $zeros = $zeros . '0';
            }

            /* we get the exact the format of the body number that will be matched to the database */
            $body_number = $zeros . $i;

            $mtop_application = Tricycle::leftJoin('mtop_applications', 'mtop_applications.id', 'tricycles.mtop_application_id')
                ->leftJoin('taxpayer', 'taxpayer.id', 'mtop_applications.taxpayer_id')
                ->leftJoin('mtop_application_charges', 'mtop_application_charges.mtop_application_id', 'mtop_applications.id')
                ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
                ->where('tricycles.body_number', $body_number)
                ->where('tricycles.mtop_application_id', '<>', null)
                ->groupBy('mtop_applications.id', 'taxpayer.id', 'colhdr.or_number', 'mtop_application_charges.mtop_application_id', 'colhdr.id')
                ->select(
                    'mtop_applications.*',
                    'taxpayer.full_name',
                    'taxpayer.address1',
                    'taxpayer.mobile',
                    'colhdr.or_number as or_no',
                    'colhdr.trnx_date',
                    DB::raw('SUM(mtop_application_charges.price) as amount')
                )
                ->first();

            /* get transaction type */

            $transaction_type = explode(',', $mtop_application['transact_type']);
            $application_type = $this->mtop_applications->getApplicationType($transaction_type, $body_number, $mtop_application['id']);

            $data = [
                'body_number' => $body_number,
                'mtfrb_case_no' => $mtop_application['mtfrb_case_no'],
                'full_name' => $mtop_application['full_name'],
                'address' => $mtop_application['address1'],
                'mobile' => $mtop_application['mobile'],
                'date_issue_day' => date('d', strtotime($mtop_application['trnx_date'])),
                'date_issue_month_year' => date('F', strtotime($mtop_application['trnx_date'])) . ', ' . date('Y', strtotime($mtop_application['trnx_date'])),
                'date_issue' => date('m-d-Y', strtotime($mtop_application['trnx_date'])),
                'validity_date' => $mtop_application['validity_date'] !== null ? date('m-d-Y', strtotime($mtop_application['validity_date'])) : '',
                'transact_type' => $mtop_application['mtfrb_case_no'] !== null ? $application_type : '',
                'transact_date' => $mtop_application['transact_date'] !== null ? date('m-d-Y', strtotime($mtop_application['transact_date'])) : '',
                'make_type' => $mtop_application['make_type'],
                'engine_motor_no' => $mtop_application['engine_motor_no'],
                'chassis_no' => $mtop_application['chassis_no'],
                'plate_no' => $mtop_application['plate_no'],
                'approve_date' => $mtop_application['approve_date'] !== null ? date('m-d-Y', strtotime($mtop_application['approve_date'])) : '',
                'or_no' => $mtop_application['or_no'],
                'amount' => $mtop_application['amount'],
            ];

            array_push($applications, $data);

        }

        return response()->json(['applications' => $applications]);
    }

    public function master_list_export() {
        return Excel::download(new MasterListExport(), 'master_list.xls');
    }


}
