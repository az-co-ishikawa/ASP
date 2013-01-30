<?php

	//���ݒ� //

	//accountLock�e�[�u���̐ݒ�
	$EDIT_TYPE                            = 'accountLock';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = true;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LST[ $EDIT_TYPE ]                    = './lst/module/accountLock.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/module/accountLock.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'ACL';
	$ID_LENGTH[ $EDIT_TYPE ]              = 16;

	//accountLockConfig�e�[�u���̐ݒ�
	$EDIT_TYPE                            = 'accountLockConfig';
	$TABLE_NAME[]                         = $EDIT_TYPE;
	$THIS_TABLE_IS_USERDATA[ $EDIT_TYPE ] = false;
	$THIS_TABLE_IS_NOHTML[ $EDIT_TYPE ]   = false;
	$LOGIN_KEY_COLUM[ $EDIT_TYPE ]        = null;
	$LOGIN_PASSWD_COLUM[ $EDIT_TYPE ]     = null;
	$LST[ $EDIT_TYPE ]                    = './lst/module/accountLockConfig.csv';
	$TDB[ $EDIT_TYPE ]                    = './tdb/module/accountLockConfig.csv';
	$ID_HEADER[ $EDIT_TYPE ]              = 'ACLC';
	$ID_LENGTH[ $EDIT_TYPE ]              = 8;

	//���N���X //

	/**
		@brief ���W���[���F���p�B
	*/
	class mod_accountLock extends command_base
	{}

	/**
		@brief �A�J�E���g���b�N�Ɋ֘A���鏈���B
	*/
	class accountLockLogic //
	{
		//���f�[�^�擾 //

		/**
			@brief  ���̃N���X����������e�[�u�������擾����B
			@return �e�[�u�����B
		*/
		function getType() //
			{ return 'accountLock'; }

		/**
			@brief     ���O�C��ID���烆�[�U�[��ʂ��擾����B
			@exception InvalidArgumentException $iLoginID_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iLoginID_ ���O�C��ID�B
			@retval    ���[�U�[��� �Y�����郆�[�U�[�����������ꍇ
			@retval    null         �Y�����郆�[�U�[��������Ȃ������ꍇ
			@attention ���̊֐��� accountLockConfig �̉e�����󂯂܂���B\n
			           �A�J�E���g���b�N�̑ΏۊO�̃��[�U�[��ʂł����Ă��A�Y�����郍�O�C��ID�ł���Βl��Ԃ��܂��B
			@remarks   ���̊֐�����񃆁[�U�[�e�[�u���̃��[�U�[��ʂ��Ԃ邱�Ƃ͂���܂���B
		*/
		function getUserType( $iLoginID_ ) //
		{
			if( !$iLoginID_ ) //���O�C��ID����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iLoginID_ �͖����ł�' ); }

			global $TABLE_NAME;
			global $THIS_TABLE_IS_USERDATA;
			global $LOGIN_KEY_COLUM;

			foreach( $TABLE_NAME as $tableName ) //�S�Ẵe�[�u��������
			{
				if( !$THIS_TABLE_IS_USERDATA[ $tableName ] ) //���[�U�[�e�[�u���ł͂Ȃ��ꍇ
					{ continue; }

				//�Y�����郍�O�C��ID�������[�U�[��T��
				$db    = GMList::getDB( $tableName );
				$table = $db->getTable();
				$table = $db->searchTable( $table , $LOGIN_KEY_COLUM[ $tableName ] , '=' , $iLoginID_ );
				$row   = $db->getRow( $table );

				if( !$row ) //���[�U�[��������Ȃ������ꍇ
					{ continue; }

				return $tableName;
			}

			return null;
		}

		/**
			@brief ���O�C���g���C�񐔂��K�������𒴂��Ă��邩�m�F����B
			@retval true  �K�������𒴂��Ă���ꍇ�B
			@retval false �K�������𒴂��Ă��Ȃ��A�܂��̓��O�C��ID�����M����Ă��Ȃ��ꍇ�B
		*/
		function isTryOver() //
		{
			global $LOGIN_KEY_FORM_NAME;

			if( !array_key_exists( $LOGIN_KEY_FORM_NAME , $_POST ) ) //���O�C��ID�����M����Ă��Ȃ��ꍇ
				{ return false; }

			$loginID  = $_POST[ $LOGIN_KEY_FORM_NAME ];

			if( $loginID ) //���O�C��ID�����M����Ă���ꍇ
				{ $userType = self::getUserType( $loginID ); }
			else //���O�C��ID����̏ꍇ
				{ return false; }

			if( !$userType ) //���[�U�[��ʂ��擾�ł��Ȃ��ꍇ
				{ return false; }

			if( !accountLockConfigLogic::isValidUserType( $userType ) ) //�A�J�E���g���b�N�ݒ肪�L���łȂ��ꍇ
				{ return false; }

			$db    = GMList::getDB( self::getType() );
			$table = $db->getTable();
			$table = $db->searchTable( $table , 'login_id' , '=' , $loginID );
			$row   = $db->getRow( $table );

			if( !$row ) //���O�C���g���C������������Ȃ��ꍇ
				{ return false; }

			$rec      = $db->getRecord( $table , 0 );
			$tryTime  = $db->getData( $rec , 'try_time' );
			$tryTimes = explode( '/' , $tryTime );

			$cDB         = GMList::getDB( 'accountLockConfig' );
			$cRec        = accountLockConfigLogic::getConfigRec( $userType );
			$maxTryTime  = $cDB->getData( $cRec , 'max_try_time' );
			$maxTryCount = $cDB->getData( $cRec , 'max_try_count' );
			$beginTime   = time() - $maxTryTime;

			$tryCount = 0;

			foreach( $tryTimes as $tryTime ) //���O�C���g���C���Ԃ̗���������
			{
				if( $beginTime <= $tryTime ) //�J�E���g�J�n���Ԃ���Ƀ��O�C�����Ă���ꍇ
					{ ++$tryCount; }
			}

			return( $maxTryCount <= $tryCount );
		}

		//������ //

		/**
			@brief ���O�C���g���C�������X�V����B
		*/
		function addTryCount() //
		{
			global $LOGIN_KEY_FORM_NAME;

			if( !array_key_exists( $LOGIN_KEY_FORM_NAME , $_POST ) ) //���O�C��ID�����M����Ă��Ȃ��ꍇ
				{ return; }

			$loginID  = $_POST[ $LOGIN_KEY_FORM_NAME ];

			if( $loginID ) //���O�C��ID�����M����Ă���ꍇ
				{ $userType = self::getUserType( $loginID ); }
			else //���O�C��ID����̏ꍇ
				{ return; }

			$db    = GMList::getDB( self::getType() );
			$table = $db->getTable();
			$table = $db->searchTable( $table , 'login_id' , '=' , $loginID );
			$row   = $db->getRow( $table );

			if( !$row ) //������������Ȃ��ꍇ
			{
				$rec = $db->getNewRecord();

				$db->setData( $rec , 'id' , SystemUtil::getNewID( $db , self::getType() ) );
				$db->setData( $rec , 'login_id' , $loginID );

				$tryTimes = Array();
			}
			else //���������������ꍇ
			{
				$rec      = $db->getRecord( $table , 0 );
				$tryTime  = $db->getData( $rec , 'try_time' );
				$tryTimes = explode( '/' , $tryTime );
			}

			if( 256 <= count( $tryTimes ) ) //�����̕ێ������ő吔�𒴂���ꍇ
				{ array_shift( $tryTimes ); }

			array_push( $tryTimes , time() );

			$db->setData( $rec , 'try_time' , implode( '/' , $tryTimes ) );
			$db->setData( $rec , 'unlock_token' , md5( rand() ) );
			$db->setData( $rec , 'onetime_password' , substr( md5( rand() ) , 0 , 8 ) );

			if( !$row ) //������������Ȃ��ꍇ
				{ $db->addRecord( $rec ); }
			else //���������������ꍇ
				{ $db->updateRecord( $rec ); }

			$isOver = self::isTryOver();

			if( $isOver && 'admin' == $userType ) //�Ǘ��҂̃A�J�E���g�����b�N���ꂽ�ꍇ
			{
				$template = Template::getLabelFile( 'ACCOUNT_LOCK_ALERT_MAIL' );

				$sysDB   = GMList::getDB( 'system' );
				$sysRec  = $sysDB->selectRecord( 'ADMIN' );
				$sysMail = $sysDB->getData( $sysRec , 'mail_address' );

				$gm = GMList::getGM( self::getType() );

				Mail::send( $template , $sysMail , $sysMail , $gm , $rec );
			}
		}

		/**
			@brief ���O�C���g���C����������������B
		*/
		function resetTryCount() //
		{
			global $LOGIN_KEY_FORM_NAME;

			if( !array_key_exists( $LOGIN_KEY_FORM_NAME , $_POST ) ) //���O�C��ID�����M����Ă��Ȃ��ꍇ
				{ return; }

			$loginID  = $_POST[ $LOGIN_KEY_FORM_NAME ];

			if( $loginID ) //���O�C��ID�����M����Ă���ꍇ
				{ $userType = self::getUserType( $loginID ); }
			else //���O�C��ID����̏ꍇ
				{ return; }

			$db    = GMList::getDB( self::getType() );
			$table = $db->getTable();
			$table = $db->searchTable( $table , 'login_id' , '=' , $loginID );
			$row   = $db->getRow( $table );

			if( !$row ) //������������Ȃ��ꍇ
				{ return; }

			$rec = $db->getRecord( $table , 0 );

			$db->setData( $rec , 'try_time' , '' );
			$db->updateRecord( $rec );
		}
	}

	/**
		@brief �A�J�E���g���b�N�̐ݒ�Ɋ֘A���鏈���B
	*/
	class accountLockConfigLogic //
	{
		//���f�[�^�擾 //

		/**
			@brief  ���̃N���X����������e�[�u�������擾����B
			@return �e�[�u�����B
		*/
		function getType() //
			{ return 'accountLockConfig'; }

		/**
			@brief     ���[�U�[�̃A�J�E���g���b�N�ݒ肪�L�����m�F����B
			@exception InvalidArgumentException $iUserType_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@retval    true  ���[�U�[���A�J�E���g���b�N���g�p����ꍇ�B
			@retval    false ���[�U�[���A�J�E���g���b�N���g�p���Ȃ��A�܂��͐ݒ肪������Ȃ��ꍇ�B
		*/
		function isValidUserType( $iUserType_ ) //
		{
			if( !$iUserType_ ) //���[�U�[��ʂ���̏ꍇ
				{ throw new InvalidArgumentException( '���� $iUserType_ �͖����ł�' ); }

			$db    = GMList::getDB( self::getType() );
			$table = $db->getTable();
			$table = $db->searchTable( $table , 'user_type' , '=' , $iUserType_ );
			$row   = $db->getRow( $table );

			if( !$row ) //�ݒ肪������Ȃ��ꍇ
				{ return false; }

			$rec   = $db->getRecord( $table , 0 );
			$isUse = $db->getData( $rec , 'is_use' );

			return $isUse;
		}

		/**
			@brief     ���[�U�[�̃A�J�E���g���b�N�ݒ���擾����B
			@exception InvalidArgumentException $iUserType_ �ɖ����Ȓl���w�肵���ꍇ�B
			@exception RuntimeException                     �ݒ肪������Ȃ��ꍇ�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@return    �Y�����[�U�[��ʂɑ΂���A�J�E���g���b�N�ݒ�B
		*/
		function getConfigRec( $iUserType_ ) //
		{
			if( !$iUserType_ ) //���[�U�[��ʂ���̏ꍇ
				{ throw new InvalidArgumentException( '���� $iUserType_ �͖����ł�' ); }

			$db    = GMList::getDB( self::getType() );
			$table = $db->getTable();
			$table = $db->searchTable( $table , 'user_type' , '=' , $iUserType_ );
			$row   = $db->getRow( $table );

			if( !$row ) //�ݒ肪������Ȃ��ꍇ
				{ throw new RuntimeException( 'getConfigRec �������ł��܂���[' . $iUserType_ . ']' ); }

			$rec = $db->getRecord( $table , 0 );

			return $rec;
		}
	}