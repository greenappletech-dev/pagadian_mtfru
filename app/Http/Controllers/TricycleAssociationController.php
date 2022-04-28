<?php

namespace App\Http\Controllers;

use App\Models\Tricycle;
use App\Models\TricycleAssociation;
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

}
