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
        // First, let's try a different approach - get transactions directly from colhdr
        // and then try to match with mtop_applications
        $transactions = \DB::table('colhdr')
            ->leftJoin('mtop_applications', function($join) {
                $join->on('colhdr.mtop_application_id', '=', 'mtop_applications.id');
            })
            ->leftJoin('tricycles', 'mtop_applications.tricycle_id', '=', 'tricycles.id')
            ->leftJoin('taxpayer', 'mtop_applications.taxpayer_id', '=', 'taxpayer.id')
            ->where(function($query) use ($tricycle) {
                $query->where('mtop_applications.tricycle_id', $tricycle->id)
                      ->orWhere(function($subQuery) use ($tricycle) {
                          // Fallback: try to match by body number in case mtop_application_id is not set
                          $subQuery->whereNull('colhdr.mtop_application_id')
                                   ->where('colhdr.inc_desc', 'LIKE', '%' . $tricycle->body_number . '%');
                      });
            })
            ->whereNull('colhdr.cancel')
            ->select([
                'tricycles.body_number',
                'colhdr.trnx_date',
                'colhdr.or_number',
                'colhdr.amount AS ln_amnt',
                'taxpayer.full_name',
                'colhdr.inc_desc',
                'mtop_applications.transact_type',
                'colhdr.mtop_application_id',
                'mtop_applications.id as mtop_app_id'
            ])
            ->orderByDesc('colhdr.trnx_date')
            ->get();

        foreach($transactions as $transaction){
            $dataArr[] = [
                'body_number' => $transaction->body_number ?: $tricycle->body_number,
                'trnx_date' => $transaction->trnx_date,
                'or_number' => $transaction->or_number,
                'ln_amnt' => $transaction->ln_amnt,
                'full_name' => $transaction->full_name ?: $tricycle->full_name,
                'inc_desc' => $transaction->inc_desc,
                'trans_type' => $transaction->transact_type ? $this->mapTransactionType($transaction->transact_type) : 'N/A',
            ];
        }
    }

    return $dataArr;
}

private function mapTransactionType($type){
    $map = [
        '1' => 'New',
        '2' => 'Renewal',
        '3' => 'Dropping',
        '4' => 'Change Unit',
    ];

    $typeArray = explode(',', $type);
    $mapped = array_map(fn($id) => $map[trim($id)] ?? 'Unknown', $typeArray);

    return implode(', ', $mapped);
}

   
}