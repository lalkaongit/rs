<?php

namespace App\Http\Controllers\Teacher;

use App\RS;
use App\Discipline;
use App\Group;
use App\Specialties;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $objRS = new RS();
        $rss = $objRS->get();

        $objDiscipline = new Discipline();
        $disciplines = $objDiscipline->get();

        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();


        $id_teacherf = Auth::user()->id;

        $tds = array(); //массив id дисциплин БРС
        $trss = array(); //массив БРС для залогиневшегося учителя

        foreach($rss as $rs)
        {
            if($rs->id_teacher == $id_teacherf)
            {
                array_push($tds, $rs->id_discipline);
                array_push($trss, $rs);
            }
        }

        $data = array('tds' => $tds, 'disciplines' => $disciplines, 'trss' => $trss, 'groups' => $groups, 'specialties' => $specialties);

        return view('teacher.index', $data);
    }
}
