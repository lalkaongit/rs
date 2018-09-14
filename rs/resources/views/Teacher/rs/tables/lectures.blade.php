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

            <a v-b-toggle.accordion99 class="active">Успеваемость</a>
            <a v-b-toggle.accordion0 >Посещаемость</a>

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
                    <th>Посещено <br/>лекций</th>

                    <?php
                    for ($i = 0; $i < $countword; $i++)
                    {
                      echo '<th >', $names_mass[$i],'</th>';
                      $task_name = $names_mass[$i];
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

                      $sumfromwork  = $sumfromwork + round($sumtask, 1);
                    }
                    ?>
                  </td>

                  <td>
                    <?php

                    $sum = 0;
                    for ($i = 0; $i <= $rs->number_lectures; $i++)
                    {
                      $sum =$sum + $student->{'date_' . $i};
                    }
                    $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;

                    echo round($sum * $score_one_lecture, 1)+$sumfromwork;
                    ?>
                  </td>

                  <td>
                    <?php //Сумма баллов за посещение всех лекций

                    $sum = 0;
                    for ($i = 0; $i <= $rs->number_lectures; $i++)
                    {
                      $sum =$sum + $student->{'date_' . $i};
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




                </tr>

                @endforeach
              </table>


            </b-collapse>


            <?php


            //Таблица с лекциями
            //

            ?>



            <b-collapse id="accordion0" visible accordion="my-accordion">
              <table class="datatable" id="datatable">
                <thead>
                  <tr>
                    <th>№</th>
                    <th width="230">ФИО</th>
                    <th>Всего <br/>баллов </th>
                    <th>Посещено <br/>лекций</th>
                    <?php
                    for ($i = 0; $i <= $rs->number_lectures; $i++)
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

                  <td>
                    <?php
                    $sum = 0;
                    for ($i = 0; $i <= $rs->number_lectures; $i++)
                    {
                      $sum =$sum + $student->{'date_' . $i};
                    }
                    $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;

                    echo round($sum * $score_one_lecture, 1);
                    ?>
                  </td>

                  <td>
                    <?php
                    $sum = 0;
                    for ($i = 0; $i <= $rs->number_lectures; $i++)
                    {
                      $sum = $sum + $student->{'date_' . $i};
                    }
                    echo $sum;
                    ?>

                  </td>

                  <?php
                  $i = 0;
                  for ($i = 0; $i <= $rs->number_lectures; $i++)
                  {
                    echo '<td class="number_td" style="cursor:text;" contenteditable onblur="update(';
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

                              echo '<td> ',round($sumtask, 1),'</td>';
                            }
                          }


                          for ($j = 0; $j < $count_mass[$i]; $j++)
                          {

                            //найти id  работы

                            foreach($tasks as $task)
                            {


                              if($task->id_student == $id_stud && $task->id_rs == $rs->id && $task->name_task == $task_name)
                              {
                                echo '<td class="number_t" style="cursor:text;" contenteditable onblur="updatet(';
                                echo $task->id;
                                echo', $(this).text(),';
                                echo "'task_";
                                echo $j;
                                echo "'";
                                echo ')"';
                                echo ' id="';
                                echo $task->id;
                                echo '">';
                                echo $task->{'task_' . $j};
                                echo '</td>';

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

                <?php } ?>


                <b-collapse id="accordion200" accordion="my-accordion">
                  <table >
                    <thead>
                      <tr>
                        <th>№</th>
                        <th style="width: 230px;">ФИО</th>
                        <th>Всего <br/>баллов </th>
                        <?php
                        $counter = 0;
                        $count_tests = $rs->count_tests;
                        for ($j = 0; $j < $count_tests; $j++)
                        {
                          echo '<th>Тест № ',$j+1,'</th>';
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
                        $count_tests = $test->count_tests;
                        ?>
                      </td>

                      <td>
                        <?php

                        ?>
                      </td>


                      </tr>
                      @endforeach


                  </table>
                </b-collapse>


                <b-collapse id="accordion201" accordion="my-accordion">
                  <table >
                    <thead>
                      <tr>
                        <th>№</th>
                        <th style="width: 230px;">ФИО</th>
                        <th>Всего <br/>баллов </th>
                        <?php
                        $counter = 0;
                        $count_main_tests = $rs->count_main_tests;
                        for ($j = 0; $j < $count_main_tests; $j++)
                        {
                          echo '<th>Итоговый тест № ',$j+1,'</th>';
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
                            if($user->id == $test->id_student)
                            {
                              echo $user->surname,' ',$user->name,' ',$user->patronymic;
                            }
                          }
                          ?>
                        </td>


                        </tr>
                        @endforeach
                  </table>

                </b-collapse>




              </div>
            </div>






            <button type="submit" class="button btn-stand" name="rand" onClick="getrand('{{$string_stud_task}}')">И отвечает на вопрос:</button>
            <span id="oj" class="winner">Студент</span>




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


function update(id, text, name_column)
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
      _token: $('#signup-token').val()
    },
  });

  location.reload();

}



function updatet(id, text, name_column)
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
      _token: $('#signup-token').val()
    },
  });

  location.reload();

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



<script>
$(document).ready(function(){

  $("#theTarget").skippr();

});
</script>
@endsection
@endsection
