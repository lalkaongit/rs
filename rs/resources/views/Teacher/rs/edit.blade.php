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
                <div class="block_flot">
                    <div>
                        <h3>Редактирование БРС</span></h3>
                        <br>
                        <form method="post">
                            {!! csrf_field() !!}

                            <p>Преподаватель</p><input type="text" name="id_teacher" class="form-control" value="{{$rs->id_teacher}}"/>
                            <p>Дисциплина</p><input type="text" name="id_discipline" class="form-control" value="{{$rs->id_discipline}}"/>
                            <p>Группа студентов</p><input type="text" name="id_group" class="form-control" value="{{$rs->id_group}}"/>
                            <p>Количество баллов на дисциплину</p><input type="text" name="all_points" class="form-control" value="{{$rs->all_points}}"/>
                            <p>Количество лекционных занятий</p><input type="text" name="number_lectures" class="form-control" value="{{$rs->number_lectures}}"/>
                            <p>Количество практических работ</p><input type="text" name="number_practicals" class="form-control" value="{{$rs->number_practicals}}"/>
                            <p>Количество лабораторных работ</p><input type="text" name="number_labs" class="form-control" value="{{$rs->number_labs}}"/>
                            <p>Количество докладов</p><input type="text" name="number_reports" class="form-control" value="{{$rs->number_reports}}"/>
                            <p>Количество тестов</p><input type="text" name="number_tests" class="form-control" value="{{$rs->number_tests}}"/>
                            <p>Количество баллов за посещение всех лекций</p><input type="text" name="all_points_visits" class="form-control" value="{{$rs->all_points_visits}}"/>
                            <p>Количество баллов за сдачу всех практических работ</p><input type="text" name="all_points_practicals" class="form-control" value="{{$rs->all_points_practicals}}"/>
                            <p>Количество баллов за сдачу всех лабораторных работ</p><input type="text" name="all_points_labs" class="form-control" value="{{$rs->all_points_labs}}"/>

                            <button type="submit" class="btn btn-success" style="cursor: pointer; float: right;">Редактировать</button>
                        </form>
                    </table>
                </div>
            </div>
            @stop
            @section('js')

        </div>
    </div>
</div>
@endsection
