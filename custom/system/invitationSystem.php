<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class invitationSystem extends System
	{
		//������

		/**
		 * �o�^�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function registProc( &$iGM_ , &$ioRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ = false )
		{
			global $LOGIN_ID;

			$db = SystemUtil::getGMforType( self::$Type )->getDB();

			$db->setData( $ioRec_ , 'owner' , $LOGIN_ID );

			parent::registProc( $iGM_ , $ioRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ );
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

			$db       = SystemUtil::getGMforType( self::$Type )->getDB();
			$mail     = $db->getData( $iRec_ , 'mail' );
			$template = Template::getLabelFile( 'INVITATION_MAIL' );

			Mail::send( $template , $MAILSEND_ADDRES , $mail , $iGM_[ self::$Type ] , $iRec_ , $MAILSEND_NAMES );
		}

		//���ϐ�
		private static $Type = 'invitation'; ///<�e�[�u���̖��O�B
	}
