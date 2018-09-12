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
              echo '<br> (',$discipline->name, ')';

            }
          }
          //Название курса (конец)

          ?>
        </div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">

          </div>
          @endif
          <div class="tabs">
            <a v-b-toggle.accordion0 class="active">Посещаемость</a>

            <?php
            for ($j = 0; $j < count($names_mass); $j++)
            {
               echo '<a v-b-toggle.accordion',$j+1,'>',$names_mass[$j],'</a>';
            }
            ?>


          </div>

          <input type="hidden" id="signup-token" name="_token" value="{{csrf_token()}}">

          <div class="raz">
            <b-collapse id="accordion0" visible accordion="my-accordion">
            <table class="datatable" id="datatable">
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
                  echo '<td class="number_td" style="cursor:text;" contenteditable onblur="update(';
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
