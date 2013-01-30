<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class secretAdwaresSystem extends System
	{
		//������

		/**
		 * �o�^�O�i�K�����B
		 * �t�H�[�����͈ȊO�̕��@�Ńf�[�^��o�^����ꍇ�́A�����Ń��R�[�h�ɒl�������܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �t�H�[���̂���̓��̓f�[�^�𔽉f�������R�[�h�f�[�^�B
		 */
		function registProc( &$iGM_ , &$iRec_ , $iLoginUserType_ , $iLoginUserRank_ ,$iCheck_ = false )
		{
			global $LOGIN_ID;

			switch( $iLoginUserType_ ) //���[�U�[��ʂŕ���
			{
				case 'cUer' : //�L����
				{
					$db = SystemUtil::getGMforType( self::$Type );

					$db->setData( $ioRec_ , 'cuser' , $LOGIN_ID );

					break;
				}

				default :
					{ break; }
			}

			return parent::registProc( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ ,$iCheck_ );
		}

		/**
		 * �o�^���e�m�F�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param edit �ҏW�Ȃ̂��A�V�K�ǉ��Ȃ̂���^�U�l�œn���B
		 * @return �G���[�����邩��^�U�l�œn���B
		 */
		function registCheck( &$iGM_, $iEdit_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			SecretAdwaresLogic::KillCheck();

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

			$ioTable_ = SecretAdwaresLogic::SearchMine( $ioTable_ , $iLoginUserType_ , $LOGIN_ID );
			$ioTable_ = SecretAdwaresLogic::SearchOpen( $ioTable_ , $iLoginUserType_ , $LOGIN_ID );

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

			if( SecretAdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
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

			if( SecretAdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
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

			if( SecretAdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
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

			if( SecretAdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
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

			if( SecretAdwaresLogic::IsMine( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
				{ parent::drawDeleteComp( $iGM_ , $iRec_ , 'owner' , $iLoginUserRank_ ); }
			else
				{ parent::drawDeleteComp( $iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ ); }
		}

		/**
		 * �������ʂ�`��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawSearch( &$iGM_ , &$iSR_ , $iTable_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			SearchTableStack::pushStack( $iTable_ );

			if( $_GET[ 'exstyle' ] )
				{ $label = 'SEARCH_RESULT_DESIGN_' . strtoupper( $_GET[ 'exstyle' ] ); }
			else
				{ $label = 'SEARCH_RESULT_DESIGN'; }

			Template::drawTemplate( $iGM_[ self::$Type ] , null , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , $label );
		}

		/**
		 * �������ʁA�Y���Ȃ���`��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawSearchNotFound( &$iGM_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			if( $_GET[ 'exstyle' ] )
				{ $label = 'SEARCH_NOT_FOUND_DESIGN_' . strtoupper( $_GET[ 'exstyle' ] ); }
			else
				{ $label = 'SEARCH_NOT_FOUND_DESIGN'; }

			Template::drawTemplate( $iGM_[ self::$Type ] , null , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , $label );
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
			global $LOGIN_ID;
			global $HOME;

			global $LOGIN_ID;

			if( !SecretAdwaresLogic::IsOpen( $iRec_ , $iLoginUserType_ , $LOGIN_ID ) )
				{ $loginUserType = 'nobody'; }

			$iGM_[ self::$Type ]->setVariable( 'host' , $HOME );
			$iGM_[ self::$Type ]->setVariable( 'loginID' , $LOGIN_ID );

			Template::drawTemplate( $iGM_[ self::$Type ] , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'INFO_PAGE_DESIGN' , 'info.php?type=' . self::$Type . '&id=' . $_GET[ 'id' ] );
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
		function getSearchResult( &$iGM_, $iTable_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			global $gm;

			$type = SearchTableStack::getType();

			if( $_GET[ 'exstyle' ] )
				{ $label = 'SEARCH_LIST_PAGE_DESIGN_' . strtoupper( $_GET[ 'exstyle' ] ); }
			else
				{ $label = 'SEARCH_LIST_PAGE_DESIGN'; }

			$partsName = SearchTableStack::getPartsName( 'list' );

			if( $partsName )
				{ return Template::getListTemplateString( $gm[ $type ] , $iTable_ , $iLoginUserType_ , $iLoginUserRank_ , $type , $label , false , $partsName ); }
			else
				{ return Template::getListTemplateString( $gm[ $type ] , $iTable_ , $iLoginUserType_ , $iLoginUserRank_ , $type , $label ); }
		}

		//���ϐ� //
		private static $Type = 'secretAdwares'; ///<�e�[�u���̖��O�B
	}
