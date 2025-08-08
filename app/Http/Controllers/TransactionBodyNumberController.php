<?php

namespace App\Http\Controllers;

use App\Models\MtopApplication;
use App\Models\Taxpayer;
use App\Models\Tricycle;
use App\Models\TransactionBodyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionBodyNumberController extends Controller
{
    public function index()
    {
        return view('mtfru.transaction_body_number');
    }

    public function search_operator($string){
        $result = Taxpayer::query()
        ->where('full_name', 'LIKE', '%' . $string . '%')
        ->get();    
        
        return response()->json(['data' => $result], 200);
    }

    public function search_tricycle($string) {
        $result = Tricycle::where('body_number', $string)->first();
        return response()->json(['data' => $result], 200);
    }

    public function search($option, $string){
        if($option === 'tricycle'){
            return $this->search_tricycle($string);
        }

        return $this->search_operator($string);
    }

    public function process_data($filter, $data_id){
        return response()->json(['data' => $this->get_data($filter, $data_id)], 200);
    }

    public function get_or_details($or_number){
        //     $charges = \DB::table('collne2')
        //     ->select('otherinc.inc_desc', 'collne2.ln_amnt')
        //     ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
        //     ->leftJoin('colhdr', 'colhdr.or_code', 'collne2.or_code')
        //     ->where('collne2.or_code', $or_number)
        //     ->whereNull('colhdr.cancel')
        //     ->get();

        // $header = \DB::table('colhdr')
        //     ->where('or_number', $or_number)
        //     ->first();

        // return response()->json([
        //     'charges' => $charges,
        //     'header' => $header
        // ], 200);
        $header = \DB::table('colhdr')
            ->leftJoin('mtop_applications', 'colhdr.mtop_application_id', '=', 'mtop_applications.id')
            ->select('colhdr.*', 'mtop_applications.mtfrb_case_no')
            ->where('colhdr.or_number', $or_number)
            ->where(function($q){
                $q->where('colhdr.trans_type', 'MTOP')
                  ->orWhereNull('colhdr.trans_type')
                  ->orWhere('colhdr.trans_type', '');
            })
            ->whereNull('cancel')
            ->first();

            if(!$header){
                return response()->json([
                    'charges' => [],
                    'header' => null
                ], 200);
            }

            $charges = \DB::table('collne2')
                ->select('otherinc.inc_desc', 'collne2.ln_amnt')
                ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
                ->where('collne2.or_code', $header->or_code)
                ->get();

            return response()->json([
                'charges' => $charges,
                'header' => $header
            ], 200);
    }

    public function get_data($filter, $data_id){
    $getTricycle = Tricycle::query()
        ->select('tricycles.*', 'taxpayer.full_name')
        ->leftJoin('taxpayer', 'taxpayer.id', 'tricycles.operator_id')
        ->where(function($query) use ($filter, $data_id){
            if($filter === 'body_number'){
                $query->where('tricycles.body_number', $data_id);
            } else {
                $query->where('tricycles.operator_id', $data_id); 
            }
        })
        ->get();

    $dataArr = [];

    foreach($getTricycle as $tricycle){
        // Get transactions from colhdr - simplified approach
        $transactions = \DB::table('colhdr')
            ->leftJoin('mtop_applications', function($join) {
                $join->on('colhdr.mtop_application_id', '=', 'mtop_applications.id');
            })
            ->leftJoin('tricycles', 'mtop_applications.tricycle_id', '=', 'tricycles.id')
            ->leftJoin('taxpayer', 'mtop_applications.taxpayer_id', '=', 'taxpayer.id')
            ->where('mtop_applications.tricycle_id', $tricycle->id)
            ->whereNull('colhdr.cancel')
            ->select([
                'tricycles.body_number',
                'colhdr.trnx_date',
                'colhdr.or_number',
                'colhdr.or_code',
                'taxpayer.full_name',
                'mtop_applications.transact_type',
                'colhdr.mtop_application_id',
                'mtop_applications.id as mtop_app_id'
            ])
            ->orderByDesc('colhdr.trnx_date')
            ->get();

        foreach($transactions as $transaction){
            // Calculate total amount from collne2 table
            $totalAmount = \DB::table('collne2')
                ->where('or_code', $transaction->or_code)
                ->sum('ln_amnt');

            // Get description from collne2 and otherinc tables
            $descriptions = \DB::table('collne2')
                ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
                ->where('collne2.or_code', $transaction->or_code)
                ->pluck('otherinc.inc_desc')
                ->filter()
                ->toArray();

            $dataArr[] = [
                'body_number' => $transaction->body_number ?: $tricycle->body_number,
                'trnx_date' => $transaction->trnx_date,
                'or_number' => $transaction->or_number,
                'ln_amnt' => $totalAmount ?: 0,
                'full_name' => $transaction->full_name ?: $tricycle->full_name,
                'inc_desc' => implode(', ', $descriptions),
                'trans_type' => $transaction->transact_type ? $this->mapTransactionType($transaction->transact_type) : 'N/A',
            ];
        }
    }

    return $dataArr;
}

private function mapTransactionType($type){
    // Handle null or empty values
    if (empty($type)) {
        return 'N/A';
    }

    // Map of transaction type IDs to names
    $map = [
        '1' => 'Renewal',
        '2' => 'Transfer',
        '3' => 'Change Unit',
        '4' => 'New',
    ];

    // Split the type string by comma and process each ID
    $typeArray = explode(',', $type);
    $mapped = [];

    foreach ($typeArray as $typeId) {
        $trimmedId = trim($typeId);
        if (isset($map[$trimmedId])) {
            $mapped[] = $map[$trimmedId];
        } else {
            $mapped[] = 'Unknown (' . $trimmedId . ')';
        }
    }

    // Join the mapped types with comma and space
    return implode(', ', $mapped);
}

   
}