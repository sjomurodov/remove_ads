<?php 

/*
* SQLite3 Class
* based on the code of miquelcamps
* @see http://7devs.com/code/view.php?id=67
*/

class DB{
  private $sqlite;
  private $mode;

  function __construct( $filename, $mode = SQLITE3_ASSOC ){
    $this->mode = $mode;
    $this->sqlite = new SQLite3($filename);

  }

  function __destruct(){
    @$this->sqlite->close();
  }

  function clean( $str ){
    return $this->sqlite->escapeString( $str );
  }

  function query( $query ){
    $res = $this->sqlite->query( $query );
    if ( !$res ){
      throw new Exception( $this->sqlite->lastErrorMsg() );
    }

    return $res;
  }

  function queryRow( $query ){
    $res = $this->query( $query );
    $row = $res->fetchArray( $this->mode );
    return $row;
  }

  function queryOne( $query ){
    $res = $this->query( $query );
    $row = $res->fetchArray( $this->mode );
    return $row;
  }

  function onerow( $query ){
    $res = $this->query( $query );
    $row = $res->fetchArray( $this->mode );
    return $row;
  }

  function queryAll( $query ){
    $rows = array();
    if( $res = $this->query( $query ) ){
      while($row = $res->fetchArray($this->mode)){
        $rows[] = $row;
      }
    }
    return $rows;
  }

  function querycount( $query ){
    $rows = array();
    if( $res = $this->query( $query ) ){
      while($row = $res->fetchArray($this->mode)){
        $rows[] = $row;
      }
    }
    return count($rows);
  }

  function outmoney(){
    $rows = 0;
    if( $res = $this->query("SELECT outmoney FROM members") ){
      while($row = $res->fetchArray($this->mode)){
        $rows = $rows + $row[outmoney];
      }
    }
    return $rows;
  }

  function getid(){
    $rows = [];
    if( $res = $this->query("SELECT user_id FROM members") ){
      while($row = $res->fetchArray($this->mode)){
        $rows[] =  $row[user_id];
      }
    }
    return $rows;
  }

  function getLastID(){
    return $this->sqlite->lastInsertRowID();
  }
}