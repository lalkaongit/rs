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
                    <h1>Редактировать предмет</h1>
       <br>

       <form method="post">
            {!! csrf_field() !!}
            <p>Номер:<br><input type="text" name="number" class="form-control" value="{{$discipline->number}}" required> </p>
            <p>Название:<br><input type="text" name="name" class="form-control" value="{{$discipline->name}}" required> </p>
            <p>МДК:<br><input type="text" name="mdk" class="form-control" value="{{$discipline->mdk}}" required> </p>

<button type="submit" class="btn btn-success" style="cursor: pointer; float: right;">Редактировать</button>
        </form>

       </table>
   </main>
@stop

                </div>
            </div>
        </div>

    </div>
</div>
