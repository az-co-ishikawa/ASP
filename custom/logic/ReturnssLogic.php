<?php

	/**
		@brief   Returnss�e�[�u���̏����Z�b�g�B
		@ingroup SystemAPI
	*/
	class ReturnssLogic
	{
		//������

		/**
			@brief     �A�t�B���G�C�^�[�̕�V����\���z�������B
			@exception InvalidArgumentException $iReturnssRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iReturnssRec_ �\�����̃��R�[�h�f�[�^�B
		*/
		static function CutNUserReward( $iReturnssRec_ )
		{
			if( !$iReturnssRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( "���� $iReturnssRec_ �͖����ł�" ); }

			$db      = SystemUtil::getGMforType( self::$Type )->getCachedDB();
			$ownerID = $db->getData( $iReturnssRec_ , 'owner' );
			$cost    = $db->getData( $iReturnssRec_ , 'cost' );

			$nDB  = SystemUtil::getGMforType( 'nUser' )->getCachedDB();
			$nRec = $nDB->selectRecord( $ownerID );

			$nDB->setCalc( $nRec , 'pay' , '-' , $cost );
			$nDB->updateRecord( $nRec );
		}

		/**
			@brief     �A�t�B���G�C�^�[�̕�V�ɐ\���z��߂��B
			@exception InvalidArgumentException $iReturnssRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iReturnssRec_ �\�����̃��R�[�h�f�[�^�B
		*/
		static function ReturnNUserReward( $iReturnssRec_ )
		{
			if( !$iReturnssRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( "���� $iReturnssRec_ �͖����ł�" ); }

			$db      = SystemUtil::getGMforType( self::$Type )->getDB();
			$ownerID = $db->getData( $iReturnssRec_ , 'owner' );
			$cost    = $db->getData( $iReturnssRec_ , 'cost' );

			$nDB  = SystemUtil::getGMforType( 'nUser' )->getDB();
			$nRec = $nDB->selectRecord( $ownerID );

			$nDB->setCalc( $nRec , 'pay' , '+' , $cost );
			$nDB->updateRecord( $nRec );
		}

		//���f�[�^�ύX

		/**
			@brief         ���R�[�h�̏����l��������B
			@exception     InvalidArgumentException $ioRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in,out] $ioRec_ ���R�[�h�f�[�^�B
		*/
		static function SetDefaultParameter( &$ioRec_ )
		{
			global $LOGIN_ID;

			if( !$ioRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( "���� $ioRec_ �͖����ł�" ); }

			$db = SystemUtil::getGMforType( self::$Type )->getCachedDB();

			$db->setData( $ioRec_ , 'id'     , md5( time() . $LOGIN_ID ) ); //���R�[�hID
			$db->setData( $ioRec_ , 'regist' , time() );                    //�o�^����
			$db->setData( $ioRec_ , 'owner'  , $LOGIN_ID  );                //�A�t�B���G�C�^�[ID
			$db->setData( $ioRec_ , 'state'  , '�Ǘ��Ҋm�F�҂�' );          //�F�؏��
		}

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
					$endTime   = mktime( 0 , 0 , 0 , $iEndM_ + 1 , 1 , $iEndY_ );
				}

				$db    = SystemUtil::getGMforType( self::$Type )->getDB();
				$table = $db->searchTable( $iTable_ , 'regist' , 'b' , $beginTime , $endTime );

				return $table;
			}

			return $iTable_;
		}

		/**
			@brief     ���[�U�[���ɏ��L���̂���e�[�u������������B
			@exception InvalidArgumentException $iTable_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iTable_    �e�[�u���f�[�^�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@param[in] $iUserID_   ���[�U�[ID�B
			@return    ������̃e�[�u���f�[�^�B
		*/
		static function SearchMine( $iTable_ , $iUserType_ , $iUserID_ )
		{
			if( !$iTable_ ) //�e�[�u������̏ꍇ
				{ throw new InvalidArgumentException( "���� $iTable_ �͖����ł�" ); }

			switch( $iUserType_ ) //���[�U�[��ʂŕ���
			{
				case 'nUser' : //�A�t�B���G�C�^�[
				{
					$db    = SystemUtil::getGMforType( self::$Type )->getDB();
					$table = $db->searchTable( $iTable_ , 'owner' , '=' , $iUserID_ );

					return $table;
				}

				default : //���̑�
					{ return $iTable_; }
			}
		}

		//���ϐ�
		private static $Type = 'returnss'; ///<�e�[�u�����B
	}
