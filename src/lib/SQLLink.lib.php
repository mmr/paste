<?
/*
 * $Id: SQLLink.lib.php,v 1.5 2005/02/11 05:15:20 mmr Exp $
 * mmr 2005-01-02
 */

final class SQLLink {
  private $link = null;

  public function __construct($config_file){
    if(is_readable($config_file)){
      $this->connect($config_file);
    }
    else {
      Error::msg("Could not open $config_file", __METHOD__);
    }
  }

  public function __destruct(){
    $this->disconnect();
  }

  private function connect($config_file){
    if($this->isConnected()){ 
      Error::msg("Already connected", __METHOD__);
    }

    require_once($config_file);

    $db_host = (empty($db_host)?'':'host = ' . $db_host);
    $connstr = "$db_host dbname = $db_name user = $db_user password = $db_pass";

    return $this->link = pg_connect($connstr);
  }

  private function isConnected(){
    return $this->link;
  }

  public function singleQuery($query){
    if(! $this->isConnected()){
      Error::msg("Not Connected", __METHOD__);
    }

    $result = pg_query($this->link, $query . " LIMIT 1");

    if(! $result){
      Error::msg(
        "Query Failed: '<pre>$query</pre>'\n" . pg_last_error($this->link),
        __METHOD__);
    }

    $result = pg_query($this->link, $query . ' LIMIT 1');
    if((pg_num_rows($result)>0) &&
       ($row = pg_fetch_array($result, 0, PGSQL_ASSOC)))
    {
      return $row;
    }
    return false;
  }

  public function query($query){
    if(! $this->isConnected()){
      Error::msg("Not Connected", __METHOD__);
    }

    $result = pg_query($this->link, $query);

    if(! $result){
      Error::msg(
        "Query Failed: '<pre>$query</pre>'\n" . pg_last_error($this->link),
        __METHOD__);
    }

    if(is_bool($result)){
      return pg_affected_rows($this->link);
    }

    $num = pg_num_rows($result);

    if($num > 0){
      for($i=0; $i<$num; $i++){
        $row[$i] = pg_fetch_array($result, $i, PGSQL_ASSOC);
      }

      return $row;
    }
    return true;
  }

  private function disconnect(){
    if(! is_null($this->link)){
      return pg_close($this->link);
    }

    return false;
  }
}
?>
