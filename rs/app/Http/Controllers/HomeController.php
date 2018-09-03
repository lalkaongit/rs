<?php

namespace App\Http\Controllers;
use App\Specialties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();


               if(Auth::user()->status == 0){
                 return redirect('admin');
               }else

               if(Auth::user()->status == 1){
                 return redirect('teacher');
               }

        return view('home', ['specialties' => $specialties]);
    }
}
