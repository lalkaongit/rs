<div id="bonus-form" class="bonus-form">
  <p>Проставлялка бонусных баллов</p>

  <div style="margin-left:  20px;" >
    <div id="valuea">


    <?php
    $values = array();

    $values = explode(',', $bonuse_info[0]->values);
    foreach ($values as $vs )
    {
      echo '<a onClick="$(';
      echo "'#count-bonus').val($(this).html());";
      echo '">',$vs;
      echo '</a>';
    }
    ?></div>
  </br>
  <input id="count-bonus" placeholder="Количество баллов"/><a v-b-tooltip.hover title="Добавить кнопку с таким значением" onClick="save_bonus_value()" id="save-value">+</a>
</div>
<div
<div id="themea">
  <?php
  $themes= array();

  $themes = explode(',', $bonuse_info[0]->themes);
  foreach ($themes as $ts )
  {
    if($ts!="")
    {
      echo '<a onClick="$(';
      echo "'#info-bonus').val($(this).html());";
      echo '">',$ts;
      echo '</a>';
    }
  }
  ?>
</br>
<input id="info-bonus" style="width: 400px;" placeholder="Тема вопроса"/><a v-b-tooltip.hover  title="Добавить кнопку с таким значением" onClick="save_bonus_theme()" id="save-value">+</a>
</div>
<div>
<span class="block-rs-cookies"> <i class="fas fa-redo-alt rs_id_redo"></i><span id="cookie_rs_id">
<?php
$rs_str = 'teacher/rs/view/bonuses/'.$rs->id;
$rs_str_att = 'teacher/rs/view/att/'.$rs->id;
$rs_str_bf = 'teacher/rs/view/bf/'.$rs->id;
$str = 'rs'.$rs->id;
if (!isset($_COOKIE[$str]))
{
  echo '0';
}
else echo $_COOKIE[$str];
 ?></span></span>
<button type="submit" class="button btn-stand"  style="margin-left:  8px;" name="rand" onClick="getrand()">И отвечает на вопрос:</button>
<span id="oj" class="winner">Студент</span>
<input type='hidden' id="id-stud-bonus" />
<input id="id-rs-bonus" value="{{$rs->id}}" style="display:none;"/>
<span id="likes_number"></span>


<button id="stav" type="submit" class="button btn-stand plus" onClick="plus()">Ответил</button>
<button type="submit" class="button btn-stand minus" onClick="minus()">Не ответил</button>
</div>

<span id="suc"></span>

<span id="suca" style="display: none"><i class="far fa-save save-bonus"></i></span>

</div>
