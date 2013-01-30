<?php

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class categorySystem extends System
	{
		//������

		/**
		 * �o�^�t�H�[����`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistForm( &$iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			$this->setErrorMessage( $iGM_[ self::$Type ] );

			if( 'true' == $_GET[ 'hfnull' ] )
				{ Template::drawTemplate( $iGM_[ self::$Type ] , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'REGIST_FORM_PAGE_DESIGN_POPUP' , 'regist.php?hfnull=true&type=' . self::$Type , null , null , 'v' ); }
			else
				{ Template::drawTemplate( $iGM_[ self::$Type ] , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'REGIST_FORM_PAGE_DESIGN' , 'regist.php?type=' . self::$Type ); }
		}

		/**
		 * �o�^���e�m�F�y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �o�^�����i�[�������R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistCheck( &$iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			if( 'true' == $_GET[ 'hfnull' ] )
				{ Template::drawTemplate( $iGM_[ self::$Type ] , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'REGIST_CHECK_PAGE_DESIGN_POPUP' , 'regist.php?hfnull=true&type=' . self::$Type , null , null , 'v' ); }
			else
				{ Template::drawTemplate( $iGM_[ self::$Type ] , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'REGIST_CHECK_PAGE_DESIGN' , 'regist.php?type=' . self::$Type ); }
		}

		/**
		 * �o�^�����y�[�W��`�悷��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�z��@�A�z�z��� gm[ TABLE�� ] �ŃA�N�Z�X���\�ł��B
		 * @param rec �o�^�����i�[�������R�[�h�f�[�^
		 * @param loginUserType ���O�C�����Ă��郆�[�U�̎��
		 * @param loginUserRank ���O�C�����Ă��郆�[�U�̌���
		 */
		function drawRegistComp( &$iGM_ , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ )
		{
			if( 'true' == $_GET[ 'hfnull' ] )
				{ Template::drawTemplate( $iGM_[ self::$Type ] , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'REGIST_COMP_PAGE_DESIGN_POPUP' ); }
			else
				{ Template::drawTemplate( $iGM_[ self::$Type ] , $iRec_ , $iLoginUserType_ , $iLoginUserRank_ , self::$Type , 'REGIST_COMP_PAGE_DESIGN' ); }
		}

		//���ϐ�
		private static $Type = 'category'; ///<�e�[�u���̖��O�B
	}
