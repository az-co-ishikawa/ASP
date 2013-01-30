<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class multimailSystem extends System
	{
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
			if( !$_POST[ 'receive_id' ] )
			{
				$nDB     = SystemUtil::getGMforType( 'nUser' )->getDB();
				$nTable  = $nDB->getTable();
				$nRow    = $nDB->getRow( $nTable );
				$userIDs = Array();

				for( $i = 0 ; $i < $nRow ; $i++ )
				{
					$nRec      = $nDB->getRecord( $nTable , $i );
					$userIDs[] = $nDB->getData( $nRec , 'id' );
				}

				$nDB->setData( $iRec_ , 'receive_id' , implode( '/' , $userIDs ) );
			}

			parent::registProc( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ );
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
			global $MAILSEND_ADDRES;
			global $MAILSEND_NAMES;

			Mail::sendString( addslashes( $_POST[ 'sub' ] ) , addslashes( $_POST[ 'main' ] ) , $MAILSEND_ADDRES , $MAILSEND_ADDRES , $MAILSEND_NAMES );

			parent::registComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
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
			$nDB    = SystemUtil::getGMforType( 'nUser' )->getDB();
			$nTable = $nDB->getTable();
			$nRow   = $nDB->getRow( $nTable );

			if( !$nRow )
				{ $iLoginUserType_ = 'notFound'; }

			parent::drawRegistForm( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		//���ϐ� //
		private static $Type = 'multimail'; ///<�e�[�u���̖��O�B
	}
