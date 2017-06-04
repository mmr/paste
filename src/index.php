<?
// $Id: index.php,v 1.29 2006/03/14 16:30:39 mmr Exp $
// Library Path
define('b1n_PATH_LIB',    'lib');
define('b1n_PATH_CONFIG', 'config');

// Libs
require_once(b1n_PATH_LIB . '/Error.lib.php');    // Error handling
require_once(b1n_PATH_LIB . '/Data.lib.php');     // Data Get/Set
require_once(b1n_PATH_LIB . '/SQLLink.lib.php');  // Database Access
require_once(b1n_PATH_LIB . '/Paste.lib.php');    // Paste Lib

// Configuration
require_once(b1n_PATH_CONFIG . '/config.php'); 
require_once(b1n_PATH_CONFIG . '/setup.php');     // Setup

// Session
session_start();

// Include
$inc = 'paste';

try {
  // Creating Database Connection
  $sql = new SQLLink(b1n_SQLCONFIG_FILE);
  $paste = new Paste($sql);

  if(Data::cmp($d['action'], 'add')){
    if($r = $paste->add($d)){
      $inc = 'added';
    }
  }
  elseif(Data::cmp($d['action'], 'list')){
    $inc = 'list';
  }
  elseif(Data::cmp($d['action'], 'text') &&
         Data::checkFilled($d['id']) &&
         ($r = $paste->getDataText($d)))
  {
    header("Content-type: text/plain");
    require_once(b1n_PATH_INC . '/text.inc.php');
    exit();
  }
  elseif(Data::cmp($d['action'], 'random')){
    $r = $paste->getDataRandom();
    $inc = 'show';
  }
  elseif(Data::checkFilled($d['id']) && ($r = $paste->getData($d))){
    $inc = 'show';
  }
}
catch(Exception $e){
  $exception = $e->getMessage();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
     "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='pt-br'>
<head>
  <meta http-equiv='pragma' content='no-cache'/>

  <meta http-equiv='content-type' content='text/html; charset=ISO-8859-1'/>
  <meta name='description' content='Paste Everything'/>
  <meta name='keywords' content='paste, code, programming'/>
  <meta name='robots' content='ALL'/>
  <meta name='language' content='pt-br'/>

  <link rel='shortcut icon' href='/common/img/favicon.ico'/>
  <link rel='author' href='http://b1n.org/'/>
  <link rel='home' href='<?= b1n_URL ?>'/>
  <link rel='help' href='<?= b1n_URL_ACTION ?>?action=help'/>

  <link rel='stylesheet' href='<?= b1n_PATH_CSS ?>/oldies.css'/>
  <style type='text/css'>
    @import '<?= b1n_PATH_CSS ?>/global.css';
    @import '<?= b1n_PATH_CSS ?>/form.css';
    @import '<?= b1n_PATH_CSS ?>/custom.css';
  </style>
  <title><?= b1n_PROGNAME ?></title>
</head>
<body>

<div id='container'>
  <div id='header'>
    <h1 onclick='location.href = "<?= b1n_URL ?>"'><?= b1n_PROGNAME ?></h1>
  </div>

  <div id='content'>
    <div id='left'>
      <?
      try {
        require_once(b1n_PATH_INC.'/left.inc.php');
      }
      catch(Exception $e){
        $exception = $e->getMessage();
      }
      ?>
    </div>

    <div id='right'>
      <?
      if(isset($exception)){
        echo "<div id='exception'><pre>$exception</pre></div>";
      }
      require_once(b1n_PATH_INC.'/'.$inc.'.inc.php');
      ?>
    </div>
  </div>

  <div id='footer'>
    <p>
      <a href='http://www.vim.org/'
        title='Vim the editor'><img src='img/vimpowered.gif'
        alt='Vim the editor' /></a>

      <a href='http://www.php.net/'
        title='PHP Powered'><img src='img/php.png'
        alt='Vim the editor' /></a>

      <a href='http://www.myownbeer.com/recipes/'
      title='Beer Powered'><img src='img/beerpowered.gif'
      alt='beerpowered.gif' /></a>
      
      <a href='http://www.coffeeforums.com/'
        title='Coffe Powered'><img src='img/coffee.gif'
        alt='Coffee Powered' /></a>

      <a href='http://www.mozilla.org/products/firefox/'
        title='Get Firefox NOW!'><img src='img/get-firefox.png'
        alt='Get Firefox NOW!' /></a>

      <a href='http://www.mozilla.org/products/firefox/'
        title='Yes, Internet Explorer SUCKS'><img src='img/ie_sucks.png'
        alt='Yes, Internet Explorer SUCKS!' /></a>
    </p>

    <p>
      <a href='mailto:<?= b1n_AUTHOR_MAIL?>'
        title='Mail me'><?= b1n_AUTHOR_NAME ?></a>
      - 1999-<?= date('Y'); ?>
      - &copy;<a href='http://b1n.org/'>b1n.org</a>
    </p>
  </div>
</div>
</body>
</html>
