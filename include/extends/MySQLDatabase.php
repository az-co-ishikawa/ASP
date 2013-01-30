<?php

include_once "./include/base/SQLDatabase.php";

/*******************************************************************************************************
 * <PRE>
 *
 * SQLデータベースシステム　MySQL用
 *
 * @author 吉岡幸一郎
 * @original 丹羽一智
 * @version 2.0.0
 *
 * </PRE>
 *******************************************************************************************************/

class SQLDatabase extends SQLDatabaseBase
{

	/**
	 * コンストラクタ。
	 * @param $dbName DB名
	 * @param $tableName テーブル名
	 * @param $colName カラム名を持った配列
	 */
	function SQLDatabase($dbName, $tableName, $colName, $colType, $colSize ){

		global $DB_LOG_FILE;
		global $ADD_LOG;
		global $UPDATE_LOG;
		global $DELETE_LOG;
		global $SQL_SERVER;
		global $SQL_ID;
		global $SQL_PASS;
		global $TABLE_PREFIX;
		global $SQL_PORT;
			
		// フィールド変数の初期化
		$this->log		 = new OutputLog($DB_LOG_FILE);

		if($SQL_PORT != "")
			$connect	 = mysql_connect( $SQL_SERVER.":".$SQL_PORT, $SQL_ID, $SQL_PASS );
		else
			$connect	 = mysql_connect( $SQL_SERVER, $SQL_ID, $SQL_PASS );

		if( !$connect ){
			throw new RuntimeException("SQLDatabase() : DB CONNECT ERROR. -> mysql_connect( ".$SQL_SERVER." )\n");
		}
		if(  !mysql_select_db( $dbName, $connect )  ){
			throw new RuntimeException("SQLDatabase() : DB CONNECT ERROR. -> mysql_select_db( ". $dbName. " )\n");
		}
			
		$this->dbName		 = $dbName ;
		$this->tableName	 = strtolower( $TABLE_PREFIX.$tableName );
		$colName[]			 = strtolower( 'SHADOW_ID' );
		$this->colName		 = $colName;
		$this->colType		 = $colType;
		$this->colSize		 = $colSize;
			
		$this->addLog		 = $ADD_LOG;
		$this->updateLog	 = $UPDATE_LOG;
		$this->delLog		 = $DELETE_LOG;
			
		$this->dbInfo		 = $dbName. ",". $tableName;

		$this->prefix		 = $TABLE_PREFIX;

		//mySQLからの出力コードをSJISに
		mysql_query("set names sjis");
			
		//set name ***と違い、[mysql_real_escape_string]にも有効。  正しphp5.2.3、MySQL5.0.7以降のみ利用可能
//		mysql_set_charset('sjis');

			
		//	mysql_query("SET NAMES binary;");
		//	mysql_set_charset('binary');
	}

	function sql_query($sql){
		return mysql_query( $sql );
	}

	function sql_fetch_assoc( &$result ,$index){
		mysql_data_seek($result , $index);
		return mysql_fetch_assoc($result);
	}

	function sql_fetch_array( &$result ){
		return mysql_fetch_array( $result );
	}

	function sql_fetch_all( &$result ){
		if(function_exists('mysqli_fetch_all'))
			return mysqli_fetch_all( $result );
	    $all = array();
	    while ($r = mysql_fetch_assoc($result)) { array_push($all,$r); }
	    mysql_data_seek($result,0);
	    return $all;
	}

	function sql_num_rows( &$result ){
		return mysql_num_rows( $result );
	}

	function sql_convert( $val ){
		return $val;
	}

	function sql_escape($val){
		return mysql_real_escape_string(($val));
	}

	function sql_date_group($column,$format_type,$format=null){
		
		if(is_null($format)){
			switch($format_type){
				case 'y':
					$format = '%Y';
					break;
				case 'm':
					$format = '%Y-%m';
					break;
				case 'd':
				default:
					$format = '%Y-%m-%d';
					break;
			}
		}
		return "FROM_UNIXTIME($column,'$format')";
	}
	

	function to_boolean( $val ){
		if( is_bool( $val ) )		{ return $val; }
		else if( is_string($val )){
			$val = strtolower($val);
			if( $val == 'false' ){ return false; }
			if( $val == 'true' ){ return true; }
		}
		if( $val == 1 )		{ return true; }
		//else if( !strlen($val) )    { return false;}
		return false;
	}

	private function getColumnType($name){
		$t = $this->getTable();
		$t->offset	 = 0;
		$t->limit	 = 1;
		$ret = $this->sql_query( "SELECT $name FROM ". strtolower($this->tableName)." ".$t->getLimitOffset() );

		return mysql_field_type($ret,0);
	}

	/**
	 * テーブルのLike結合
	 * 
	 * @param $table テーブルデータ
	 * @param $name カラム名
	 * @param $asc 昇順・降順を 'asc' 'desc' で指定します。
	 */
	function joinTableLike( &$tbl, $b_name, $n_name, $b_col, $n_col, $n_tbl_name = null ){
		$_b_name = strtolower($this->prefix.$b_name);
		$_n_name = strtolower($this->prefix.$n_name);
		if( !is_null($n_tbl_name) )	{ $_n_name = $_n_name.' '.$n_tbl_name; $_n_tbl_name = $n_tbl_name; }
		else						{ $_n_tbl_name = $_n_name; }
		
		return $this->joinTableSQL( $tbl, $b_name, $n_name, $_n_tbl_name.".".$n_col." like concat( '%', ".$_b_name.".".$b_col.", '%') ", $n_tbl_name );
	}
}

/*******************************************************************************************************/
/*******************************************************************************************************/
/*******************************************************************************************************/
/*******************************************************************************************************/

class Table extends TableBase{

	function Table($from){

		$this->select	 = '*';
		$this->from		 = $from;
		$this->delete	 = '( delete_key = FALSE OR delete_key IS NULL )';

		$this->sql_char_code = "EUC-JP";//mysql_client_encoding();
		parent::TableBase($from);
	}

	function getLimitOffset(){
		global $SQL_MASTER;
		if( ($this->offset == 0 || $this->offset != null) && $this->limit != null ){
			$str	 = " LIMIT ". $this->offset. ',' .$this->limit;
			return $str;
		}else{
			return "";
		}
	}

	function sql_convert( $val ){
		return $val;
	}
}
?>