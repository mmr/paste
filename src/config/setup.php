<?
/*
 * $Id: setup.php,v 1.3 2005/09/11 23:29:43 mmr Exp $
 * mmr 2005-02-10
 */

// Headers
header('Expires: Wed, 06 Aug 2003 15:50:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  // HTTP/1.1
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-Control: private');
  // HTTP/1.0
header('Pragma: no-cache');

// Getting vars from $_REQUEST
Data::getVar('id',        $d['id']);
Data::getVar('action',    $d['action']);

Data::getVar('language',  $d['language']);
Data::getVar('author',    $d['author']);
Data::getVar('title',     $d['title']);
Data::getVar('source',    $d['source']);
Data::getVar('seccode',   $d['seccode']);

// Rewrite
if(b1n_REWRITE_ON){
  define('b1n_URL','http://' . $_SERVER['HTTP_HOST']);
  define('b1n_URL_ACTION', b1n_URL . '/' . $_SERVER['SCRIPT_NAME']);
  define('b1n_URL_ID', b1n_URL . '/');
}
else {
  define('b1n_URL','http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);
  define('b1n_URL_ID', b1n_URL . '?id=');
}
?>
