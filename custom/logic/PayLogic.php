<?php

	/**
		@brief   Pay�e�[�u���̏����Z�b�g�B
		@ingroup SystemAPI
	*/
	class PayLogic
	{
		//������

		/**
			@brief     ���[�U�[�̕�V��ǉ�����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_    ���ʏ��̃��R�[�h�f�[�^�B
			@param[in] $iReward_ ��V�z�B�ȗ����� $iRec_ �̕�V�z���g�p�B
		*/
		static function AddPay( $iRec_ , $iReward_ = null )
		{
			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iRec_ �͖����ł�' ); }

			$db = SystemUtil::getGMforType( self::$Type )->getCachedDB();

			$ownerID = $db->getData( $iRec_ , 'owner' );
			$reward  = $db->getData( $iRec_ , 'cost' );

			if( !is_null( $iReward_ ) ) //��V����̏ꍇ
				{ $reward = $iReward_; }

			addPay( $ownerID , $reward , $db , $iRec_ , $tier );

			return $tier;
		}

		/**
			@brief     ���[�U�[�̕�V����菜������B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_    ���ʏ��̃��R�[�h�f�[�^�B
			@param[in] $iReward_ ��V�z�B�ȗ����� $iRec_ �̕�V�z���g�p�B
		*/
		static function SubPay( $iRec_ , $iReward_ = null )
		{
			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iRec_ �͖����ł�' ); }

			$db = SystemUtil::getGMforType( self::$Type )->getCachedDB();

			$ownerID = $db->getData( $iRec_ , 'owner' );
			$reward  = $db->getData( $iRec_ , 'cost' );

			if( !is_null( $iReward_ ) ) //��V����̏ꍇ
				{ $reward = $iReward_; }

			subPay( $ownerID , $reward , $db , $iRec_ , $tier );

			return $tier;
		}

		
		/**
			@brief     ���[�U�[�̕�V�̕ω��Ɋւ��郍�O���L�^����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_      ���R�[�h�f�[�^�B
			@param[in] $action_      �ҏW��ʁB
			@param[in] $iNewCost_  �V�������ʊz�B
			@param[in] $iNewState_ �V�����F�؏�ԁB
		*/
		static function AddPayLog( $iRec_, $action_ )
		{
			global $LOGIN_ID;
			
			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iRec_ �͖����ł�' ); }
			
			$cost = self::GetNeedUpdateUser( $iRec_ );
			
			if( $cost != 0 ){
				$payDB     = GMList::getDB( self::$Type );
				$logDB     = GMList::getDB( 'log_pay' );
				
				$logRec = $logDB->getNewRecord();
				
				$logDB->setData( $logRec, 'id', $logDB->getTimeID());
				$logDB->setData( $logRec, 'pay_type', self::$Type );
				$logDB->setData( $logRec, 'pay_id', $payDB->getData( $iRec_,'id') );
				$logDB->setData( $logRec, 'nuser_id', $payDB->getData( $iRec_,'owner') );
				$logDB->setData( $logRec, 'operator', $LOGIN_ID );
				$logDB->setData( $logRec, 'cost', $cost );
				$logDB->setData( $logRec, 'action', $action_ );
				$logDB->setData( $logRec, 'state', $payDB->getData( $iRec_,'state') );
				$logDB->setData( $logRec, 'regist', time() );
				
				$logDB->addRecord( $logRec );
			}
		}
	
		/**
			@brief     ���[�U�[�̕�V��ǉ�����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@exception LogicException           �I���W�i���f�[�^���L�^����Ă��Ȃ��ꍇ�B
			@param[in] $iRec_    ���ʏ��̃��R�[�h�f�[�^�B
		*/
		static function UpdateReward( $iRec_ )
		{
			global $ACTIVE_ACTIVATE;
			global $ACTIVE_NONE;

			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iRec_ �͖����ł�' ); }

			if( !self::$OriginRec ) //�I���W�i���f�[�^�̋L�^���Ȃ��ꍇ
				{ throw new LogicException( "�Ăяo��������������܂���" ); }

			$db       = SystemUtil::getGMforType( self::$Type )->getDB();
			$nowState = $db->getData( $iRec_ , 'state' );
			$oldState = $db->getData( self::$OriginRec , 'state' );

			if( $oldState != $nowState ) //�X�e�[�^�X���ύX����Ă���ꍇ
			{
				switch( $nowState ) //���݂̃X�e�[�^�X�ŕ���
				{
					case $ACTIVE_ACTIVATE: //�F��
					{
						PayLogic::AddPay( $iRec_ );

						break;
					}

					case $ACTIVE_NONE: //���F��
					{
						$oldCost = $db->getData( self::$OriginRec , 'cost' );

						PayLogic::SubPay( $iRec_ , $oldCost );

						break;
					}
				}
			}
			else //�X�e�[�^�X���ύX����Ă��Ȃ��ꍇ
			{
				if( $ACTIVE_ACTIVATE == $oldState ) //�X�e�[�^�X���F�؂̏ꍇ
				{
					$nowCost = $db->getData( $iRec_ , 'cost' );
					$oldCost = $db->getData( self::$OriginRec , 'cost' );

					if( $oldCost < $nowCost ) //��V�𑝂₵���ꍇ
						{ PayLogic::AddPay( $iRec_ , $nowCost - $oldCost ); }
					else if( $oldCost > $_POST[ 'cost' ] ) //��V�����炵���ꍇ
						{ PayLogic::SubPay( $iRec_ , $oldCost - $nowCost ); }
				}
			}
		}

		/**
			@brief     ���[�U�[�̕�V�ɉe������ύX���𒲂ׁA�e��������ꍇ�͂��̋��z��Ԃ��B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@exception LogicException           �I���W�i���f�[�^���L�^����Ă��Ȃ��ꍇ�B
			@param[in] $iRec_    ���ʏ��̃��R�[�h�f�[�^�B
		*/
		static function GetNeedUpdateUser( $iRec_ )
		{
			global $ACTIVE_ACTIVATE;
			global $ACTIVE_NONE;

			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iRec_ �͖����ł�' ); }

			if( !self::$OriginRec ) //�I���W�i���f�[�^�̋L�^���Ȃ��ꍇ
				{ throw new LogicException( "�Ăяo��������������܂���" ); }

			$db       = SystemUtil::getGMforType( self::$Type )->getDB();
			
			$nowDelete = $db->getData( $iRec_ , 'delete_key' );
			$oldDelete = $db->getData( self::$OriginRec , 'delete_key' );
			
			if( $nowDelete != $oldDelete )
			{
				//�폜����Ă���B
				$nowCost = $db->getData( $iRec_ , 'cost' );
				return $nowCost*-1;
			}
			
			$nowState = $db->getData( $iRec_ , 'state' );
			$oldState = $db->getData( self::$OriginRec , 'state' );

			if( $oldState != $nowState ) //�X�e�[�^�X���ύX����Ă���ꍇ
			{
				switch( $nowState ) //���݂̃X�e�[�^�X�ŕ���
				{
					case $ACTIVE_ACTIVATE: //�F��
					{
						$nowCost = $db->getData( $iRec_ , 'cost' );
						return $nowCost;
					}

					case $ACTIVE_NONE: //���F��
					{
						$oldCost = $db->getData( self::$OriginRec , 'cost' );
						return $oldCost*-1;
					}
				}
			}
			else //�X�e�[�^�X���ύX����Ă��Ȃ��ꍇ
			{
				if( $ACTIVE_ACTIVATE == $oldState ) //�X�e�[�^�X���F�؂̏ꍇ
				{
					$nowCost = $db->getData( $iRec_ , 'cost' );
					$oldCost = $db->getData( self::$OriginRec , 'cost' );
					if( $oldCost != $nowCost ) //��V�ɕω����������ꍇ�B
						return $nowCost - $oldCost;
				}
			}
			return 0;
		}
		

		//���f�[�^�ύX

		/**
			@brief     �I���W�i���̃��R�[�h�f�[�^���L�^����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_    ���ʏ��̃��R�[�h�f�[�^�B
		*/
		static function MemoryOriginRec( $iRec_ )
		{
			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iRec_ �͖����ł�' ); }

			$db              = SystemUtil::getGMforType( self::$Type )->getDB();
			$id              = $db->getData( $iRec_ , 'id' );
			self::$OriginRec = $db->selectRecord( $id );
		}

		/**
			@brief     �e�[�u�������Z�b�g����B
			@param[in] $iType_ �e�[�u�����B
		*/
		static function SetType( $iType_ )
			{ self::$Type = $iType_; }

		//���f�[�^�擾

		/**
			@brief     ���R�[�h�̕ҏW�𐳏�ɍs���邩�m�F����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_      ���R�[�h�f�[�^�B
			@param[in] $iNewCost_  �V�������ʊz�B
			@param[in] $iNewState_ �V�����F�؏�ԁB
			@retval    true  ����ɕҏW�ł���ꍇ�B
			@retval    false ����ɕҏW�ł��Ȃ��ꍇ�B
		*/
		static function Editable( $iRec_ , $iNewCost_ , $iNewState_ )
		{
			global $ACTIVE_NONE;
			global $ACTIVE_ACTIVATE;

			$db     = GMList::getDB( self::$Type );
			$userID = $db->getData( $iRec_ , 'owner' );
			$cost   = $db->getData( $iRec_ , 'cost' );
			$state  = $db->getData( $iRec_ , 'state' );

			if( 'admin' == $loginUserType )
			{
				if( $cost > $iNewCost_ ) //���ʊz����������ꍇ
					{ $diff = $cost - $iNewCost_; }
			}

			if( $ACTIVE_ACTIVATE == $state && $ACTIVE_NONE == $iNewState_ ) //���F�؂ɂȂ�ꍇ
				{ $diff = $cost; }

			$nDB  = GMList::getDB( 'nUser' );
			$nRec = $nDB->selectRecord( $userID );
			$pay  = $nDB->getData( $nRec , 'pay' );

			if( 0 > ( $pay - $diff ) ) //�ύX��̐��ʂ��}�C�i�X�ɂȂ�ꍇ
				{ return false; }

			$nTable = $nDB->getTable();
			$pay    = $nDB->getSum( 'pay' , $nTable );

			if( 0 > ( $pay - $diff ) ) //�ύX��̐��ʂ��}�C�i�X�ɂȂ�ꍇ
				{ return false; }

			return true;
		}

		
		/**
			@brief     ���R�[�h���F�؂���Ă��邩�m�F����B
			@exception InvalidArgumentException $iRec_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iRec_      ���R�[�h�f�[�^�B
			@retval    true  �F�؂���Ă���ꍇ�B
			@retval    false �F�؂���Ă��Ȃ��ꍇ�B
		*/
		static function IsActivate( $iRec_ )
		{
			global $ACTIVE_ACTIVATE;

			if( !$iRec_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iRec_ �͖����ł�' ); }

			$db    = SystemUtil::getGMforType( self::$Type )->getCachedDB();
			$state = $db->getData( $iRec_ , 'state' );

			if( $ACTIVE_ACTIVATE == $state )
				{ return true; }
			else
				{ return false; }
		}

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
			if( !$iTable_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iTable_ �͖����ł�' ); }

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
			else //�ǂ��炩���w�肳��Ă��Ȃ��ꍇ
			{
				if( $_GET[ 'registA' ] ) //�^�C���X�^���v�`���Ŏw�肳��Ă���ꍇ
				{
					$date = date( 'Y/n/j' , $_GET[ 'registA' ] );

					List( $_GET[ 'y' ] , $_GET[ 'm' ] , $_GET[ 'd' ] ) = explode( $date );

					unset( $_GET[ 'registA' ] );
				}

				if( $_GET[ 'registB' ] && 'top' != $_GET[ 'registB' ] ) //�^�C���X�^���v�`���Ŏw�肳��Ă���ꍇ
				{
					$_GET[ 'd2' ] = date( 'j' , $_GET[ 'registB' ] );

					unset( $_GET[ 'registB' ] );
				}

				return $iTable_;
			}
		}

		/**
			@brief     ���[�U�[���ɏ��L���̂���e�[�u������������B
			@exception InvalidArgumentException $iTable_ �ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iTable_    �e�[�u���f�[�^�B
			@param[in] $iUserType_ ���[�U�[��ʁB
			@param[in] $iUserID_   ���[�U�[ID�B
			@remarks   ���L���Ɗւ��Ȃ����[�U�[�̏ꍇ�͍i�荞�܂�܂���B
			@return    ������̃e�[�u���f�[�^�B
		*/
		static function SearchMine( $iTable_ , $iUserType_ , $iUserID_ )
		{
			if( !$iTable_ ) //���R�[�h����̏ꍇ
				{ throw new InvalidArgumentException( '���� $iTable_ �͖����ł�' ); }

			switch( $iUserType_ )
			{
				case 'nUser' :
				{
					$db = GMList::getDB( self::$Type );

					return $db->searchTable( $iTable_ , 'owner' , '=' , $iUserID_ );
				}

				case 'cUser' :
				{
					$db = GMList::getDB( self::$Type );

					return $db->searchTable( $iTable_ , 'cuser' , '=' , $iUserID_ );
				}

				default :
					{ return $iTable_; }
			}
		}

		//���ϐ�
		private static $Type      = 'pay'; ///<�e�[�u�����B
		private static $OriginRec = null;  ///<���̃��R�[�h�f�[�^�B�B
	}
