<?
final class Search {
  private $sql = null;
  private $DATA = array(
    'titulo'  => array(
      'name'  => 'T&iacute;tulo',
      'db'    => 'pas_title',
      'order' => 'ASC'),
    'autor'   => array(
      'name'  => 'Autor',
      'db'    => 'pas_author',
      'order' => 'ASC'),
    'linguagem' => array(
      'name'  => 'Linguagem',
      'db'    => 'lan_name',
      'order' => 'ASC'),
    'acessos'   => array(
      'name'  => 'Acessos',
      'db'    => 'pas_view_qt',
      'order' => 'DESC'),
    'tamanho'   => array(
      'name'  => 'Tamanho',
      'db'    => 'pas_length',
      'order' => 'DESC'),
    'data'      => array(
      'name'  => 'Data',
      'db'    => 'pas_add_dt',
      'order' => 'DESC'));

  public function __construct($sql){
    $this->sql = $sql;
  }

  public function show($d){
    $q_orig = $d['q'];

    $this->check($d);
    $ret = $this->format($d['q']);

    if(empty($d['q'])){
      $get_all = true;
      $d['query_where'] = '';
    }
    else {
      $get_all = false;
      $d['query_where'] = "
      WHERE
        pas_title   ILIKE '%".$d['q']."%' OR
        pas_source_text  ILIKE '%".$d['q']."%' OR
        pas_author  ILIKE '%".$d['q']."%'";
    }

    $navigation = $this->getNavigationStuff($d, $c);
    $rs = $this->doSearch($d);

    if (is_array($rs)) {
      if (! $get_all){
        $ret .= "<p>Foram encontrados $c registros para sua busca por '$q_orig'.</p>";
      } else {
        $ret .= "<p>Nenhum crit&eacute;rio para busca, mostrando todos $c registros.</p>";
      }
      $ret .= '<hr />';

      $base = b1n_URL_ACTION.'?action=list&amp;q=' . urlencode($q_orig) .
        '&amp;pg=' . $d['pg'] . '&amp;orderby=';

      $ret .= '<table id="search"><tr>';
      $ret .= '<th>&nbsp;</th>';
      foreach($this->DATA as $col => $o){
        $url = $base;
        $order = '';
        if(Data::cmp($d['orderby'], $o['db'])){
          if(Data::cmp($d['order'], 'DESC')){
            $order = '&amp;order=ASC';
          }
          elseif(Data::cmp($d['order'], 'ASC')){
            $order = '&amp;order=DESC';
          }
        }
        $ret .= '<th><a href="'.$base.$col.$order.'">'.$o['name'].'</a></th>';
      }
      $ret .= '</tr>';

      $tr = 's_even';
      $i = 1 + (($d['pg'] - 1) * $d['per_page']);
      foreach($rs as $r){
        $id_base = base_convert($r['pas_id'], 10, b1n_CODE_BASE);

        $ret .= '<tr class="' . $tr . '">';
        $ret .= '<td class="s_number">' . $i . '</td>';
        $ret .= 
          '<td class="s_title"><a href="' . b1n_URL_ID . $id_base . '">' .
          Data::inHtmlLimit($r['pas_title'], b1n_TITLE_MAX_CHARS) . '</a></td>';

        $ret .= '<td class="s_author">';
        if(strlen($r['pas_author'])){
          $ret .=
            '<a href="' . b1n_URL_ACTION . '?action=list&amp;q=' .
            urlencode($r['pas_author']) . '">' .
            Data::inHtmlLimit($r['pas_author'], b1n_AUTHOR_MAX_CHARS).'</a>';
        }
        else {
          $ret .= 'Anônimo';
        }
        $ret .=
          '</td>' .
          '<td>' .
          Data::inHtml($r['pas_language']).'</td>';

        $ret .= '<td class="s_number">' .
          Data::inHtml($r['pas_view_qt']).'</td>';

        $ret .= '<td class="s_number">' .
          Data::inHtml(Data::formatSize($r['pas_length'])).'</td>';

        $ret .=
          '<td class="s_date">' . 
          Data::formatDateShowAbr($r['pas_add_dt']).'</td>';

        $ret .= '</tr>';
        $tr = ($tr === 's_even')?'s_odd':'s_even';
        $i++;
      }
      $ret .= '</table>';

      if(!empty($navigation)){
        $ret .= $navigation;
      }
    }
    else {
      $ret .= "Nenhum registro foi encontrado para sua busca por '$q_orig'";
    }

    return $ret;
  }

