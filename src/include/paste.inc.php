<h3>Novo Paste</h3>

<form id='f' method='post' action='<?= b1n_URL ?>'>
  <h4>Linguagem</h4>
  <p>
    <input type='hidden' name='action' value='add' />
    <?= $paste->buildLanguagesSelect($d['language']); ?>
  </p>

  <h4>Autor</h4>
  <p>
    <input type='text' name='author' value='<?= $d['author'] ?>'
      size='65' maxlength='128' />
  </p>

  <h4>Título</h4>
  <p>
    <input type='text' name='title' value='<?= $d['title'] ?>'
      size='65' maxlength='128' />
  </p>

  <h4>Código Fonte</h4>
  <p>
    <textarea name='source' rows='10'
      cols='50'><?= Data::inHtml($d['source']) ?></textarea>
  </p>

  <h4>Digite o que você vê escrito na imagem</h4>
  <p>
    <img src='createimg.php' alt='' style='border: 1pt solid #fff' /><br />
    <input type='text' name='seccode' value='' size='10' maxlength='5' />
  </p>

  <hr />

  <p>
    <input type='submit' value='    É isso aí!    ' />
  </p>
</form>
<script type='text/javascript'>
<!--
if(document.getElementById){
  document.getElementById('f').author.focus();
}
//-->
</script>
