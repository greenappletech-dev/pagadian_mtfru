<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScheduledController extends Controller
{
    public function uploadImages() {
        $files = Storage::disk('public')->allFiles('operator_image');

        foreach($files as &$file) {
            $file = str_replace('operator_image/', '', $file);

            $checkDB = \DB::table('operator_images')
                ->select('id')
                ->where('name', $file)
                ->where('uploaded', null)
                ->first();

            if(file_exists(public_path() . '/image/operator_image/' . $file) && $checkDB)
            {
                /* upload files */
                Storage::disk('google')->putFileAs('', public_path() . '/image/operator_image/' . $file, $file);

                /* mark image as uploaded in db */
                \DB::table('operator_images')->where('id', $checkDB->id)->update(['uploaded' => true]);

            }
        }

        return 'Upload Finished';
    }
}
