<?php

namespace App\Http\Controllers;

use App\Models\signatories;
use Illuminate\Http\Request;

class SignatoriesController extends Controller
{
    public function index() {
        return view('signatories.signatories');
    }

    public function addSignatoriesForm()
    {
        $formData = Signatories::get();
    
        return view('signatories.add_signatories_form', compact('formData')); 
    }
    
    public function saveFormData(Request $request)
    {
        // Validate the request data
        $request->validate([
            // Add validation rules if necessary
        ]);
    
          // Retrieve the ID of the record to be updated
        $id = $request->input('id');

        // Find the record by ID
        $formData = Signatories::findOrFail($id);
    
        $formData->ac_officer = $request->input('ac_officer');
        $formData->ac_verified = $request->input('ac_verified');
        $formData->ac_noted = $request->input('ac_noted');
        $formData->ac_approved = $request->input('ac_approved');
    
        $formData->cn_recommending = $request->input('cn_recommending');
        $formData->cn_noted = $request->input('cn_noted');
        $formData->cn_approved = $request->input('cn_approved');
    
        $formData->sc_inspected = $request->input('sc_inspected');
        $formData->sc_noted = $request->input('sc_noted');
        $formData->sc_approved = $request->input('sc_approved');
    
        $formData->mo_recommending = $request->input('mo_recommending');
        $formData->mo_approved = $request->input('mo_approved');
    
        $formData->bc_recommending = $request->input('bc_recommending');
        $formData->bc_approved = $request->input('bc_approved');
    
        $formData->cf_recommending = $request->input('cf_recommending');
        $formData->cf_approved = $request->input('cf_approved');
    
        $formData->sf_recommending = $request->input('sf_recommending');
        $formData->sf_verified = $request->input('sf_verified');
        $formData->sf_approved = $request->input('sf_approved');
    
        $formData->pb_certified = $request->input('pb_certified');
    
        $formData->save();

        $request->session()->flash('success', 'Form data saved successfully!');
    
       return view('signatories.signatories');
    }
    
}
