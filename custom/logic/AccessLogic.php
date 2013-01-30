<?php

	/**
		@brief   Access�e�[�u���̏����Z�b�g�B
		@ingroup SystemAPI
	*/
	class AccessLogic
	{
		//���f�[�^�擾

		/**
			@brief     ���t����e�[�u������������B
			@exception InvalidArgumentException $iTable_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iTable_  ��������e�[�u���f�[�^�B
			@param[in] $iBeginY_ �J�n�N�B
			@param[in] $iBeginM_ �J�n���B
			@param[in] $iBeginD_ �J�n���B
			@param[in] $iEndY_   �I���N�B
			@param[in] $iEndM_   �I�����B
			@param[in] $iEndD_   �I�����B
			@return    ������̃e�[�u���f�[�^�B
		*/
		static function SearchDays( $iTable_ , $iBeginY_ , $iBeginM_ , $iBeginD_ , $iEndY_ , $iEndM_ , $iEndD_ )
		{
			if( !$iTable_ ) //�e�[�u������̏ꍇ
				{ throw new InvalidArgumentException( "���� $iTable_ �͖����ł�" ); }

			if( $iBeginY_ && $iBeginM_ ) //�J�n�N�����w�肳��Ă���ꍇ
			{
				if( $iBeginD_ && $iEndD_ ) //�J�n���ƏI���ɒl���w�肳��Ă���ꍇ
				{
					$beginTime = mktime( 0 , 0 , 0 , $iBeginM_ , $iBeginD_ , $iBeginY_ );
					$endTime   = mktime( 0 , 0 , 0 , $iEndM_ , $iEndD_ + 1 , $iEndY_ );
				}
				else //�����w�肳��Ă��Ȃ��ꍇ
				{
					$beginTime = mktime( 0 , 0 , 0 , $iBeginM_ , 1 , $iBeginY_ );
					$endTime   = mktime( 0 , 0 , 0 , $iBeginM_ + 1 , 1 , $iBeginY_ );
				}

				$db    = SystemUtil::getGMforType( self::$Type )->getDB();
				$table = $db->searchTable( $iTable_ , 'regist' , 'b' , $beginTime , $endTime );

				return $table;
			}

			return $iTable_;
		}

		/**
			@brief     ���[�U�[���ɏ��L���̂���e�[�u������������B
			@exception InvalidArgumentException $iTable_ , $iUserType_ �̂����ꂩ�ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iTable_    ��������e�[�u���f�[�^�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@param[in] $iUserID_   ���[�U�[ID�B
			@remarks   ���L���Ɗւ��Ȃ����[�U�[�̏ꍇ�͍i�荞�܂�܂���B
			@return    ������̃e�[�u���f�[�^�B
		*/
		static function SearchMine( $iTable_ , $iUserType_ , $iUserID_ )
		{
			if( !$iTable_ ) //�e�[�u������̏ꍇ
				{ throw new InvalidArgumentException( "���� $iTable_ �͖����ł�" ); }

			if( !$iUserType_ ) //���[�U�[��ʂ��k�̏ꍇ
				{ throw new InvalidArgumentException( "���� $iUserType_ �͖����ł�" ); }

			switch( $iUserType_ ) //���[�U�[��ʂŕ���
			{
				case 'nUser' : //�A�t�B���G�C�^�[
				{
					$db    = SystemUtil::getGMforType( self::$Type )->getDB();
					$table = $db->searchTable( $iTable_ , 'owner' , '=' , $iUserID_ );

					return $table;
				}

				case 'cUser' : //�L����
				{
					$db    = SystemUtil::getGMforType( self::$Type )->getDB();
					$table = $db->searchTable( $iTable_ , 'cuser' , '=' , $iUserID_ );

					return $table;
				}

				default : //���̑�
					{ return $iTable_; }
			}
		}

		//���ϐ�
		private static $Type = 'access'; ///<�e�[�u�����B
	}
