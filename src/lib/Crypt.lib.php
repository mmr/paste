<?
// $Id: Crypt.lib.php,v 1.1 2005/02/06 01:19:53 mmr Exp $

final class Crypt {
  public static function encrypt($str){
    require_once(b1n_SECRETKEY_FILE);
    $iv =
      mcrypt_create_iv(
        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
        MCRYPT_RAND);

    $str =
      base64_encode(
        mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv));

    return $str;
  }

  public static function decrypt($str){
    require(b1n_SECRETKEY_FILE);
    $str = base64_decode($str);
    $iv  =
      mcrypt_create_iv(
        mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB),
        MCRYPT_RAND);

    $str =
      mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv);

    return $str;
  }
}
?>
