    <?php

    $countlec = $rs->number_lectures;
    $today = date("d.m");
    $numberdate = 0;

    for ($i= 0; $i < $countlec; $i++)
    {
      if ($dates[0]->{'date_' . $i} == $today) $numberdate = $i;
    }

        //Название курса

        $names_tasks = $rs->names_tasks; // строка с именами работ
        $count_tasks = $rs->count_tasks;
        $score_tasks = $rs->score_tasks;

        $att_tasks = $rs->at_tasks;

        $names_mass = explode(",",$names_tasks); //массив с названием работ типа: (Практическая, Лабораторная)
        $count_mass = explode(",",$count_tasks); //массив с количеством работ для каждой типа: Практическая: 5 шт. Лабораторная 6шт. (5,6)
        $score_mass = explode(",",$score_tasks); //массив с количеством возможных балло за сдачу всех работ (200, 150)

        $att_mass = explode(",",$att_tasks); //массив со значениями для аттестации по практическим работам

        $countword = count($names_mass); //число работ (2)





        $at_visit_max = 0;
        $at_test_max = 0;
        $at_main_test_max = 0;

        if(!empty($tasks))
        {
          for ($i = 0; $i < $countword; $i++)
          {
            ${'at_task_max'.$i} = 0;
          }
        }



              $id_lecture_mass = array();
              $id_student_mass = array();
              $counter = 0;
              $idrowlecture = 0;
              $mainsummscoretests = 0;
              $summscoretests = 0;
              $sum_bonus =  0;
              ?>
              @foreach($students as $student)



                  <?php
                  if (!empty($test_info))
                  {
                    $summscoretests = 0;
                    $count_tests = $test_info->count_tests;
                    $vr_max_t = 0;

                    foreach($tests as $test)
                    {
                      if($test->id_student == $student->id_student)
                      {
                        for($i = 0; $i < $count_tests; $i++)
                        {
                          if($test_info->{'test_' . $i} > 0)
                          {

                            $summscoretests = $summscoretests + (($test_info->score_one / $test_info->{'test_' . $i}) * $test->{'test_' . $i});
                            if($test->{'test_' . $i} > 0)
                            {
                              $vr_max_t++;
                            }


                          }
                        }
                      }
                    }
                  if($at_test_max < $vr_max_t) $at_test_max = $vr_max_t;

                  }

                  if (!empty($bonuses))
                  {
                    $sum_bonus =  0;

                  foreach ($bonuses as $bonuse)
                  {
                    if($bonuse->id_student == $student->id_student)
                    {
                      $sum_bonus += $bonuse->count_bonus;

                    }
                  }
                }


                  if (!empty($main_test_info))
                  {

                    $count_tests = $main_test_info->count_tests;
                    $vr_max_tm = 0;

                    foreach($maintests as $maintest)
                    {
                      if($maintest->id_student == $student->id_student)
                      {
                        for($i = 0; $i < $count_tests; $i++)
                        {
                          if($main_test_info->{'test_' . $i} !=0)
                          {
                            $vr_max_tm++;
                            $mainsummscoretests = round($mainsummscoretests + (($main_test_info->score_one / $main_test_info->{'test_' . $i}) * $maintest->{'test_' . $i}), 1);
                          }
                        }
                      }
                    }
                    if($at_main_test_max < $vr_max_tm) $at_main_test_max = $vr_max_tm;
                  }


                  $sumfromwork = 0;
                  foreach($users as $user)
                  {
                    if($user->id == $student->id_student)
                    {

                      array_push($id_student_mass, $user->id); // id студентов
                      $idstudentnow = $user->id;
                    }
                  }
                  $sumtask = 0;


                  for ($i = 0; $i < $countword; $i++) //от 0 до количества внекласных работ типа: (Практическая + Лабораторная + Тест = 3)
                  {
                    $vr_max_task = 0;

                    foreach($tasks as $task)
                    {
                      if($task->id_student == $student->id_student && $task->name_task == $names_mass[$i])
                      {
                        for ($j = 0; $j < $count_mass[$i]; $j++) // до количества работ типа Практическая 5 шт.
                        {


                          if($task->{'task_' . $j} > 0)
                          {
                            $vr_max_task++;
                          }
                          $sumtask = $sumtask + ($task->score_one * ($task->{'task_' . $j} / 100));

                        }
                        if (${'at_task_max'.$i} < $vr_max_task) ${'at_task_max'.$i} = $vr_max_task;

                      }
                    }


                  }

                  //Столбик сумма всех баллов

                  $sum = 0;
                  $vr_max_l = 0;
                  for ($i = 0; $i < $rs->number_lectures; $i++)
                  {
                    $sum = $sum + $student->{'date_' . $i};

                    if($student->{'date_' . $i} > 0)
                    {
                      $vr_max_l++;
                    }
                  }

                  if ($at_visit_max < $vr_max_l) $at_visit_max = $vr_max_l;

                  $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;


                  $sum = 0;
                  for ($p = 0; $p < $rs->number_lectures; $p++)
                  {
                    $sum =$sum + $student->{'date_' . $p};
                  }
                  $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;



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


                  }
                }




                ?>




              @endforeach





          <?php

          $visit_at = 0;
          if(!empty($tasks))
          {
            for ($i = 0; $i < $countword; $i++)
            {
                ${'tasks_at'.$i} = 0;

            }
          }
          $test_at = 0;
          $main_test = 0;
          $bonus_at = 0;





          ?>

              <table class="att">
              <thead>
                <tr>
                  <th rowspan="2" >№</th>
                  <th rowspan="2" rowspan="3" width="230">ФИО</th>
                  <th rowspan="2" >Всего <br/>баллов </th>
                  <th rowspan="2" >
                  Оценка
                  </th>
                  <th style="display:none" v-b-tooltip.hover title="Оценка по требованиям преподавателя" rowspan="2" >
                  Оценка*
                  </th>
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
                  <?php
                  if (!empty($bonuses))
                  {
                    echo '<th>Бонусные баллы</th>';
                  }
                  ?>




                </tr>
                <tr class="with-data-title">

                  <?php
                  echo '<th class="number_t" style="cursor:text;" v-b-tooltip.hover title="Сколько пар нужно было посетить" contenteditable onblur="update_att(';
                  echo $rs->id;
                  echo ',';
                  echo "'at_visit'";
                  echo ', $(this).text()';
                  echo ')"';
                  echo ">";
                  echo $rs->at_visit;
                  echo '</th>';

                  if(!empty($tasks))
                  {
                    for ($t = 0; $t < $countword; $t++)
                    {
                      if($rs->at_tasks == null)
                      {
                        echo '<th class="number_t" style="cursor:text;" v-b-tooltip.hover title="Сколько тестов должно быть сдано" contenteditable onblur="update_att(';
                        echo $rs->id;
                        echo ',';
                        echo "'at_tasks";
                        echo $t, "'";
                        echo ', $(this).text()';
                        echo ')"';
                        echo ">";
                        echo '</th>';

                      }
                      else {
                        echo '<th class="number_t" style="cursor:text;" v-b-tooltip.hover title="Сколько тестов должно быть сдано" contenteditable onblur="update_att(';
                        echo $rs->id;
                        echo ',';
                        echo "'at_tasks";
                        echo $t, "'";
                        echo ', $(this).text()';
                        echo ')"';
                        echo ">";
                        echo $att_mass[$t];
                        echo '</th>';
                      }
                    }
                  }

                  if (!empty($test_info))
                  {
                    echo '<th class="number_t" style="cursor:text;" v-b-tooltip.hover title="Сколько тестов должно быть сдано" contenteditable onblur="update_att(';
                    echo $rs->id;
                    echo ',';
                    echo "'at_tests'";
                    echo ', $(this).text()';
                    echo ')"';
                    echo ">";
                    echo $rs->at_tests;
                    echo '</th>';
                  }

                  if (!empty($main_test_info))
                  {
                    echo '<th class="number_t" style="cursor:text;" v-b-tooltip.hover title="Сколько итоговых тестов должно быть сдано" contenteditable onblur="update_att(';
                    echo $rs->id;
                    echo ',';
                    echo "'at_main_tests'";
                    echo ', $(this).text()';
                    echo ')"';
                    echo ">";
                    echo $rs->at_main_tests;
                    echo '</th>';
                  }

                  if (!empty($bonuses))
                  {
                    echo '<th class="number_t" style="cursor:text;" v-b-tooltip.hover title="Сколько бонусных должно быть заработано" contenteditable onblur="update_att(';
                    echo $rs->id;
                    echo ',';
                    echo "'at_bonuses'";
                    echo ', $(this).text()';
                    echo ')"';
                    echo ">";
                    echo $rs->at_bonuses;
                    echo '</th>';
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

                  $score_one_test = 0;
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
                            $score_one_test = $test_info->score_one;
                            $summscoretests = $summscoretests + (($test_info->score_one / $test_info->{'test_' . $i}) * $test->{'test_' . $i});
                          }
                        }
                      }
                    }

                  }



                  if (!empty($bonuses))
                  {

                  $sum_bonus =  0;
                  foreach ($bonuses as $bonuse)
                  {
                    if($bonuse->id_student == $student->id_student)
                    {
                      $sum_bonus += $bonuse->count_bonus;
                    }
                  }
                }

                $score_one_main_test = 0;


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
                            $score_one_main_test = $main_test_info->score_one;

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
                          ${'score_one_task'.$i} = $task->score_one;
                          $sumtask = $sumtask + ($task->score_one * ($task->{'task_' . $j} / 100));
                        }
                      }
                    }
                  }

                  ?>
                </td>

                <td>
                  <?php
                  //Столбик сумма всех баллов

                  $sum = 0;
                  $vr_max_l = 0;
                  for ($i = 0; $i < $rs->number_lectures; $i++)
                  {
                    $sum = $sum + $student->{'date_' . $i};

                    if($student->{'date_' . $i} > 0)
                    {
                      $vr_max_l++;
                    }
                  }

                  if ($at_visit_max < $vr_max_l) $at_visit_max = $vr_max_l;

                  $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;

                  $summall = round($sum * $score_one_lecture, 1) + round($sumtask, 1) + round($mainsummscoretests, 1) + round($summscoretests, 1) + $sum_bonus;

                  echo round($sum * $score_one_lecture, 1) + round($sumtask, 1) + round($mainsummscoretests, 1) + round($summscoretests, 1) + $sum_bonus;
                  ?>
                </td>
