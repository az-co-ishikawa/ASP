<?PHP

class GMList
{
	static $gmList;
	
	/**
	 * GM�I�u�W�F�N�g���擾����
	 * 
	 * @param name �e�[�u�����B
	 * @return GUIManager GM�I�u�W�F�N�g�B
	 */
	static function getGM( $name )
	{
		if( !isset( self::$gmList[$name] ) ) { self::$gmList[$name] = SystemUtil::getGMforType($name); }
		
		return self::$gmList[$name];
	}
	
	/**
	 * DB�I�u�W�F�N�g���擾����
	 * 
	 * @param name �e�[�u�����B
	 * @return DatabaseBase DB�I�u�W�F�N�g�B
	 */
	static function getDB( $name )
	{
		if( !isset( self::$gmList[$name] ) ) { self::$gmList[$name] = SystemUtil::getGMforType($name); }
		
		return self::$gmList[$name]->getDB();
	}
	
	/**
	 * DB�I�u�W�F�N�g���擾����
	 * 
	 * ���̊֐�����擾�����ꍇ�ADB�I�u�W�F�N�g�̃L���b�V���̓��Z�b�g����܂���B
	 * �f�[�^���Q�Ƃ��郌�R�[�h�����Ɏ擾�ł��Ă���ꍇ�ȂǂɎg�p���Ă��������B
	 * @param name �e�[�u�����B
	 * @return DatabaseBase DB�I�u�W�F�N�g�B
	 */
	static function GetCachedDB( $name )
	{
		if( !isset( self::$gmList[$name] ) ) { self::$gmList[$name] = SystemUtil::getGMforType($name); }

		return self::$gmList[$name]->getCachedDB();
	}
}

?>