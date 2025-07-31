<?php

namespace App\Http\Controllers;

use App\Models\MtopApplication;
use App\Models\Taxpayer;
use App\Models\Tricycle;
use App\Models\TransactionBodyNumber;
use Illuminate\Http\Request;

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
            ->where('or_number', $or_number)
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
            }else{
               $query->where('tricycles.operator_id', $data_id); 
            }
        })
        ->get();

    $dataArr = array();

    foreach($getTricycle as $tricycle){
        $colhdr_details = \DB::table('mtop_applications')
            ->select(
                'colhdr.trnx_date',
                'colhdr.or_number',
                'collne2.ln_amnt',
                'otherinc.inc_desc',
                'mtop_applications.transact_type as transact_type'
            )
            ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
            ->leftJoin('collne2', 'colhdr.or_code', 'collne2.or_code')
            ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
            ->where('otherinc.annual_tax', 'Y')
            ->whereNull('colhdr.cancel')
            ->where('mtop_applications.tricycle_id', $tricycle->id)
            ->orderBy('colhdr.trnx_date', 'desc')
            ->get();

        foreach($colhdr_details as $tricycle_collection){
            $dataArr[] = [
                'full_name' => $tricycle->full_name,
                'body_number' => $tricycle->body_number,
                'trnx_date' => $tricycle_collection->trnx_date,
                'or_number' => $tricycle_collection->or_number,
                'ln_amnt' => $tricycle_collection->ln_amnt,
                'inc_desc' => $tricycle_collection->inc_desc,
                'trans_type' => $tricycle_collection->transact_type ? $this->mapTransactionType($tricycle_collection->transact_type) : 'N/A',
            ];
        }
    }
    return $dataArr;
}
      private function mapTransactionType($types){
        $map = [
            '1' => 'New',
            '2' => 'Renewal',
            '3' => 'Dropping',
            '4' => 'Change Unit',
        ];

        $parts = explode(',', $types);
        $labels = array_map(function ($type) use ($map){
            return $map[trim($type)] ?? $type;
        }, $parts);

        return implode(', ', $labels);
    }

   
}