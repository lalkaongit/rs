@extends('layouts.admin')

    @section('content')
    <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <p class="date">
        <?php
        $days = array( 1 => 'Понедельник' , 'Вторник' , 'Среда' , 'Четверг' , 'Пятница' , 'Суббота' , 'Воскресенье' );

        echo $days[date( 'N' )]," ", date("d.m.Y");

        $countlec = $rs->number_lectures;
        $today = date("d.m");
        $numberdate = 0;

        for ($i= 0; $i < $countlec; $i++)
        {
          if ($dates[0]->{'date_' . $i} == $today)
          {
            $numberdate = $i;
          }

        }

        ?>
      </p>
          <div class="card-header">


            <?php


            //Название курса

            $names_tasks = $rs->names_tasks; // строка с именами работ
            $count_tasks = $rs->count_tasks;
            $score_tasks = $rs->score_tasks;

            $names_mass = explode(",",$names_tasks); //массив с названием работ типа: (Практическая, Лабораторная)
            $count_mass = explode(",",$count_tasks); //массив с количеством работ для каждой типа: Практическая: 5 шт. Лабораторная 6шт. (5,6)
            $score_mass = explode(",",$score_tasks); //массив с количеством возможных балло за сдачу всех работ (200, 150)

            $countword = count($names_mass); //число работ (2)


            echo $specialty;


            echo ' ',(date('Y') - $group->year_adms), ' курс';
            echo '<br> <span class="izi">',$discipline, '</span>';


            ?>
          </div>

          <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">

            </div>
            @endif
            <div class="tabs">

              <a v-b-toggle.accordion99 onClick="window.location.reload()">Успеваемость</a>
              <a v-b-toggle.accordion0 >Посещаемость</a>
              <a v-b-toggle.accordion100 >Аттестация</a>
            </br>

            <?php
            if(!empty($tasks))
            {
              $nummassname = count($names_mass);
              for ($j = 0; $j < $nummassname; $j++)
              {
                echo '<a v-b-toggle.accordion',$j+1,'>',$names_mass[$j],'</a>';
              }
              $task_name = '';
            }




            foreach($tests as $test)
            {
              if($test->id_rs == $rs->id)
              {
                echo '<a v-b-toggle.accordion200>Тесты</a>';
                break;
              }
            }

            foreach($maintests as $maintest)
            {
              if($maintest->id_rs == $rs->id)
              {
                echo '<a v-b-toggle.accordion201>Итоговое тестирование</a>';
                break;
              }
            }

            if($rs->bonus == "on")
            {
              echo '<a v-b-toggle.accordion202>Бонусные баллы</a>';
            }
            ?>


          </div>

          <input type="hidden" id="signup-token" name="_token" value="{{csrf_token()}}">

          <div>
            <div class="raz">

              <b-collapse id="accordion99" visible accordion="my-accordion">
                <h2 class="name-table">Успеваемость</h2>

                <table>
                  <thead>
                    <tr>
                      <th>№</th>
                      <th width="230">ФИО</th>
                      <th>Всего <br/>баллов </th>
                      <th>Посещаемость</th>

                      <?php
                      if(!empty($tasks))
                      {
                        for ($i = 0; $i < $countword; $i++)
                        {
                          echo '<th >', $names_mass[$i],'</th>';
                          $task_name = $names_mass[$i];
                        }
                      }
                      ?>


                      <?php
                      if (!empty($test_info))
                      {
                        echo '<th>Тесты</th>';
                      }
                      ?>
                      <?php
                      if (!empty($main_test_info))
                      {
                        echo '<th>Итоговые тесты</th>';
                      }
                      ?>



                    </tr>
                  </thead>
                  <?php
                  $id_lecture_mass = array();
                  $id_student_mass = array();
                  $counter = 0;
                  $idrowlecture = 0;
                  $mainsummscoretests = 0;
                  $summscoretests = 0;
                  ?>
                  @foreach($students as $student)
                  <tr>

                    <td>
                      <?php
                      $counter++;
                      echo $counter;
                      ?>
                    </td>

                    <td>
                      <?php
                      if (!empty($test_info))
                      {
                        $summscoretests = 0;
                        $count_tests = $test_info->count_tests;

                        foreach($tests as $test)
                        {
                          if($test->id_student == $student->id_student)
                          {
                            for($i = 0; $i < $count_tests; $i++)
                            {
                              if($test_info->{'test_' . $i} !=0)
                              {
                                $summscoretests = $summscoretests + (($test_info->score_one / $test_info->{'test_' . $i}) * $test->{'test_' . $i});
                              }
                            }
                          }
                        }
                      }


                      if (!empty($main_test_info))
                      {

                        $count_tests = $main_test_info->count_tests;

                        foreach($maintests as $maintest)
                        {
                          if($maintest->id_student == $student->id_student)
                          {
                            for($i = 0; $i < $count_tests; $i++)
                            {
                              if($main_test_info->{'test_' . $i} !=0)
                              {
                                $mainsummscoretests = round($mainsummscoretests + (($main_test_info->score_one / $main_test_info->{'test_' . $i}) * $maintest->{'test_' . $i}), 1);
                              }
                            }
                          }
                        }
                      }


                      $sumfromwork = 0;
                      foreach($users as $user)
                      {
                        if($user->id == $student->id_student)
                        {
                          echo $user->surname,' ',$user->name,' ',$user->patronymic;
                          array_push($id_student_mass, $user->id); // id студентов
                          $idstudentnow = $user->id;
                        }
                      }
                      $sumtask = 0;

                      for ($i = 0; $i < $countword; $i++) //от 0 до количества внекласных работ типа: (Практическая + Лабораторная + Тест = 3)
                      {

                        foreach($tasks as $task)
                        {
                          if($task->id_student == $student->id_student && $task->name_task == $names_mass[$i])
                          {
                            for ($j = 0; $j < $count_mass[$i]; $j++) // до количества работ типа Практическая 5 шт.
                            {
                              $sumtask = $sumtask + ($task->score_one * ($task->{'task_' . $j} / 100));
                            }
                          }
                        }

                      }
                      ?>
                    </td>

                    <td>
                      <?php

                      $sum = 0;
                      for ($i = 0; $i < $rs->number_lectures; $i++)
                      {
                        $sum =$sum + $student->{'date_' . $i};
                      }
                      $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;

                      echo round($sum * $score_one_lecture, 1) + round($sumtask, 1) + round($mainsummscoretests, 1) + round($summscoretests, 1);
                      ?>
                    </td>

                    <td>
                      <?php //Сумма баллов за посещение всех лекций

                      $sum = 0;
                      for ($p = 0; $p < $rs->number_lectures; $p++)
                      {
                        $sum =$sum + $student->{'date_' . $p};
                      }
                      $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;

                      echo round($sum * $score_one_lecture, 1);
                      ?>


                    </td>


                    <?php //Сумма баллов за работы (Практическая, Лабораторная)
                    if(!empty($tasks))
                    {

                      for ($i = 0; $i < $countword; $i++) //от 0 до количества внекласных работ типа: (Практическая + Лабораторная + Тест = 3)
                      {



                        $sumtask = 0;
                        foreach($tasks as $task)
                        {
                          if($task->id_student == $student->id_student && $task->id_rs == $rs->id && $task->name_task == $names_mass[$i])
                          {
                            for ($j = 0; $j < $count_mass[$i]; $j++) // до количества работ типа Практическая 5 шт.
                            {
                              $sumtask = $sumtask + ($task->score_one * ($task->{'task_' . $j} / 100));
                            }
                          }
                        }

                        echo '<td> ',round($sumtask, 1),'</td>';
                      }
                    }


                    if (!empty($test_info))
                    {
                      echo '<td> ',round($summscoretests, 1), '</td>';
                    }

                    if (!empty($main_test_info))
                    {
                      echo '<td> ',round($mainsummscoretests, 1), '</td>';
                    }


                    ?>



                  </tr>

                  @endforeach
                </table>


              </b-collapse>


              <?php


              //Таблица с лекциями
              //

              ?>



              <b-collapse style="display:none;" id="accordion0" accordion="my-accordion">
                <h2 class="name-table">Посещаемость</h2>
                <table class="datatable my-div" id="datatable table-lec table-rs">
                  <thead>
                    <tr>
                      <th>№</th>
                      <th width="230">ФИО</th>
                      <th>Всего <br/>баллов </th>
                      <th>Посещено <br/>лекций</th>
                      <?php
                      $first_date = $dates[0]->date_0;


                      for ($i = 0; $i < $rs->number_lectures; $i++)
                      {

                        if(is_null($dates[0]->{'date_' . $i}))
                        {
                          echo '<th style="cursor:text;" oncontextmenu="$(this).html(',date("d.m"),');" contenteditable onblur="update_date(';
                          echo $rs->id;
                          echo', $(this).text(),';
                          echo "'date_";
                          echo $i;
                          echo "'";
                          echo ')" >';
                          echo date("d.m", strtotime($first_date)),' </th>';
                        }
                        else {
                          echo '<th style="cursor:text;" oncontextmenu="$(this).html(',date("d.m"),');" contenteditable onblur="update_date(';
                          echo $rs->id;
                          echo', $(this).text(),';
                          echo "'date_";
                          echo $i;
                          echo "'";
                          echo ')" >';
                          echo $dates[0]->{'date_' . $i},' </th>';
                        }

                      }
                      ?>
                    </tr>
                  </thead>
                  <?php
                  $id_lecture_mass = array();
                  $counter = 0;
                  ?>
                  @foreach($students as $student)
                  <tr>

                    <td>
                      <?php
                      $counter++;
                      echo $counter;
                      ?>
                    </td>

                    <td>
                      <?php
                      foreach($users as $user)
                      {
                        if($user->id == $student->id_student)
                        {
                          echo $user->surname,' ',$user->name,' ',$user->patronymic;
                        }
                      }
                      ?>
                    </td>

                    <td id="score{{$counter-1}}">
                      <?php
                      $sum = 0;
                      for ($i = 0; $i < $rs->number_lectures; $i++)
                      {
                        $sum =$sum + $student->{'date_' . $i};
                      }
                      $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;

                      echo round($sum * $score_one_lecture, 1);
                      ?>
                    </td>

                    <td id="summ{{$counter-1}}">
                      <?php
                      $sum = 0;
                      for ($i = 0; $i < $rs->number_lectures; $i++)
                      {
                        $sum = $sum + $student->{'date_' . $i};
                      }
                      echo $sum;
                      ?>

                    </td>

                    <?php
                    $i = 0;
                    for ($i = 0; $i < $rs->number_lectures; $i++)
                    {
                      echo '<td onClick="$(this).html(1);" oncontextmenu="$(this).html(0);" class="number_td" style="cursor:text;" contenteditable onblur="update(';
                      echo $rs->id, ",";
                      echo $counter-1, ",";
                      echo $student->id;
                      echo', $(this).text(),';
                      echo "'date_";
                      echo $i;
                      echo "'";
                      echo ')"';
                      echo ' id="';
                      echo $student->id;
                      echo '">';
                      echo $student->{'date_' . $i};
                      echo '</td>';
                    }
                    ?>
                  </tr>
                  @endforeach
                </table>
              </b-collapse>

              <?php


              //
              //
              // Вывод работ
              //
              //

              //мщу студентов которые есть в данной БРС
              $mass_stud_task = [];



              foreach($students as $student)
              {
                if($student->id_rs == $rs->id && $student->{'date_' . $numberdate} > 0){
                  array_push($mass_stud_task,$student->id_student);
                }

              }

              $mass_stud_task = array_unique($mass_stud_task); // id студентов
              $string_stud_task= "";
              $string_stud_task = implode(",", $mass_stud_task);

              ?>

              <?php
              $id_stud = 0;
              $task_name = '';
              ?>
              <div id="container">
                <div id="theTarget">


                  <?php

                  for ($i = 0; $i < $countword; $i++)
                  {
                    ?>
                    <div>
                      <b-collapse style="display:none;" id="accordion{{$i+1}}" accordion="my-accordion" >

                        <h2 class="name-table"><?php  echo $names_mass[$i]; ?>



                        </h2>

                        <table >

                          <thead>
                            <tr>
                              <th>№</th>
                              <th style="width: 230px;">ФИО</th>
                              <th>Всего <br/>баллов </th>
                              <?php
                              for ($j = 0; $j < $count_mass[$i]; $j++)
                              {
                                echo '<th >', $names_mass[$i] ,' №', $j+1, ' </th>';
                                echo '<th class="none">Оценка </th>';
                                $task_name = $names_mass[$i];
                              }
                              ?>
                            </tr>
                          </thead>

                          <?php
                          $counter = 0;

                          $u=0;
                          foreach($mass_stud_task as $mass_stud_tas){
                            echo '<tr>';
                            ?>

                            <td>
                              <?php
                              $counter++;
                              echo $counter;
                              ?>
                            </td>
                            <?php

                            foreach($users as $user)
                            {


                              if($user->id == $mass_stud_tas)
                              {
                                echo '<td>', $user->surname,' ',$user->name,' ',$user->patronymic, '</td>';
                                $id_stud = $user->id;

                                $sumtask = 0;
                                foreach($tasks as $task)
                                {
                                  if($task->id_student == $id_stud && $task->id_rs == $rs->id && $task->name_task == $task_name)
                                  {
                                    for ($j = 0; $j < $count_mass[$i]; $j++)
                                    {
                                      $sumtask = $sumtask + ($task->score_one * ($task->{'task_' . $j} / 100));

                                    }
                                  }

                                }

                                echo '<td id="',$i,'scoretask',$counter-1,'"> ',round($sumtask, 1),'</td>';
                              }
                            }


                            for ($j = 0; $j < $count_mass[$i]; $j++)
                            {


                              //найти id  работы

                              foreach($tasks as $task)
                              {


                                if($task->id_student == $id_stud && $task->name_task == $task_name)
                                {

                                  $scr_one = $score_mass[$i] / $count_mass[$i];



                                  echo '<td class="number_t" style="cursor:text;" contenteditable onblur="updatet(';
                                  echo $rs->id;
                                  echo ',';
                                  echo $task->id;
                                  echo ',';
                                  echo $i;
                                  echo ',';
                                  echo $counter-1;
                                  echo ',';
                                  echo $scr_one;
                                  echo ',';
                                  echo $count_mass[$i];
                                  echo', $(this).text(),';
                                  echo "'task_";
                                  echo $j;
                                  echo "'";
                                  echo ',';
                                  echo $j;
                                  echo ')"';
                                  echo ' id="';
                                  echo $task->id;
                                  echo '">';
                                  echo $task->{'task_' . $j};
                                  echo '</td>';


                                  if(empty($task->{'task_' . $j}))
                                  {
                                    echo '<td id="',$i,'va',$j,'lue',$counter-1,'"> - </td>';
                                  }
                                  else {
                                    if($task->{'task_' . $j} >= 85)
                                    {
                                      echo '<td id="',$i,'va',$j,'lue',$counter-1,'">', 5, '</td>';
                                    }
                                    if($task->{'task_' . $j} < 85 && $task->{'task_' . $j} >= 70)
                                    {
                                      echo '<td id="',$i,'va',$j,'lue',$counter-1,'">', 4, '</td>';
                                    }
                                    if($task->{'task_' . $j} < 70 && $task->{'task_' . $j} >= 55)
                                    {
                                      echo '<td id="',$i,'va',$j,'lue',$counter-1,'">', 3, '</td>';
                                    }
                                    if($task->{'task_' . $j} < 55)
                                    {
                                      echo '<td id="',$i,'va',$j,'lue',$counter-1,'">', 2, '</td>';
                                    }
                                  }
                                  $u++;
                                }
                              }
                            }
                            echo '</tr>';
                          }
                          ?>

                        </table>
                      </b-collapse>
                    </div>

                    <?php
                    $count_quest = array();
                  }

                  if (!empty($test_info))
                  {

                    ?>

                  </div>
                  <b-collapse style="display:none;" id="accordion200" accordion="my-accordion">
                    <h2 class="name-table">Тесты</h2>
                    <table >
                      <thead>
                        <tr>
                          <th rowspan="3">№</th>
                          <th rowspan="3" style="width: 230px;">ФИО</th>
                          <th rowspan="3" >Всего <br/>баллов </th>
                          <?php
                          $counter = 0;
                          $count_tests = $rs->count_tests;
                          for ($j = 0; $j < $count_tests; $j++)
                          {
                            echo '<th colspan="',$count_tests,'">Тест № ',$j+1,'</th>';

                          }
                          $count_tests = $test_info->count_tests;
                          ?>
                        </tr>

                        <tr>
                          <th style="border-radius:0px;" colspan="{{$count_tests*2}}">Количество вопросов в тесте</th>
                        </tr>
                        <tr>


                          <?php

                          for ($j = 0; $j < $count_tests; $j++)
                          {
                            echo '<th class="number_t" title="Количество вопросов в тесте" style="cursor:text;border-radius:0px;border: 1px solid rgba(255,255,255,0.2);" contenteditable onblur="updateti(';
                            echo $test_info->id;
                            echo', $(this).text(),';
                            echo "'test_";
                            echo $j;
                            echo "'";
                            echo ')"';
                            echo ' id="';
                            echo $test_info->id;
                            echo '">';
                            echo $test_info->{'test_' . $j};
                            echo '</th>';

                            array_push($count_quest, $test_info->{'test_' . $j});



                            echo '<th class="none">Оценка </th>';
                          }
                          ?>

                        </tr>
                      </thead>

                      @foreach($tests as $test)
                      <tr>

                        <td>
                          <?php
                          $counter++;
                          echo $counter;
                          ?>
                        </td>




                        <td>
                          <?php
                          foreach($users as $user)
                          {
                            if($user->id == $test->id_student)
                            {
                              echo $user->surname,' ',$user->name,' ',$user->patronymic;
                            }
                          }
                          ?>
                        </td>

                        <td id="{{$counter-1}}summtest">
                          <?php

                          $summscoretests = 0;
                          for($i = 0; $i < $count_tests; $i++)
                          {
                            if($test_info->{'test_' . $i} != 0)
                            {
                              $summscoretests = $summscoretests + (($test_info->score_one / $test_info->{'test_' . $i}) * $test->{'test_' . $i});
                            }

                          }
                          echo round($summscoretests, 1);
                          ?>
                        </td>



                        <?php
                        $count_tests = $test_info->count_tests;

                        for ($j = 0; $j < $count_tests; $j++)
                        {


                          echo '<td class="number_t" style="cursor:text;" contenteditable onblur="updatetest(';
                          echo $j;
                          echo ',';
                          echo $counter-1;
                          echo ',';
                          echo $rs->id;
                          echo ',';
                          echo $test->id;
                          echo', $(this).text(),';
                          echo "'test_";
                          echo $j;
                          echo "'";
                          echo ')"';
                          echo ' id="';
                          echo $test->id;
                          echo '">';
                          echo $test->{'test_' . $j};
                          echo '</td>';

                          if(!empty($test->{'test_' . $j}) && $count_quest[$j] != 0)
                          {
                            $scorefortest = ($test->{'test_' . $j} / $count_quest[$j])  * 100;
                          }




                          if(empty($test->{'test_' . $j}) || $count_quest[$j] == 0)
                          {
                            echo '<td id="',$j,'valuet',$counter-1,'"> - </td>';
                          }
                          else {
                            if($scorefortest >= 85)
                            {
                              echo '<td id="',$j,'valuet',$counter-1,'">', 5, '</td>';
                            }
                            if($scorefortest < 85 && $scorefortest >= 70)
                            {
                              echo '<td id="',$j,'valuet',$counter-1,'">', 4, '</td>';
                            }
                            if($scorefortest < 70 && $scorefortest >= 55)
                            {
                              echo '<td id="',$j,'valuet',$counter-1,'">', 3, '</td>';
                            }
                            if($scorefortest < 55)
                            {
                              echo '<td id="',$j,'valuet',$counter-1,'">', 2, '</td>';
                            }
                          }

                        }
                        ?>




                      </tr>
                      @endforeach


                    </table>
                  </b-collapse>

                  <?php
                }

                $count_quest_main = array();



                if (!empty($main_test_info))
                {

                  ?>


                  <b-collapse style="display:none;" id="accordion201" accordion="my-accordion">
                    <h2 class="name-table">Итоговое тестирование</h2>
                    <table >
                      <thead>
                        <tr>
                          <th rowspan="3">№</th>
                          <th rowspan="3" style="width: 230px;">ФИО</th>
                          <th rowspan="3" >Всего <br/>баллов </th>
                          <?php
                          $counter = 0;
                          $count_tests = $main_test_info->count_tests;
                          for ($j = 0; $j < $count_tests; $j++)
                          {
                            echo '<th colspan="',$count_tests,'">Итоговый тест № ',$j+1,'</th>';

                          }

                          ?>
                        </tr>

                        <tr>
                          <th style="border-radius:0px;" colspan="{{$count_tests*2}}">Количество вопросов в тесте</th>
                        </tr>
                        <tr>


                          <?php

                          for ($j = 0; $j < $count_tests; $j++)
                          {
                            echo '<th class="number_t" title="Количество вопросов в тесте" style="cursor:text;border-radius:0px;border: 1px solid rgba(255,255,255,0.2);" contenteditable onblur="updatetmaini(';
                            echo $main_test_info->id;
                            echo', $(this).text(),';
                            echo "'test_";
                            echo $j;
                            echo "'";
                            echo ')"';
                            echo ' id="';
                            echo $main_test_info->id;
                            echo '">';
                            echo $main_test_info->{'test_' . $j};
                            echo '</th>';

                            array_push($count_quest_main, $main_test_info->{'test_' . $j});

                            echo '<th class="none">Оценка </th>';
                          }
                          ?>

                        </tr>
                      </thead>

                      @foreach($maintests as $maintest)
                      <tr>

                        <td>
                          <?php
                          $counter++;
                          echo $counter;
                          ?>
                        </td>




                        <td>
                          <?php
                          foreach($users as $user)
                          {
                            if($user->id == $maintest->id_student)
                            {
                              echo $user->surname,' ',$user->name,' ',$user->patronymic;
                            }
                          }
                          ?>
                        </td>

                        <td  id ="{{$counter-1}}summtest-main">
                          <?php
                          $summscoretests = 0;
                          for($i = 0; $i < $count_tests; $i++)
                          {
                            if($main_test_info->{'test_' . $i} ==0)
                            {
                              $summscoretests = $summscoretests + 0;
                            }
                            else {
                              $summscoretests = $summscoretests + (($main_test_info->score_one / $main_test_info->{'test_' . $i}) * $maintest->{'test_' . $i});
                            }

                          }
                          echo round($summscoretests, 1);
                          ?>
                        </td>



                        <?php
                        $count_tests = $main_test_info->count_tests;
                        for ($j = 0; $j < $count_tests; $j++)
                        {
                          echo '<td class="number_t" style="cursor:text;" contenteditable onblur="updatemain(';
                          echo $j;
                          echo ',';
                          echo $counter-1;
                          echo ',';
                          echo $rs->id;
                          echo ',';
                          echo $maintest->id;
                          echo', $(this).text(),';
                          echo "'test_";
                          echo $j;
                          echo "'";
                          echo ')"';
                          echo ' id="';
                          echo $maintest->id;
                          echo '">';
                          echo $maintest->{'test_' . $j};
                          echo '</td>';


                          if(!empty($maintest->{'test_' . $j}) && $count_quest_main[$j] != 0)
                          {
                            $scorefortest = ($maintest->{'test_' . $j} / $count_quest_main[$j])  * 100;
                          }


                          if(empty($maintest->{'test_' . $j}))
                          {
                            echo '<td id="',$j,'valuet-main',$counter-1,'"> - </td>';
                          }
                          else {
                            if($scorefortest >= 85)
                            {
                              echo '<td id="',$j,'valuet-main',$counter-1,'">', 5, '</td>';
                            }
                            if($scorefortest < 85 && $scorefortest >= 70)
                            {
                              echo '<td id="',$j,'valuet-main',$counter-1,'">', 4, '</td>';
                            }
                            if($scorefortest < 70 && $scorefortest >= 55)
                            {
                              echo '<td id="',$j,'valuet-main',$counter-1,'">', 3, '</td>';
                            }
                            if($scorefortest < 55)
                            {
                              echo '<td id="',$j,'valuet-main',$counter-1,'">', 2, '</td>';
                            }
                          }


                        }
                        ?>




                      </tr>
                      @endforeach


                    </table>
                  </b-collapse>

                  <?php
                }
                $counter = 0;
                $array_dates_bonus = array(); //Массив дат где стоят банусные баллы !!!!!!!!!!!!!!!!!
                $array_dates_info_bonus = array(); //Массив тем по которым отвечали студенты в каждую дату !!!!!!!!!!!!!!!
                $array_dates_for_bonus = array(); // Массив в котором храняться количество кругов вопросов

                foreach ($bonuses as $bonuse) {
                  if (!in_array(date("d.m", strtotime($bonuse->created_at)) , $array_dates_bonus))
                  {
                    array_push($array_dates_bonus, date("d.m", strtotime($bonuse->created_at)));
                  }
                }


                  foreach ($array_dates_bonus as $adb)
                {
                  if(!isset($array_dates_info_bonus[$adb]))
                  {
                    $array_dates_info_bonus[$adb] = array();
                  }

                  foreach ($bonuses as $bonuse)
                  {
                  if (date("d.m", strtotime($bonuse->created_at) == $adb))
                  {

                    if (!in_array($bonuse->info, $array_dates_info_bonus[$adb]) && $bonuse->info != null)
                    {

                      //array_push($array_dates_info_bonus, $adb => $bonuse->info);
                      //$array_dates_info_bonus += array($adb => $bonuse->info);
                      //$array_dates_info_bonus += [$adb => $bonuse->info];

                      //array_push($data, array($category => $question);

                      $array_dates_info_bonus[$adb][] = $bonuse->info;

                    }
                  }
                }
              }

              foreach ($array_dates_bonus as $adb)
            {
              if(!isset($array_dates_for_bonus[$adb]))
              {
                $array_dates_for_bonus[$adb] = array();
              }

              foreach ($bonuses as $bonuse)
              {
              if (date("d.m", strtotime($bonuse->created_at) == $adb))
              {

                if ( (!in_array($bonuse->date, $array_dates_for_bonus[$adb])) && (date("d.m", strtotime($bonuse->created_at)) == $adb) )
                {

                  //array_push($array_dates_info_bonus, $adb => $bonuse->info);
                  //$array_dates_info_bonus += array($adb => $bonuse->info);
                  //$array_dates_info_bonus += [$adb => $bonuse->info];

                  //array_push($data, array($category => $question);

                  $array_dates_for_bonus[$adb][] = $bonuse->date;

                }
              }
            }
          }

              //dd($array_dates_info_bonus);
              //dd($array_dates_for_bonus, $array_dates_info_bonus);


                if (!empty($bonuses))
                {

                  ?>


                  <b-collapse style="display:none;" id="accordion202" accordion="my-accordion">
                    <h2 class="name-table">Бонусные баллы</h2>
                    <table id="bonuses" class="bonus-table">
                      <thead>
                        <tr>
                          <th rowspan="3">№</th>
                          <th rowspan="3" style="width: 230px;">ФИО</th>
                          <th rowspan="3" >Всего <br/>баллов </th>
                          <?php

                          foreach ($array_dates_bonus as $adb)
                          {
                            echo '<th colspan="',count($array_dates_for_bonus[$adb]),'">',$adb,'</th>';
                          }

                          ?>
                        </tr>
                        <tr>
                          <?php
                          foreach ($array_dates_bonus as $adb)
                          {
                          foreach ($array_dates_for_bonus[$adb] as $adfb)
                          {
                            foreach ($bonuses as $bonuse)
                            {
                              if ( ($bonuse->date == $adfb) && (date("d.m", strtotime($bonuse->created_at)) == $adb) )
                              {
                                $title = $bonuse->info;

                              }
                            }
                            echo '<th title="', $title ,'">',$adfb,'</th>';
                          }
                        }

                          ?>
                        </tr>

                      </thead>
                      @foreach($students as $student)

                      <tr>

                        <td>
                          <?php
                          $counter++;
                          echo $counter;
                          ?>
                        </td>

                        <td>
                          <?php
                          foreach($users as $user)
                          {
                            if($user->id == $student->id_student)
                            {
                              echo $user->surname,' ',$user->name,' ',$user->patronymic;
                            }
                          }
                          ?>
                        </td>

                        <td>
                          <?php
                          $sum_bonus =  0;
                          foreach ($bonuses as $bonuse)
                          {
                            if($bonuse->id_student == $student->id_student)
                            {
                              $sum_bonus += $bonuse->count_bonus;

                            }
                          }
                          echo $sum_bonus;
                          ?>
                        </td>

                        <?php

                        foreach ($array_dates_bonus as $adb)
                        {
                          foreach ($array_dates_for_bonus[$adb] as $adfb)
                          {
                            $flag = 0;

                              foreach ($bonuses as $bonuse)
                              {
                                if ( ($bonuse->date == $adfb) && (date("d.m", strtotime($bonuse->created_at)) == $adb) && ($bonuse->id_student == $student->id_student) )
                                {
                                  echo '<td>',$bonuse->count_bonus,'</td>';
                                  $flag = 1;

                                }
                            }
                            if ($flag == 0) echo '<td></td>';
                          }
                        }

                        ?>


                      </tr>
                      @endforeach



                    </table>
                  </b-collapse>

              <?php  }  ?>




              </div>
            </div>





            <div class="bonus-form">
              <p>Проставлялка бонусных баллов</p>

              <div style="margin-left:  20px;">
                <a v-on:click="score = '5'">5</a>
                <a v-on:click="score = '10'">10</a>
                <a v-on:click="score = '15'">15</a>
                <a v-on:click="score = '20'">20</a>
                <a v-on:click="score = '25'">25</a>
                <a v-on:click="score = '30'">30</a>
              </br>
              <input id="count-bonus" placeholder="Количество баллов" v-model="score"/>
            </div>
            <div>
              <a v-on:click="namescore = 'По презентации'">По презентации</a>
              <a v-on:click="namescore = 'По практическим'">По практическим</a>
              <a v-on:click="namescore = 'По компетенциям'">По компетенциям</a>
              <a v-on:click="namescore = 'По схемам'">По схемам</a>
            </br>
            <input id="info-bonus" style="width: 400px;" placeholder="Тема вопроса" v-model="namescore"/>
          </div>
          <div>
          <span class="block-rs-cookies"> <i class="fas fa-redo-alt rs_id_redo"></i><span id="cookie_rs_id">
            <?php $str = 'rs'.$rs->id;
            if (!isset($_COOKIE[$str]))
            {
              echo '0';
            }
            else echo $_COOKIE[$str];
             ?></span></span>
          <button type="submit" class="button btn-stand"  style="margin-left:  8px;" name="rand" onClick="getrand('{{$string_stud_task}}')">И отвечает на вопрос:</button>
            <span id="oj" class="winner">Студент</span>
            <input type='hidden' id="id-stud-bonus" />
            <input id="id-rs-bonus" value="{{$rs->id}}" style="display:none;"/>
            <span id="likes_number"></span>


            <button id="stav" type="submit" class="button btn-stand plus" onClick="plus()">Ответил</button>
            <button type="submit" class="button btn-stand minus" onClick="minus()">Не ответил</button>
          </div>
          <span id="suc"></span>

        </div>




        <br/><br/>

      </div>
    </div>
    </div>
    </div>
    </div>
    </div>






    @section('js')

    <script>


    function plus()
    {

     $.ajax({
      type: "POST",
      beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
      url:'{{URL::to("/plus")}}',
      data:{
        id_rs: $("#id-rs-bonus").val(),
        info:document.getElementById("info-bonus").value,
        id_stud: document.getElementById("id-stud-bonus").value,
        score: document.getElementById("count-bonus").value
      },
      success: function(){
        $("#suc").html("Схоранил ;3");
      }
    });
    }

    function minus()
    {

     $.ajax({
      type: "POST",
      beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
      url:'{{URL::to("/minus")}}',
      data:{
        id_rs: $("#id-rs-bonus").val(),
        info:document.getElementById("info-bonus").value,
        id_stud: document.getElementById("id-stud-bonus").value,
        score: document.getElementById("count-bonus").value
      },
      success: function(){
        $("#suc").html("Схоранил ;3");
      }
    });
    }


    function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}



    function getrand(mass)
    {
    console.log(mass);

    $.ajax({
      type: "POST",
      beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
      url:'{{URL::to("/getrand")}}',
      data:{
        id_rs: $("#id-rs-bonus").val(),
        array:mass
      },
      success: function(dat){
        var where = dat.lastIndexOf("/");
        var id = "";
        id = dat.substring(0,where);

        $("#oj").text(dat.substring(where+1));

        $("input#id-stud-bonus").val(id);

        if (typeof (readCookie("rs" + $("#id-rs-bonus").val())) !== 'undefined')
        {
          $("#cookie_rs_id").html(readCookie("rs" + $("#id-rs-bonus").val()));
        }
        else {
          $("#cookie_rs_id").html('0');
        }




      }
});
    }
    </script>

    <script>
    var app = new Vue({
    el: '#app',
    data: {
      isActive: false,
      array_stud: "",
      stroka: "",
      item: 0,
      score: "",
      namescore: ""
    },
    methods: {
      active(){
        this.isActive = !this.isActive;

      }
    }
    })
    </script>

    <script>

    $(document).ready(function(){
    $('.number_td').mask('0.0');

    });

    $(document).ready(function(){
    $('.number_t').mask('000');

    });


    function update(rs_id, idrow, id, text, name_column)
    {

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
      type: "POST",
      url:'{{URL::to("/update")}}',
      data:{
        text:st,
        name_column:name_column,
        id: id,
        rs_id: rs_id,
        _token: $('#signup-token').val()
      },
      success: function(dat){
        $("#score" + idrow).html(dat['score'])

        $("#summ" + idrow).html(dat['sum'])
      }
    });

    }

    function updatetmaini(id, text, name_column)
    {

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
      type: "POST",
      url:'{{URL::to("/updatetmaini")}}',
      data:{
        text:st,
        name_column:name_column,
        id: id,
        _token: $('#signup-token').val()
      },
    });



    }

    function updatemain(test, str, rs_id,id, text, name_column)
    {

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
      type: "POST",
      url:'{{URL::to("/updatemain")}}',
      data:{
        text:st,
        name_column:name_column,
        id: id,
        rs_id: rs_id,
        str: str,
        test: test,
        _token: $('#signup-token').val()
      },
      success: function(dat){
        $("#" + str + "summtest-main").html(dat['score'])

        $("#" + test + "valuet-main" + str).html(dat['value'])
      }
    });


    }

    function updatetest(test, str, rs_id, id, text, name_column)
    {

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
      type: "POST",
      url:'{{URL::to("/updatetest")}}',
      data:{
        text:st,
        name_column:name_column,
        id: id,
        rs_id: rs_id,
        str: str,
        test: test,
        _token: $('#signup-token').val()
      },
      success: function(dat){
        $("#" + str + "summtest").html(dat['score'])

        $("#" + test + "valuet" + str).html(dat['value'])
      }
    });


    }

    function updateti(id, text, name_column)
    {

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
      type: "POST",
      url:'{{URL::to("/updateti")}}',
      data:{
        text:st,
        name_column:name_column,
        id: id,
        _token: $('#signup-token').val()
      },
    });


    }

    function update_date(id_rs, text, name_column)
    {

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
      type: "POST",
      url:'{{URL::to("/update-date")}}',
      data:{
        text:st,
        name_column:name_column,
        id_rs: id_rs,
        _token: $('#signup-token').val()
      },
    });


    }

    function updatet(rs_id, id, id_task, idrow, scr_one, c_task ,text, name_column, j)
    {

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
      type: "POST",
      url:'{{URL::to("/updatet")}}',
      data:{
        text:st,
        name_column:name_column,
        id: id,
        rs_id: rs_id,
        scr_one: scr_one,
        c_task: c_task,
        _token: $('#signup-token').val()
      },
      success: function(dat){
        $("#" + id_task + "scoretask" + idrow).html(dat['score'])

        $("#" + id_task + "va" + j +"lue" + idrow).html(dat['value'])
      }
    });

    }


    </script>

    <script>
    $(document).ready(function(){
    $(".number_td").keypress(function(event){
      if(event.keyCode==13){
        event.preventDefault();
      }
    });

    });
    </script>

    <script type="text/javascript">
    $(document).ready(function() {
    $(".my-div").on("contextmenu",function(){
      return false;
    });
    });
    </script>



    @endsection
    @endsection