  public function doSearch($d){
    $query = "
      SELECT
        pas_id,
        lan_name AS pas_language,
        CASE
        WHEN (LENGTH(pas_title) > ".b1n_TITLE_MAX_CHARS.") THEN
          SUBSTRING(pas_title, 1, ".b1n_TITLE_MAX_CHARS.") || '...'
        WHEN(pas_title IS NULL) THEN
          'Sem título'
        ELSE
          pas_title
        END AS pas_title,
        pas_author,
        pas_length,
        pas_view_qt,
        pas_add_dt
      FROM
        paste p JOIN language l ON (p.lan_id = l.lan_id)
        " . $d['query_where'] . "
      ORDER BY " . $d['orderby'] . " " . $d['order'] . "
      LIMIT
        " . $d['per_page'] . " OFFSET " . (($d['pg'] - 1) * $d['per_page']);

    return $this->sql->query($query);
  }

  private function check(&$d){
    if(empty($d['orderby']) || !in_array($d['orderby'], array_keys($this->DATA))){
      $d['orderby'] = 'data';
    }

    if(empty($d['order']) || ($d['order'] != 'ASC' && $d['order'] != 'DESC')){
      $d['order'] = $this->DATA[$d['orderby']]['order'];
    }

    $d['orderby'] = $this->DATA[$d['orderby']]['db'];
  }

  private function format(&$q){
    $ret = '';

    $q = ereg_replace('[[:space:]]{2,}', ' ', trim($q));
    $q = addslashes($q);
    $q = str_replace('%', '\%', $q);
    $q = str_replace('_', '\_', $q);

    if(!empty($q)){
      $words = explode(' ', $q); 
      $valid_words = array();
      foreach($words as $w){
        $w = trim($w);
        if(!empty($w)){
          if(strlen($w) <= b1n_SEARCH_MIN){
            $ret .= "<p>Palavra '$w' muito pequena, desconsiderada.</p>";
          }
          else {
            $valid_words[] = $w;
          }
        }
      }
      if(sizeof($valid_words)){
        $q = implode('%', $valid_words);
      }
    }

    return $ret;
  }

  private function getNavigationStuff(&$d, &$c){
    $ret = '';
    $query = "
      SELECT
        COUNT(pas_id) AS count
      FROM
        paste".$d['query_where'];

    $res = $this->sql->singleQuery($query);
    if (!is_array($res) || !isset($res['count'])) {
        $c = 0;
    } else {
        $c = $res['count'];
    }
    $pgs = max(1, ceil($c / $d['per_page']));

    if(empty($d['pg'])){
      $d['pg'] = 1;
    }

    if($d['pg'] < 1){
      $d['pg'] = 1;
    }

    if($d['pg'] > $pgs){
      $d['pg'] = $pgs;
    }

    $orderby = '';
    foreach ($this->DATA as $t => $v) {
      if (Data::cmp($d['orderby'], $v['db'])) {
        $orderby = $t;
      }
    }

    if($pgs > 1){
      $base = b1n_URL_ACTION.'?action=list&amp;orderby='.$orderby.'&amp;q='.$d['q'];
      $ret .= '<div id="s_navigation">';
      $ret .= '<ul>';
      if($d['pg'] > 1){
        $ret .=  '
          <li><a href="' . $base . '&amp;pg=' . ($d['pg'] - 1) . '"
            title="Anterior"><img src="' . b1n_PATH_IMG . '/left.gif"
            alt="Anterior" /></a></li>';
      }

      $half = (int) b1n_SEARCH_PAGES/2;
      $x = $d['pg'] - $half;
      $y = $d['pg'] + $half;

      if($x < 1){
        $x = 1;
      }

      if($y > $pgs){
        $y = $pgs;
      }

      for($i=$x; $i<=$y; $i++){
        $ret .= '<li>';
        if($i == $d['pg']){
          $ret .= "$i";
        }
        else {
          $ret .= '<a href="' . $base . '&amp;pg=' . $i . '">' . $i . '</a>';
        }
        $ret .= '</li>';
      }

      if($pgs > $d['pg']){
        $ret .=  '
          <li><a href="' . $base . '&amp;pg=' . ($d['pg'] + 1) . '"
            title="Próxima"><img src="' . b1n_PATH_IMG . '/right.gif"
            alt="Próxima" /></a></li>';
      }
      $ret .= '</ul>';
      $ret .= '</div>';
    }
    else {
      $ret = '';
    }

    return $ret;
  }
}
?>
