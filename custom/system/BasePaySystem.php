<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	abstract class BasePaySystem extends System
	{
		//���f�[�^�擾

		abstract function GetType();

		//������

		/**
		 * �o�^�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function registProc( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ = false )
		{
			global $LOGIN_ID;

			$db = SystemUtil::getGMforType( $this->GetType() )->getDB();

			$db->setData( $iRec_ , 'id' , md5( time() . $LOGIN_ID ) );
			$db->setData( $iRec_ , 'regist' ,time() );

			if( !$iCheck_ )
				{ $this->uplodeComp( $iGM_ , $db , $iRec_ ); }
		}

		/**
		 * �o�^�������������B
		 * �o�^�������Ƀ��[���œ��e��ʒm�������ꍇ�Ȃǂɗp���܂��B
		 * 
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec ���R�[�h�f�[�^�B
		 */
		function registComp( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			PayLogic::SetType( $this->GetType() );

			if( PayLogic::IsActivate( $iRec_ ) ) //�F�؏�Ԃ̏ꍇ
				{ PayLogic::AddPay( $iRec_ ); }

			parent::registComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ );
		}

		/**
		 * �ҏW�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function editProc( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ = false )
		{
			PayLogic::SetType( $this->GetType() );
			PayLogic::MemoryOriginRec( $iRec_ );

			if( $dCost = PayLogic::GetNeedUpdateUser( $iRec_ ) )
			{
				$iGM_[ $this->GetType() ]->setVariable( 'alert', '�Y���̃A�t�B���G�C�^�[�ɑ΂��A'.abs($dCost).'�~�̕�V��'.(($dCost>=0)?'���Z':'���Z').'����܂��B' );
			}

			parent::editComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ );
		}

		/**
		 * �ҏW���������B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function editComp( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			PayLogic::SetType( $this->GetType() );
			PayLogic::UpdateReward( $iRec_ );
			PayLogic::AddPayLog( $iRec_, "edit" );

			parent::editComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		/**
		 * �폜���e�m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 * @return �G���[�����邩��^�U�l�œn���B
		 */
		function deleteCheck( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ ){
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $TEMPLATE_CLASS_SYSTEM;
			global $ACTIVE_ACTIVATE;
			// **************************************************************************************
		
			$db  = SystemUtil::getGMforType( $this->GetType() )->getCachedDB();
			$state = $db->getData( $iRec_ , 'state' );
			$cost = $db->getData( $iRec_ , 'cost' );
			
			if( $state == $ACTIVE_ACTIVATE && $cost > 0 )
			{
				$iGM_[ $this->GetType() ]->setVariable( 'alert', '�Y���̃A�t�B���G�C�^�[�ɑ΂��A'.$cost.'�~�̕�V�����Z����܂��B' );
			}
			
			return self::$checkData->getCheck();
		}
	
		/**
		 * �폜�����B
		 * �폜�����s����O�Ɏ��s����������������΁A�����ɋL�q���܂��B
		 * �Ⴆ�΃��[�U�f�[�^���폜����ۂɃ��[�U�f�[�^�ɕR�t����ꂽ�f�[�^���폜����ۂȂǂɗL���ł��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function deleteProc( &$iGM_, &$iRec_, $iLoginUserType_, $iLoginUserRank_ )
		{
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			global $LOGIN_ID;
			// **************************************************************************************
			
			PayLogic::SetType( $this->GetType() );
			PayLogic::MemoryOriginRec( $iRec_ );
			
			parent::deleteProc( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
		}
		
		/**
		 * �폜���������B
		 * �o�^�폜�������Ɏ��s����������������΃R�R�ɋL�q���܂��B
		 * �폜�������[���𑗐M�������ꍇ�Ȃǂɗ��p���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function deleteComp( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			PayLogic::SetType( $this->GetType() );

			if( PayLogic::IsActivate( $iRec_ ) )
				{ 
					PayLogic::SubPay( $iRec_ );
					PayLogic::AddPayLog( $iRec_, "delete" );
				}

			parent::deleteComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		/**
		 * ���������B
		 * �t�H�[�����͈ȊO�̕��@�Ō���������ݒ肵�����ꍇ�ɗ��p���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param table �t�H�[���̂���̓��͓��e�Ɉ�v���郌�R�[�h���i�[�����e�[�u���f�[�^�B
		 */
		function searchProc( &$iGM_ , &$ioTable_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $ACTIVE_ACTIVATE;
			global $LOGIN_ID;

			PayLogic::SetType( $this->GetType() );

			$ioTable_ = PayLogic::SearchDays( $ioTable_ , $_GET[ 'y' ] , $_GET[ 'm' ] , $_GET[ 'd' ] , $_GET[ 'y' ] , $_GET[ 'm' ] , $_GET[ 'd2' ] );
			$ioTable_ = PayLogic::SearchMine( $ioTable_ , $iLoginUserType_ , $LOGIN_ID );

			$db  = SystemUtil::getGMforType( $this->GetType() )->getCachedDB();
			$row = $db->getRow( $ioTable_ );

			if( $row )
			{
				if( $_POST[ 'id' ] && 0 <= $_POST[ 'cost' ] )
				{
					$rec = $db->selectRecord( $_POST[ 'id' ] );

					switch( $iLoginUserType_ )
					{
						case 'admin' :
						{
							$rec = $db->selectRecord( $_POST[ 'id' ] );

							if( $rec )
							{
								PayLogic::MemoryOriginRec( $rec );
								$nowState = $db->getData( $rec , 'state' );

								$db->setData( $rec , 'cost' , $_POST[ 'cost' ] );
								$db->setData( $rec , 'state' , $_POST[ 'state' ] );
								$db->updateRecord( $rec );

								PayLogic::UpdateReward( $rec );
								PayLogic::AddPayLog( $rec, "edit" );

								if( $ACTIVE_ACTIVATE != $nowState ) //�F�؈ȊO�̃X�e�[�^�X����̕ύX�̏ꍇ
									{ sendPayMail( $rec , $this->GetType() ); }
							}

							break;
						}

						case 'cUser' :
						{
							$rec = $db->selectRecord( $_POST[ 'id' ] );

							if( $rec )
							{
								PayLogic::MemoryOriginRec( $rec );
								$nowState = $db->getData( $rec , 'state' );

								$db->setData( $rec , 'state' , $_POST[ 'state' ] );
								$db->updateRecord( $rec );

								PayLogic::UpdateReward( $rec );
								PayLogic::AddPayLog( $rec, "edit" );

								if( $ACTIVE_ACTIVATE != $nowState ) //�F�؈ȊO�̃X�e�[�^�X����̕ύX�̏ꍇ
									{ sendPayMail( $rec , $this->GetType() ); }
							}

							break;
						}

						default :
							{ break; }
					}
				}

				$sum = $db->getSum( 'cost' , $ioTable_ );
				$iGM_[ $this->GetType() ]->setVariable( 'SUM' , $sum );
			}

			unset( $_POST[ 'id' ] );
			unset( $_POST[ 'cost' ] );
			unset( $_POST[ 'state' ] );
		}
	}
