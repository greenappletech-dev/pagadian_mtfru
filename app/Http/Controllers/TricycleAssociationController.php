<?php

namespace App\Http\Controllers;

use App\Models\Taxpayer;
use App\Models\Tricycle;
use App\Models\TricycleAssociation;
use App\Models\TricycleAssociationMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TricycleAssociationController extends Controller
{
    public function index() {
        $data = TricycleAssociation::get();
        return view('system.association', compact('data'));
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'required|unique:tricycle_associations,name',
        ],
        [
            'name.required' => 'Association Name is Required',
            'name.unique' => 'Association Name Already Exist',
        ]);

        $save = new TricycleAssociation();
        $save->name = $request->name;
        $save->save();

        return response()->json(['message' => 'Data Saved'], 200);
    }

    public function edit($id)
    {
        $data = TricycleAssociation::where('id', $id)->first();
        return response()->json(['data' => $data], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tricycle_associations,name,'.$request->id,
        ],
        [
            'name.required' => 'Association Name is Required',
            'name.unique' => 'Association Name Already Exist',
        ]);

        $save = TricycleAssociation::where('id', $request->id)->first();
        $save->name = $request->name;
        $save->save();

        return response()->json(['message' => 'Data Saved']);
    }

    public function destroy($id) {
        TricycleAssociation::where('id', $id)->delete();
        return response()->json(['message' => 'Data Deleted'], 200);
    }

    public function showOperators() {
        return response()->json(['data' => Taxpayer::orderBy('last_name')->select('taxpayer.id as taxpayer_id', 'full_name as operator')->get()], 200);
    }

    public function showMembers($id) {
        return response()->json(['data' => TricycleAssociationMember::leftJoin('taxpayer','taxpayer.id','tricycle_association_members.taxpayer_id')
            ->where('tricycle_association_id', $id)
            ->select('tricycle_association_members.*','taxpayer.full_name as operator', 'taxpayer.id as taxpayer_id')
            ->orderBy('full_name')
            ->get()
        ], 200);
    }

    public function saveMembers(Request $request) {

        $idRemoveArr = [];

        foreach($request->details as $detail)
        {

            if(isset($detail->id))
            {
                $data = TricycleAssociationMember::where('id', $detail->id)->first();
            }
            else
            {
                $data = new TricycleAssociationMember();
            }

            $data->taxpayer_id = $detail['taxpayer_id'];
            $data->tricycle_association_id = $request->id;
            $data->save();

            $idRemoveArr[] = $data->id;

        }


        TricycleAssociationMember::where('tricycle_association_id', $request->id)->whereNotIn('id', $idRemoveArr)->delete();

        return response()->json(['data' => 'success'], 200);


    }

}
