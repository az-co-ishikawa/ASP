<?php
	
	
/*************************
 ** SQL DATABSE 用 定義 **
 *************************/
 
	$SQL											 = true;					// SQLを用いるかどうかのフラグ
	$SQL_SERVER										 = 'mysql304.db.sakura.ne.jp';				// SQLのサーバ
//	$SQL_PORT										 = '3306';

	// SQLデーモンのクラス名
//	$SQL_MASTER										 = 'SQLiteDatabase';
	$SQL_MASTER										 = 'MySQLDatabase';

	$DB_NAME										 = 'prom_asp';			// DB名
	$SQL_ID	 										 = 'prom';					// 管理ユーザーＩＤ
	$SQL_PASS  										 = 'mobile1ma';					// 管理ユーザーＰＡＳＳ

	$TABLE_PREFIX									 = '';

?>