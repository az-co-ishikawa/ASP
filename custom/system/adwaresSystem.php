<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class adwaresSystem extends System
	{
		//������

		/**
		 * �o�^���e�m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param edit �ҏW�Ȃ̂��A�V�K�ǉ��Ȃ̂���^�U�l�œn���B
		 * @return �G���[�����邩��^�U�l�œn���B
		 */
		function registCheck( &$iGM_, $iEdit_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			AdwaresLogic::KillCheck();

			return parent::registCheck( $iGM_, $iEdit_ , $iLoginUserType_ , $iLoginUserRank_ );
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

			$ioTable_ = AdwaresLogic::SearchMine( $ioTable_ , $iLoginUserType_ , $LOGIN_ID );
			$ioTable_ = AdwaresLogic::SearchOpen( $ioTable_ , $iLoginUserType_ , $LOGIN_ID );

			return parent::searchProc( $iGM_ , $ioTable_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		/**
		 * �ҏW�t�H�[����`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawEditForm( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $LOGIN_ID;

			if( AdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
				{ parent::drawEditForm( $iGM_ , $iRec_ , 'owner' , $iLoginUserRank_ ); }
			else
				{ parent::drawEditForm( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ ); }
		}

		/**
		 * �ҏW���e�m�F�y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawEditCheck( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $LOGIN_ID;

			if( AdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
				{ parent::drawEditCheck( $iGM_ , $iRec_ , 'owner' , $iLoginUserRank_ ); }
			else
				{ parent::drawEditCheck( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ ); }
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
			global $LOGIN_ID;

			if( AdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
				{ parent::drawEditComp( $iGM_ , $iRec_ , 'owner' , $iLoginUserRank_ ); }
			else
				{ parent::drawEditComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ ); }
		}

		/**
		 * �폜�m�F�y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawDeleteCheck( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $LOGIN_ID;

			if( AdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
				{ parent::drawDeleteCheck( $iGM_ , $iRec_ , 'owner' , $iLoginUserRank_ ); }
			else
				{ parent::drawDeleteCheck( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ ); }
		}

		/**
		 * �폜�����y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawDeleteComp( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $LOGIN_ID;

			if( AdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
				{ parent::drawDeleteComp( $iGM_ , $iRec_ , 'owner' , $iLoginUserRank_ ); }
			else
				{ parent::drawDeleteComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ ); }
		}

		/**
		 * �ڍ׏��y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �ҏW�Ώۂ̃��R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawInfo( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $HOME;
			global $LOGIN_ID;

			$iGM_[ self::$Type ]->setVariable( 'host' , $HOME );
			$iGM_[ self::$Type ]->setVariable( 'loginID' , $LOGIN_ID );

			parent::drawInfo( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ );
		}

		//���ϐ� //
		private static $Type = 'adwares'; ///<�e�[�u���̖��O�B
	}
