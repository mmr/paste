<?
/*
 * $Id: atom.php,v 1.4 2005/02/13 00:42:29 mmr Exp $
 * mmr 2005-02-11
 */

// Library Path
define('b1n_PATH_LIB',    'lib');
define('b1n_PATH_CONFIG', 'config');

// Libs
require_once(b1n_PATH_LIB . '/Error.lib.php');    // Error handling
require_once(b1n_PATH_LIB . '/Data.lib.php');     // Data Get/Set
require_once(b1n_PATH_LIB . '/SQLLink.lib.php');  // Database Access

// Configuration
require_once(b1n_PATH_CONFIG . '/config.php');    // Defines
require_once(b1n_PATH_CONFIG . '/setup.php');     // Headers & Dynamic Defines

try {
  $sql = new SQLLink(b1n_SQLCONFIG_FILE);

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

      CASE
      WHEN (LENGTH(pas_author) > ".b1n_AUTHOR_MAX_CHARS.") THEN
        SUBSTRING(pas_author, 1, ".b1n_AUTHOR_MAX_CHARS.") || '...'
      WHEN(pas_author IS NULL) THEN
        'Anônimo'
      ELSE
        pas_author
      END AS pas_author,

      pas_length,
      pas_view_qt,
      pas_add_dt
    FROM
      paste p JOIN language l ON (p.lan_id = l.lan_id)
    ORDER BY
      pas_add_dt DESC
    LIMIT " . b1n_TOP_RECENT; 

  $rs = $sql->query($query);
}
catch(Exception $e){
  die($e->getMessage());
}

header('Content-type: application/xml');
echo "<?xml version='1.0' encoding='ISO-8859-1' ?>\n";
?>
<feed version="0.3" xmlns="http://purl.org/atom/ns#" xml:lang="pt-br">
  <title><?= b1n_PROGNAME ?></title>
  <link rel="alternate" type="text/html" href="<?= b1n_URL ?>"/>
  <modified><?= Data::formatDateShowW3CDTF(date('r')) ?></modified>
  <author>
    <name>Marcio Ribeiro</name>
    <url>http://cv.b1n.org/</url>
  </author>
<?
    foreach($rs as $r){
      $title =
        $r['pas_title'] .  ' (' .
        $r['pas_language'] . ', ' . $r['pas_view_qt'] . ', ' .
        Data::formatSize($r['pas_length']) . ')';

      $id = base_convert($r['pas_id'], 10, b1n_CODE_BASE);
      $dt = Data::formatDateShowW3CDTF($r['pas_add_dt']);
?>
    <entry>
      <title><?= Data::inHtml($title) ?></title>
      <link rel="alternate" type="text/html"
        href="<?= b1n_URL_ID . $id ?>"/>
      <issued><?= $dt ?></issued>
      <author><name><?= Data::inHtml($r['pas_author']) ?></name></author>
      <modified><?= $dt ?></modified>
      <id><?= b1n_URL_ID . $id ?></id>
    </entry>
<?
    }
?>
</feed>
