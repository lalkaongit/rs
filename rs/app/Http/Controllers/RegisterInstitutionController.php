<?php

namespace App\Http\Controllers;

use App\Institution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class RegisterInstitutionController extends Controller
{


    public function index()
    {
        return view('auth.reg_institution');
    }

    public function create(Request $request)
    {
        Institution::create([
            'name' => $request['name'],
            'type' => $request['type']
        ]);

        return redirect()->route('home')->with('success');

    }
}
