<?php
	ob_start();
	
	try
	{
		include_once 'custom/head_main.php';

		//�p�����[�^�`�F�b�N
		ConceptCheck::IsEssential( $_GET , Array( 'type' ) );
		ConceptCheck::IsNotNull( $_GET , Array( 'type' ) );
		ConceptCheck::IsScalar( $_GET , Array( 'type' ) );

		if( !$gm[ $_GET[ 'type' ] ] )
			throw new IllegalAccessException( $_GET[ 'type' ] . '�͒�`����Ă��܂���' );

		if( !$THIS_TABLE_IS_USERDATA[ $_GET[ 'type' ] ] )
			throw new IllegalAccessException( $_GET[ 'type' ] . '�̓��[�U�[�e�[�u���ł͂���܂���' );

		if( $_GET[ 'type' ] == 'admin' )
				throw new IllegalAccessException( $_GET[ 'type' ] . '�͑���ł��܂���' );
		//�p�����[�^�`�F�b�N�����܂�

		print System::getHead($gm,$loginUserType,$loginUserRank);
	
		if( !isset($_POST['post']) )
		{// ���̓t�H�[��
			$gm['system']->addHiddenForm( 'post', 'true' );
	        $gm['system']->setVariable( 'error_msg' , "");
	        Template::drawTemplate( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_FORM_DESIGN', 'reminder.php?type='.$_GET['type']);
	    }
		else
		{// ���͓��e�m�F
			$check			 = true;
			// �ėp�I�ȋ󗓃`�F�b�N
			$html = "";
			if( $_POST['mail'] == '' )
			{// ���[���A�h���X�����͂���Ă��Ȃ��ꍇ
				$html .= Template::getTemplateString( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_FALED_DESIGN', false ,null ,'head' );
				$html .= Template::getTemplateString( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_FALED_DESIGN', false ,null ,'mail' );
				$check	 = false;
			}
			else
			{// ���͂��ꂽ���[���A�h���X�̃��R�[�h�����݂��邩�m�F
				$check	 = false;
				if( isset($_GET['type']) ){
						if(  $THIS_TABLE_IS_USERDATA[ $_GET['type'] ]  )
						{
							$db		 = $gm[ $_GET['type'] ]->getDB();
							$table	 = $db->getTable();
							$table	 = $db->searchTable( $table, 'mail', '=', $_POST[ 'mail' ] );
							$table	 = $db->searchTable(  $table, 'activate', '!', $ACTIVE_NONE  );
							if( $db->getRow($table) != 0 )
							{// ���R�[�h�����݂���ꍇ���[���𑗐M
								$rec			 = $db->getRecord( $table, 0 );
								Mail::send( Template::getTemplate( 'reminder', $loginUserRank, $_GET['type'], 'SEND_MAIL') , $MAILSEND_ADDRES, $_POST['mail'], $gm[ $_GET['type'] ], $rec, $MAILSEND_NAMES );
								Template::drawTemplate( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_COMP_DESIGN', 'reminder.php?type='.$_GET['type']);
								$check			 = true;
							}
						}
				}else{
					for($i=0; $i<count($TABLE_NAME); $i++)
					{
						if(  $THIS_TABLE_IS_USERDATA[ $TABLE_NAME[$i] ]  )
						{
							$db		 = $gm[ $TABLE_NAME[$i] ]->getDB();
							$table	 = $db->getTable();
							$table	 = $db->searchTable( $table, 'mail', '=', $_POST[ 'mail' ] );
							$table	 = $db->searchTable(  $table, 'activate', '!', $ACTIVE_NONE  );
							if( $db->getRow($table) != 0 )
							{// ���R�[�h�����݂���ꍇ���[���𑗐M
								$rec			 = $db->getRecord( $table, 0 );
								Mail::send( Template::getTemplate( 'reminder', $loginUserRank, $_GET['type'], 'SEND_MAIL') , $MAILSEND_ADDRES, $_POST['mail'], $gm[ $TABLE_NAME[$i] ], $rec, $MAILSEND_NAMES );
								Template::drawTemplate( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_COMP_DESIGN', 'reminder.php?type='.$_GET['type']);
								$check			 = true;
								break;
							}
						}
					}
				}
				
				if( !$check )
				{ 
					$html .= Template::getTemplateString( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_FALED_DESIGN', false ,null ,'head' ); 
					$html .= Template::getTemplateString( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_FALED_DESIGN', false ,null ,'record' );
				}
			}
	
				
			if( !$check )
			{// ���͓��e�ɕs��������ꍇ
				$html .= Template::getTemplateString( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_FALED_DESIGN', false ,null ,'foot' );
				$gm['system']->setVariable( 'error_msg' , $html);
				$gm['system']->addHiddenForm( 'post', 'check' );
				Template::drawTemplate( $gm['system'], null, 'reminder', $loginUserRank, $_GET['type'], 'SEND_FORM_DESIGN', 'reminder.php?type='.$_GET['type']);
			}
	
		}
		
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
