<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class System extends command_base
	{
		/**********************************************************************************************************
		 * �ėp�V�X�e���p���\�b�h
		 **********************************************************************************************************/

		// �A�b�v���[�h�t�@�C���̊i�[�t�H���_�w��
		// ext�Ŋg���q�ijpg���jcat�Ŏ�ށiimage���j�A���̑�timeformat���w��\�B�����K�w�̏ꍇ��/�ŋ�؂�B
		var $fileDir = 'cat/Ym'; // �L�q��) cat/ext/Y/md -> �i�[�t�H���_ image/jpg/2009/1225
		
		//getHead��getFoot�̌Ăяo���Ǘ�
		static $head = false;
		static $foot = false;
		

		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �o�^�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////


		/**
		 * �o�^���e�m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param edit �ҏW�Ȃ̂��A�V�K�ǉ��Ȃ̂���^�U�l�œn���B
		 * @return �G���[�����邩��^�U�l�œn���B
		 */
		function registCheck( &$gm, $edit, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************

			// �`�F�b�N����
			self::$checkData->generalCheck($edit);

			return self::$checkData->getCheck();
		}

		/**
		 * �����o�^�����m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param edit �ҏW�Ȃ̂��A�V�K�ǉ��Ȃ̂���^�U�l�œn���B
		 * @return �����o�^���\����^�U�l�ŕԂ��B
		 */
		function duplicateCheck( &$gm, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $LOGIN_ID;
			// **************************************************************************************

			// �Ǘ��҂͑S�Ė������ɋ���
			if( 'admin' == $loginUserType )
				return true;

			switch( $_GET[ 'type' ] )
			{
				default :
					return false;
			}
		}

		/**
		 * �����o�^�����m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param edit �ҏW�Ȃ̂��A�V�K�ǉ��Ȃ̂���^�U�l�œn���B
		 * @return �����o�^���\����^�U�l�ŕԂ��B
		 */
		function copyCheck( &$gm, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $LOGIN_ID;
			// **************************************************************************************

			// �Ǘ��҂͑S�Ė������ɋ���
			if( 'admin' == $loginUserType )
				return true;

			switch( $_GET[ 'type' ] )
			{
				default :
					return false;
			}
		}
		
		/**
		 * �폜���e�m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 * @return �G���[�����邩��^�U�l�œn���B
		 */
		function deleteCheck(&$gm, &$rec, $loginUserType, $loginUserRank ){
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $TEMPLATE_CLASS_SYSTEM;
			// **************************************************************************************
			
			return self::$checkData->getCheck();
		}
        
		/**
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 * @param edit �ҏW�Ȃ̂��A�V�K�ǉ��Ȃ̂���^�U�l�œn���B
		 * @return �G���[�����邩��^�U�l�œn���B
		 */
        function registCompCheck( &$gm, $rec, $loginUserType, $loginUserRank ,$edit=false){
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
            global $LOGIN_ID;
            global $CART_STATE_IN;
            global $REGIST_ERROR_DESIGN;
			// **************************************************************************************
        
			// �`�F�b�N����
            
			$check			 = true;
            $db	 = $gm[ $_GET['type'] ]->getDB();
            
            if(!$edit){
	            //�d���o�^�`�F�b�N
	            $table	 = $db->searchTable(  $db->getTable(), 'id', '=', $db->getData( $rec, 'id' )  );
	            if($db->getRow($table) >= 1){
	                self::$checkData->addError('duplication_id');
	            }
            }

			if( $edit )
			{
				//Const/AdminData/MailDup�̃`�F�b�N
				$options = $gm[ $_GET[ 'type' ] ]->colEdit;

				foreach( $options as $column => $validates )
				{
					$validates = explode( '/' , $validates );

					if( in_array( 'Const' , $validates ) )
						self::$checkData->checkConst( $column , null );

					if( in_array( 'AdminData' , $validates ) )
						self::$checkData->checkAdminData( $column , null );

					if( in_array( 'MailDup' , $validates ) )
						self::$checkData->checkMailDup( $column , null );
				}
			}

           	// �ŗL�̃`�F�b�N����
/*			switch( $_GET['type'] )
			{
                case 'job':
            }*/
			return self::$checkData->getCheck();
        }

		/**
		 * �o�^�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function registProc( &$gm, &$rec, $loginUserType, $loginUserRank ,$check = false)
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $ID_LENGTH;
			global $ID_HEADER;
			global $LOGIN_ID;
			global $ACTIVE_NONE;
			global $terminal_type;
			// **************************************************************************************
            
			$db	 = $gm[ $_GET['type'] ]->getDB();
            
			// ID�Ɠo�^���Ԃ��L�^�B
			$db->setData(  $rec, 'id',			 SystemUtil::getNewId( $db, $_GET['type']) );
			$db->setData( $rec, 'regist',		 time() );

			if(!$check) { $this->uplodeComp($gm,$db,$rec); } // �t�@�C���̃A�b�v���[�h��������
		}



		/**
		 * �o�^�������������B
		 * �o�^�������Ƀ��[���œ��e��ʒm�������ꍇ�Ȃǂɗp���܂��B
		 * 
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec ���R�[�h�f�[�^�B
		 */
		function registComp( &$gm, &$rec, $loginUserType, $loginUserRank )
		{}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �ҏW�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �ҏW�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function editProc( &$gm, &$rec, $loginUserType, $loginUserRank ,$check = false)
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $_GLOBAL_PAY_OLD_STATE;
			global $_GLOBAL_PAY_OLD_COST;
			// **************************************************************************************

			if( !$check )
			{
				//�t�@�C���̃A�b�v���[�h��������
				$db = $gm[ $_GET[ 'type' ] ]->getDB();

				$this->uplodeComp($gm , $db , $rec );
			}
		}



		/**
		 * �ҏW���������B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function editComp( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $_GLOBAL_PAY_OLD_STATE;
			global $_GLOBAL_PAY_OLD_COST;
			global $ACTIVE_NONE;
			global $ACTIVE_ACTIVATE;
			// **************************************************************************************
		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �폜�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �폜�����B
		 * �폜�����s����O�Ɏ��s����������������΁A�����ɋL�q���܂��B
		 * �Ⴆ�΃��[�U�f�[�^���폜����ۂɃ��[�U�f�[�^�ɕR�t����ꂽ�f�[�^���폜����ۂȂǂɗL���ł��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function deleteProc( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $LOGIN_ID;
			// **************************************************************************************
			
			$db		 = $gm[ $_GET['type'] ]->getDB();
			
			// �폜���s����
			switch( $_GET['type'] )
			{
				default:
					// ���R�[�h���폜���܂��B
					$db->deleteRecord( $rec );
					break;
			}
			
		}



		/**
		 * �폜���������B
		 * �o�^�폜�������Ɏ��s����������������΃R�R�ɋL�q���܂��B
		 * �폜�������[���𑗐M�������ꍇ�Ȃǂɗ��p���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function deleteComp( &$gm, &$rec, $loginUserType, $loginUserRank ){
			global $LOGIN_ID;
            global $ACTIVE_ACTIVATE;
            
            $db = $gm[$_GET['type']]->getDB();
            if( $_GET['type'] == $loginUserType && $LOGIN_ID == $db->getData( $rec , 'id' ) ){
                SystemUtil::logout($loginUserType);
            }
		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �����֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �����O�����B
		 * �������������������s�O�ɕύX�������ꍇ�ɗ��p���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param sr �����p�����[�^���Z�b�g�ς݂�Search�I�u�W�F�N�g
		 */
		function searchResultProc( &$gm, &$sr, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $THIS_TABLE_IS_USERDATA;
			global $ACTIVE_ACTIVATE;
			// **************************************************************************************
			global $LOGIN_ID;
			// **************************************************************************************
			
			$db		 = $gm[ $_GET['type'] ]->getDB();
					
			switch( $_GET['type'] )
			{
				default:
					break;
			}
            
			
		}

		/**
		 * ���������B
		 * �t�H�[�����͈ȊO�̕��@�Ō���������ݒ肵�����ꍇ�ɗ��p���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param table �t�H�[���̂���̓��͓��e�Ɉ�v���郌�R�[�h���i�[�����e�[�u���f�[�^�B
		 */
		function searchProc( &$gm, &$table, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $THIS_TABLE_IS_USERDATA;
			global $ACTIVE_ACTIVATE;
            global $ACTIVE_ACCEPT;
			// **************************************************************************************
			global $LOGIN_ID;
            global $HOME;
			// **************************************************************************************
			
			$db		 = $gm[ $_GET['type'] ]->getDB();
					
			switch( $_GET['type'] )
			{
				default:
					if(  $THIS_TABLE_IS_USERDATA[ $_GET['type'] ]  )
					{
						if( $loginUserType != 'admin' )	 { $table	 = $db->searchTable( $table, 'activate', 'in', array($ACTIVE_ACTIVATE ,$ACTIVE_ACCEPT) ); }
					}
					break;
			}
		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �ڍ׏��֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////


		/**
		 * �ڍ׏�񂪉{�����ꂽ�Ƃ��ɕ\�����ėǂ���񂩂�Ԃ����\�b�h�B
		 * activate�J��������J�ۃt���O�Aregist��update���ɂ��\�����Ԃ̐ݒ�A�A�N�Z�X�����ɂ��t�B���^�Ȃǂ��s���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �A�N�Z�X���ꂽ���R�[�h�f�[�^�B
		 * @return �\�����ėǂ����ǂ�����^�U�l�œn���B
		 */
        function infoCheck( &$gm, &$rec, $loginUserType, $loginUserRank )
        {
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
            global $ACTIVE_ACTIVATE;
            global $ACTIVE_ACCEPT;
            global $ACTIVE_NONE;
            global $LOGIN_ID;
			// **************************************************************************************

            return true;
        }


		/**
		 * �ڍ׏�񂪉{�����ꂽ�Ƃ��ɌĂяo����鏈���B
		 * ���ɑ΂���A�N�Z�X���O����肽���Ƃ��ȂǂɗL�p�ł��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �A�N�Z�X���ꂽ���R�[�h�f�[�^�B
		 */
		function doInfo( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************

		}

		/**
		 * �ڍ׏��O�����B
		 * �ȈՏ��ύX�ŗ��p
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �A�N�Z�X���ꂽ���R�[�h�f�[�^�B
		 */
		function infoProc( &$gm, &$rec, $loginUserType, $loginUserRank ){
		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//   �A�N�e�B�x�[�g�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////

        //activate����y�уA�N�e�B�x�[�g��������
        function activateAction( &$gm, &$rec, $loginUserType, $loginUserRank ){
        global $ACTIVE_NONE;
        global $ACTIVE_ACTIVATE;
        global $MAILSEND_ADDRES;
        global $MAILSEND_NAMES;
		global $template_path;
		global $mobile_path;
        
            $db = $gm[ $_GET['type'] ]->getDB();
            
			if(  $db->getData( $rec, 'activate' ) == $ACTIVE_NONE  )
			{
				$db->updateRecord( $rec );

                $mail_template = Template::getTemplate('',1,$_GET['type'], "ACTIVATE_COMP_MAIL" );
				Mail::send( $mail_template , $MAILSEND_ADDRES, $db->getData( $rec, 'mail' ), $gm[ $_GET['type'] ], $rec , $MAILSEND_NAMES );
				Mail::send( $mail_template , $MAILSEND_ADDRES, $MAILSEND_ADDRES, $gm[ $_GET['type'] ], $rec , $MAILSEND_NAMES );
			}
            return true;
        }


		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//   ���O�C���֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////

        //���O�A�E�g���ԏ���
        //�A��l��false�ɂ���ƃ��O�A�E�g�����~�����
        function logoutProc( $loginUserType ){
        	
        	if( $_SESSION['ADMIN_MODE'] ){
        		unset($_SESSION['ADMIN_MODE']);
        	}
        	
            return true;
        }
        
        //���O�C�����ԏ���
        //�Ԃ�l��false�ɂ���ƃ��O�C�������~�����
        function loginProc( $check , &$loginUserType , &$id ){
        	global $gm;
			global $LOGIN_ID;
        	
        	if( $loginUserType == 'admin' && isset($_GET['type']) && isset($_GET['id']) ){
        		$loginUserType = $_GET['type'];
        		$id	= $_GET['id'];
        		$_SESSION['ADMIN_MODE'] = true;
        		return true;
        	}
        	
        	if( $_SESSION['ADMIN_MODE'] ){
        		$loginUserType = 'admin';
        		$id	= 'ADMIN';
        		unset($_SESSION['ADMIN_MODE']);
        		return true;
        	}
        	
        	//false���X���[
            if(!$check){return $check;}
            return true;
        }
        
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/



		/**********************************************************************************************************
		 * �ėp�V�X�e���`��n�p���\�b�h
		 **********************************************************************************************************/



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �o�^�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �o�^�t�H�[����`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistForm( &$gm, $rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $REGIST_FORM_PAGE_DESIGN;
			// **************************************************************************************
			global $TABLE_NAME;
			global $THIS_TABLE_IS_USERDATA;
			global $THIS_TABLE_IS_NOHTML;
			global $MAIL_SEND_FALED_DESIGN;
			// **************************************************************************************
			global $ADWARES_EXCHANGE;
            global $LOGIN_ID;
            
            $this->setErrorMessage($gm[ $_GET['type'] ]);
            
			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
					if($gm[$_GET['type']]->maxStep >= 2)
	                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'REGIST_FORM_PAGE_DESIGN' . $_POST['step'] , 'regist.php?type='. $_GET['type'] );
					else
	                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'REGIST_FORM_PAGE_DESIGN' , 'regist.php?type='. $_GET['type'] );
			}
		}


		/**
		 * �o�^���e�m�F�y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �o�^�����i�[�������R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistCheck( &$gm, $rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $REGIST_CHECK_PAGE_DESIGN;
			// **************************************************************************************
			global $TABLE_NAME;
			global $THIS_TABLE_IS_USERDATA;
			global $MAIL_SEND_FALED_DESIGN;
			global $LOGIN_PASSWD_COLUM;
			// **************************************************************************************
			
			// �m�F���͌n�̕⊮
			if(  $THIS_TABLE_IS_USERDATA[ $_GET['type'] ]  )
			{
				foreach( $gm[ $_GET['type'] ]->colName as $name ){
					if( $LOGIN_PASSWD_COLUM[ $_GET['type'] ] != $name ){//�}�[�W������type��password��������
						if( ($pos = strpos( $gm[ $_GET['type'] ]->colRegist[$name], 'ConfirmInput' ) ) !== FALSE ){
							$after = substr( $gm[ $_GET['type'] ]->colRegist[$name], $pos+13);
							if( ( $end = strpos( $after, '/') ) !== FALSE ){ $after = substr( $after, 0, $end ); }
							$db = $gm[$_GET['type']]->getDB();
							$gm[ $_GET['type'] ]->addHiddenForm( $after, $db->getData( $rec, $name ) );
						}
					}
				}
			}
			
			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'REGIST_CHECK_PAGE_DESIGN' , 'regist.php?type='. $_GET['type'] );
			}
		
		}



		/**
		 * �o�^�����y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �o�^�����i�[�������R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistComp( &$gm, $rec, $loginUserType, $loginUserRank )
		{
	
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************
		
			
			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'REGIST_COMP_PAGE_DESIGN' );

			}
            

		
		}



		/**
		 * �o�^���s��ʂ�`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistFaled( &$gm, $loginUserType, $loginUserRank )
		{
            $this->setErrorMessage($gm[ $_GET['type'] ]);
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************
            Template::drawTemplate( $gm[ $_GET['type'] ] , null ,'' , $loginUserRank , '' , 'REGIST_FALED_DESIGN' );

//            Template::simpleDrawTemplate( 'REGIST_FALED_DESIGN' );

		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �ҏW�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �ҏW�t�H�[����`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawEditForm( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $EDIT_FORM_PAGE_DESIGN;
			global $LOGIN_ID;
            global $NOT_LOGIN_USER_TYPE;
			// **************************************************************************************
			
            $this->setErrorMessage($gm[ $_GET['type'] ]);
            
			$db = $gm[ $_GET[ 'type' ] ]->getDB();

			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'EDIT_FORM_PAGE_DESIGN' , 'edit.php?type='. $_GET['type'] .'&id='. $_GET['id'] );
			}
			
		
		}


		/**
		 * �ҏW���e�m�F�y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawEditCheck( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $EDIT_CHECK_PAGE_DESIGN;
			global $LOGIN_ID;
            global $NOT_LOGIN_USER_TYPE;
			// **************************************************************************************
			
			$db = $gm[ $_GET[ 'type' ] ]->getDB();
			
			// �m�F���͌n�̕⊮
			if(  $THIS_TABLE_IS_USERDATA[ $_GET['type'] ]  )
			{
				foreach( $gm[ $_GET['type'] ]->colName as $name ){
					if( $LOGIN_PASSWD_COLUM[ $_GET['type'] ] != $name ){//�}�[�W������type��password��������
						if( ($pos = strpos( $gm[ $_GET['type'] ]->colRegist[$name], 'ConfirmInput' ) ) !== FALSE ){
							$after = substr( $gm[ $_GET['type'] ]->colRegist[$name], $pos+13);
							if( ( $end = strpos( $after, '/') ) !== FALSE ){ $after = substr( $after, 0, $end ); }
							$gm[ $_GET['type'] ]->addHiddenForm( $after, $db->getData( $rec, $name ) );
						}
					}
				}
			}
			
			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'EDIT_CHECK_PAGE_DESIGN' , 'edit.php?type='. $_GET['type'] .'&id='. $_GET['id'] );
			}
		
		}



		/**
		 * �ҏW�����y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawEditComp( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $EDIT_COMP_PAGE_DESIGN;
			global $LOGIN_ID;
            global $NOT_LOGIN_USER_TYPE;
			// **************************************************************************************
			
			$db = $gm[ $_GET[ 'type' ] ]->getDB();

			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'EDIT_COMP_PAGE_DESIGN' , 'edit.php?type='. $_GET['type'] .'&id='. $_GET['id'] );
			}
		}



		/**
		 * �ҏW���s��ʂ�`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawEditFaled( &$gm, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************
			
			Template::drawErrorTemplate();
		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �폜�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �폜�m�F�y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawDeleteCheck( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $DELETE_CHECK_PAGE_DESIGN;
			// **************************************************************************************
			global $TABLE_NAME;
			global $THIS_TABLE_IS_USERDATA;
			global $LOGIN_ID;
			global $NOT_LOGIN_USER_TYPE;
			// **************************************************************************************
			
			$db = $gm[ $_GET[ 'type' ] ]->getDB();

			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'DELETE_CHECK_PAGE_DESIGN' , 'edit.php?type='. $_GET['type'] .'&id='. $_GET['id'] );
			}
			
		
		}



		/**
		 * �폜�����y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawDeleteComp( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $DELETE_COMP_PAGE_DESIGN;
			global $LOGIN_ID;
			global $NOT_LOGIN_USER_TYPE;
			// **************************************************************************************
			
			$db = $gm[ $_GET[ 'type' ] ]->getDB();

			switch(  $_GET['type']  ){

				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'DELETE_COMP_PAGE_DESIGN'  );
					break;
			}
		
		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �����֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �����t�H�[����`�悷��B
		 *
		 * @param sr Search�I�u�W�F�N�g�B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawSearchForm( &$sr, $loginUserType, $loginUserRank )
		{
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $SEARCH_FORM_PAGE_DESIGN;
			// **************************************************************************************
			$sr->addHiddenForm( 'type', $_GET['type'] );
			
			switch( $_GET['type'] )
			{
				default:
                    $file = Template::getTemplate( $loginUserType , $loginUserRank , $_GET['type'] , 'SEARCH_FORM_PAGE_DESIGN' );
                    if( strlen( $file ) )	{ print $sr->getFormString( $file , 'search.php'  ); }
                    else		{ Template::drawErrorTemplate(); }
					break;
			}
		}

        function drawSearch( &$gm, &$sr, $table, $loginUserType, $loginUserRank ){
            SearchTableStack::pushStack($table);

            if(  isset( $_GET['multimail'] )  ){
                $db = $gm[ $_GET['type'] ]->getDB();
                $row	 = $db->getRow( $table );
                for($i=0; $i<$row; $i++){
                    $rec	 = $db->getRecord( $table, $i );
                    $_GET['receive_id'][] = $db->getData( $rec, 'id' );
                }
                $_GET['type'] = 'multimail';
                include_once "regist.php";
            }else{

			$label = 'SEARCH_RESULT_DESIGN';

			if( $_GET[ 'exstyle' ] )
				$label .= '_' . strtoupper( $_GET[ 'exstyle' ] );

            Template::drawTemplate( $gm[ $_GET['type'] ] , null , $loginUserType , $loginUserRank , $_GET['type'] , $label );
            }
        }

		/**
		 * �������ʁA�Y���Ȃ���`��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawSearchNotFound( &$gm, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $SEARCH_NOT_FOUND_DESIGN;
			// **************************************************************************************
			global $NOT_LOGIN_USER_TYPE;
			// **************************************************************************************
			
			$label = 'SEARCH_NOT_FOUND_DESIGN';

			if( $_GET[ 'exstyle' ] )
				$label .= '_' . strtoupper( $_GET[ 'exstyle' ] );

			Template::drawTemplate( $gm[ $_GET['type'] ] , null , $loginUserType , $loginUserRank , $_GET['type'] , $label );
/*
			switch( $_GET['type'] )
			{					
				default:
					Template::drawTemplate( $gm[ $_GET['type'] ] , null , $loginUserType , $loginUserRank , $_GET['type'] , 'SEARCH_NOT_FOUND_DESIGN' );
					break;
			}
*/
		
		}

		/**
		 * �����G���[��`��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawSearchError( &$gm, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************
			Template::drawErrorTemplate();
		
		}



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		// �ڍ׃y�[�W�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////



		/**
		 * �ڍ׏��\���G���[��`��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawInfoError( &$gm, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************
			Template::drawErrorTemplate();
		
		}

		/**
		 * �ڍ׏��y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawInfo( &$gm, &$rec, $loginUserType, $loginUserRank )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $INFO_PAGE_DESIGN;
			// **************************************************************************************
			global $TABLE_NAME;
			global $THIS_TABLE_IS_USERDATA;
			global $LOGIN_ID;
            global $HOME;
			// **************************************************************************************
			
			switch(  $_GET['type']  )
			{
				default:
					// �ėp����
                    Template::drawTemplate( $gm[ $_GET['type'] ] , $rec , $loginUserType , $loginUserRank , $_GET['type'] , 'INFO_PAGE_DESIGN' , 'info.php?type='. $_GET['type'] .'&id='. $_GET['id'] );
			}
			
		
		}

	/**
	 * �������o�́B
	 * ����̏����ŏo�͓��e��ύX�������ꍇ��$buffer�ɂ��̓��e���w�肵�Ă�������
	 *
	 * @param gm GUIManager�I�u�W�F�N�g�ł��B
	 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B
	 * @param args �R�}���h�R�����g�����z��ł��B�@��������ID��n���܂��B �������Ƀ����N���邩��^�U�l�œn���܂��B
	 */
	function drawDescription( &$gm, $rec, $args )
	{
		$buffer = SystemUtil::getSystemData('description');
		if( strpos($_SERVER['REQUEST_URI'], "info.php") !== false)
		{// �ڍ׃y�[�W�̏ꍇ
		
		}
		else if( strpos($_SERVER['REQUEST_URI'], "search.php") !== false)
		{// �����y�[�W�̏ꍇ
		
		}
		
		$this->addBuffer( $buffer );
	}


	/**
	 * �L�[���[�h���o�́B
	 * ����̏����ŏo�͓��e��ύX�������ꍇ��$buffer�ɂ��̓��e���w�肵�Ă�������
	 *
	 * @param gm GUIManager�I�u�W�F�N�g�ł��B
	 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B
	 * @param args �R�}���h�R�����g�����z��ł��B�@��������ID��n���܂��B �������Ƀ����N���邩��^�U�l�œn���܂��B
	 */
	function drawKeywords( &$gm, $rec, $args )
	{
		$buffer = SystemUtil::getSystemData('keywords');
		if( strpos($_SERVER['REQUEST_URI'], "info.php") !== false)
		{// �ڍ׃y�[�W�̏ꍇ
		
		}
		else if( strpos($_SERVER['REQUEST_URI'], "search.php") !== false)
		{// �����y�[�W�̏ꍇ
		
		}
		
		$this->addBuffer( $buffer );
	}

		/**
		 * �e���v���[�g�̎��s��ʂ�`�悷��B
		 *
		 * @param gm template��GUIManager
		 * @param error_name error��  �f�U�C���̃p�[�c��
		 */
		function getTemplateFaled( $gm, $lavel , $error_name  ){
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
            global $loginUserType;
            global $loginUserRank;
			// **************************************************************************************

            $h = Template::getTemplateString( $gm , null , $loginUserType, $loginUserRank , 'stemplate' , $lavel , null , null , 'head' );
            $h .=Template::getTemplateString( $gm , null , $loginUserType, $loginUserRank , 'stemplate' , $lavel , null , null , $error_name );
            $h .=Template::getTemplateString( $gm , null , $loginUserType, $loginUserRank , 'stemplate' , $lavel , null , null , 'foot' );
            return $h;
		}
		



		/////////////////////////////////////////////////////////////////////////////////////////////////////////
		//   �A�N�e�B�x�[�g�֌W
		/////////////////////////////////////////////////////////////////////////////////////////////////////////

        function drawActivateComp( &$gm, &$rec, $loginUserType, $loginUserRank ){
                $gm[ $_GET['type'] ]->draw( Template::getLabelFile('ACTIVATE_DESIGN_HTML'), $rec );
        }
        function drawActivateFaled( &$gm, &$rec, $loginUserType, $loginUserRank ){
                $gm[ $_GET['type'] ]->draw( Template::getLabelFile('ACTIVATE_FALED_DESIGN_HTML'), $rec );
        }

		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/


		/**
		 * �t�@�C���A�b�v���[�h���s��ꂽ�ꍇ�̈ꎞ�����B
		 *
		 * @param db Database�I�u�W�F�N�g
		 * @param rec ���R�[�h�f�[�^
		 * @param colname �A�b�v���[�h���s��ꂽ�J����
		 * @param file �t�@�C���z��
		 */		
		function doFileUpload( &$db, &$rec, $colname, &$file )
		{
			if( isset($_POST[ $colname . '_DELETE' ]) &&
					is_array($_POST[ $colname . '_DELETE' ]) &&
					$_POST[ $colname . '_DELETE' ][0] == "true" ){ return; }
			
			if( $file[ $colname ]['name'] != "" ){
				global $MAX_FILE_SIZE;
				if( isset( $_POST['MAX_FILE_SIZE'] ) ){
					$max_size = $_POST['MAX_FILE_SIZE'];
				}else{
					$max_size = $MAX_FILE_SIZE;
				}
				if( $file[ $colname ]['size'] > $max_size ){ return; }
				
				
				// �g���q�̎擾
				preg_match( '/(\.\w*$)/', $file[ $colname ]['name'], $tmp );
				$ext		 = strtolower(str_replace( ".", "", $tmp[1] ));
				
				// �f�B���N�g���̎w��
				$dirList	 = explode( '/', $this->fileDir );
				$directory	 = 'file/tmp/';
				if(!is_dir($directory)) { mkdir( $directory, 0777 ); } //�f�B���N�g�������݂��Ȃ��ꍇ�͍쐬
				
				// �t�@�C���p�X�̍쐬
				$fileName	 = $directory.md5( time(). $file[ $colname ]['name'] ).'.'.$ext;
				
				// ���g���q�̂݃t�@�C���̃A�b�v���[�h
				switch($ext)
				{
				case 'gif':
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'swf':
				case 'lzh':
				case 'zip':
					move_uploaded_file( $file[ $colname ]['tmp_name'], $fileName );
					$db->setData( $rec, $colname, $fileName );
					break;
				default:
					break;
				}
			}else if( $_POST[ $colname . '_filetmp' ] != ""  ){
				$db->setData( $rec, $colname, $_POST[ $colname.'_filetmp' ] );
				return;
			}else if( $_POST[ $colname ] != "" ){
				$db->setData( $rec, $colname, $_POST[ $colname ] );
			}
		}
	
		
		/**
		 * �t�@�C���A�b�v���[�h�̊��������B
		 * �ꎞ�A�b�v���[�h�Ƃ��Ă����t�@�C���𐳎��A�b�v���[�h�ւƏ���������B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g
		 * @param db Database�I�u�W�F�N�g
		 * @param rec ���R�[�h�f�[�^
		 */
		function uplodeComp( &$gm, &$db, &$rec )
		{
			// �J�����̂����t�@�C���A�b�v���[�h�^�C�v�̂ݓ��e���m�F����
			foreach( $db->colName as $colum )
			{
				if( $gm[$_GET['type']]->colType[$colum] == 'image' ||  $gm[$_GET['type']]->colType[$colum] == 'file' )
				{
					$before	 = $db->getData( $rec, $colum );
					$after	 = preg_replace( '/(file\/tmp\/)(\w*\.\w*)$/', '\2', $before );
					if( $before != $after )
					{// �t�@�C���̃A�b�v���[�h���s���Ă����ꍇ�f�[�^�������ւ���B
						// �g���q�̎擾
						preg_match( '/(\.\w*$)/', $after, $tmp );
						$ext		 = strtolower(str_replace( ".", "", $tmp[1] ));
						// �f�B���N�g���̎w��
						$dirList	 = explode( '/', $this->fileDir );
						$directory	 = 'file/';
						foreach( $dirList as $dir )
						{
							switch($dir)
							{
							case 'ext': // �g���q	
								$directory .= $ext.'/'; 
								break;
							case 'cat':	// ��ޕ�
								switch($ext)
								{
								case 'gif':
								case 'jpg':
								case 'jpeg':
								case 'png':
									$cat = 'image';
									break;
								case 'swf':
									$cat = 'flash';
									break;
								case 'lzh':
								case 'zip':
									$cat = 'archive';
									break;
								default:
									$cat = 'category';
									break;
								}
								$directory .= $cat.'/'; 
								break;
							default:	// timeformat
								$directory .= date($dir).'/'; 
								break;
							}
							if(!is_dir($directory)) { mkdir( $directory, 0777 ); } //�f�B���N�g�������݂��Ȃ��ꍇ�͍쐬
						}
						if(!is_dir($directory)) { mkdir( $directory, 0777 ); } //�f�B���N�g�������݂��Ȃ��ꍇ�͍쐬
						
					    if( is_file($before) && copy($before, $directory.$after) ){ unlink($before); }
						$db->setData( $rec, $colum, $directory.$after );
					}
				}
			}
		
		}


		/**
		 * �������ʕ`��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B
		 * @param args �R�}���h�R�����g�����z��ł��B�@��������ID��n���܂��B �������Ƀ����N���邩��^�U�l�œn���܂��B
		 */
		function searchResult( &$gm, $rec, $args )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			
			global $loginUserType;
			global $loginUserRank;
			
			global $resultNum;
			global $pagejumpNum;
			global $phpName;
			// **************************************************************************************
			
			$db		 = $gm->getDB();
			
			$table   = SearchTableStack::getCurrent();
			$row	 = $db->getRow( $table );
		
			// �ϐ��̏������B
			if(  !isset( $_GET['page'] )  ){ $_GET['page']	 = 0; }
			
			if( 0 < $_GET[ 'page' ] ) //�y�[�W���w�肳��Ă���ꍇ
			{
				$beginRow = $_GET[ 'page' ] * $resultNum; //�y�[�W���̍ŏ��̃��R�[�h�̍s��
				$tableRow = $db->getRow( $table );        //�e�[�u���̍s��

				if( $tableRow <= $beginRow ) //�e�[�u���̍s���𒴂��Ă���ꍇ
				{
					$maxPage = ( int )( ( $tableRow - 1 ) / $resultNum ); //�\���\�ȍő�y�[�W

					$_GET[ 'page' ] = $maxPage;
				}
			}

			if(  $_GET['page'] < 0 || $_GET['page'] * $resultNum + 1 > $db->getRow( $table )  )
			{
				// �������ʂ�\������y�[�W�����������ꍇ

                $tgm	 = SystemUtil::getGM();
                for($i=0; $i<count($TABLE_NAME); $i++)
                {

                    $tgm[ $_GET['type'] ]->addAlias(  $TABLE_NAME[$i], $tgm[ $TABLE_NAME[$i] ]  );	
                }
				$this->drawSearchError( $tgm , $loginUserType, $loginUserRank );
			}
			else
			{
				// �������ʏ����o�́B
				$viewTable	 = $db->limitOffset(  $table, $_GET['page'] * $resultNum, $resultNum  );
				
				switch( $args[0] )
				{
					case 'info':
						// �������ʏ��f�[�^����
						$gm->setVariable( 'RES_ROW', $row );
						
						$gm->setVariable( 'VIEW_BEGIN', $_GET['page'] * $resultNum + 1 );
						if( $row >= $_GET['page'] * $resultNum + $resultNum )
						{
							$gm->setVariable( 'VIEW_END', $_GET['page'] * $resultNum + $resultNum );
							$gm->setVariable( 'VIEW_ROW', $resultNum );
						}
						else
						{
							$gm->setVariable( 'VIEW_END', $row );
							$gm->setVariable( 'VIEW_ROW', $row % $resultNum );
						}
						$this->addBuffer( $this->getSearchInfo( $gm, $viewTable, $loginUserType, $loginUserRank ) );
						
						break;
						
					case 'result':
						// �������ʂ����X�g�\��
						for($i=0; $i<count($TABLE_NAME); $i++)
						{
							$tgm[ $_GET['type'] ]->addAlias(  $TABLE_NAME[$i], $tgm[ $TABLE_NAME[$i] ]  );	
						}
						$this->addBuffer( $this->getSearchResult( $gm, $viewTable, $loginUserType, $loginUserRank ) );
						break;
					case 'pageChange':
						$this->addBuffer( $this->getSearchPageChange( $gm, $viewTable, $loginUserType, $loginUserRank, $row, $pagejumpNum, $resultNum, $phpName, 'page' )  );
						break;
					case 'setResultNum':
						$resultNum				 = $args[1];
						break;
						
					case 'setPagejumpNum':
						$pagejumpNum			 = $args[1];
						break;
						
					case 'setPhpName': // �y�[�W���[�̃����Nphp�t�@�C�����w��(���ݒ莞��search.php)
						$phpName				 = $args[1];
						break;
					case 'row':
						$this->addBuffer( $row );
						break;
				}
			}
		}
		
		/**
		 * �������ʕ`��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B
		 * @param args �R�}���h�R�����g�����z��ł��B�@��������ID��n���܂��B �������Ƀ����N���邩��^�U�l�œn���܂��B
		 */
		function searchCreate( &$gm, $rec, $args )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			
			global $loginUserType;
			global $loginUserRank;
			
			global $resultNum;
			global $pagejumpNum;
			// **************************************************************************************

			switch($args[0]){
				case 'new':
					if( isset( $args[1] ))
						$type = $args[1];
					else
						$type = $_GET['type'];
					SearchTableStack::createSearch( $type );
					break;
				case 'run':
					SearchTableStack::runSearch();
					break;
				case 'setPal':
				case 'setParam':
					SearchTableStack::setParam($args[1],array_slice($args,2));
					break;
				case 'setVal':
				case 'setValue':
					SearchTableStack::setValue($args[1],array_slice($args,2));
					break;
				case 'setAlias':
					SearchTableStack::setAlias($args[1],array_slice($args,2));
					break;
				case 'setAliasParam':
					SearchTableStack::setAliasParam($args[1],array_slice($args,2));
					break;
				case 'set'://�\��
					break;
				case 'end':
					SearchTableStack::endSearch();
					break;
				case 'setPartsName':
					SearchTableStack::setPartsName($args[1],$args[2]);
					break;
				case 'sort':
					SearchTableStack::sort($args[1],$args[2]);
					break;
				case 'row':
					$this->addBuffer( SearchTableStack::getCurrentRow() );
					break;
			}
		}
        
		/**
		 * �������ʂ����X�g�`�悷��B
		 * �y�[�W�؂�ւ��͂��̗̈�ŕ`�悷��K�v�͂���܂���B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g
		 * @param table �������ʂ̃e�[�u���f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function getSearchResult( &$_gm, $table, $loginUserType, $loginUserRank )
		{
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $gm;
			// **************************************************************************************
			
			$type = SearchTableStack::getType();
			
			$label = 'SEARCH_LIST_PAGE_DESIGN';

			if( $_GET[ 'exstyle' ] )
				$label .= '_' . strtoupper( $_GET[ 'exstyle' ] );

			switch( $type )
			{
				default:
					if(SearchTableStack::getPartsName('list'))
	                    $html = Template::getListTemplateString( $gm[ $type ] , $table , $loginUserType , $loginUserRank , $type , $label , false , SearchTableStack::getPartsName('list') );
	                else
	                    $html = Template::getListTemplateString( $gm[ $type ] , $table , $loginUserType , $loginUserRank , $type , $label );
                    break;
			}
            return $html;
		}

		/**
		 * �������ʃy�[�W�؂�ւ�����`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g
		 * @param table �������ʂ̃e�[�u���f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 * @param partkey �����L�[
		 */
		function getSearchPageChange( &$gm, $table, $loginUserType, $loginUserRank, $row, $pagejumpNum, $resultNum, $phpName, $param )
		{
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $phpName;
            
			$type = SearchTableStack::getType();
			
			switch( $type )
			{
				default:					
					$design		 = Template::getTemplate( $loginUserType , $loginUserRank , '' , 'SEARCH_PAGE_CHANGE_DESIGN' );

					if(!strlen($phpName)) { $phpName = 'search.php'; }
					
					$html = SystemUtil::getPager( $gm, $design, $_GET, $row, $pagejumpNum, $resultNum, $phpName, $param , SearchTableStack::getPartsName('change') );
                    break;

			}
            return $html;
		}

		/**
		 * �������ʂ̃y�[�W�؂�ւ������擾����B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g
		 * @param table �������ʂ̃e�[�u���f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function getSearchInfo( &$gm, $table, $loginUserType, $loginUserRank )
		{
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************
			
			$type = SearchTableStack::getType();
			
			switch( $type )
			{
				default:
					if(SearchTableStack::getPartsName('info'))
	                    $html = Template::getTemplateString( $gm , null , $loginUserType , $loginUserRank , '' , 'SEARCH_PAGE_CHANGE_DESIGN' , false , null, SearchTableStack::getPartsName('info') );
	                else
	                    $html = Template::getTemplateString( $gm , null , $loginUserType , $loginUserRank , '' , 'SEARCH_PAGE_CHANGE_DESIGN' , false , null, 'info' );
                    break;

			}
            return $html;
		}

        //main css output
        function css_load( &$gm, $rec, $args ){
        global $css_name;
        global $css_file_paths;
        global $loginUserType;
            if(isset($_GET['css_name'])){
                $css_name = $_GET['css_name'];
            }
            
            $file = Template::getTemplate( '' , 3 , $css_name , 'CSS_LINK_LIST' );
            if(strlen($file))
                $this->addBuffer( '<link rel="stylesheet" type="text/css" href="'.$file.'" media="all" />'."\n" );
            
            if( isset($css_file_paths) ){
                if( isset($css_file_paths['all']) || is_array($css_file_paths['all']) ){
                    foreach( $css_file_paths['all'] as $css_file_path ){
                        $this->addBuffer( '<link rel="stylesheet" type="text/css" href="'.$css_file_path.'" media="all" />'."\n" );
                    }
                }
                if( isset($css_file_paths[$loginUserType]) || is_array($css_file_paths[$loginUserType]) ){
                    foreach( $css_file_paths[$loginUserType] as $css_file_path ){
                        $this->addBuffer( '<link rel="stylesheet" type="text/css" href="'.$css_file_path.'" media="all" />'."\n" );
                    }
                }
            }
        }
        //main js output
        function js_load( &$gm, $rec, $args ){
            global $js_file_paths;
            global $loginUserType;
            
            if( isset($js_file_paths['all']) || is_array($js_file_paths['all']) ){
                foreach( $js_file_paths['all'] as $js_file_path ){
                	$this->addBuffer( '<script type="text/javascript" src="'.$js_file_path.'"></script>'."\n" );
            	}
        	}
            if( isset($js_file_paths[$loginUserType]) || is_array($js_file_paths[$loginUserType]) ){
                foreach( $js_file_paths[$loginUserType] as $js_file_path ){
                    $this->addBuffer( '<script type="text/javascript" src="'.$js_file_path.'"></script>'."\n" );
                }
            }
        }
        //main link output
        function link_load( &$gm, $rec, $args ){
            global $head_link_object;
            
            if( is_null($head_link_object) || !is_array($head_link_object) )
                return;
            foreach( $head_link_object as $head_link ){
                $this->addBuffer( '<link rel="'.$head_link['rel'].'" type="'.$head_link['type'].'" href="'.$head_link['href'].'" />'."\n" );
            }
        }
		/*
		 * error���b�Z�[�W�̌ʕ\���p
		 */
		function validate( &$gm, $rec, $args ){
			foreach( $args as $error ){
				$this->addBuffer( self::$checkData->getError( $error ) );
			}
		}
		
		/*
		 * error���b�Z�[�W�̌ʕ\���p
		 */
		function is_validate( &$gm, $rec, $args ){
			foreach( explode('/',$args[0]) as $l ){
				$this->addBuffer( self::$checkData->isError( $l , $args[1] ) );
			}
		}
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/


		/**********************************************************************************************************
		 * �V�X�e���p���\�b�h
		 **********************************************************************************************************/

        static $checkData = null;
		
		/**
		 * �R���X�g���N�^�B
		 */
		function System()	{ $this->flushBuffer(); }
	
        /*
         * �G���[���b�Z�[�W��GUIManager��variable�ɃZ�b�g����
         */
        function setErrorMessage(&$gm){
            if( self::$checkData && !self::$checkData->getCheck() ){
                  $gm->setVariable( 'error_msg' , self::$checkData->getError() );
                  $this->error_msg = "";
            }else{
                $gm->setVariable( 'error_msg' , '' );
            }
        }
		
		/*
		 * �y�[�W�S�̂ŋ��ʂ�head��Ԃ���B
		 * �e��\���y�[�W�̍ŏ��ɌĂяo�����֐�
		 * 
		 * �o�͂ɐ��������������ꍇ�╪�򂵂����ꍇ�͂����ŕ��򏈗����L�ڂ���B
		 */
		static function getHead($gm,$loginUserType,$loginUserRank){
			global $NOT_LOGIN_USER_TYPE;
			
			if( self::$head || isset( $_GET['hfnull'] ) ){ return "";}
			
			self::$head = true;
			
			$html = "";
			
			if( $loginUserType == $NOT_LOGIN_USER_TYPE )	{ $html = Template::getTemplateString( null , null , $loginUserType , $loginUserRank , '' , 'HEAD_DESIGN' ); }
			else											{ $html = Template::getTemplateString( $gm[ $loginUserType ] , $rec , $loginUserType , $loginUserRank , '' , 'HEAD_DESIGN' ); }
			
			if($_SESSION['ADMIN_MODE']){
				$html .= Template::getTemplateString( $gm[ $loginUserType ] , $rec , $loginUserType , $loginUserRank , '' , 'HEAD_DESIGN_ADMIN_MODE' );
			}
			return $html;
		}
		
		/*
		 * �y�[�W�S�̂ŋ��ʂ�foot��Ԃ��B
		 * �e��\���y�[�W�̍Ō�ŌĂяo�����֐�
		 * 
		 * �o�͂ɐ��������������ꍇ�╪�򂵂����ꍇ�͂����ŕ��򏈗����L�ڂ���B
		 */
		static function getFoot($gm,$loginUserType,$loginUserRank){
			global $NOT_LOGIN_USER_TYPE;
			
			if( self::$foot || isset( $_GET['hfnull'] ) ){ return "";}
			
			self::$foot = true;
			
			if( $loginUserType == $NOT_LOGIN_USER_TYPE )	{ return Template::getTemplateString( null , null , $loginUserType , $loginUserRank , '' , 'FOOT_DESIGN' ); }
			else											{ return Template::getTemplateString( $gm[ $loginUserType ] , $rec , $loginUserType , $loginUserRank , '' , 'FOOT_DESIGN' ); }
		}
	}

	
	class SearchTableStack{
		private static $stack = Array();
		private static $current_count = 0;
		private static $current_search = null;
		//private static $stack_search = Array();
		
		private static $list_parts = Array();
		private static $info_parts = Array();
		private static $change_parts = Array();
		
		static function pushStack(&$table){
			self::$stack[ self::$current_count ] = $table;
		}
		
		static function popStack(){
			self::$stack[ self::$current_count ];
		}
	
		//�O������̋����㏑��
		static function setCurrent(&$table){
			self::$stack[ self::$current_count ] = $table;
		}
		
		static function getCurrent(){
			return self::$stack[ self::$current_count ];
		}
		
		static function getCurrentCount(){
			return self::$current_count;
		}
		
		static function getCurrentRow(){
			global $gm;
			
			return $gm[ self::getType() ]->getDB()->getRow( self::$stack[ self::$current_count ] );
		}
		
		static function createSearch($type){
			global $gm;
			self::$current_count++;
			
			self::$current_search = new Search($gm[ $type ],$type);
			self::$current_search->paramReset();
			
			self::$list_parts[ self::$current_count ] = "";
			self::$info_parts[ self::$current_count ] = "";
			self::$change_parts[ self::$current_count ] = "";
		}
	
		static function setValue($coumn_name,$var){
			if( count($var) == 1 ){
				self::$current_search->setValue($coumn_name,$var[0]);
			}else{
				self::$current_search->setValue($coumn_name,$var);
			}
		}
		
		static function setParam($table_name,$var){
			self::$current_search->setParamertor($table_name,$var);
		}
	
		static function setAlias($table_name,$var){
			if( is_array($var) ){
				self::$current_search->setAlias($table_name,implode( ' ', $var ) );
			}else{
				self::$current_search->setAlias($table_name,$var);
			}
		}
		static function setAliasParam($coumn_name,$var){
			self::$current_search->setAliasParam($coumn_name,$var);
		}
		
		static function runSearch(){
			global $gm;
			global $loginUserType;
			global $loginUserRank;
			
			$sys	 = SystemUtil::getSystem( self::getType() );
			
			$sys->searchResultProc( $gm, self::$current_search, $loginUserType, $loginUserRank );
			
			$table = self::$current_search->getResult();
			
			$sys->searchProc( $gm, $table, $loginUserType, $loginUserRank );
			
			self::pushStack( $table );
		}
		
		static function endSearch(){
			self::popStack();
			self::$current_count--;
		}
		
		static function setPartsName( $type, $parts ){
			switch($type){
				case 'list':
					self::$list_parts[ self::$current_count ] = $parts;
					break;
				case 'info':
					self::$info_parts[ self::$current_count ] = $parts;
					break;
				case 'change':
					self::$change_parts[ self::$current_count ] = $parts;
					break;
			}
		}
		
		static function getPartsName($type){
			switch($type){
				case 'list':
					return self::$list_parts[ self::$current_count ];
				case 'info':
					return self::$info_parts[ self::$current_count ];
				case 'change':
					return self::$change_parts[ self::$current_count ];
			}
			return "";
		}
		
		static function getType(){
			if( self::$current_count == 0 )
				return $_GET['type'];
			else
				return self::$current_search->type;
		}
		
		static function sort($key,$param){
			self::$current_search->sort['key'] = $key;
			self::$current_search->sort['param'] = $param;
		}
	}

?>
