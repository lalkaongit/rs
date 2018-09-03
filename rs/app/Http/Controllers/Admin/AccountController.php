<?php

namespace App\Http\Controllers\Admin;
use App\Specialties;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        return view('admin.index', ['specialties' => $specialties]);
    }
}
