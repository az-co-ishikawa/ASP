<?php

	/*******************************************************************************************************
	 * <PRE>
	 *
	 * activate.php - ��p�v���O����
	 * ���[�U�̃A�N�e�B�x�[�V�����������s���v���O�����B
	 *
	 * </PRE>
	 *******************************************************************************************************/

	ob_start();

	try
	{
		include_once 'custom/head_main.php';

		//�p�����[�^�`�F�b�N
		ConceptCheck::IsEssential( $_GET , Array( 'type' , 'id' , 'md5' ) );
		ConceptCheck::IsNotNull( $_GET , Array( 'type' , 'id' , 'md5' ) );
		ConceptCheck::IsScalar( $_GET , Array( 'type' , 'id' , 'md5' ) );

		if( !$THIS_TABLE_IS_USERDATA[ $_GET[ 'type' ] ] )
				throw new IllegalAccessException( $_GET[ 'type' ] . '�̓��[�U�[�e�[�u���ł͂���܂���' );

		if( !$gm[ $_GET[ 'type' ] ] )
				throw new IllegalAccessException( $_GET[ 'type' ] . '�͒�`����Ă��܂���' );

		if( $_GET[ 'type' ] == 'admin' )
				throw new IllegalAccessException( $_GET[ 'type' ] . '�͑���ł��܂���' );
		//�p�����[�^�`�F�b�N�����܂�

		print System::getHead( $gm , $loginUserType , $loginUserRank );

		//�F�؂��郆�[�U�[������
		$db    = $gm[ $_GET[ 'type' ] ]->getDB();
		$table = $db->getTable();
		$table = $db->searchTable( $table , 'id' , '=' , $_GET[ 'id' ] );

		if( $db->getRow( $table ) <= 0 ) //���[�U�[��������Ȃ�
			throw new RuntimeException( $_GET[ 'type' ] . '��' . $_GET[ 'id' ] . '�͑��݂��Ȃ����R�[�h�ł�' );

		//�F�ؗpMD5���v�Z����
		$rec = $db->getRecord( $table , 0 );
		$id  = $db->getData( $rec , 'id' );
		$mail = $db->getData( $rec , 'mail' );
		$md5 = md5( $id . $mail );

		if( $md5 != $_GET[ 'md5' ] ) //�F�ؗpMD5����v���Ȃ�
			throw new RuntimeException( 'MD5����v���܂���' );

		//���[�U�[��F�؂���
		$sys     = SystemUtil::getSystem( $_GET[ 'type' ] );
		$success = $sys->activateAction( $gm , $rec , $loginUserType , $loginUserRank );

		//��ʏo��
		if( $success ) //�F�؂ɐ��������ꍇ
			$sys->drawActivateComp( $gm , $rec , $loginUserType , $loginUserRank );
		else //�F�؂Ɏ��s�����ꍇ
			$sys->drawActivateFaled( $gm , $rec , $loginUserType , $loginUserRank );

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
?>