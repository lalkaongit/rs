<?php

namespace App\Http\Controllers\Teacher;

use App\Group;
use App\Specialties;
use App\Discipline;
use App\Lecture;
use App\Test;
use App\Task;
use App\Report;
use App\Practical;
use App\Institution;
use Illuminate\Support\Collection;
use App\Lab;
use App\RS;
use App\User;
use Session;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class RSController extends Controller
{

    public function getIdTeacher(int $id)
    {
      $objRS = RS::find($id);

      if(!$objRS) {
          return abort(404);
      }

      $teacher = User::find($objRS->id_teacher);
      return $teacher->id;
    }

    public function getIdDiscipline(int $id)
    {
      $objRS = RS::find($id);

      if(!$objRS) {
          return abort(404);
      }

      $discipline = Discipline::find($objRS->id_discipline);
      return $discipline->id;
    }

    public function getIdGroup(int $id)
    {
      $objRS = RS::find($id);

      if(!$objRS) {
          return abort(404);
      }

      $group = Group::find($objRS->id_group);
      return $group->id;
    }

    public function getIdInstitution(int $id)
    {
      $objRS = RS::find($id);
      if(!$objRS) {
          return abort(404);
      }

      $institution = Institution::find($objRS->id_institution);

      return $institution->id;
    }

    public function index()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        $objInst = new Institution();
        $institutions = $objInst->get();

        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        $objTask = new Task();
        $task = $objTask->get();

        $objRS = new RS();
        $rss = $objRS->get();

        $data = array('rss' => $rss, 'disciplines' => $disciplines, 'groups' => $groups, 'specialties' => $specialties, 'institutions' => $institutions, 'task' => $task);

        return view('teacher.rs.index', $data); //Во вьюшку всех БРС передаю все группы, дисциплины, специальности и все БРС
    }

    public function countWord($word)
    {
      return explode(",", $word);
    }

    public function addRequestRS(Request $request)
    {

        $id_teacher = $request->input('id_teacher');
        $id_discipline = $request->input('id_discipline');
        $id_group= $request->input('id_group');
        $all_points = $request->input('all_points');
        $all_points_visits = $request->input('all_points_visits');
        $number_lectures = $request->input('number_lectures');
        $id_institution = $request->input('id_institution');

        if($number_lectures !=0 && $all_points_visits !=0) {$score_one_lecture = $all_points_visits/$number_lectures;}

        $data = array('id_teacher' => $id_teacher, 'id_discipline' => $id_discipline, 'id_group' => $id_group,
        'all_points' => $all_points, 'all_points_visits' => $all_points_visits, 'number_lectures' => $number_lectures);

        //валидация полей

        $validator = Validator::make($data, [
            'id_teacher'  => 'required',
            'id_discipline'  => 'required',
            'id_group' =>  'required',
            'all_points'  => 'required',
            'all_points_visits'  =>  'required',
            'number_lectures' => 'required'
        ]);

        if($validator->fails()) {
            return back()->with('error', 'Вы заполнили не все поля');
        }

        $objRS = new RS;
        $objRS = $objRS->create([
            'id_teacher' => $id_teacher,
            'id_discipline' => $id_discipline,
            'id_group' => $id_group,
            'all_points' => $all_points,
            'all_points_visits' => $all_points_visits,
            'number_lectures' => $number_lectures,
            'id_institution' => $id_institution,
            'names_tasks' => $request->input('name_tasks'),
            'count_tasks' => $request->input('count_tasks'),
            'score_tasks' => $request->input('score_tasks')
        ]);


        $objRS = new RS;
        $rss =   $objRS->get();
        $ta_rs = 0;

        foreach($rss as $rs)
        {
            if($rs->id_teacher == $id_teacher && $rs->id_discipline == $id_discipline && $rs->id_group == $id_group)
            {
                $ta_rs = $rs->id;
            }
        }


        $objUsers = new User();
        $users = $objUsers->get();
        $students = array();

        //ищу студентов из группы для БРС

        foreach($users as $user)
        {
            if($user->status == 2 && $user->id_group == $id_group)
            {
                array_push($students, $user->id);
            }
        }



        $i=0;
        //для каждого студента резервию поля для лекций

        foreach($students as $student)
        {

            $objLecture = new Lecture;
            $objLecture = $objLecture->create([
                'id_teacher' => $request->input('id_teacher'),
                'id_group' => $id_group,
                'id_student' => $students[$i],
                'id_discipline' => $request->input('id_discipline'),
                'sum_points' => $request->input('id_discipline'),
                'score_one_lecture' => $score_one_lecture,
                'id_rs' => $ta_rs
            ]);

            $i++;

        }

        $word = explode(",",$request->input('name_tasks'));

        $c_task = explode(",",$request->input('count_tasks'));
        $s_task = explode(",",$request->input('score_tasks'));

        $countword = count($word);

        //если внеурочные работы есть, то поля

        if( $countword > 0 ){


          $i=0;

          foreach($students as $student)
          {

            for ($j = 0; $j < $countword; $j++) {

              $s_o = 0;
                $s_o = ((int)$s_task[$j]/(int)$c_task[$j]);



              $objTask = new Task;
              $task = $objTask->create([
                'id_student' => $students[$i],
                'name_task' => $word[$j],
                'score_one' => $s_o,
                'id_rs' => $ta_rs
              ]);

            }

            $i++;

          }
        }


        if($objRS) {
            return redirect()->route('teacher')->with('success', 'БРС успешно добавлена');
        }return back()->with('error');



    }

    public  function addRS()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        $objInst = new Institution();
        $institutions = $objInst->get();

        $objUsers = new User();
        $users = $objUsers->get();

        $data = array('disciplines' => $disciplines, 'groups' => $groups, 'specialties' => $specialties, 'users' => $users, 'institutions' => $institutions);


        return view('teacher.rs.add', $data);
    }

    public function editRS(int $id)
    {
        $rs = RS::find($id);
        if(!$rs) {
            return abort(404);
        }
        return view('teacher.rs.edit', ['rs' => $rs]);
    }

    public function viewRS(int $id)
    {
        $rs = RS::find($id);
        if(!$rs) {
            return abort(404);
        }

        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        $data = array('disciplines' => $disciplines, 'groups' => $groups, 'specialties' => $specialties, 'rs' => $rs);

        return view('teacher.rs.view', $data);
    }

    public function viewLecturesRS(int $id)
    {
        //В реквест мне приходит id БРС

        //Ищу такую БРС, проверяю наличие
        $rs = RS::find($id);
        if(!$rs) {
            return abort(404);
        }

        //Смотрю все лекции, пользователей, Дисциплины, Специальности

        $objLectures = new Lecture();
        $lectures = $objLectures->get();

        $objUsers = new User();
        $users = $objUsers->get();

        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        $objTask = new Task();
        $tasks = $objTask->get();


        $id_row_lectures = array();
        //ищу студентов из группы для БРС

        foreach($lectures as $lecture)
        {
            if($lecture->id_rs == $rs->id)
            {
                array_push($id_row_lectures, $lecture);
                //записываю все строки в которых содержатся те студенты что принадлежат данной БРС
            }
        }

        $data = array('rs' => $rs, 'lectures' => $lectures, 'users' => $users, 'disciplines' => $disciplines, 'specialties' => $specialties, 'id_row_lectures' => $id_row_lectures, 'groups' => $groups, 'tasks' => $tasks);

        return view('teacher.rs.tables.lectures', $data);
    }



    public function updateLecturesRS(Request $request)
    {

        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('lectures')->where('id', $request->id)->update($data);

    }

    public function updateTRS(Request $request)
    {


        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('tasks')->where('id', $request->id)->update($data);

    }




    public function editRequestRS(Request $request, int $id)
    {
        try {
            $this->validate($request, [
                'id_teacher'  => 'required',
                'id_discipline'  => 'required',
                'id_group' =>  'required',
                'all_points'  => 'required',
                'all_points_visits'  =>  'required',
                'number_lectures' => 'required'
            ]);
            $objRS = RS::find($id);
            if(!$objRS) {
                return abort(404);
            }

            $objRS->id_teacher = $request->input('id_teacher');
            $objRS->id_discipline = $request->input('id_discipline');
            $objRS->id_group = $request->input('id_group');
            $objRS->all_points = $request->input('all_points');
            $objRS->all_points_visits = $request->input('all_points_visits');
            $objRS->all_points_practicals = $request->input('all_points_practicals');
            $objRS->all_points_labs = $request->input('all_points_labs');
            $objRS->all_points_reports = $request->input('all_points_reports');
            $objRS->all_points_tests = $request->input('all_points_tests');
            $objRS->number_lectures = $request->input('number_lectures');
            $objRS->number_practicals = $request->input('number_practicals');
            $objRS->number_labs = $request->input('number_labs');
            $objRS->number_reports = $request->input('number_reports');
            $objRS->number_tests = $request->input('number_tests');
            $objRS->mass_date_lpl = $request->input('mass_date_lpl');


            if($objRS->save()) {
                return redirect()->route('teacher')->with('success', 'БРС успешно изменена');
            }

            return back()->with('error', 'Не удалось изменить категорию');
        }catch(ValidationException $e) {
            \Log::error($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }




    public function deleteRS(Request $request)
    {
        if($request->ajax()) {
             $id = (int)$request->input('id');
             $objRS = new RS();

             $objRS->where('id', $id)->delete();

             return redirect()->route('teacher')->with('success', 'БРС успешно удалена');
        }
    }
}
