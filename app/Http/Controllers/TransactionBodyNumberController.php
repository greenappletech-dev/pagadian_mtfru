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
                $colhdr_details = MtopApplication::query()
                    ->select('colhdr.*', 'collne2.*', 'otherinc.*')
                    ->leftJoin('colhdr', 'colhdr.mtop_application_id', 'mtop_applications.id')
                    ->leftJoin('collne2', 'colhdr.or_code', 'collne2.or_code')
                    ->leftJoin('otherinc', 'otherinc.inc_code', 'collne2.inc_code')
                    ->where('otherinc.annual_tax', 'Y')
                    ->where('colhdr.cancel', null)
                    ->where('mtop_applications.tricycle_id', $tricycle->id)
                    ->orderBy('colhdr.trnx_data', 'desc')
                    ->get();


                    foreach($colhdr_details as $tricycle_collection){
                        $dataArr[] = [
                            'full_name' => $tricycle->full_name,
                            'body_number' => $tricycle->body_number,
                            'trnx_data' => $tricycle_collection->trnx_data,
                            'or_number' => $tricycle_collection->or_number,
                            'ln_amnt' => $tricycle_collection->ln_amnt,
                            'inc_desc' => $tricycle_collection->inc_desc,
                            'trans_type' => $tricycle_collection->trans_type
                        ];
                    }
            }
            return $dataArr;
    }

   
}
