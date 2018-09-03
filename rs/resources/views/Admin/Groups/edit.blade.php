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
                    <h1>Редактировать группу </h1>
       <br>

       <form method="post">
            {!! csrf_field() !!}
            <p>Введите год зачисления:<br><input type="text" name="year_adms" class="form-control" value="{{$group->year_adms}}" required> </p>
            <p>Специальность:<br>
                <select name="specialties" class="form-control" multiple>
                    @foreach($specialties as $specialty)
                    <option value="{{$specialty->id}}"
                              @if($specialty->id == ($group->id_specialty))  selected @endif>{{$specialty->name}}</option>

                    @endforeach
                </select>
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
