<?
/*
 * $Id: Error.lib.php,v 1.2 2005/02/11 05:15:20 mmr Exp $
 * mmr 2005-01-03
 */

final class Error {
  public static function msg($msg, $method){
    $msg = "$method:$msg";
    throw new Exception($msg);
  }
}
?>
