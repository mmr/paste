<?
/*
 * $Id: Data.lib.php,v 1.9 2005/02/11 06:34:44 mmr Exp $
 * mmr 2005-02-01
 */

final class Data {

  # Pega variavel de $_REQUEST ($_GET + $_POST + $_COOKIE)
  public static function getVar($var, &$dest, $default=''){
    $dest = $default;

    if($ret = isset($_REQUEST[$var])){
      $dest = $_REQUEST[$var];
    }

    return $ret;
  }

  # Comparacao dependendo do tipo (===)
  public static function cmp($v1, $v2){
    // Numeric
    if(is_numeric($v1) && is_numeric($v2)){
      return $v1 == $v2;
    }
    else {
    // String
      return (strcmp($v1, $v2) == 0);
    }
  }

  # Formata dados para BD, tirando espacos em branco do fim e comeco
  # fazendo escapeamento de '
  public static function inBd($var, $delim = "'"){
    $var = trim($var);

    if(strlen($var)==0 || is_null($var)){
      return 'NULL';
    }

    return $delim . addslashes($var) . $delim;
  }

  # converte caracteres como <,>,",' para entidades HTML (&lt;, &gt;, etc)
  public static function inHtml($var){
    return htmlspecialchars($var, ENT_QUOTES);
  }

  # inHtml sem br (break, quebra de linha)
  public static function inHtmlNoBr($var){
    return "<span style='white-space: nowrap'>" .
      htmlspecialchars($var, ENT_QUOTES) . "</span>";
  }

  # inHtml com limitacao de caracteres para amostragem
  public static function inHtmlLimit($var, $max, $cont = "..."){
    return self::inHtml(
      (strlen($var) <= $max)? $var : (substr($var, 0, $max) . $cont));
  }

  #-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
  # Metodos de Checagem 

  # Checagem de Data
  public static function checkDate($month, $day, $year, $mandatory = false){
    if(!$mandatory && (empty($month) && empty($day) && empty($year))){
      return true;
    }
    return checkDate($month, $day, $year);
  }

  public static function checkDateArray($dt, $mandatory = false){
    if(isset($dt['day']) && isset($dt['month']) && isset($dt['year'])){
      return self::checkDate(
        $dt['day'], $dt['month'], $dt['year'], $mandatory);
    }
    if($mandatory){
      return false;
    }
  }

  # Checagem de Hora
  public static function checkHour($hour, $min, $mandatory = false){
    if(!$mandatory && (empty($hour) && empty($min))){
      return true;
    }

    $ret = self::checkNumeric($hour, $mandatory) &&
           self::checkNumeric($min, $mandatory)  &&
           ($hour >= 0 && $hour <= 23) &&
           ($min  >= 0 && $min  <= 59);

    return $ret;
  }

  # Checagem de data e hora
  public static function checkDateHour(
    $month, $day, $year, $hour, $min, $mandatory = false)
  {
    return self::checkDate($month, $day, $year, $mandatory) && 
           self::checkHour($hour, $min, $mandatory);
  }

  # Checagem de tipo numerico
  public static function checkNumeric($str, $mandatory = false){
    if(!$mandatory && empty($str)){
      return true;
    }

    return is_numeric($str);
  }

  # Checagem de preenchimento
  public static function checkFilled($str){
    return is_numeric($str) || !empty($str);
  }

  # Checagem de tipo booleano
  public static function checkBoolean($str, $mandatory = false){
    if(!$mandatory && empty($str)){
      return true;
    }

    // PHP Boolean
    $ret = is_bool($str);

    // PostgreSQL Boolean
    if(!$ret){
     $ret = self::cmp($str, 't') || self::cmp($str, 'f');
    }

    // Numeric Boolean
    if(!$ret){
      $ret = is_numeric($str) && ($str === 0 || $str === 1);
    }

    return $ret;
  }

  # Checagem de telefone (pode conter numeros e/ou -)
  public static function checkPhone($str, $mandatory = false){
    if(!$mandatory && empty($str)){
      return true;
    }

    return preg_match('#^[\d-]+$#', $str);
  }

  # Checagem de Email
  public static function checkEmail($str, $mandatory = false){
    if(!$mandatory && empty($str)){
      return true;
    }

    $er = '^\w+(\.[\w\-]+)*\@[\w\-]+(\.[\w\-]+)+$';
    return preg_match("#$er#", $str); 
  }

  # Checagem de URL
  public static function checkUrl(&$str, $mandatory = false){
    if(!$mandatory && empty($str)){
      return true;
    }

    $er  = "^((https?|ftp)://)?\w+(\.[\w\-]+)+(/[\w\-]+\.?[\w\-]*)*/?$";
    $ret = preg_match("#$er#", $str, $match); 

    if($ret && empty($match[2])){
      $str = 'http://' . $str;
    }
    return $ret;
  }

  #-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#
  # Metodos de Formatacao 

  # Formatacao de hora de array para formato do bd (yyyy-mm-dd hh:ii:ss)
  public static function formatDateFromArrayToDb($arr){
    $ret = '';
    if(isset($arr['year']) && isset($arr['month']) && isset($arr['day'])){
      $ret = sprintf('%04d-%02d-%02d',
        $arr['year'], $arr['month'], $arr['day']);

      if(isset($arr['hour']) && isset($arr['minute']) && isset($arr['second'])){
        $ret .= sprintf(' %02d:%02d:%02d',
          $arr['hour'], $arr['minute'], $arr['second']);
      }
    }
    return $ret;
  }

  public static function formatDateFromDbToArray($str){
    #$er = '([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})';
    $er = '([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})';

    if(ereg($er, $str, $match)){
      list(,$y, $m, $d, $h, $i, $s) = $match;
      $ret = array(
        'day'   => $d,
        'month' => $m,
        'year'  => $y,
        'hour'  => $h,
        'minute'=> $i,
        'second'=> $s);

      return $ret;
    }
    return false;
  }

  # Formatacao de data e hora para Feed RSS
  public static function formatDateShowRSS($var){
    return date('r', strtotime($var));
  }

  # Formatacao de data e hora para W3CDTF (W3C Date and Time Format, ISO8601)
  public static function formatDateShowW3CDTF($var){
    $tzd = date('O');
    $tzd = substr($tzd, 0, 3) . ':' . substr($tzd, 3, 2);
    return date('Y-m-d\Th:i:s', strtotime($var)) . $tzd;
  }

  # Formatacao de data e hora por extenso (03 de Agosto de 2003 as 18:00)
  public static function formatDateShow($var){
    if($dt = self::formatDateFromDbToArray($var)){
      $mes_br = array('Foo',
        'Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio',
        'Junho', 'Julho', 'Agosto', 'Setembro',
        'Outubro', 'Novembro', 'Dezembro');

      return $dt['day'] . ' de ' .
             $mes_br[intval($dt['month'])] . ' de ' .
             $dt['year'];
    }
    return false;
  }

  # Formatacao de data abreviado (03/08/2005) 
  public static function formatDateShowAbr($var){
    if($dt = self::formatDateFromDbToArray($var)){
      return $dt['day'] . '/' . $dt['month'] . '/' . $dt['year'];
    }
    return false;
  }

  # Formata tamanho (bytes, kbytes, mbytes)
  public static function formatSize($v){
    $units = array('b', 'kb', ' mb', 'gb', 'tb');

    for($i=0;
        $v > 1024 && $i < count($units) - 1;
        $i++, $v /= 1024);

    return number_format($v, 2, ',', '.') . ' ' . $units[$i];
  }
}
?>
