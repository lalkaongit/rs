@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Админка</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <h1>Список дисциплин</h1>

                    <br>
                    <a href="{!! route('disciplines.add') !!}" class="btn btn-info">Добавить дисциплину</a>
                     <a href="{!! route('importdiscp') !!}" class="btn btn-info" style="margin-left:20px;">Импорт дисциплин</a>
                    <br><br><br>



                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                              <th>Номер</th>
                            <th>Название</th>
                            <th>МДК</th>
                            <th>Действия</th>
                        </tr>
                        @foreach($disciplines as $discipline)
                            <tr>
                                <td>{{$discipline->id}}</td>
                                <td>{!!$discipline->number !!}</td>
                                <td>  {!!$discipline->name !!} </td>
                                <td>  {!!$discipline->mdk !!} </td>
                                <td><a href="{!! route('disciplines.edit', ['id' => $discipline->id]) !!}">Редактировать</a> ||
                                    <a href="javascript:;" class="delete" rel="{{$discipline->id}}">Удалить</a></td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
   <script>
       $(function(){
           $(".delete").on('click', function () {
               if(confirm("Вы действительно хотите удалить эту запись ?")) {
                   let id = $(this).attr("rel");
                   $.ajax({
                       type: "DELETE",
                       url: "{!! route('disciplines.delete') !!}",
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
