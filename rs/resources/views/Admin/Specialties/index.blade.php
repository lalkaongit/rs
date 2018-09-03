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


                    <h1>Список специальностей</h1>
       <br>
       <a href="{!! route('specialties.add') !!}" class="btn btn-info">Добавить специальность</a>
       <a href="{!! route('importspec') !!}" class="btn btn-info" style="margin-left:20px;">Импорт специальностей</a>
       <br><br><br>
       <table class="table table-bordered">
           <tr>
               <th>#</th>
                 <th>Номер</th>
               <th>Наименование</th>
               <th>Действия</th>
           </tr>
           @foreach($specialties as $specialty)
               <tr>
                   <td>{{$specialty->id}}</td>
                   <td>{!! $specialty->number !!}</td>
                   <td>{{$specialty->name}}</td>
                   <td><a href="{!! route('specialties.edit', ['id' => $specialty->id]) !!}">Редактировать</a> ||
                       <a href="javascript:;" class="delete" rel="{{$specialty->id}}">Удалить</a></td>
               </tr>
           @endforeach
       </table>
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
                       url: "{!! route('specialties.delete') !!}",
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
