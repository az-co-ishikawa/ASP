<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class log_paySystem extends System
	{

		//������

		/**
		 * ���������B
		 * �t�H�[�����͈ȊO�̕��@�Ō���������ݒ肵�����ꍇ�ɗ��p���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param table �t�H�[���̂���̓��͓��e�Ɉ�v���郌�R�[�h���i�[�����e�[�u���f�[�^�B
		 */
		function searchProc( &$iGM_ , &$ioTable_ , $iLoginUserType_ , $iLoginUserRank_ )
		{

			PayLogic::SetType( self::$Type );

			$ioTable_ = PayLogic::SearchDays( $ioTable_ , $_GET[ 'y' ] , $_GET[ 'm' ] , $_GET[ 'd' ] , $_GET[ 'y' ] , $_GET[ 'm' ] , $_GET[ 'd2' ] );
			$ioTable_ = PayLogic::SearchMine( $ioTable_ , $iLoginUserType_ , $LOGIN_ID );

			$db  = SystemUtil::getGMforType( self::$Type )->getCachedDB();
			$row = $db->getRow( $ioTable_ );

			unset( $_POST[ 'id' ] );
			unset( $_POST[ 'cost' ] );
			unset( $_POST[ 'state' ] );
		}

		//���ϐ� //
		private static $Type = 'log_pay'; ///<�e�[�u���̖��O�B
	}
