<?php

namespace App\Http\Controllers\Admin;

use App\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class DisciplineController extends Controller
{
    public function index()
    {
        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        return view('admin.disciplines.index', ['disciplines' => $disciplines]);
    }

    public function addRequestDiscipline(Request $request)
    {

        $number = $request->input('number');
        $name = $request->input('name');
        $mdk= $request->input('mdk');



        $data = array('number' => $number, 'name' => $name, 'mdk' => $mdk);

        $validator = Validator::make($data, [
            'name' => 'required',
        ]);

        if($validator->fails()) {
            return back()->with('error', 'Вы заполнили не все поля');
        }



        $objGroup = new Discipline;
        $objGroup = $objGroup->create([
            'number' => $request->input('number'),
            'name' => $request->input('name'),
            'mdk' => $request->input('mdk')
        ]);

        if($objGroup) {
            return redirect()->route('disciplines')->with('success');
        }return back()->with('error');

    }

    public  function addDiscipline()
    {
    return view('admin.disciplines.add');
    }

    public function editDiscipline(int $id)
    {
       $discipline = Discipline::find($id);
       if(!$discipline) {
           return abort(404);
       }
       return view('admin.disciplines.edit', ['discipline' => $discipline]);
    }
    public function editRequestDiscipline(Request $request, int $id)
    {

        try {
            $this->validate($request, [
                'name' => 'required',
            ]);
            $objDiscipline = Discipline::find($id);
            if(!$objDiscipline) {
                return abort(404);
            }

            $objDiscipline->name = $request->input('name');
            $objDiscipline->number = $request->input('number');
            $objDiscipline->mdk = $request->input('mdk');


            if($objDiscipline->save()) {
                return redirect()->route('disciplines')->with('success', 'Категория успешно изменена');
            }

            return back()->with('error', 'Не удалось изменить категорию');
        }catch(ValidationException $e) {
            \Log::error($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function deleteDiscipline(Request $request)
    {
        if($request->ajax()) {
             $id = (int)$request->input('id');
             $objDiscipline = new Discipline();

             $objDiscipline->where('id', $id)->delete();

             echo "success";
        }
    }
}
