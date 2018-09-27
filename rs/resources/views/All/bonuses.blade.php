<?php
  $counter = 0;
  $array_dates_bonus = array(); //Массив дат где стоят банусные баллы !!!!!!!!!!!!!!!!!
  $array_dates_info_bonus = array(); //Массив тем по которым отвечали студенты в каждую дату !!!!!!!!!!!!!!!
  $array_dates_for_bonus = array(); // Массив в котором храняться количество кругов вопросов

  foreach ($bonuses as $bonuse)
  {
    if (!in_array(date("d.m", strtotime($bonuse->created_at)) , $array_dates_bonus))
    {
      array_push($array_dates_bonus, date("d.m", strtotime($bonuse->created_at)));
    }
  }

  foreach ($array_dates_bonus as $adb)
  {
    if(!isset($array_dates_info_bonus[$adb]))
    {
      $array_dates_info_bonus[$adb] = array();
    }
    foreach ($bonuses as $bonuse)
    {
      if (date("d.m", strtotime($bonuse->created_at) == $adb))
      {
        if (!in_array($bonuse->info, $array_dates_info_bonus[$adb]) && $bonuse->info != null)
        {
          $array_dates_info_bonus[$adb][] = $bonuse->info;
        }
      }
    }
  }

  foreach ($array_dates_bonus as $adb)
  {
    if(!isset($array_dates_for_bonus[$adb]))
    {
      $array_dates_for_bonus[$adb] = array();
    }
    foreach ($bonuses as $bonuse)
    {
      if (date("d.m", strtotime($bonuse->created_at) == $adb))
      {
        if ( (!in_array($bonuse->date, $array_dates_for_bonus[$adb])) && (date("d.m", strtotime($bonuse->created_at)) == $adb) )
        {
          $array_dates_for_bonus[$adb][] = $bonuse->date;
        }
      }
    }
  }

  if (!empty($bonuses))
  {
    ?><table id="bonuses" class="bonus-table">
        <thead>
          <tr>
            <th rowspan="3">№</th>
            <th rowspan="3" style="width: 230px;">ФИО</th>
            <th rowspan="3" >Всего <br/>баллов </th>
            <?php

            foreach ($array_dates_bonus as $adb)
            {
              echo '<th colspan="',count($array_dates_for_bonus[$adb]),'">',$adb,'</th>';
            }
            ?>
          </tr>
          <tr>
            <?php
            foreach ($array_dates_bonus as $adb)
            {
              foreach ($array_dates_for_bonus[$adb] as $adfb)
              {
                foreach ($bonuses as $bonuse)
                {
                  if ( ($bonuse->date == $adfb) && (date("d.m", strtotime($bonuse->created_at)) == $adb) )
                  {
                    $title = $bonuse->info;
                  }
                }
                echo '<th class="bonus-td" data-title="', $title ,'">',$adfb,'</th>';
              }
            }
            ?>
          </tr>

        </thead>
        @foreach($students as $student)

        <tr>

          <td>
            <?php
            $counter++;
            echo $counter;
            ?>
          </td>

          <td>
            <?php
            foreach($users as $user)
            {
              if($user->id == $student->id_student)
              {
                echo $user->surname,' ',$user->name,' ',$user->patronymic;
              }
            }
            ?>
          </td>

          <td>
            <?php
            $sum_bonus =  0;
            foreach ($bonuses as $bonuse)
            {
              if($bonuse->id_student == $student->id_student)
              {
                $sum_bonus += $bonuse->count_bonus;
              }
            }
            echo $sum_bonus;
            ?>
          </td>

          <?php
          foreach ($array_dates_bonus as $adb)
          {
            foreach ($array_dates_for_bonus[$adb] as $adfb)
            {
              $flag = 0;
              $countbon = 0;

                foreach ($bonuses as $bonuse)
                {
                  if ( ($bonuse->date == $adfb) && (date("d.m", strtotime($bonuse->created_at)) == $adb) && ($bonuse->id_student == $student->id_student) )
                  {
                    $countbon += $bonuse->count_bonus;
                    $flag = 1;


                  }
              }
              if ($flag > 0) echo '<td>',$countbon,'</td>';
              if ($flag == 0) echo '<td></td>';
            }
          }
          ?>
        </tr>

        @endforeach
        </table>

  <?php  }  ?>
