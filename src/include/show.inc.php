<?
// $Id: show.inc.php,v 1.27 2005/02/06 23:36:46 mmr Exp $

if(empty($r['pas_title'])){
  $title = 'Sem t&iacute;tulo';
}
else {
  $title = Data::inHtml($r['pas_title']);
}

if(empty($r['pas_author'])){
  $author = 'An&ocirc;nimo';
}
else {
  $author = Data::inHtml($r['pas_author']);
}

$date = Data::formatDateShow($r['pas_add_dt']);
$language = Data::inHtml($r['pas_language']);

$views = $r['pas_view_qt'] . " acesso";
if($r['pas_view_qt'] > 1){
  $views .= "s";
}

/*
$line_numbers = (isset($_COOKIE['line_numbers']))?$_COOKIE['line_numbers']:true;
$highlight    = (isset($_COOKIE['highlight']))?$_COOKIE['highlight']:true;
*/

$size = Data::formatSize($r['pas_length']);
?>
<script type='text/javascript' src='<?= b1n_PATH_JS . '/cookie.js' ?>'></script>
<script type='text/javascript' src='<?= b1n_PATH_JS . '/show.js' ?>'></script>

<h3><?= $title ?></h3>
<form id='f' method='get' action='<?= b1n_URL ?>'>
<p>
  <a href='<?= b1n_URL_ID . $r['id'] ?>'
    title='<?= Data::inHtml($r['pas_title']) ?>'><?= b1n_URL_ID.$r['id'] ?></a>
  <br/>
  <?= $author ?> em  <?= $date ?>
  <small>(<?= $language . ", " . $views . ", " . $size ?>)</small><br/>

  <br/>

  <input type='hidden' name='id' value='<?= $r['id'] ?>'/>
  <input type='hidden' name='action' value=''/>

  <input type='checkbox' name='line_numbers' style='border:0'
    value='1' onclick='showLinesToggle(this)' checked='checked'/>
  Mostrar linhas

  <input type='checkbox' name='highlight' style='border:0'
    value='1' onclick='showHighlightToggle(this)' checked='checked'/>
  Iluminar sintaxe 

  <input type='button' value=' Download como Texto '
    onclick='getDataText(this)'/>
</p>
</form>

<hr/>
<h4>Código Formatado</h4>
<div id='code'>
  <?= $r['pas_source'] ?>
</div>
