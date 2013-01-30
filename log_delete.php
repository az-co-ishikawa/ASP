<?php

	ob_start();

	try
	{
		include_once 'custom/head_main.php';

		if( 'admin' != $loginUserType ) //�Ǘ��҂łȂ��ꍇ
			{ throw new IllegalAccessException( '�s���ȃA�N�Z�X�ł�' ); }

		if( !isset( $_POST[ 'post' ] ) ) //�p�����[�^�����M����Ă��Ȃ��ꍇ
			{ throw new IllegalAccessException( '�s���ȃA�N�Z�X�ł�' ); }

		$count = ( int )( $_POST[ 'delete_day' ] );

		if( 0 >= $count ) //�p�����[�^�����M����Ă��Ȃ��ꍇ
			{ throw new InvalidArgumentException( '���� delete_day �͖����ł�[' . $_POST[ 'delete_day' ] . ']' ); }

		$year  = date( 'Y' );
		$month = date( 'n' );
		$day   = date( 'j' );

		$time = mktime( 0 , 0 , 0 , $month , $day - $count , $year );

		print System::getHead( $gm , $loginUserType , $loginUserRank );

		$db    = GMList::getDB( 'access' );
		$table = $db->getTable();
		$table = $db->searchTable( $table , 'regist' , '<' , $time );

		$db->realDeleteTable( $table );

		$_GET[ 'type' ] = 'access';
		$sys            = SystemUtil::getSystem( $_GET[ 'type' ] );
		$dummy          = Array();

		$sys->drawDeleteComp( $gm , $dummy , $loginUserType , $loginUserRank );

		print System::getFoot( $gm , $loginUserType , $loginUserRank );
	}
	catch( Exception $e_ )
	{
		ob_end_clean();

		//�G���[���b�Z�[�W�����O�ɏo��
		$errorManager = new ErrorManager();
		$errorMessage = $errorManager->GetExceptionStr( $e_ );

		$errorManager->OutputErrorLog( $errorMessage );

		//��O�ɉ����ăG���[�y�[�W���o��
		$className = get_class( $e_ );
		ExceptionUtil::DrawErrorPage( $className );
	}

	ob_end_flush();
