<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    public function multipleUpload(Request $request){
        // getting all of the post data
        $files = Input::file('images');
        // Making counting of uploaded images
        $file_count = count($files);
        // start count how many uploaded
        $uploadcount = 0;

        foreach ($files as $file) {
            $rules = array('file' => 'required|mimes:jpeg,pdf'); //'required|mimes:png,gif,jpeg,txt,pdf,doc'
            $validator = Validator::make(array('file' => $file), $rules);
            if ($validator->passes()) {
                $destinationPath = 'storage';
                $filename        = $file->getClientOriginalName();
                $upload_success  = $file->move($destinationPath, $filename);
                $uploadcount++;
            }
        }
        $message = 'Files Uploaded';
        if ($uploadcount == $file_count) {
            if ($request->ajax()){
                return response()->json([ 'message' => $message]);
            }
        } else {
            return Redirect::to('upload')->withInput()->withErrors($validator);
        }
    }
}
