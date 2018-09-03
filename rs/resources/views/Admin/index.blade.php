@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center admin">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Панель администратора</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="block_flot ">
                        <p><a class="btn btn-primary" href="{{ route('specialties') }}"><i class="fas fa-cog"></i> Управление специальностями</a></p>

                        <p><a class="btn btn-primary" href="{{ route('groups') }}"><i class="fas fa-cog"></i> Управление группами</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
