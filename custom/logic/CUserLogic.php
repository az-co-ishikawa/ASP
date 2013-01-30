<?php

	/**
		@brief   CUser�e�[�u���̏����Z�b�g�B
		@ingroup SystemAPI
	*/
	class CUserLogic
	{
		//������

		/**
			@brief     ���[�U�[�ɃA�N�e�B�x�[�g���[���𑗐M����B
			@exception InvalidArgumentException $iUserRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@exception FileIOException          ���[���e���v���[�g��������Ȃ��ꍇ�B
			@param[in] $iUserRec_ ���[�U�[�̃��R�[�h�f�[�^�B
		*/
		static function SendActivateMail( $iUserRec_ )
		{
			if( !$iUserRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iUserRec_ �͖����ł�' ); }

			global $ACTIVE_NONE;
			global $ACTIVE_ACTIVATE;
			global $ACTIVE_ACCEPT;
			global $MAILSEND_ADDRES;
			global $MAILSEND_NAMES;
			global $template_path;
			global $mobile_path;

			$gm          = SystemUtil::getGMforType( self::$Type );
			$db          = $gm->getCachedDB();
			$activate    = $db->getData( $iUserRec_ , 'activate' );
			$isMobile    = $db->getData( $iUserRec_ , 'is_mobile' );
			$mailAddress = $db->getData( $iUserRec_ , 'mail' );

			switch( $activate ) //�A�N�e�B�x�[�g���x���ŕ���
			{
				case $ACTIVE_NONE : //���F��
				{
					$label = 'ACTIVATE_MAIL';

					break;
				}

				case $ACTIVE_ACTIVATE : //���F��
				{
					$label = 'ACTIVATE_COMP_MAIL';

					break;
				}

				case $ACTIVE_ACCEPT : //�F��
				{
					$label = 'REGIST_COMP_MAIL';

					break;
				}

				default : //���̑�
					{ return; }
			}

			if( $isMobile ) //�o�^���̒[�����g�т̏ꍇ
			{
				$currentPath   = $template_path;
				$template_path = $mobile_path;
				$template      = Template::getTemplate( '' , 1 , self::$Type , $label );
				$template_path = $currentPath;
			}
			else //���̑��̒[���̏ꍇ
				{ $template = Template::getTemplate( '' , 1 , self::$Type , $label ); }

			if( !$template ) //�e���v���[�g��������Ȃ��ꍇ
				{ throw new FileIOException( '�e���v���[�g�t�@�C����������܂���[' . self::$Type . '][' . $label . ']' ); }

			Mail::send( $template , $MAILSEND_ADDRES , $mailAddress , $gm , $iUserRec_ , $MAILSEND_NAMES );

			if( $isMobile ) //�o�^���̒[�����g�т̏ꍇ
			{
				$template = Template::getTemplate( '' , 1 , self::$Type , $label );

				if( !$template ) //�e���v���[�g��������Ȃ��ꍇ
					{ throw new FileIOException( '�e���v���[�g�t�@�C����������܂���[' . self::$Type . '][' . $label . ']' ); }
			}

			Mail::send( $template , $MAILSEND_ADDRES , $MAILSEND_ADDRES , $gm , $iUserRec_ , $MAILSEND_NAMES );
		}

		/**
			@brief         �ȈՃA�b�v�f�[�g�����s����B
			@details       �ڍ׃y�[�W�⌟�����ʈꗗ����̊ȈՃA�b�v�f�[�g���������܂��B
			@exception     InvalidArgumentException $ioRec_ �ɖ����Ȓl���w�肵���A�܂��� $iQuery_ �ɃX�J�����w�肵���ꍇ�B
			@param[in,out] $ioRec_  ���R�[�h�f�[�^�B
			@param[in]     $iQuery_ �K�p����N�G���p�����[�^�z��B
		*/
		static function QuickUpdate( &$ioRec_ , &$iQuery_ )
		{
			global $ACTIVE_ACCEPT;

			if( !$ioRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $ioRec_ �͖����ł�' ); }

			if( !is_array( $iQuery_ ) ) //�z��łȂ��ꍇ
				{ throw new InvalidArgumentException( '���� $iQuery_ �͖����ł�' ); }

			$db       = SystemUtil::getGMforType( self::$Type )->getCachedDB();
			$activate = $db->getData( $ioRec_ , 'activate' );
			$update   = false;

			foreach( $db->colName as $column ) //�S�ẴJ����������
			{
				if( isset( $iQuery_[ $column ] ) ) //�N�G�������݂���ꍇ
				{
					$db->setData( $ioRec_ , $column , $iQuery_[ $column ] );

					$update = true;
				}
			}

			if( $update ) //�ύX���ꂽ�J����������ꍇ
			{
				$db->updateRecord( $ioRec_ );

				if( $ACTIVE_ACCEPT == $iQuery_[ 'activate' ] && $ACTIVE_ACCEPT > $activate ) //�蓮�ŔF�؂ɂ����ꍇ
					{ self::SendActivateMail( $ioRec_ ); }

				//��������N�X�V�`�F�b�N
				$id = $db->getData( $ioRrec_ , 'id' );

				updateRank( $id );
			}
		}

		//���f�[�^�ύX

		/**
			@brief         ���R�[�h�̒[������������B
			@exception     InvalidArgumentException $ioRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in,out] $ioRec_ ���R�[�h�f�[�^�B
		*/
		static function SetClientTerminalType( &$ioRec_ )
		{
			global $terminal_type;

			if( !$ioRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $ioRec_ �͖����ł�' ); }

			$db = SystemUtil::getGMforType( self::$Type )->getCachedDB();

			if( 0 < $terminal_type ) //�g�ђ[���̏ꍇ
				{ $db->setData( $ioRec_ , 'is_mobile' , true ); }
			else //���̑��̒[���̏ꍇ
				{ $db->setData( $ioRec_ , 'is_mobile' , false ); }
		}

		/**
			@brief         ���R�[�h�̏����l��������B
			@exception     InvalidArgumentException $ioRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in,out] $ioRec_ ���R�[�h�f�[�^�B
		*/
		static function SetDefaultParameter( &$ioRec_ )
		{
			if( !$ioRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $ioRec_ �͖����ł�' ); }

			$db = SystemUtil::getGMforType( self::$Type )->getCachedDB();

			$db->setData( $ioRec_ , 'logout'   , time() );                             //���O�A�E�g����
			$db->setData( $ioRec_ , 'limits'   , 0 );                                  //���p����
			$db->setData( $ioRec_ , 'activate' , getDefaultActivate( self::$Type ) );  //�A�N�e�B�x�[�g���x��
		}

		//���ϐ�
		private static $Type = 'cUser'; ///<�e�[�u�����B
	}
