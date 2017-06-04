<?
// $Id: added.inc.php,v 1.8 2005/02/06 07:07:12 mmr Exp $
$url = b1n_URL_ID . $r['id'];
?>
<form id='foo' method='get' action='<?= b1n_URL ?>'>
Você pode acessar o código através do link:<br />
<textarea name='link' id='link' style='display: none'><?= $url ?></textarea>
<a href='<?= $url ?>'><?= $url ?></a><br /><br />
<script type='text/javascript'>
<!--
if(document.getElementById){
  function b1n_putInClipboard(){
    var link = document.getElementById('link');
    Copied = link.createTextRange();
    Copied.execCommand('RemoveFormat');
    Copied.execCommand('Copy');
  }
  document.write('<p><input type="button" value=" Copiar para Área de Transferência " onClick="b1n_putInClipboard();"></p>');
}
// -->
</script>
</form>
<br />
<?
$r = $paste->getData($r);
require_once(b1n_PATH_INC . '/show.inc.php');
?>
