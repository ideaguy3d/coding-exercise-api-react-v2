<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    //
    public function file(Request $request) {
        echo $request->file('people_file')->store('people_files');
    }
}
