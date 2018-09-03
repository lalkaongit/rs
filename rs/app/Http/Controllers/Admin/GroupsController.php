<?php

namespace App\Http\Controllers\Admin;
use App\Group;
use App\Specialties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class GroupsController extends Controller
{
    public function index()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        return view('admin.groups.index', ['specialties' => $specialties], ['groups' => $groups]);
    }

    public  function addGroup()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

    return view('admin.groups.add', ['specialties' => $specialties]);
    }

    public function addRequestGroup(Request $request)
    {

        $year_adms = $request->input('year_adms');
        $id_specialty = $request->input('specialties');



        $data = array('year_adms' => $year_adms, 'id_specialty' => $id_specialty);

        $validator = Validator::make($data, [
            'year_adms' => 'required',
            'id_specialty' => 'required',
        ]);

        if($validator->fails()) {
            return back()->with('error', 'Вы заполнили не все поля');
        }



        $objGroup = new Group;
        $objGroup = $objGroup->create([
            'year_adms' => $year_adms,
            'id_specialty' => $id_specialty
        ]);

        if($objGroup) {
            return redirect()->route('groups')->with('success', 'Группа успешно добавлена');
        }return back()->with('error');

    }

    public function editGroup(int $id)
    {
       $group = Group::find($id);
       if(!$group) {
           return abort(404);
       }

       $objSpecialties = new Specialties();
       $specialties = $objSpecialties->get();



       return view('admin.groups.edit',[
       'group' => $group,
       'specialties' => $specialties
       ]);
    }
    public function editRequestGroup(Request $request, int $id)
    {


        try {
            $this->validate($request, [
                'year_adms' => 'required',
                'specialties' => 'required'
            ]);
            $objGroup = Group::find($id);
            if(!$objGroup) {
                return abort(404);
            }

            $objGroup->year_adms = $request->input('year_adms');
            $objGroup->id_specialty = $request->input('specialties');


            if($objGroup->save()) {
                return redirect()->route('groups')->with('success', 'Группа успешно изменена');
            }

            return back()->with('error', 'Не удалось изменить категорию');
        }catch(ValidationException $e) {
            \Log::error($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function deleteGroup(Request $request)
    {
        if($request->ajax()) {
             $id = (int)$request->input('id');
             $objGroup = new Group();

             $objGroup->where('id', $id)->delete();

             echo "success";
        }
    }



}
