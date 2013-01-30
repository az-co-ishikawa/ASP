<?php

	/*******************************************************************************************************
	 * <PRE>
	 *
	 * index.php - ��p�v���O����
	 * �C���f�b�N�X�y�[�W���o�͂��܂��B
	 *
	 * </PRE>
	 *******************************************************************************************************/

	ob_start();
	try
	{
		include_once 'custom/head_main.php';

		//�Љ�R�[�h����
		friendProc();

        if ($loginUserType == "nUser" || $loginUserType == "cUser") {
            $db	 = $gm[$loginUserType]->getDB();
            $rec = $db->selectRecord($LOGIN_ID);
            $_SESSION['login_user_data'] = $rec;
        }

		switch($loginUserType)
		{
		default:
			print System::getHead($gm,$loginUserType,$loginUserRank);
			
			if( $loginUserType != $NOT_LOGIN_USER_TYPE )
				Template::drawTemplate( $gm[ $loginUserType ] , $rec , $loginUserType , $loginUserRank , '' , 'TOP_PAGE_DESIGN' );
			else
				Template::drawTemplate( $gm[ 'system' ] , $rec , $loginUserType , $loginUserRank , '' , 'TOP_PAGE_DESIGN' );
			
			print System::getFoot($gm,$loginUserType,$loginUserRank);
			break;
		}
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