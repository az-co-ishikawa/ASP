<?php

	/*******************************************************************************************************
	 * <PRE>
	 *
	 * page.php - ��p�v���O����
	 * �w�肵���t�@�C�����o�͂��܂��B
	 *
	 * </PRE>
	 *******************************************************************************************************/

	ob_start();

	try
	{
		include_once 'custom/head_main.php';

		//�p�����[�^�`�F�b�N
		ConceptCheck::IsEssential( $_GET , Array( 'p' ) );
		ConceptCheck::IsNotNull( $_GET , Array( 'p' ) );
		ConceptCheck::IsScalar( $_GET , Array( 'p' ) );
		//�p�����[�^�`�F�b�N�����܂�

		print System::getHead($gm,$loginUserType,$loginUserRank);
	    
		$db = $gm['page']->getDB();
		
		$table = $db->getTable();
		
		$table = $db->searchTable( $table, 'name', '=', $_GET['p'] );
		
		if( 'admin' != $loginUserType )
			{ $table = $db->searchTable( $table, 'authority', '=', '%'.$loginUserType.'%' ); }
		
		$table = $db->searchTable( $table, 'open', '=', true );
		
		$row = $db->getRow($table);
		
		if( $row ){
			$rec = $db->getRecord( $table, 0 );
			$HTML = $page_path.$db->getData($rec,'id').".dat";
		}else{
			$HTML =  Template::getLabelFile( 'ERROR_PAGE_DESIGN' );
		}
		print $gm['system']->getString($HTML , null ,null );
		
		print System::getFoot($gm,$loginUserType,$loginUserRank);
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