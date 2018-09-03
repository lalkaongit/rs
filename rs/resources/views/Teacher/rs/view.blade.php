@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Панель управления</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif


           <h3>
               <?php
               foreach($disciplines as $discipline)
               {
                   if($discipline->id == $rs->id_discipline)
                   {
                       echo $discipline->name;
                   }
               }
               ?>

           </h3>

           <p>Группа
               <?php
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
               ?>
               </p>


               <p>Количество лекций: {{$rs->number_lectures}} / <?php  if($rs->all_points_visits > 0 && $rs->number_lectures > 0) { $b_l = ($rs->all_points_visits) / ($rs->number_lectures); }else $b_l = 0; ?>  {{ round($b_l, 1) }} за лекцию / За все лекции {{$rs->all_points_visits}}</p>

               <p>Всего баллов: {{$rs->all_points}}</p>

                   <a href="{!! route('rs.edit', ['id' => $rs->id]) !!}">Редактировать</a> ||
                       <a href="javascript:;" class="delete" rel="{{$rs->id}}">Удалить</a> ||


                       <a href="{!! route('rs.lectures', ['id' => $rs->id]) !!}">Журнал</a>



   </main>
@stop
@section('js')
   <script>
       $(function(){
           $(".delete").on('click', function () {
               if(confirm("Вы действительно хотите удалить эту запись ?")) {
                   let id = $(this).attr("rel");
                   $.ajax({
                       type: "DELETE",
                       url: "{!! route('rs.delete') !!}",
                       data: {_token:"{{csrf_token()}}", id:id},
                       complete: function() {
                           alert("Статья удалена");
                           location.reload();
                       }
                   });
               }else{
                   alertify.error("Дествие отменено пользователем");
               }
           });
       });
   </script>

@endsection
                </div>
            </div>
        </div>

    </div>
</div>
