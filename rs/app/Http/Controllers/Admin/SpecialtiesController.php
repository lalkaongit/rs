<?php


namespace App\Http\Controllers\Admin;
use App\Specialties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class SpecialtiesController extends Controller
{
    public function index()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        return view('admin.specialties.index', ['specialties' => $specialties]);
    }

    public function addRequestSpecialties(Request $request)
    {

        $year_adms = $request->input('name');
        $id_specialty = $request->input('number');



        $data = array('name' => $year_adms, 'number' => $id_specialty);

        $validator = Validator::make($data, [
            'name' => 'required',
            'number' => 'required',
        ]);

        if($validator->fails()) {
            return back()->with('error', 'Вы заполнили не все поля');
        }



        $objGroup = new Specialties;
        $objGroup = $objGroup->create([
            'name' => $request->input('name'),
            'number' => $request->input('number')
        ]);

        if($objGroup) {
            return redirect()->route('specialties')->with('success');
        }return back()->with('error');

    }

    public  function addSpecialties()
    {
    return view('admin.specialties.add');
    }

    public function editSpecialty(int $id)
    {
       $specialty = Specialties::find($id);
       if(!$specialty) {
           return abort(404);
       }
       return view('admin.specialties.edit', ['specialty' => $specialty]);
    }
    public function editRequestSpecialty(Request $request, int $id)
    {

        try {
            $this->validate($request, [
                'name' => 'required',
                'number' => 'required'
            ]);
            $objSpecialty = Specialties::find($id);
            if(!$objSpecialty) {
                return abort(404);
            }

            $objSpecialty->name = $request->input('name');
            $objSpecialty->number = $request->input('number');


            if($objSpecialty->save()) {
                return redirect()->route('specialties')->with('success', 'Категория успешно изменена');
            }

            return back()->with('error', 'Не удалось изменить категорию');
        }catch(ValidationException $e) {
            \Log::error($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }


    public function deleteSpecialties(Request $request)
    {
        if($request->ajax()) {
             $id = (int)$request->input('id');
             $objSpecialties = new Specialties();

             $objSpecialties->where('id', $id)->delete();

             echo "success";
        }
    }

}
