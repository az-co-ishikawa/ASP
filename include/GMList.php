<?PHP

class GMList
{
	static $gmList;
	
	/**
	 * GMオブジェクトを取得する
	 * 
	 * @param name テーブル名。
	 * @return GUIManager GMオブジェクト。
	 */
	static function getGM( $name )
	{
		if( !isset( self::$gmList[$name] ) ) { self::$gmList[$name] = SystemUtil::getGMforType($name); }
		
		return self::$gmList[$name];
	}
	
	/**
	 * DBオブジェクトを取得する
	 * 
	 * @param name テーブル名。
	 * @return DatabaseBase DBオブジェクト。
	 */
	static function getDB( $name )
	{
		if( !isset( self::$gmList[$name] ) ) { self::$gmList[$name] = SystemUtil::getGMforType($name); }
		
		return self::$gmList[$name]->getDB();
	}
	
	/**
	 * DBオブジェクトを取得する
	 * 
	 * この関数から取得した場合、DBオブジェクトのキャッシュはリセットされません。
	 * データを参照するレコードが既に取得できている場合などに使用してください。
	 * @param name テーブル名。
	 * @return DatabaseBase DBオブジェクト。
	 */
	static function GetCachedDB( $name )
	{
		if( !isset( self::$gmList[$name] ) ) { self::$gmList[$name] = SystemUtil::getGMforType($name); }

		return self::$gmList[$name]->getCachedDB();
	}
}

?>