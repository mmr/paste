<?
/*
 * $Id: Paste.lib.php,v 1.27 2006/01/03 07:35:06 mmr Exp $
 * mmr 2005-01-25
 */

final class Paste {
  private $sql = null;

  public function __construct($sql){
    $this->sql = $sql;
  }

  public function getData($d){
    $id_10 = Data::inBd(base_convert($d['id'], b1n_CODE_BASE, 10));

    $query = "
      SELECT
        lan_name AS pas_language,

        pas_author,
        pas_title,
        pas_source,
        pas_length,
        pas_view_qt,
        pas_add_dt
      FROM
        paste p JOIN language l ON (p.lan_id = l.lan_id)
      WHERE
        pas_id  = " . $id_10;

    $r = $this->sql->singleQuery($query);

    if(is_array($r)){
      if(!isset($_COOKIE['pas_'.$d['id']])){
        $query = "
          UPDATE paste SET
            pas_view_qt = pas_view_qt + 1
          WHERE
            pas_id  = " . $id_10;

        if($this->sql->query($query)){
          setcookie('pas_'.$d['id'], '1', time()+b1n_COOKIE_LIFETIME);
        }
        $r['pas_view_qt']++;
      }
      $r['id'] = $d['id'];
      return $r;
    }
    Error::msg("C&oacute;digo inv&aacute;lido", __METHOD__);
  }

  public function getDataRandom(){
    $query = "
      SELECT
        lan_name AS pas_language,

        pas_id AS id,
        pas_author,
        pas_title,
        pas_source,
        pas_length,
        pas_view_qt,
        pas_add_dt
      FROM
        paste p JOIN language l ON (p.lan_id = l.lan_id)
      ORDER BY
        RANDOM()";

    $r = $this->sql->singleQuery($query);
    if(is_array($r)){
      $id_10 = $r['id'];
      $r['id'] = base_convert($r['id'], 10, b1n_CODE_BASE);
      if(!isset($_COOKIE['pas_'.$r['id']])){
        $query = "
          UPDATE paste SET
            pas_view_qt = pas_view_qt + 1
          WHERE
            pas_id  = " . $id_10;

        if($this->sql->query($query)){
          setcookie('pas_'.$r['id'], '1', time()+b1n_COOKIE_LIFETIME);
        }
        $r['pas_view_qt']++;
      }
      return $r;
    }
    else {
      Error::msg("N&atilde;o h&aacute; registros.", __METHOD__);
    }
    return false;
  }

  public function getDataText($d){
    $id_10 = Data::inBd(base_convert($d['id'], b1n_CODE_BASE, 10));

    $query = "
      SELECT
        pas_source_text
      FROM
        paste
      WHERE
        pas_id  = " . $id_10;

    $r = $this->sql->singleQuery($query);

    if(is_array($r)){
      return $r;
    }
    Error::msg("C&oacute;digo inv&aacute;lido", __METHOD__);
  }

  private function check($d){
    global $LANGUAGES;

    if(! Data::checkFilled($d['language'])){
      Error::msg(
        "Por favor, escolha uma linguagem.",
        __METHOD__);
    }

    if(! in_array($d['language'], array_keys($LANGUAGES))){
      Error::msg(
        "A linguagem '".$d['language']."' não é válida",
        __METHOD__);
    }

    if(empty($d['source'])){
      Error::msg(
        "Por favor, preencha o campo <b>código fonte</b>.",
        __METHOD__);
    }

    require_once(b1n_PATH_LIB . "/Crypt.lib.php");
    $seccode = $d['seccode'];
    $seccode = Crypt::encrypt(strtolower($seccode));

    if(! isset($_SESSION['seccode'])){
      Error::msg(
        "Digite o que está escrito na imagem corretamente.",
        __METHOD__);
    }

    if(strcmp($seccode, $_SESSION['seccode']) != 0){
      Error::msg(
        "Digite o que está escrito na imagem corretamente.",
        __METHOD__);
    }

    $md5 = md5($d['source']);
    $query =
        "SELECT pas_id FROM paste WHERE pas_md5 = '" . $md5 . "'";
    $rs = $this->sql->singleQuery($query);

    if(is_array($rs) && count($rs)){
      $id = base_convert($rs['pas_id'], 10, b1n_CODE_BASE);
      $url = b1n_URL_ID . $id;
      Error::msg(
        "Já existe um código igual a esse no banco de dados.<br />
         Veja: <a href='$url'>$url</a>",
        __METHOD__);
    }

    return true;
  }

  public function add($d){
    global $LANGUAGES_FILES;

    if(! $this->check($d)){
      return false;
    }

    require_once(b1n_PATH_GESHI . '/geshi.php');

    $geshi = new GeSHI(
      $d['source'], $LANGUAGES_FILES[$d['language']], b1n_PATH_GESHI . '/geshi');
    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 10);
    $geshi->set_header_type(GESHI_HEADER_DIV);
    $highlight = $geshi->parse_code();

    $this->sql->query("BEGIN TRANSACTION");

