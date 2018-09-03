@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <?php
                    foreach($groups as $group)
                    {
                        if($group->id == $rs->id_group)
                        {
                            foreach($specialties as $specialty)
                            {
                                if($specialty->id == $group->id_specialty)
                                {
                                    echo $specialty->name;
                                    echo ' ',(date('Y') - $group->year_adms), ' курс';
                                }
                            }

                        }
                    }

                    foreach($disciplines as $discipline)
                    {
                        if($discipline->id == $rs->id_discipline)
                        {
                            echo ' (',$discipline->name, ')';


                        }
                    }
                    ?>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                        <div class="tabs">
                            <a class="active" href="{{ route('rs.lectures',['id' => $rs->id]) }}">Посещаемость</a>
                            <?php
                            if($rs->number_practicals > 0)
                            {
                              echo '<a href="';
                              echo route('rs.practicals',['id' => $rs->id]);
                              echo '">Практические работы</a>';
                            }

                            if($rs->number_labs > 0)
                            {
                              echo '<a href="';
                              echo route('rs.labs',['id' => $rs->id]);
                              echo '">Лабораторные работы</a>';
                            }

                           ?>
                            <a href="{{ route('rs.labs',['id' => $rs->id]) }}">Тесты</a>
                        </div>

                    <input type="hidden" id="signup-token" name="_token" value="{{csrf_token()}}">
                    @{{ message }}
<div class="raz">
                    <table  title="На сколько процентов выполнена практическая работа" class="datatable" id="datatable">
                        <thead>
                            <tr>
                                <th>ФИО</th>
                                <th>Всего <br/>баллов </th>

                                <?php
                                for ($i = 0; $i <= $rs->number_practicals; $i++)
                                {
                                    echo '<th>Практическая № ', $i+1;
                                    echo '</th>';
                                }
                                ?>
                            </tr>
                        </thead>
                        <?php $id_lecture_mass = array(); ?>
                        @foreach($id_row_lectures as $id_row_lecture)
                        <tr>
                            <td>
                                <?php
                                foreach($users as $user)
                                {
                                    if($user->id == $id_row_lecture->id_student)
                                    {
                                        echo $user->surname,' ',$user->name,' ',$user->patronymic;
                                    }
                                }
                                ?>
                            </td>


                            <td  onblur="update({{$id_row_lecture->id}}, $(this).text(),'sum_points')"  id="{{$id_row_lecture->id}}">
                                <?php
                                $sum = 0;
                                for ($i = 0; $i <= $rs->number_practicals; $i++)
                                {
                                    $sum = $sum + $id_row_lecture->{'date_' . $i};

                                }
                                echo round(($sum / 100) * ($rs->all_points_practicals / $rs->number_practicals), 1);
                                ?>

                            </td>

                            <?php
                            $i = 0;
                            for ($i = 0; $i <= $rs->number_practicals; $i++)
                            {
                                echo '<td class="number_td" contenteditable onblur="update(';
                                echo $id_row_lecture->id;
                                echo', $(this).text(),';
                                echo "'date_";
                                echo $i;
                                echo "'";
                                echo ')"';
                                echo ' id="';
                                echo $id_row_lecture->id;
                                echo '">';
                                echo $id_row_lecture->{'date_' . $i};
                                echo '</td>';
                            }
                            ?>
                        </tr>
                        @endforeach
                    </table>

</div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('js')


<script>
var app = new Vue({
    el: '#app',
    data: {
        message: 'Привет, Vue!'
    }
})
</script>

<script>

$(document).ready(function(){
    $('.number_td').mask('000');

});


function update(id, text, name_column)
{

    var st = text.replace(",",".");
    console.log(name_column);
    $.ajax({
        type: "POST",
        url:'{{URL::to("/updatepr")}}',
        data:{
            text:st,
            name_column:name_column,
            id: id,
            _token: $('#signup-token').val()
        },
    });
    location.reload();
}



</script>

<script>
$(document).ready(function(){
	     	 $(".number_td").keypress(function(event){
	     	   if(event.keyCode==13){
	     	   event.preventDefault();
	     	   }
	     	 });

	     });
</script>

@endsection
@endsection
