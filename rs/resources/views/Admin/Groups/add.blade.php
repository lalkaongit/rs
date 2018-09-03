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

                    <div class="block_flot">
                        <div>
                            <h2>Добавить <br/>группу</h2>
                            <form method="post">
                                {!! csrf_field() !!}
                                <p>Год поступления</p><input type="number" min="1900" max="2099" step="1" value="2016" name="year_adms" class="form-control"/>
                                <p>Специальность</p>
                                <select id="specialties" name="specialties" class="form-control" value="Нет данных">
                                    @foreach($specialties as $specialty)
                                    <option value="{{$specialty->id}}">{{$specialty->name}}</option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-primary">Добавить</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
