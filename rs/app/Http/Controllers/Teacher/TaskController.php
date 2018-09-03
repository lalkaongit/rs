<?php

namespace App\Http\Controllers\Teacher;

use App\Group;
use App\Specialties;
use App\Discipline;
use App\Lecture;
use App\Test;
use App\Report;
use App\Practical;
use Illuminate\Support\Collection;
use App\Lab;
use App\RS;
use App\User;
use App\Task;
use Session;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function index()
    {
        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        $objRS = new RS();
        $rss = $objRS->get();

        $data = array('rss' => $rss, 'disciplines' => $disciplines, 'groups' => $groups, 'specialties' => $specialties);

        return view('teacher.rs.index', $data); //Во вьюшку всех БРС передаю все группы, дисциплины, специальности и все БРС
    }


    public function addRequestRS(Request $request)
    {


        $id_teacher = $request->input('id_teacher');
        $id_discipline = $request->input('id_discipline');
        $id_group= $request->input('id_group');
        $all_points = $request->input('all_points');
        $all_points_visits = $request->input('all_points_visits');
        $all_points_practicals= $request->input('all_points_practicals');
        $all_points_labs = $request->input('all_points_labs');
        $all_points_reports = $request->input('all_points_reports');
        $all_points_tests= $request->input('all_points_tests');
        $number_lectures = $request->input('number_lectures');
        $number_practicals = $request->input('number_practicals');
        $number_labs= $request->input('number_labs');
        $number_reports = $request->input('number_reports');
        $number_tests = $request->input('number_tests');
        $mass_date_lpl= $request->input('mass_date_lpl');

        if($number_lectures !=0 && $all_points_visits !=0) {$score_one_lecture = $all_points_visits/$number_lectures;}
        if($number_practicals !=0 && $all_points_practicals !=0) {$score_one_practical = $all_points_practicals/$number_practicals;}
        if($number_labs !=0 && $all_points_labs !=0) {$score_one_lab = $all_points_labs/$number_labs;}
        if($number_tests !=0 && $all_points_tests !=0) {$score_one_test = $all_points_tests/$number_tests;}
        if($number_reports !=0 && $all_points_reports !=0) {$score_one_report = $all_points_reports/$number_reports;}



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
            'id_teacher' => $request->input('id_teacher'),
            'id_discipline' => $request->input('id_discipline'),
            'id_group' => $request->input('id_group'),
            'all_points' => $request->input('all_points'),
            'all_points_visits' => $request->input('all_points_visits'),
            'all_points_practicals' => $request->input('all_points_practicals'),
            'all_points_labs' => $request->input('all_points_labs'),
            'all_points_reports' => $request->input('all_points_reports'),
            'all_points_tests'=> $request->input('all_points_tests'),
            'number_lectures' => $request->input('number_lectures'),
            'number_practicals' => $request->input('number_practicals'),
            'number_labs' => $request->input('number_labs'),
            'number_reports' => $request->input('number_reports'),
            'number_tests' => $request->input('number_tests'),
            'mass_date_lpl' => $request->input('mass_date_lpl')
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

        // проверяю есть ли практики и для каждого студента резервию поля для практик
        if (isset($number_practicals))
        {

            $i=0;

            foreach($students as $student)
            {

                $objPractical = new Practical;
                $objPractical = $objPractical->create([
                    'id_teacher' => $request->input('id_teacher'),
                    'id_group' => $id_group,
                    'id_student' => $students[$i],
                    'id_discipline' => $request->input('id_discipline'),
                    'score_one_practical' => $score_one_practical,
                    'id_rs' => $ta_rs
                ]);

                $i++;

            }
        }

        // проверяю есть ли лабы и для каждого студента резервию поля для лаб

        if (isset($number_labs))
        {

            $i=0;

            foreach($students as $student)
            {

                $objLab = new Lab;
                $objLab = $objLab->create([
                    'id_teacher' => $request->input('id_teacher'),
                    'id_group' => $id_group,
                    'id_student' => $students[$i],
                    'id_discipline' => $request->input('id_discipline'),
                    'score_one_lab' => $score_one_lab,
                    'id_rs' => $ta_rs
                ]);

                $i++;

            }
        }

        if (isset($number_test))
        {

            $i=0;

            foreach($students as $student)
            {

                $objTest = new Test;
                $objTest = $objTest->create([
                    'id_teacher' => $request->input('id_teacher'),
                    'id_group' => $id_group,
                    'id_student' => $students[$i],
                    'id_discipline' => $request->input('id_discipline'),
                    'score_one_test' => $score_one_test,
                    'id_rs' => $ta_rs
                ]);

                $i++;

            }
        }

        if (isset($number_reports))
        {

            $i=0;

            foreach($students as $student)
            {

                $objReport = new Report;
                $objReport = $objReport->create([
                    'id_teacher' => $request->input('id_teacher'),
                    'id_group' => $id_group,
                    'id_student' => $students[$i],
                    'id_discipline' => $request->input('id_discipline'),
                    'score_one_reports' => $score_one_report,
                    'id_rs' => $ta_rs
                ]);

                $i++;

            }
        }

        //Проверяю не пустая ли БРС и возвращаюсь в кабинет препода

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

        $objUsers = new User();
        $users = $objUsers->get();

        $data = array('disciplines' => $disciplines, 'groups' => $groups, 'specialties' => $specialties, 'users' => $users);


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

        $id_row_lectures = array();
        //ищу студентов из группы для БРС

        foreach($lectures as $lecture)
        {
            if($lecture->id_group == $rs->id_group && $lecture->id_discipline == $rs->id_discipline && $lecture->id_teacher == $rs->id_teacher)
            {
                array_push($id_row_lectures, $lecture);
                //записываю все строки в которых содержатся те студенты что принадлежат данной БРС
            }
        }

        $data = array('rs' => $rs, 'lectures' => $lectures, 'users' => $users, 'disciplines' => $disciplines, 'specialties' => $specialties, 'id_row_lectures' => $id_row_lectures, 'groups' => $groups);

        return view('teacher.rs.tables.lectures', $data);
    }


    public function viewPracticalsRS(int $id)
    {
        //В реквест мне приходит id БРС

        //Ищу такую БРС, проверяю наличие
        $rs = RS::find($id);
        if(!$rs) {
            return abort(404);
        }

        //Смотрю все лекции, пользователей, Дисциплины, Специальности

        $objLectures = new Practical();
        $lectures = $objLectures->get();

        $objUsers = new User();
        $users = $objUsers->get();

        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        $id_row_lectures = array();
        //ищу студентов из группы для БРС

        foreach($lectures as $lecture)
        {
            if($lecture->id_group == $rs->id_group && $lecture->id_discipline == $rs->id_discipline && $lecture->id_teacher == $rs->id_teacher)
            {
                array_push($id_row_lectures, $lecture);
                //записываю все строки в которых содержатся те студенты что принадлежат данной БРС
            }
        }

        $data = array('rs' => $rs, 'lectures' => $lectures, 'users' => $users, 'disciplines' => $disciplines, 'specialties' => $specialties, 'id_row_lectures' => $id_row_lectures, 'groups' => $groups);

        return view('teacher.rs.tables.practicals', $data);
    }

    public function viewLabsRS(int $id)
    {
        //В реквест мне приходит id БРС

        //Ищу такую БРС, проверяю наличие
        $rs = RS::find($id);
        if(!$rs) {
            return abort(404);
        }

        //Смотрю все лекции, пользователей, Дисциплины, Специальности

        $objLectures = new Lab();
        $lectures = $objLectures->get();

        $objUsers = new User();
        $users = $objUsers->get();

        $objDisciplines = new Discipline();
        $disciplines = $objDisciplines->get();

        $objSpecialties = new Specialties();
        $specialties = $objSpecialties->get();

        $objGroups = new Group();
        $groups = $objGroups->get();

        $id_row_lectures = array();
        //ищу студентов из группы для БРС

        foreach($lectures as $lecture)
        {
            if($lecture->id_group == $rs->id_group && $lecture->id_discipline == $rs->id_discipline && $lecture->id_teacher == $rs->id_teacher)
            {
                array_push($id_row_lectures, $lecture);
                //записываю все строки в которых содержатся те студенты что принадлежат данной БРС
            }
        }

        $data = array('rs' => $rs, 'lectures' => $lectures, 'users' => $users, 'disciplines' => $disciplines, 'specialties' => $specialties, 'id_row_lectures' => $id_row_lectures, 'groups' => $groups);

        return view('teacher.rs.tables.labs', $data);
    }


    public function updateLecturesRS(Request $request)
    {

        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('lectures')->where('id', $request->id)->update($data);

        $lec = Lecture::find($request->id);

        $objRS = new RS();
        $rss = $objRS->get();

        foreach($rss as $rs){
            if($lec->id_group == $rs->id_group && $lec->id_discipline == $rs->id_discipline && $lec->id_teacher == $rs->id_teacher)
            {
                $ta_rs = $rs->id;
                //записываю все строки в которых содержатся те студенты что принадлежат данной БРС
            }
        }

        $sum = 0;

        for ($i = 0; $i <= $ta_rs->number_lectures; $i++)
        {
            $sum = $sum + $lec->{'date_' . $i};

        }


        $data['sum_visited'] = $sum;

        DB::table('lectures')->where('id', $request->id)->update($data);

        $data['sum_points'] = $lec->score_one_lecture;

        DB::table('lectures')->where('id', $request->id)->update($data);



    }

    public function updatePracticalsRS(Request $request)
    {

        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('practicals')->where('id', $request->id)->update($data);

    }

    public function updateLabsRS(Request $request)
    {

        $data = array();

        $data[$request->name_column] = $request->text;

        DB::table('labs')->where('id', $request->id)->update($data);

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
