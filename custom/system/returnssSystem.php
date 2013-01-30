<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class returnssSystem extends System
	{
		//������

		/**
		 * �o�^���e�m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param edit �ҏW�Ȃ̂��A�V�K�ǉ��Ȃ̂���^�U�l�œn���B
		 * @return �G���[�����邩��^�U�l�œn���B
		 */
		function registCheck( &$iGM_ , $iEdit_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $ADWARES_EXCHANGE;
			global $LOGIN_ID;

			parent::registCheck( $iGM_ , $iEdit_ , $iLoginUserType_ , $iLoginUserRank_ );

			if( self::$checkData->getCheck() )
			{
				if( $ADWARES_EXCHANGE > $_POST[ 'cost' ] )
					{ self::$checkData->addError( 'limit' ); }
				else
				{
					$nDB     = SystemUtil::getGMforType( 'nUser' )->getDB();
					$nRec    = $nDB->selectRecord( $LOGIN_ID );
					$nReward = $nDB->getData( $nRec , 'pay' );

					if( $nReward < $_POST[ 'cost' ] )
						{ self::$checkData->addError( 'outof' ); }
				}
			}

			return self::$checkData->getCheck();
		}

		/**
		 * �o�^�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function registProc( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ = false )
		{
			ReturnssLogic::SetDefaultParameter( $iRec_ );

			if( !$iCheck_ )
			{
				$db = SystemUtil::getGMforType( self::$Type )->getDB();

				$this->uplodeComp( $iGM_ , $db , $iRec_ );
			}
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
			ReturnssLogic::CutNUserReward( $iRec_ );

			parent::registComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
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
			global $LOGIN_ID;

			$ioTable_ = ReturnssLogic::SearchMine( $ioTable_ , $iLoginUserType_ , $LOGIN_ID );
			$ioTable_ = ReturnssLogic::SearchDays( $ioTable_ , $_GET[ 'y' ] , $_GET[ 'm' ] , null , $_GET[ 'y2' ] , $_GET[ 'm2' ] , null );

			if( $_POST[ 'id' ] )
			{
				$db  = SystemUtil::getGMforType( self::$Type )->getDB();
				$rec = $db->selectRecord( $_POST[ 'id' ] );

				if( $rec )
				{
					$db->setData( $rec , 'state' , $_POST[ 'state' ] );

					if( '�����߂�' == $_POST[ 'state' ] )
						{ ReturnssLogic::ReturnNUserReward( $rec ); }

					$db->updateRecord( $rec );
				}

				unset( $_POST[ 'state' ] );
			}

			$db        = SystemUtil::getGMforType( self::$Type )->getDB();
			$tempTable = $db->searchTable( $ioTable_ , 'state' , '!=' , '�����߂�' );
			$sum       = $db->getSum( 'cost' , $tempTable );

			$iGM_[ self::$Type ]->setVariable( 'SUM' , $sum );

			parent::searchProc( $iGM_ , $ioTable_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		/**
		 * �o�^�t�H�[����`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistForm( &$iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $ADWARES_EXCHANGE;
			global $LOGIN_ID;
			global $users_returnss;

			$this->setErrorMessage( $iGM_[ self::$Type ] );

			if( !$users_returnss )
			{
				Template::drawErrorTemplate();

				return;
			}

			$nDB  = SystemUtil::getGMforType( 'nUser' )->getDB();
			$nRec = $nDB->selectRecord( $LOGIN_ID );

			if( !$nRec )
			{
				Template::drawTemplate( $iGM_[ self::$Type ] , $nRec , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'ADWARES_EXCHANGE' );

				return;
			}

			$pay = $nDB->getData( $nRec , 'pay' );

			if( $ADWARES_EXCHANGE > $pay )
			{
				Template::drawTemplate( $iGM_[ self::$Type ] , $nRec , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'ADWARES_EXCHANGE' );

				return;
			}

			return parent::drawRegistForm( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		/**
		 * �ҏW�����y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawEditComp( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			print '
				<script language="javascript">
				<!--
						window.location.replace(\'search.php?type=returnss&run=true\');
				// #-->
				</script>
				<a href="index.php">�N���b�N���Ă�������</a><br>
			';
		}

		//���ϐ� //
		private static $Type = 'returnss'; ///<�e�[�u���̖��O�B
	}
