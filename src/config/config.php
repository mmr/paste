<?
/*
 * $Id: config.php,v 1.18 2005/07/05 14:26:11 mmr Exp $
 * mmr 2005-01-02
 */

// We do NOT want magic_quotes_gpc or get_magic_quotes_runtime
if(get_magic_quotes_gpc() || get_magic_quotes_runtime()){
  die('Turn magic_quote_gpc and magic_quote_runtime off.');
}

// Prog & Author
define('b1n_VERSION',     '2.5.1');
define('b1n_PROGNAME',    'PasteB1N ' . b1n_VERSION);
define('b1n_AUTHOR_MAIL', 'mmr@b1n.org');
define('b1n_AUTHOR_NAME', 'Marcio Ribeiro');

// PATHs
define('b1n_PATH_INC',    'include');
define('b1n_PATH_CSS',    'css');   // Stylesheets
define('b1n_PATH_JS',     'js');    // Javascript
define('b1n_PATH_IMG',    'img');   // Images
define('b1n_PATH_GESHI',  'geshi'); // GeSHI

// SQL
define('b1n_SQLCONFIG_FILE', b1n_PATH_CONFIG . '/sqlconfig.php');

// Search
define('b1n_SEARCH_MIN', 2);
define('b1n_SEARCH_PER_PAGE', 20);
define('b1n_SEARCH_PAGES', 20);

// Chars
define('b1n_AUTHOR_MAX_CHARS', 10);
define('b1n_TITLE_MAX_CHARS', 16);

// Toc
define('b1n_TOP_RECENT', 7); // qtd de itens mais recentes pra mostrar na toc
define('b1n_TOP_ACCESS', 7); // qtd de itens mais acessados pra mostrar na toc

// Linguagens suportadas
define('b1n_DEFAULT_LANGUAGE', 'PHP');
$LANGUAGES = array(
 1 =>'ActionScript',
 2 =>'Ada',
 3 =>'Apache Log File',
 4 =>'ASM (NASM based)',
 5 =>'ASP',
 6 =>'Bash',
 7 =>'C',
 8 =>'C++',
 9 =>'C#',
10 =>'CSS',
11 =>'Delphi',
12 =>'HTML',
13 =>'Java',
14 =>'Javascript',
15 =>'Lisp',
16 =>'Lua',
17 =>'Objective C',
18 =>'Pascal',
19 =>'Perl',
20 =>'PHP',
21 =>'Python',
22 =>'Quick BASIC',
23 =>'SQL',
24 =>'VisualBasic',
25 =>'VB.NET',
26 =>'XML');

$LANGUAGES_FILES = array(
 1 => 'actionscript',
 2 => 'ada',
 3 => 'apache',
 4 => 'asm',
 5 => 'asp',
 6 => 'bash',
 7 => 'c',
 8 => 'cpp',
 9 => 'csharp',
10 => 'css',
11 => 'delphi',
12 => 'html4strict',
13 => 'java',
14 => 'javascript',
15 => 'lisp',
16 => 'lua',
17 => 'objc',
18 => 'pascal',
19 => 'perl',
20 => 'php',
21 => 'python',
22 => 'qbasic',
23 => 'sql',
24 => 'vb',
25 => 'vbnet',
26 => 'xml');

// Misc
define('b1n_REWRITE_ON', true);
define('b1n_COOKIE_LIFETIME', 2592000); /* 60*60*24*30 (30 days in sec) */
define('b1n_CODE_BASE', 36); // base 36 = 0123456789abcdefghijklmnopqrstuvwxyz 
define('b1n_SECRETKEY_FILE', '../doc/secured/secretkey.php'); // arquivo com chave par criptografia
?>
