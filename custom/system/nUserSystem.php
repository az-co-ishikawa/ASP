<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class nUserSystem extends System
	{
		//������

		/**
		 * �o�^�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function registProc( &$iGM_ , &$ioRec_ , $iLoginUserType_ , $iLoginUserRank_ ,$iCheck_ = false )
		{
			nUserLogic::SetDefaultParameter( $ioRec_ );
			nUserLogic::SetParentID( $ioRec_ , $_SESSION[ 'friend' ] );
			nUserLogic::SetClientTerminalType( $ioRec_ );

			parent::registProc( $iGM_ , $ioRec_ , $iLoginUserType_ , $iLoginUserRank_ , $iCheck_ );
		}

		/**
		 * �o�^�������������B
		 * �o�^�������Ƀ��[���œ��e��ʒm�������ꍇ�Ȃǂɗp���܂��B
		 * 
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec ���R�[�h�f�[�^�B
		 */
		function registComp( &$iGM_ , &$ioRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			if( $_SESSION[ 'friend' ] ) //�eID���w�肳��Ă���ꍇ
				{ $_SESSION[ 'friend' ] = ''; }

			nUserLogic::SendActivateMail( $ioRec_ );

			parent::registComp( $iGM_ , $ioRec_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		/**
		 * �ڍ׏�񂪉{�����ꂽ�Ƃ��ɕ\�����ėǂ���񂩂�Ԃ����\�b�h�B
		 * activate�J��������J�ۃt���O�Aregist��update���ɂ��\�����Ԃ̐ݒ�A�A�N�Z�X�����ɂ��t�B���^�Ȃǂ��s���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �A�N�Z�X���ꂽ���R�[�h�f�[�^�B
		 * @return �\�����ėǂ����ǂ�����^�U�l�œn���B
		 */
		function infoCheck( &$iGM_ , &$ioRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $ACTIVE_NONE;

			if( 'admin' != $iLoginUserType_ ) //�Ǘ��҈ȊO�̏ꍇ
			{
				$db       = SystemUtil::getGMforType( self::$Type )->getCachedDB();
				$activate = $db->getData( $ioRec_ , 'activate' );

				if( $ACTIVE_NONE >= $activate ) //���A�N�e�B�x�[�g�̏ꍇ
					{ return false; }
			}

			return true;
		}

		/**
		 * �ڍ׏��O�����B
		 * �ȈՏ��ύX�ŗ��p
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �A�N�Z�X���ꂽ���R�[�h�f�[�^�B
		 */
		function infoProc( &$iGM_, &$ioRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			if( isset( $_POST[ 'post' ] ) ) //POST�N�G�������݂���ꍇ
			{
				if( 'admin' == $iLoginUserType_ ) //�Ǘ��҂̏ꍇ
					{ NUserLogic::QuickUpdate( $ioRec_ , $_POST ); }
			}
		}

		/**
		 * �A�N�e�B�x�[�g�����B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �A�N�Z�X���ꂽ���R�[�h�f�[�^�B
		 */
		function activateAction( &$iGM_ , &$ioRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $ACTIVE_NONE;

			$db       = SystemUtil::getGMforType( 'nUser' )->getDB();
			$activate = $db->getData( $ioRec_ , 'activate' );

			if( $ACTIVE_NONE == $activate )
			{
				$db->setData( $ioRec_ , 'activate' , SystemUtil::getSystemData( 'nuser_accept_admin' ) );
				$db->updateRecord( $ioRec_ );

				NUserLogic::SendActivateMail( $ioRec_ );

				return true;
			}

			return false;
		}

		//���ϐ� //
		private static $Type = 'nUser'; ///<�e�[�u���̖��O�B
	}
