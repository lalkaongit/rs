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
                        <h2>Добавить специальность</h2>
                        <form method="post">
                            {!! csrf_field() !!}
                            <p>Название специальности</p><input type="text" name="name" class="form-control"/>
                            <p>Номер специальности</p><input type="text" name="number" class="form-control"/>
                            <button type="submit" class="btn btn-primary">Добавить</button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection
