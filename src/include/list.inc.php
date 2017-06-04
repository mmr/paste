<?
// $Id: list.inc.php,v 1.10 2005/02/07 04:05:41 mmr Exp $
Data::getVar('pg',      $d['pg']);
Data::getVar('order',   $d['order']);
Data::getVar('q',       $d['q']);
Data::getVar('orderby', $d['orderby']);
$d['per_page'] = b1n_SEARCH_PER_PAGE;

require(b1n_PATH_LIB . '/Search.lib.php');
$search = new Search($sql);
echo $search->show($d);
?>
