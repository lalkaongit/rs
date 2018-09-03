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
                            <h2>Добавить дисциплину</h2>
                            <form method="post">
                                {!! csrf_field() !!}
                                <p>Номер</p><input type="text" name="number" class="form-control"/>
                                <p>Название</p><input type="text" name="name" class="form-control"/>
                                <p>МДК</p><input type="text" name="mdk" class="form-control"/>
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
