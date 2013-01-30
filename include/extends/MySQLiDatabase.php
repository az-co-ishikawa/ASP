<?php

include_once "./include/base/SQLDatabase.php";

/*******************************************************************************************************
 * <PRE>
 *
 * SQL�f�[�^�x�[�X�V�X�e���@MySQL�p
 *
 * @author �g���K��Y
 * @original �O�H��q
 * @version 2.0.0
 *
 * </PRE>
 *******************************************************************************************************/

class SQLDatabase extends SQLDatabaseBase
{

	/**
	 * �R���X�g���N�^�B
	 * @param $dbName DB��
	 * @param $tableName �e�[�u����
	 * @param $colName �J���������������z��
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
			
		// �t�B�[���h�ϐ��̏�����
		$this->log		 = new OutputLog($DB_LOG_FILE);

		if($SQL_PORT != "")
			$this->connect	 = new mysqli( $SQL_SERVER.":".$SQL_PORT, $SQL_ID, $SQL_PASS );
		else
			$this->connect	 = new mysqli( $SQL_SERVER, $SQL_ID, $SQL_PASS );

		if( !$this->connect ){
			throw new RuntimeException("SQLDatabase() : DB CONNECT ERROR. -> mysql_connect( ".$SQL_SERVER." )\n");
		}
		if(  !$this->connect->select_db( $dbName )  ){
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

		//mySQL����̏o�̓R�[�h��SJIS��
		//	mysql_query("set names sjis");
		$this->connect->set_charset('sjis');
		//	mysql_query("SET NAMES binary;");
		//	mysql_set_charset('binary');
	}

	function sql_query($sql){
		return $this->connect->query( $sql );
	}

	function sql_fetch_assoc( &$result ,$index){
		$result->data_seek( $index);
        return $result->fetch_assoc();
	}

	function sql_fetch_array( &$result ){
		return $result->fetch_array( );
	}

	function sql_fetch_all( &$result ){
		return $result->fetch_all( );
	}

	function sql_num_rows( &$result ){
		return $result->num_rows;
	}

	function sql_convert( $val ){
		return $val;
	}

	function sql_escape($val){
		return $this->connect->escape_string(($val));
	}
	
	function sql_date_group($column,$format){
		return "FROM_UNIXTIME($column,'$format')";
	}
	

	function to_boolean( $val ){
		if( is_bool( $val ) )		{ return $val; }
		else if( is_string($val )){
			if( $val == 'FALSE' ){ return false; }
			if( $val == 'TRUE' ){ return true; }
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

		return mysqlDataType($ret->fetch_field_direct(0)->type);
	}

	/**
	 * �e�[�u����Like����
	 * 
	 * @param $table �e�[�u���f�[�^
	 * @param $name �J������
	 * @param $asc �����E�~���� 'asc' 'desc' �Ŏw�肵�܂��B
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