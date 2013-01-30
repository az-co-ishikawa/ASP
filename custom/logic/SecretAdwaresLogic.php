<?php

	/**
		@brief   SecretAdwares�e�[�u���̏����Z�b�g�B
		@ingroup SystemAPI
	*/
	class SecretAdwaresLogic
	{
		//������

		/**
			@brief ���g�p���ڂɑ΂�����̓G���[�`�F�b�N�𖳌�������B
		*/
		static function KillCheck()
		{
			if( !$_POST[ 'money' ] ) //��V�z�����͂���Ă��Ȃ��ꍇ
			{
				switch( $_POST[ 'ad_type' ] ) //��V�^�C�v�ŕ���
				{
					case 'rank' :     //���[�U�[�����N
					case 'personal' : //�p�[�\�i�����[�g
					{
						$_POST[ 'money' ] = '0';

						break;
					}

					default : //���̑�
						{ break; }
				}
			}

			if( !$_POST[ 'limits' ] ) //�\�Z��������͂���Ă��Ȃ��ꍇ
			{
				if( !$_POST[ 'limit_type' ] ) //�\�Z������g�p���Ȃ��ꍇ
					{ $_POST[ 'limits' ] = '0'; }
			}
		}

		//���f�[�^�擾

		/**
			@brief     ���R�[�h�̏��L�������邩�m�F����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_      ���R�[�h�f�[�^�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@param[in] $iUserID_   ���[�U�[ID�B
			@retval    true  ���L��������ꍇ�B
			@retval    false ���L�����Ȃ��ꍇ�B
		*/
		static function IsMine( $iRec_ , $iUserType_ , $iUserID_ )
		{
			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( "���� $iRec_ �͖����ł�" ); }

			if( 'cUser' == $iUserType_ ) //�L����̏ꍇ
			{
				$db      = SystemUtil::getGMforType( self::$Type )->getCachedDB();
				$ownerID = $db->getData( $iRec_ , 'cuser' );

				if( $ownerID == $iUserID_ ) //�L���̏��L�҂̏ꍇ
					{ return true; }
			}

			return false;
		}

		/**
			@brief     ���R�[�h�̎Q�ƌ������邩�m�F����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_      ���R�[�h�f�[�^�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@param[in] $iUserID_   ���[�U�[ID�B
			@retval    true  �Q�ƌ�������ꍇ�B
			@retval    false �Q�ƌ����Ȃ��ꍇ�B
		*/
		static function IsOpen( $iRec_ , $iUserType_ , $iUserID_ )
		{
			if( 'nUser' == $iUserType_ )
			{
				$db      = SystemUtil::getGMforType( self::$Type )->getCachedDB();
				$usersID = $db->getData( $iRec_ , 'open_user' );

				if( FALSE !== strpos( $users , $iUserID_ ) )
					{ return true; }
			}

			return false;
		}

		/**
			@brief     ���[�U�[���ɏ��L���̂���e�[�u������������B
			@exception InvalidArgumentException $iTable_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iTable_ �e�[�u���f�[�^�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@param[in] $iUserID_   ���[�U�[ID�B
			@remarks   ���L���Ɗւ��Ȃ����[�U�[�̏ꍇ�͍i�荞�܂�܂���B
			@return    ������̃e�[�u���f�[�^�B
		*/
		static function SearchMine( $iTable_ , $iUserType_ , $iUserID_ )
		{
			if( !$iTable_ ) //�e�[�u������̏ꍇ
				{ throw new InvalidArgumentException( "���� $iTable_ �͖����ł�" ); }

			switch( $iUserType_ ) //���[�U�[��ʂŕ���
			{
				case 'cUser' : //�L����
				{
					$db    = SystemUtil::getGMforType( self::$Type )->getCachedDB();
					$table = $db->searchTable( $iTable_ , 'cuser' , '=' , $iUserID_ );

					return $table;
				}

				default : //���̑�
					{ return $iTable_; }
			}
		}

		/**
			@brief     ���[�U�[���ɎQ�ƌ��̂���e�[�u������������B
			@exception InvalidArgumentException $iTable_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iTable_    �e�[�u���f�[�^�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@param[in] $iUserID_   ���[�U�[ID�B
			@remarks   �Q�ƌ��Ɗւ��Ȃ����[�U�[�̏ꍇ�͍i�荞�܂�܂���B
			@return    ������̃e�[�u���f�[�^�B
		*/
		static function SearchOpen( $iTable_ , $iUserType_ , $iUserID_ )
		{
			if( !$iTable_ ) //�e�[�u������̏ꍇ
				{ throw new InvalidArgumentException( "���� $iTable_ �͖����ł�" ); }

			switch( $iUserType_ ) //���[�U�[��ʂŕ���
			{
				case 'nobody' : //��ʃ��[�U�[
				{
					$db    = SystemUtil::getGMforType( self::$Type )->getCachedDB();
					$table = $db->searchTable( $iTable_ , 'open' , '=' , TRUE );

					return $table;
				}

				case 'nUser' :  //�A�t�B���G�C�^�[
				{
					$db    = SystemUtil::getGMforType( self::$Type )->getCachedDB();
					$table = $db->searchTable( $iTable_ , 'open' , '=' , TRUE );
					$table = $db->searchTable( $table , 'open_user' , '=' , '%' . $iUserID_ . '%' );

					return $table;
				}

				default : //���̑�
					{ return $iTable_; }
			}
		}

		//���ϐ�
		private static $Type = 'secretAdwares'; ///<�e�[�u�����B
	}
