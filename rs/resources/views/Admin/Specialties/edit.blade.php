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
                        <h3>Редактирование специальности <span class="akc">"{{$specialty->name}} {!! $specialty->number !!}"</span></h3>
                        <br>
                        <form method="post">
                            {!! csrf_field() !!}
                            <p>Введите наименование специальности:<br><input type="text" name="name" class="form-control" value="{{$specialty->name}}" required> </p>
                            <p>Текст категории:<br><textarea name="number" class="form-control">{!! $specialty->number !!}</textarea></p>
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
