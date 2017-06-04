<?
/*
 * $Id: left.inc.php,v 1.3 2005/09/11 23:29:43 mmr Exp $
 * mmr 2005-01-28
 */
$q = Data::getVar('q', $d['q']);
?>
<div id='links'>
  <a href='<?= b1n_URL ?>'
    title='Novo Paste'>Novo Paste</a> |
  <a href='<?= b1n_URL_ACTION ?>?action=random'
    title='Aleatório'>Aleat&oacute;rio</a>

  <form id='fs' method='get' action='<?= b1n_URL ?>'>
  <p>
    <b>Busca</b><br />
      <input type='hidden' name='action' value='list' />
      <input type='text' name='q' value='<?= $d['q'] ?>'
        size='13' maxlength='128' />
      <input type='submit' value=' Go ' />
  </p>
  </form>
</div>

<br />
<?=  $paste->showTopRecent(); ?>
<br />
<?=  $paste->showTopAccess(); ?>
<br />
<?
$css = dirname(b1n_URL) . '/' . b1n_PATH_CSS . '/global.css';
?>
<div id='standards'>
  <p>
    <a href='http://jigsaw.w3.org/css-validator/validator?uri=<?= urlencode($css) ?>'
      title='Cascading Style Sheet'><img src='<?= b1n_PATH_IMG ?>/css.png'
      alt='Cascading Style Sheet' /></a><br />

    <a href='http://validator.w3.org/check/referer'
      title='XHTML 1.1'><img src='<?= b1n_PATH_IMG ?>/xhtml11.png'
      alt='<?= b1n_PATH_IMG ?>/xhtml11.png' /></a><br />

    <a href='http://www.tableless.com.br/'
      title='Tableless'><img src='<?= b1n_PATH_IMG ?>/tableless.png'
      alt='Tableless' /></a><br />

  </p>
</div>

<br />

<div id='feeds'>
  <p>
    <a href='rss.php'
      title='RSS Feed'><img src='<?= b1n_PATH_IMG ?>/rss2.png'
      alt='RSS Feed' /></a><br />

    <a href='atom.php'
      title='Atom Feed'><img src='<?= b1n_PATH_IMG ?>/atom.png'
      alt='Atom Feed' /></a><br />
  </p>
</div>
