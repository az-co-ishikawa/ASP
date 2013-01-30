<?php

	try
	{
		include_once 'custom/head_main.php';

		if( $NOT_LOGIN_USER_TYPE == $loginUserType ) //���O�C�����Ă��Ȃ��ꍇ
			{ $drawGM = $gm[ 'system' ]; }
		else //���O�C�����Ă���ꍇ
			{ $drawGM = $gm[ $loginUserType ]; }

		print System::getHead( $gm , $loginUserType , $loginUserRank );

		if( array_key_exists( 'token' , $_GET ) ) //�g�[�N����GET�ő��M����Ă���ꍇ
			{ $_POST[ 'token' ] = $_GET[ 'token' ]; }

		if( !array_key_exists( 'token' , $_POST ) ) //�g�[�N�������M����Ă��Ȃ��ꍇ
			{ throw new InvalidArgumentException( '���� token �͖����ł�' ); }

		//���O�C��������T��
		$aDB   = GMList::getDB( 'admin' );
		$aRec  = $aDB->selectRecord( 'ADMIN' );
		$aID   = $aDB->getData( $aRec , 'mail' );

		$db    = GMList::getDB( 'accountLock' );
		$table = $db->getTable();
		$table = $db->searchTable( $table , 'login_id' , '=' , $aID );
		$table = $db->searchTable( $table , 'unlock_token' , '=' , $_POST[ 'token' ] );
		$row   = $db->getRow( $table );

		if( !$row ) //�Ǘ��҂̃��b�N�������Ȃ��ꍇ
			{ throw new RuntimeException( 'unlock.php�̏����������ł��܂���' ); }

		//�p�X���[�h���m�F����
		$rec      = $db->getRecord( $table , 0 );
		$password = $db->getData( $rec , 'onetime_password' );

		if( array_key_exists( 'password' , $_POST ) ) //�p�X���[�h�����M����Ă���ꍇ
		{
			if( $password == $_POST[ 'password' ] ) //�p�X���[�h����v����ꍇ
			{
				$db->setData( $rec , 'try_time' , '' );
				$db->updateRecord( $rec );

				Template::drawTemplate( $drawGM , $rec , '' , $loginUserRank , 'accountLock' , 'ACCOUNT_UNLOCK_SUCCESS_PAGE_DESIGN' );
			}
			else //�p�X���[�h����v���Ȃ��ꍇ
				{ Template::drawTemplate( $drawGM , $rec , '' , $loginUserRank , 'accountLock' , 'ACCOUNT_UNLOCK_FAILED_PAGE_DESIGN' ); }
		}
		else //�p�X���[�h�����M����Ă��Ȃ��ꍇ
			{ Template::drawTemplate( $drawGM , $rec , '' , $loginUserRank , 'accountLock' , 'ACCOUNT_UNLOCK_PAGE_DESIGN' ); }

		print System::getFoot( $gm , $loginUserType , $loginUserRank );
	}
	catch( Exception $e )
	{
		ob_end_clean();

		//�G���[���b�Z�[�W�����O�ɏo��
		$errorManager = new ErrorManager();
		$errorMessage = $errorManager->GetExceptionStr( $e );

		$errorManager->OutputErrorLog( $errorMessage );

		//��O�ɉ����ăG���[�y�[�W���o��
		$className = get_class( $e );
		ExceptionUtil::DrawErrorPage( $className );
	}
