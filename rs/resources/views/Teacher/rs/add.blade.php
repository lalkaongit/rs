@extends('layouts.admin')

@section('content')
<div class="container">

  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
          {{ session('status') }}
        </div>
        @endif
        <div>
          <div>
            <h2>Создать БРС</h2>
            <form method="post">
              {!! csrf_field() !!}

              <?php $id_teacherf = Auth::user()->id;
              $test = 0;


              foreach($users as $user)
              {
                if($user->id == $id_teacherf)
                {
                  $intitut = $user->id_institution;
                }
              }

              ?>

              <input style="display:none;" type="text" name="id_teacher" value="{{$id_teacherf}}" class="form-control"/>
              <input style="display:none;" type="text" name="id_institution" value="{{$intitut}}" class="form-control"/>

              <input  style="display:none;" type="text" name="name_tasks" v-model="names" class="form-control"/>
              <input  style="display:none;" type="text" name="count_tasks" v-model="count" class="form-control"/>
              <input  style="display:none;" type="text" name="score_tasks" v-model="score" class="form-control"/>


              <p>Дисциплина</p>

              <select id="id_discipline" name="id_discipline" class="form-control" value="Нет данных">
                @foreach($disciplines as $discipline)
                <option value="{{$discipline->id}}">{{$discipline->name}}</option>
                @endforeach
              </select>

              <p>Группа студентов</p>

              <select id="id_group" name="id_group" class="form-control" value="Нет данных">
                @foreach($groups as $group)
                <option value="{{$group->id}}">
                  <?php
                  foreach($specialties as $specialty)
                  {
                    if($specialty->id == $group->id_specialty)
                    {
                      echo $specialty->name;
                      echo ' ',(date('Y') - $group->year_adms), ' курс';
                    }
                  }
                  ?>
                </option>
                @endforeach
              </select>

              <p>Количество баллов на дисциплину</p><input type="number" v-model="points" id="all_points" name="all_points" class="form-control"/>

              <div v-if="points > 0">
                <button @click="addTask" type="button" class="btn btn-primary" style="margin-bottom: 15px;">Добавить оценочные средства</button>
                <br/>

                <table >
                  <tr>
                    <td style="width:30%">Название работы</td>
                    <td style="width:30%">Количество работ в семестре</td>
                    <td style="width:30%">Баллы за выполнение всех</td>
                    <td style="width:10%">Удаление</td>
                  </tr>

                  <tr  style="background-color: rgba(255,255,255,0.2);">
                    <td><div><p class="as_input" >Аудиторные занятия</p></div></td>
                    <td><div><input type="number" name="number_lectures" class="form-control"/></div></td>
                    <td><input type="number" id="b_all_points_visits" name="all_points_visits" class="form-control"/></td>
                    <!--  <td><div><p>Процентная часть от всех баллов</p>
                    <div class="row-range">
                    <span class="range-sp"><input onclick="move(this.id)" type="range" step="5" id="all_points_visits" name="all_points_visits" class="range-in"/></span>

                  </div>
                </div></td>
              -->
              <td style="width:10%"></td>
            </tr>

            <tr v-for="(task, i) in tasks">
              <td>
                <select  class="form-control" v-model="tasks[i].type">
                  <option value="" disabled selected>Выберите из списка</option>
                  <option placeholder="Выберите" v-bind:value="tt" v-for="tt in tasksTypes">
                    @{{ tt }}
                  </option>
                </select>
              </td>
              <td> <input class="form-control" v-model="tasks[i].count" type="number"/> </td>
              <td> <input class="form-control" v-model="tasks[i].countScore" type="number"/> </td>
              <td> <button @click="deltask(i)" type="button" class="btn btn-primary">x</button> </td>
            </tr>

            <tr  style="background-color: rgba(255,255,255,0.2);">
              <td><div><p class="as_input" >Тесты</p></div></td>
              <td><div><input type="number" name="count_tests" placeholder="Количество тестов" class="form-control"/></div></td>
              <td><input type="number" name="score_tests" placeholder="Баллы за все тесты" class="form-control"/></td>
        <td style="width:10%"></td>
      </tr>

      <tr  style="background-color: rgba(255,255,255,0.2);">
        <td><div><span class="as_input" >Итоговый тест</span></div></td>
        <td><div><input type="number" name="count_main_tests" placeholder="Количество тестов" class="form-control"/></div></td>
        <td><div><input type="number" placeholder="Баллы за итоговый тест" name="score" class="form-control"/></div></td>
        <td></td>

