@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Личный кабинет</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h2>Предметы</h2>
                    <div class="disciplines">
                        @foreach($trss as $trs)
                        <div>
                            <p>
                                <?php
                                $i=0;
                                foreach($disciplines as $discipline)
                                {
                                    if($discipline->id == $trs->id_discipline)
                                    {
                                        echo $discipline->name;
                                        $i++;

                                    }
                                }
                                echo '<br/>';


                                foreach($groups as $group)
                                {
                                    if($group->id == $trs->id_group)
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

                                ?>
                            </p>
                            <p><a href="{!! route('rs.view', ['id' => $trs]) !!}">Подробнее</a></p>
                        </div>
                        @endforeach
                    </div>
                    <a href="{!! route('rs.add') !!}" class="btn btn-info">Добавить БРС</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
