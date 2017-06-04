<?
/*
 * $Id: rss.php,v 1.7 2005/02/13 00:42:52 mmr Exp $
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
<rss version="2.0">
  <channel>
    <title><?= b1n_PROGNAME ?></title>
    <link><?= b1n_URL ?></link>
    <description>Códigos mais recentes</description>
    <language>pt-br</language>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
    <managingEditor>mribeiro@gmail.com (Marcio Ribeiro)</managingEditor>
    <webMaster>mribeiro@gmail.com (Marcio Ribeiro)</webMaster>
    <generator>PHP <?= phpversion() ?></generator>
    <lastBuildDate><?= date('r') ?></lastBuildDate>
    <ttl>10</ttl>
<?
    foreach($rs as $r){
      $title =
        $r['pas_title'] .  ' (' .
        $r['pas_language'] . ', ' . $r['pas_view_qt'] . ', ' .
        Data::formatSize($r['pas_length']) . ')';

      $id = base_convert($r['pas_id'], 10, b1n_CODE_BASE);
      $dt = Data::formatDateShowRSS($r['pas_add_dt']);
?>
    <item>
      <title><?= Data::inHtml($title) ?></title>
      <link><?= b1n_URL_ID . $id ?></link>
      <category><?= Data::inHtml($r['pas_language']) ?></category>
      <pubDate><?= $dt ?></pubDate>
      <guid><?= b1n_URL_ID . $id ?></guid>
    </item>
<?
    }
?>
  </channel>
</rss> 
