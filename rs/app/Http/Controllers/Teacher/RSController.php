<?php

namespace App\Http\Controllers\Teacher;

use App\Group;
use App\Specialties;
use App\Discipline;
use App\Lecture;
use App\Test;
use App\TestInfo;
use App\MainTest;
use App\MainTestInfo;
use App\Task;
use App\Dates;
use App\Bonus;
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
use Cookie;

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
            'score_tasks' => $request->input('score_tasks'),
            'count_tests' => $request->input('count_tests'),
            'score_tests' => $request->input('score_tests'),
            'score_main_test' => $request->input('score'),
            'count_main_tests' => $request->input('count_main_tests'),
            'bonus' => $request->input('bonus')
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

        $today = date("d.m");

        $objDates = new Dates;
        $objDates = $objDates->create([
            'date_' . '0' => $today,
            'count_lec' => $number_lectures,
            'id_rs' => $ta_rs

        ]);


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

        if( $request->input('count_tests') > 0)
        {
          $objTestInfo = new TestInfo;
          $objTestInfo = $objTestInfo->create([
              'count_tests' => $request->input('count_tests'),
              'score_one' => ($request->input('score_tests') / $request->input('count_tests')),
              'id_rs' => $ta_rs
          ]);

          $id_test = DB::select('select * from test_info where id_rs = ?', [$ta_rs]);




            $i=0;
            //для каждого студента резервию поля для лекций

            foreach($students as $student)
            {
                $objTest = new Test;
                $objTest = $objTest->create([
                    'id_student' => $students[$i],
                    'id_rs' => $ta_rs,
                    'id_test' => $id_test[0]->id
                ]);

                $i++;

            }
        }





        if( $request->input('count_main_tests') > 0)
        {

          $objMainTestInfo = new MainTestInfo;
          $objMainTestInfo = $objMainTestInfo->create([
              'count_tests' => $request->input('count_tests'),
              'score_one' => ($request->input('score_tests') / $request->input('count_tests')),
              'id_rs' => $ta_rs
          ]);

          $id_main_test = DB::select('select * from main_test_info where id_rs = ?', [$ta_rs]);


          $i=0;
        //для каждого студента резервию поля для лекций

        foreach($students as $student)
        {
            $objMainTest = new MainTest;
            $objMainTest = $objMainTest->create([
              'id_student' => $students[$i],
              'id_rs' => $ta_rs,
              'id_test' => $id_main_test[0]->id
            ]);

            $i++;

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

        if ($request->input('count_tasks') != null)
        {
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
        $test_info = DB::select('select * from test_info where id_rs = ?', [$rs->id]);

        $main_test_info = DB::select('select * from main_test_info where id_rs = ?', [$rs->id]);

        $students = DB::select('select * from lectures where id_rs = ?', [$rs->id]);

        $tasks = DB::select('select * from tasks where id_rs = ?', [$rs->id]);

        $tests = DB::select('select * from tests where id_rs = ?', [$rs->id]);

        $maintests = DB::select('select * from main_test where id_rs = ?', [$rs->id]);

        $bonuses = DB::select('select * from bonus where id_rs = ?', [$rs->id]);

        $discipline = DB::select('select * from disciplines where id = ?', [$rs->id_discipline]);

        $discipline = $discipline[0]->name;

        $group = DB::select('select * from groups where id = ?', [$rs->id_group]);

        $specialty = DB::select('select * from specialties where id = ?', [$group[0]->id_specialty]);

        $dates = DB::select('select * from dates where id_rs = ?', [$rs->id]);

        $specialty = $specialty[0]->name;

        $group = $group[0];

        if (!empty($test_info))
        {
          $test_info = $test_info[0];
        }

        if (!empty($main_test_info))
        {
          $main_test_info = $main_test_info[0];
        }



        $objUsers = new User();
        $users = $objUsers->get();

        $data =

        array(
        'rs' => $rs,
        'students' => $students,
        'users' => $users,
        'discipline' => $discipline,
        'specialty' => $specialty,
        'group' => $group,
        'tasks' => $tasks,
        'tests' => $tests,
        'maintests' => $maintests,
        'bonuses' => $bonuses,
        'test_info' => $test_info,
        'main_test_info' => $main_test_info,
        'dates' => $dates
         );



        return view('teacher.rs.tables.lectures', $data);
    }



    public function updateLecturesRS(Request $request)
    {

        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('lectures')->where('id', $request->id)->update($data);



        $rs = DB::select('select * from rs where id = ?', [$request->rs_id]);

        $string_lecture = DB::select('select * from lectures where id = ?', [$request->id]);

        $sum = 0;
        for($i = 0; $i < $rs[0]->number_lectures; $i++)
        {

          $sum = $sum + $string_lecture[0]->{'date_' . $i};

        }

        $score_one_lecture = $rs[0]->all_points_visits / $rs[0]->number_lectures;

        $score = round($sum * $score_one_lecture, 1);

        $dat['score'] = $score;
        $dat['sum'] = $sum;

        return $dat;
    }




    public function getrandRS(Request $request)
    {

      $fio = " ";
      $objUsers = new User();
      $users = $objUsers->get();
      $arr = explode(",", $request->array);
      $ran = array_random($arr);
      $count= 0;
      foreach($arr as $ar)
      {
          $pos = strpos(Cookie::get("some_cookie_name"), $ar);
          if($pos !== false)
          {
            $count++;
          }
      }
      $pos = strpos(Cookie::get("some_cookie_name"), $ran);
        if($pos === false)
        {
          foreach($users as $user)
          {
            if($user->id == $ran)
            {
              $fio = $user->id.'/'.$user->surname." ".$user->name;
              setcookie("some_cookie_name", Cookie::get("some_cookie_name").$ran);
            }
          }
        }
        else {
          if(count($arr) > $count)
          {
            $this->getrandRS($request);
          }
          else {
            setcookie("some_cookie_name", "");
            $fio = "/Все студенты опрошены";
          }
        }
      echo $fio;




    }

    public function plus(Request $request)
    {

      $objBonus = new Bonus;
      $objBonus = $objBonus->create([
          'count_bonus' => $request->score,
          'date' => date("d.m.Y"),
          'id_student' => $request->id_stud,
          'id_rs' => $request->id_rs,
          'info' => $request->info
      ]);

    }



    public function updateTRS(Request $request)
    {


        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('tasks')->where('id', $request->id)->update($data);



        $rs = DB::select('select * from rs where id = ?', [$request->rs_id]);

        $string_task = DB::select('select * from tasks where id = ?', [$request->id]);

        $sum = 0;
        for($i = 0; $i < $request->c_task; $i++)
        {

          $sum = $sum + $string_task[0]->{'task_' . $i};

        }

        $score = round(($sum/100) * $request->scr_one, 1);







        if(empty($request->text))
        {
          $sum = " - ";
        }
        elseif($request->text >= 85)
          {
            $sum = 5;
          }
          elseif($request->text < 85 && $request->text >= 70)
            {
              $sum = 4;
            }
            elseif($request->text < 70 && $request->text >= 55)
            {
              $sum = 3;
            }
            elseif($request->text < 55)
            {
              $sum = 2;
            }






        $dat['score'] = $score;
        $dat['value'] = $sum;

        return $dat;



    }

    public function updateTestRS(Request $request)
    {


        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('tests')->where('id', $request->id)->update($data);



        $rs = DB::select('select * from rs where id = ?', [$request->rs_id]);

        $string_test = DB::select('select * from tests where id = ?', [$request->id]);

        $string_test_info = DB::select('select * from test_info where id_rs = ?', [$request->rs_id]);


        $count_test = $rs[0]->count_tests;
        $score_one = $rs[0]->score_tests / $count_test;
        $score = 0;


        for($i = 0; $i < $count_test; $i++)
        {
          if(($string_test_info[0]->{'test_' . $i} * $string_test[0]->{'test_' . $i}) != 0)
          {
            $score = $score + ($score_one / $string_test_info[0]->{'test_' . $i} * $string_test[0]->{'test_' . $i});
          }
        }

        $result = $request->text;
        $j = $request->test;
        $scorefortest = 0;
        $sum = '-';
        $countquest = $string_test_info[0]->{'test_' . $j};



        if(($result != 0) && ($countquest != 0))
        {
          $scorefortest = ($result / $countquest)  * 100;
        }




        if(empty($result))
        {
          $sum = '-';
        }
        else {
          if($scorefortest >= 85)
          {
            $sum = '5';
          }
          if($scorefortest < 85 && $scorefortest >= 70)
          {
            $sum = '4';
          }
          if($scorefortest < 70 && $scorefortest >= 55)
          {
            $sum = '3';
          }
          if($scorefortest < 55)
          {
            $sum = '2';
          }
        }


        $dat['score'] = round($score, 1);
        $dat['value'] = $sum;
        $dat['str'] = $request->str;

        return $dat;

    }

    public function updateTIRS(Request $request)
    {


        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('test_info')->where('id', $request->id)->update($data);

    }

    public function updateDATE(Request $request)
    {


        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('dates')->where('id_rs', $request->id_rs)->update($data);

    }

    public function updateMAINRS(Request $request)
    {


        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('main_test')->where('id', $request->id)->update($data);


        $rs = DB::select('select * from rs where id = ?', [$request->rs_id]);

        $string_test = DB::select('select * from main_test where id = ?', [$request->id]);

        $string_test_info = DB::select('select * from main_test_info where id_rs = ?', [$request->rs_id]);


        $count_test = $rs[0]->count_main_tests;
        $score_one = $rs[0]->score_main_test / $count_test;
        $score = 0;


        for($i = 0; $i < $count_test; $i++)
        {
          if (($string_test_info[0]->{'test_' . $i} * $string_test[0]->{'test_' . $i}) != 0)
          {
            $score = $score + ($score_one / $string_test_info[0]->{'test_' . $i} * $string_test[0]->{'test_' . $i});
          }

        }

        $result = $request->text;
        $j = $request->test;
        $scorefortest = 0;
        $sum = '-';
        $countquest = $string_test_info[0]->{'test_' . $j};



        if(($result != 0) && ($countquest != 0))
        {
          $scorefortest = ($result / $countquest)  * 100;
        }




        if(empty($result))
        {
          $sum = '-';
        }
        else {
          if($scorefortest >= 85)
          {
            $sum = '5';
          }
          if($scorefortest < 85 && $scorefortest >= 70)
          {
            $sum = '4';
          }
          if($scorefortest < 70 && $scorefortest >= 55)
          {
            $sum = '3';
          }
          if($scorefortest < 55)
          {
            $sum = '2';
          }
        }


        $dat['score'] = round($score, 1);
        $dat['value'] = $sum;
        $dat['str'] = $request->str;

        return $dat;

    }

    public function updateTImainRS(Request $request)
    {


        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('main_test_info')->where('id', $request->id)->update($data);

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