<?php
                $score_att_visit = $at_visit_max * $score_one_lecture;
                $score_att_test = $at_test_max * $score_one_test;
                $score_att_main_test = $at_main_test_max * $score_one_main_test;
                $arr_score_att_task = array();
                $summ_att = 0;

                $summ_att = $score_att_visit + $score_att_test + $score_att_main_test;

                if(!empty($tasks))
                {
                  for ($i = 0; $i < $countword; $i++) $summ_att += (${'score_one_task'.$i} * ${'at_task_max'.$i});
                }

                $att_score_stud = (($summall*100)/$summ_att);

                if(empty($att_score_stud))
                {
                  echo '<td v-b-tooltip.hover title="',round($att_score_stud,1),'%"> - </td>';
                }
                else {
                  if($att_score_stud >= 85)
                  {
                    echo '<td v-b-tooltip.hover title="',round($att_score_stud,1),'%">', 5, '</td>';
                  }
                  if($att_score_stud < 85 && $att_score_stud >= 70)
                  {
                    echo '<td v-b-tooltip.hover title="',round($att_score_stud,1),'%">', 4, '</td>';
                  }
                  if($att_score_stud < 70 && $att_score_stud >= 55)
                  {
                    echo '<td v-b-tooltip.hover title="',round($att_score_stud,1),'%">', 3, '</td>';
                  }
                  if($att_score_stud < 55)
                  {
                    echo '<td v-b-tooltip.hover title="',round($att_score_stud,1),'%">', 2, '</td>';
                  }
                }


                  //на основе вбитых данных

                  $score_att_visitp = $rs->at_visit * $score_one_lecture;
                  $score_att_testp = $rs->at_tests * $score_one_test;
                  $score_att_main_testp = $rs->at_main_tests * $score_one_main_test;
                  $summ_attp = 0;

                  $summ_attp = $score_att_visitp + $score_att_testp + $score_att_main_testp;

                  if(!empty($tasks))
                  {
                    for ($i = 0; $i < $countword; $i++) $summ_attp += ((int)${'score_one_task'.$i} * (int)$att_mass[$i]);
                  }

                  $att_score_studp = (($summall*100)/$summ_attp);

                  if(empty($att_score_studp))
                  {
                    echo '<td style="display:none"> - </td>';
                  }
                  else {
                    if($att_score_studp >= 85)
                    {
                      echo '<td style="display:none">', 5, '</td>';
                    }
                    if($att_score_studp < 85 && $att_score_studp >= 70)
                    {
                      echo '<td style="display:none">', 4, '</td>';
                    }
                    if($att_score_studp < 70 && $att_score_studp >= 55)
                    {
                      echo '<td style="display:none">', 3, '</td>';
                    }
                    if($att_score_studp < 55)
                    {
                      echo '<td style="display:none">', 2, '</td>';
                    }
                  }



?>


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
                for ($i = 0; $i < $countword; $i++) //от 0 до количества внекласных работ типа: (Практическая + Лабораторная + Тест = 3)
                {
                  ${'score_one_task'.$i} = 0;
                }

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


                if (!empty($bonuses))
                {
                echo '<td> ', $sum_bonus ,'</td>';
                }




                //на основе сдачи работ студентов


                  ?>
              </tr>

              @endforeach
