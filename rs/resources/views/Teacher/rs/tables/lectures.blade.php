@section('session')

<?php setcookie("some_cookie_name", "vu");
?>

@endsection

@extends('layouts.admin')

@section('content')
<div class="container">
  <image href="/kit.png"/>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
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

            <a v-b-toggle.accordion99 class="active" onClick="window.location.reload()">Успеваемость</a>
            <a v-b-toggle.accordion0 >Посещаемость</a>
              <a v-b-toggle.accordion100 >Аттестация</a>
              </br>

            <?php
            $nummassname = count($names_mass);
            for ($j = 0; $j < $nummassname; $j++)
            {
              echo '<a v-b-toggle.accordion',$j+1,'>',$names_mass[$j],'</a>';
            }
            $task_name = '';



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
                echo '<a v-b-toggle.accordion201>Итоговый тест</a>';
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


          <div class="raz">

            <b-collapse id="accordion99" visible accordion="my-accordion">

              <table>
                <thead>
                  <tr>
                    <th>№</th>
                    <th width="230">ФИО</th>
                    <th>Всего <br/>баллов </th>
                    <th>Посещаемость</th>

                    <?php
                    for ($i = 0; $i < $countword; $i++)
                    {
                      echo '<th >', $names_mass[$i],'</th>';
                      $task_name = $names_mass[$i];
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
                            if($test_info->{'test_' . $i} ==0)
                            {
                              $summscoretests = $summscoretests + 0;
                            }
                            else {
                              $summscoretests = $summscoretests + (($test_info->score_one / $test_info->{'test_' . $i}) * $test->{'test_' . $i});
                            }
                          }
                        }
                      }
                    }

                    ?>
                    <?php
                    if (!empty($main_test_info))
                    {
                      $mainsummscoretests = 0;
                      $count_tests = $main_test_info->count_tests;

                      foreach($maintests as $maintest)
                      {
                        if($maintest->id_student == $student->id_student)
                        {
                          for($i = 0; $i < $count_tests; $i++)
                          {
                            if($main_test_info->{'test_' . $i} ==0)
                            {
                              $mainsummscoretests = $mainsummscoretests + 0;
                            }
                            else {
                              $mainsummscoretests = round($mainsummscoretests + (($main_test_info->score_one / $main_test_info->{'test_' . $i}) * $maintest->{'test_' . $i}), 1);
                            }
                          }
                        }
                      }
                    }

                    ?>

                    <?php

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

                    for ($i = 0; $i < $countword; $i++) //от 0 до количества внекласных работ типа: (Практическая + Лабораторная + Тест = 3)
                    {
                      $sumtask = 0;
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
                      if (!empty($test_info))
                        {
                          $sumfromwork  = $sumfromwork + round($sumtask, 1) + $summscoretests;
                        }
                        else {
                          $sumfromwork  = $sumfromwork + round($sumtask, 1);
                        }

                        if (!empty($main_test_info))
                          {
                            $sumfromwork  = $sumfromwork + $mainsummscoretests;
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

                    echo round($sum * $score_one_lecture, 1)+round($sumfromwork, 1);
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

                  ?>

                  <?php
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



            <b-collapse id="accordion0" accordion="my-accordion">
              <table class="datatable" id="datatable table-lec">
                <thead>
                  <tr>
                    <th>№</th>
                    <th width="230">ФИО</th>
                    <th>Всего <br/>баллов </th>
                    <th>Посещено <br/>лекций</th>
                    <?php
                    for ($i = 0; $i < $rs->number_lectures; $i++)
                    {
                      echo '<th>Дата </th>';
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
                    echo '<td class="number_td" style="cursor:text;" contenteditable onblur="update(';
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

            foreach($tasks as $task)
            {
              if($task->id_rs == $rs->id){
                array_push($mass_stud_task,$task->id_student);
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
                    <b-collapse id="accordion{{$i+1}}" accordion="my-accordion" >

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

                <?php }

                if (!empty($test_info))
                  {

                    ?>


                <b-collapse id="accordion200" accordion="my-accordion">
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
                          echo '<th>Тест № ',$j+1,'</th>';
                        }
                          $count_tests = $test_info->count_tests;
                        ?>
                      </tr>

                        <tr>
                          <th style="border-radius:0px;" colspan="{{$count_tests}}">Количество вопросов в тесте</th>
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

                      <td>
                        <?php
                        $summscoretests = 0;
                        for($i = 0; $i < $count_tests; $i++)
                        {
                          if($test_info->{'test_' . $i} ==0)
                          {
                            $summscoretests = $summscoretests + 0;
                          }
                          else {
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
                        }
                        ?>




                      </tr>
                      @endforeach


                  </table>
                </b-collapse>

              <?php
            }



              if (!empty($main_test_info))
                {

                  ?>


              <b-collapse id="accordion201" accordion="my-accordion">
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
                        echo '<th>Итоговый тест № ',$j+1,'</th>';
                      }

                      ?>
                    </tr>

                      <tr>
                        <th style="border-radius:0px;" colspan="{{$count_tests}}">Количество вопросов в тесте</th>
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

                    <td  >
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
                      }
                      ?>




                    </tr>
                    @endforeach


                </table>
              </b-collapse>

            <?php
          }
            ?>




              </div>
            </div>






            <button type="submit" class="button btn-stand" name="rand" onClick="getrand('{{$string_stud_task}}')">И отвечает на вопрос:</button>
            <span id="oj" class="winner">Студент</span>
            <span id="likes_number"></span>




            <br/><br/>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>






@section('js')

<script>

function funsuc(data)
{
  $("#oj").text(data);
}

function getrand(mass)
{
  console.log(mass);

  $.ajax({
    type: "POST",
    beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
    url:'{{URL::to("/getrand")}}',
    dataType: "text",
    success:function(data){
      $("#oj").text(data);
    },
    data:{
      array:mass
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
    item: 0
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

  location.reload();

}

function updatemain(id, text, name_column)
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
      _token: $('#signup-token').val()
    },
  });

  location.reload();

}

function updatetest(id, text, name_column)
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
      _token: $('#signup-token').val()
    },
  });

  location.reload();

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

  location.reload();

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


@endsection
@endsection