    $query = "
      INSERT INTO paste (
        lan_id,
        pas_author,
        pas_title,
        pas_source,
        pas_source_text,
        pas_length,
        pas_md5,
        pas_usr_ip
      )
      VALUES (
        " . Data::inBd($d['language']). ",
        " . Data::inBd($d['author'])  . ",
        " . Data::inBd($d['title'])   . ",
        " . Data::inBd($highlight)    . ",
        " . Data::inBd($d['source'])  . ",
        " . Data::inBd(strlen(trim($d['source']))) . ",
        md5(" . Data::inBd($d['source'])  . "),
        " . Data::inBd($_SERVER['REMOTE_ADDR']) . "
      )";

    if($this->sql->query($query)){
      $id = $this->sql->singleQuery("
        SELECT CURRVAL('paste_pas_id_seq') AS id");
      $id['id'] = base_convert($id['id'], 10, b1n_CODE_BASE);

      $this->sql->query("COMMIT TRANSACTION");
      return $id;
    }
    Error::msg("Erro inesperado", __METHOD__);
  }

  public function showDataText($id, $data){
    $div = str_repeat('=', 79) . "\n";
    $disclaimer = $div . "Extraído de ".b1n_URL_ID."$id\n" . $div;

    echo $disclaimer;
    echo $data."\n";
    echo $disclaimer;
  }

  private function getTopRecent($top){
    $query = "
      SELECT
        pas_id,
        pas_add_dt,
        lan_name AS pas_language,
        CASE
        WHEN (LENGTH(pas_title) > ".b1n_TITLE_MAX_CHARS.") THEN
          SUBSTRING(pas_title, 1, ".b1n_TITLE_MAX_CHARS.") || '...'
        WHEN (pas_title IS NULL) THEN
          'Sem título'
        ELSE
          pas_title
        END AS pas_title,
        pas_view_qt
      FROM
        paste p JOIN language l ON (p.lan_id = l.lan_id)
      ORDER BY
        pas_add_dt DESC
      LIMIT ".$top;

    return $this->sql->query($query);
  }

  private function getTopAccess($top){
    $query = "
      SELECT
        pas_id,
        lan_name AS pas_language,
        CASE
        WHEN (LENGTH(pas_title) > ".b1n_TITLE_MAX_CHARS.") THEN
          SUBSTRING(pas_title, 1, ".b1n_TITLE_MAX_CHARS.") || '...'
        WHEN (pas_title IS NULL) THEN
          'Sem título'
        ELSE
          pas_title
        END AS pas_title,
        pas_view_qt
      FROM
        paste p JOIN language l ON (p.lan_id = l.lan_id)
      ORDER BY
        pas_view_qt DESC
      LIMIT ".$top;

    return $this->sql->query($query);
  }

  public function showTopRecent($top = b1n_TOP_RECENT){
    $ret = '';
    $top_recent = $this->getTopRecent($top);

    if(is_array($top_recent)){
      $i = 1;
      $ret .= '<table>';
      $ret .= '<tr><th colspan="3">Mais Recentes</th></tr>';
      foreach($top_recent as $t){
        $url = b1n_URL_ID . base_convert($t['pas_id'], 10, b1n_CODE_BASE);
        $ret .= '<tr>';
        $ret .= '<td class="number">'.$i.'</td>';
        $ret .=
          '<td><a href="'.$url.'">' .
          Data::inHtmlLimit($t['pas_title'], b1n_TITLE_MAX_CHARS) .
          '</a></td>';
        $ret .= '<td>(' . $t['pas_language'] . ' : ' . $t['pas_view_qt'].')</td>';
        $ret .= '</tr>';
        $i++;
      }
      $ret .= '</table>';
    }

    return $ret;
  }

  public function showTopAccess($top = b1n_TOP_ACCESS){
    $ret = '';
    $top_access = $this->getTopAccess($top);

    if(is_array($top_access)){
      $i = 1;
      $ret .= '<table>';
      $ret .= '<tr><th colspan="3">Mais Acessados</th></tr>';
      foreach($top_access as $t){
        $url = b1n_URL_ID . base_convert($t['pas_id'], 10, b1n_CODE_BASE);
        $ret .= '<tr>';
        $ret .= '<td class="number">'.$i.'</td>';
        $ret .=
          '<td><a href="'.$url.'">'.
          Data::inHtmlLimit($t['pas_title'], b1n_TITLE_MAX_CHARS).
          '</a></td>';
        $ret .= '<td>(' . $t['pas_language'] . ' : ' . $t['pas_view_qt'].')</td>';
        $ret .= '</tr>';
        $i++;
      }
      $ret .= '</table>';
    }
    return $ret;
  }

  /* Static */
  public static function buildLanguagesSelect($selected){
    global $LANGUAGES;

    $ret = '<select name="language"><option value="">- Escolha -</option>';
    #if(empty($selected) || !in_array($selected, $LANGUAGES)){
    #  $selected = b1n_DEFAULT_LANGUAGE;
    #}
    foreach($LANGUAGES as $id => $lang){
      $ret .= '<option value="'.$id.'"';
      if($lang === $selected){
        $ret .= ' selected="selected"';
      }
      $ret .= '>'.$lang.'</option>'."\n";
    }
    $ret .= '</select>';
    return $ret;
  }
}
?>
