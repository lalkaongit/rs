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
                    <h1>Список групп</h1>

                    <br>
                    <a href="{!! route('groups.add') !!}" class="btn btn-info">Добавить группу</a>
                     <a href="{!! route('importgroup') !!}" class="btn btn-info" style="margin-left:20px;">Импорт групп</a>
                    <br><br><br>



                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                              <th>Год поступления</th>
                            <th>Специальность</th>
                            <th>Действия</th>
                        </tr>
                        @foreach($groups as $group)
                            <tr>
                                <td>{{$group->id}}</td>
                                <td>{!!$group->year_adms !!}</td>
                                <td> <?php
                                foreach($specialties as $specialty)
                                {
                                    if($specialty->id == $group->id_specialty)
                                    {
                                        echo $specialty->name;
                                    }
                                }
                                    ?>
                                </td>
                                <td><a href="{!! route('groups.edit', ['id' => $group->id]) !!}">Редактировать</a> ||
                                    <a href="javascript:;" class="delete" rel="{{$group->id}}">Удалить</a></td>
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
                       url: "{!! route('groups.delete') !!}",
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