</tr>




          </table>

          <p class="bonus-p">Бонусные баллы<input type="checkbox" name="bonus" class="rs_chek form-control"/></p>



          <div style="width: 70%;display: inline-flex;background:  #bbb;padding: 5px;border-radius: 9px;margin-top: 15px;">

            <input placeholder="Например: Диктант" style="height: 38px;margin-top: 0px;margin-right: 10px;" class="form-control" v-model="newtype"/>
            <button style="margin-top: 0px;" @click="addType(newtype)" type="button" class="btn btn-primary">Добавить свой тип оценочных средств</button>
          </div>


            <p style="display:none">  @{{saveName}} @{{saveCount}} @{{saveScore}}</p>




        </br>
      </div>

      <button type="submit" class="btn btn-primary">Создать</button>
    </form>

  </div>

</div>

</div>
</div>
</div>
</div>



<div id="app">

  <!-- use the modal component, pass in the prop -->
  <modal v-if="showModal" @close="showModal = false">
    <!--
    you can use custom content here to overwrite
    default content
  -->
  <h3 slot="header">Добавили ^_^</h3>

  <button class="btn" @click="showModal = false">
    OK
  </button>
</modal>
</div>


@endsection
@section('js')
  <script type="text/x-template" id="modal-template">
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header">
              default header
            </slot>
          </div>
          Теперь можете выбрать в списке

          <button class="btn" @click="$emit('close')">
            OK
          </button>


        </div>
      </div>
    </div>
  </transition>
  </script>

  <script>


Vue.component('modal', {
  template: '#modal-template'
})


var app = new Vue({
  el: '#app',
  data: {
    tasks: [],
    tasksTypes: ['Практическая работа', 'Лабораторная работа', 'Самостоятельная работа', 'Доклад', 'Реферат'],
    tasksTypes1: {
      prac:{
        name:'Практическая работа'
      },
      laba:{
        name:'Лабораторная работа'
      }
    },
    pr: false,
    lab: false,
    rep: false,
    test: false,
    sam: false,
    esse: false,
    ref: false,
    param: false,
    newtype: '',
    showModal: false,
    points: 0,
    names: '',
    count: '',
    score: ''
  },
  methods: {
    con(){
      if (this.points > 0 ){

        this.param = true
        console.log('!0 ',this.param)
      }
      if (this.points == 0 ){

        this.param = false
        console.log('0 ',this.param)
      }
    },
    addTask(){
      this.tasks.push({type: 'Самостоятельная'});
    },
    deltask(i){
      this.tasks.splice(i, 1);
    },
    addType(typ){
      this.tasksTypes.push(typ);
      this.showModal = true;
    }
  },
  computed: {
    saveName(){
      var names = [];
      var taskmass = this.tasks;
      taskmass.forEach(function(item, i, taskmass) {
        names.push(taskmass[i].type);
      });

      this.names = names.join();



      return names.join();
    },
    saveCount(){
      var counts = [];
      var taskmass = this.tasks;
      taskmass.forEach(function(item, i, taskmass) {
        counts.push(taskmass[i].count);
      });

      this.count = counts.join();

      return counts.join();
    },
    saveScore(){
      var countsScore = [];
      var taskmass = this.tasks;
      taskmass.forEach(function(item, i, taskmass) {
        countsScore.push(taskmass[i].countScore);
      });
      this.score = countsScore.join();

      return countsScore.join();
    }
  }
})
</script>

@endsection
@section('js')
 <script>

 function move(id)
 {
 $('#output_'+ id).val($('#' + id).val());

 $('#b_'+ id).val($('#' + id).val());
 }

 function change(id)
 {
     var str = id.substr(7);
 $('#'+ str).val($('#' + id).val());
 }

 function bchange(id)
 {
     var str = id.substr(2);
 $('#'+ str).val($('#' + id).val());
 }

   </script>



@endsection
