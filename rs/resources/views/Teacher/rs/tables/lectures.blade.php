@extends('layouts.admin')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <?php

          $names_tasks = $rs->names_tasks; // строка с именами работ
          $count_tasks = $rs->count_tasks;
          $score_tasks = $rs->score_tasks;

          $names_mass = explode(",",$names_tasks);
          $count_mass = explode(",",$count_tasks);
          $score_mass = explode(",",$score_tasks);

          $countword = count($names_mass); //число работ



          foreach($groups as $group)
          {
            if($group->id == $rs->id_group)
            {
              foreach($specialties as $specialty)
              {
                if($specialty->id == $group->id_specialty)
                {
                  echo $specialty->name;
                  echo ' ',(date('Y') - $group->year_adms), ' курс';
                }
              }

            }
          }

          foreach($disciplines as $discipline)
          {
            if($discipline->id == $rs->id_discipline)
            {
              echo ' (',$discipline->name, ')';

            }
          }
          ?>
        </div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">

          </div>
          @endif
          <div class="tabs">
            <a class="active" href="{{ route('rs.lectures',['id' => $rs->id]) }}">Посещаемость</a>

            <?php
            for ($j = 0; $j < count($names_mass); $j++)
            {
               echo '<a v-on:click="active">',$names_mass[$j],'</a>';
            }
            ?>


          </div>

          <input type="hidden" id="signup-token" name="_token" value="{{csrf_token()}}">

          <div class="raz">
            <table  class="datatable" id="datatable">
              <thead>
                <tr>
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
              $id_student_mass = array();
              ?>
              @foreach($id_row_lectures as $id_row_lecture)
              <tr>
                <td>
                  <?php
                  foreach($users as $user)
                  {
                    if($user->id == $id_row_lecture->id_student)
                    {
                      echo $user->surname,' ',$user->name,' ',$user->patronymic;
                      array_push($id_student_mass, $user->id); // id студентов
                    }
                  }
                  ?>
                </td>

                <td  onblur="update({{$id_row_lecture->id}}, $(this).text(),'sum_visited')"   id="{{$id_row_lecture->id}}">
                  <?php
                  $sum = 0;
                  for ($i = 0; $i <= $rs->number_lectures; $i++)
                  {
                    $sum =$sum + $id_row_lecture->{'date_' . $i};
                  }
                  $score_one_lecture = $rs->all_points_visits / $rs->number_lectures;


                  echo round($sum * $score_one_lecture, 1);
                  ?>
                </td>

                <td  onblur="update({{$id_row_lecture->id}}, $(this).text(),'sum_points')"  id="{{$id_row_lecture->id}}">
                  <?php
                  $sum = 0;
                  for ($i = 0; $i <= $rs->number_lectures; $i++)
                  {
                    $sum = $sum + $id_row_lecture->{'date_' . $i};

                  }
                  echo $sum;
                  ?>

                </td>

                <?php
                $i = 0;
                for ($i = 0; $i <= $rs->number_lectures; $i++)
                {
                  echo '<td class="number_td" contenteditable onblur="update(';
                  echo $id_row_lecture->id;
                  echo', $(this).text(),';
                  echo "'date_";
                  echo $i;
                  echo "'";
                  echo ')"';
                  echo ' id="';
                  echo $id_row_lecture->id;
                  echo '">';
                  echo $id_row_lecture->{'date_' . $i};
                  echo '</td>';
                }
                ?>
              </tr>
              @endforeach
            </table>

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


            ?>
            <?php
            $id_stud = 0;
            $task_name = '';

            for ($i = 0; $i < $countword; $i++)
            {
              ?>

              <table class="hide" v-bind:class="{activet: isActive}">

                <thead>
                  <tr>
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

                $u=0;
                foreach($mass_stud_task as $mass_stud_tas){
                  echo '<tr>';
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
                        echo '<td class="number_t" contenteditable onblur="updatet(';
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

            <?php } ?>


            <span class="winner"><?php $mass_stud_r = array_unique($id_student_mass);

          $ran = array_random($mass_stud_r);
          foreach($users as $user)
          {
            if($user->id == $ran)
            {
              echo $user->surname,' ',$user->name,' ',$user->patronymic;
            }

          }

          ?>  </span>

          <input type="submit" class="button" name="select" value="select" />



            <br/><br/>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@section('js')


<script>
var app = new Vue({
  el: '#app',
  data: {
    isActive: false
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

@endsection
@endsection