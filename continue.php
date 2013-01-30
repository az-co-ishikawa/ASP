<?php

	/*******************************************************************************************************
	 * <PRE>
	 *
	 * continue.php - ��p�v���O����
	 * �p����V�𔭐�������v���O�����B
	 * 
	 * �ȉ��̌`���ŃA�N�Z�X���ꂽ�ꍇ�A�p����V��ǉ����܂��B
	 * continue.php?adwares=[foo]&uid=[var]
	 * foo = �L��ID
	 * var = �p����VID
	 * �����̑��̃p�����[�^��readme���Ŋm�F���Ă��������B
	 * 
	 * </PRE>
	 *******************************************************************************************************/

	/*******************************************************************************************************
	 * ���C������
	 *******************************************************************************************************/

	try
	{
		include 'custom/head_main.php';

		CheckQuery();

		$pays = new TableModel( 'pay' );
		$pays = SearchContinuePay( $pays );
		$row  = $pays->getRow();

		for( $i = 0 ; $i < $row ; $i++ )
		{
			$pay     = $pays->getRecordModel( $i );
			$adType  = $pay->getData( 'adwares_type' );
			$adwares = new RecordModel( $adType , $pay->getData( 'adwares' ) );
			$nUser   = new RecordModel( 'nUser' , $pay->getData( 'owner' ) );

			if( !IsEnoughBudget( $adwares ) ) //�\�Z���I�[�o�[���Ă���ꍇ
				continue;

			$sales  = GetSales();
			$reward = GetReward( $adwares , $nUser , $sales );

			if( 0 >= $reward ) //��V�z��0�~�ȉ��ɂȂ�̏ꍇ
			{
				continue;
			}

			$cPay = new FactoryModel( 'continue_pay' );
			$cPay->setID( md5( time() . getenv( 'REMOTE_ADDR' ) ) );

			$cPay->setData( 'adwares_type' , $adwares->getType() );
			$cPay->setData( 'adwares' , $adwares->getID() );
			$cPay->setData( 'cuser' , $adwares->getData( 'cuser' ) );
			$cPay->setData( 'pay_id' , $pay->getID() );
			$cPay->setData( 'owner' , $nUser->getID() );
			$cPay->setData( 'sales' , $sales );
			$cPay->setData( 'cost' , $reward );
			$cPay->setData( 'state' , $ACTIVE_NONE );

			//�L���̎����F�ؐݒ���m�F
			$adDB        = SystemUtil::getGMforType( 'adwares' )->getDB();
			$adRec       = $adDB->selectRecord( $pay->getData( 'adwares' ) );
			$openAdwares = $adDB->getData( $adRec , 'open' );
			$acceptType  = $adDB->getData( $adRec , 'auto' );

			if( $ADWARES_AUTO_ON == $adwares->getData( 'continue_auto' ) )
			{
				$cPay->setData( 'state' , $ACTIVE_ACTIVATE );
				$cPay = $cPay->register();
				$tier = 0;

				$payDB = $pay->getDB();
				addPay( $nUser->getID() , $reward , $payDB , $pay->getRecord() , $tier );

				//�L���̗\�Z�Ɍv�シ��
				$money         = $adwares->getData( 'money_count' );
				$continueCount = $adwares->getData( 'continue_money_count' );

				$adwares->setData( 'money_count' , $money + $reward + $tier );
				$adwares->setData( 'continue_money_count' , $continueCount + 1 );
				$adwares->update();
			}
			else
				$cPay = $cPay->register();

			sendPayMail( $cPay->getRecord() , 'continue_pay' );
			updateRank( $ownerID );

			if( $adwares->getData( 'open' ) ) //�L�������J����Ă���ꍇ
			{
				if( !IsEnoughBudget( $adwares ) ) //�\�Z���I�[�o�[���Ă���ꍇ
				{
					$adwares->setData( 'open' , false );
					$adwares->update();

					//��艺���ʒm���[���𑗐M
					$template = Template::GetLabelFile( 'HIDDEN_NOTICE_MAIL' );
					Mail::Send( $template , $MAILSEND_ADDRES , $MAILSEND_ADDRES , $gm[ 'adwares' ] , $adwares->getRecord() , $MAILSEND_NAMES );
				}
			}
		}
	}
	catch( Exception $e_ )
	{
		WriteErrorLog( $e_ );
	}

	/*******************************************************************************************************
	 * �֐�
	 *******************************************************************************************************/

	//���`�F�b�N

	/**
		@brief   �N�G�������؂���B
		@details �N�G���ɕs���Ȓl���܂܂��ꍇ�A��O���X���[���܂��B
	*/
	function CheckQuery()
	{
		global $ADWARES_PASS;

		ConceptCheck::IsEssential( $_GET , Array( 'check' ) );
		ConceptCheck::IsNotNull( $_GET , Array( 'check' ) );
		ConceptCheck::IsScalar( $_GET , Array( 'adwares' , 'check' , 'cost' , 'sales' , 'uid' ) );

		if( $_GET[ 'check' ] != $ADWARES_PASS )
				throw new IllegalAccessException( '�F�؃p�X������������܂���' );
	}

	/**
		@brief  �L���̗\�Z�����؂���B
		@param  $adwares_ RecordModel�I�u�W�F�N�g�B
		@return �\�Z���ݒ肳��Ă��Ȃ��A�܂��͗]���Ă���ꍇ��true�B\n
		        �\�Z���]���Ă��Ȃ��ꍇ��false�B
	*/
	function IsEnoughBudget( $adwares_ )
	{
		global $ADWARES_LIMIT_TYPE_YEN;          //���ʕ�V
		global $ADWARES_LIMIT_TYPE_CNT;          //�N���b�N��
		global $ADWARES_LIMIT_TYPE_CNT_CLICK;    //�N���b�N��V
		global $ADWARES_LIMIT_TYPE_CNT_CONTINUE; //�p����V

		//�\�Z�̐ݒ���擾����
		$budgetValue = $adwares_->getData( 'limits' );
		$budgetType  = $adwares_->getData( 'limit_type' );

		//�ݒ�ɉ����Ĕ�r������U�蕪����
		switch( $budgetType )
		{
			case $ADWARES_LIMIT_TYPE_YEN : //���ʕ�V�z
				return ( $adwares_->getData( 'money_count' ) < $budgetValue );

			case $ADWARES_LIMIT_TYPE_CNT : //�N���b�N��
				return ( $adwares_->getData( 'pay_count' ) < $budgetValue );

			case $ADWARES_LIMIT_TYPE_CNT_CLICK : //�N���b�N��V�z
				return ( $adwares_->getData( 'click_money_count' ) < $budgetValue );

			case $ADWARES_LIMIT_TYPE_CNT_CONTINUE : //�p����V�z
				return ( $adwares_->getData( 'continue_money_count' ) < $budgetValue );

			default : //�\�Z����Ȃ�
				return true;
		}
	}

	//������ //

	/**
		@brief �G���[���O���o�͂���B
		@param $e_ ��O�I�u�W�F�N�g�B
	*/
	function WriteErrorLog( $e_ )
	{
		//�G���[���b�Z�[�W�����O�ɏo��
		$errorManager = new ErrorManager();
		$errorMessage = $errorManager->GetExceptionStr( $e_ );

		$errorManager->OutputErrorLog( $errorMessage );
	}

	//���擾

	/**
		@brief ��V�z���v�Z����B
		@param $adwares_ RecordModel�I�u�W�F�N�g�B
		@param $nUser_   RecordModel�I�u�W�F�N�g�B
		@param $sales_   �w���V�z�B
	*/
	function GetReward( $adwares_ , $nUser_ , $sales_ )
	{
		global $ADWARES_MONEY_TYPE_PER;
		global $ADWARES_MONEY_TYPE_BANK;
		global $ADWARES_MONEY_TYPE_PERSONAL;

		//�L���ɐݒ肳�ꂽ��V�v�Z���[�g���Q�Ƃ���
		$rewardType = $adwares_->getData( 'continue_type' );

		switch( $rewardType )
		{
			//����
			case $ADWARES_MONEY_TYPE_PER :
				$rate = $adwares_->getData( 'continue_money' );
				break;

			//��������N
			case $ADWARES_MONEY_TYPE_BANK :
				$rank = new RecordModel( $nUser_->getData( 'rank' ) );
				$rate = $rank->getData( 'rate' );
				break;

			//�p�[�\�i�����[�g
			case $ADWARES_MONEY_TYPE_PERSONAL :
				$rate = $nUser_->getData( 'personal_rate' );
				break;

			//�~
			default :
				return $adwares_->getData( 'continue_money' );
		}

		$magni = $nUser_->getData( 'magni' );

		return $sales_ * ( $rate / 100 ) * ( $magni / 100 );
	}

	/**
		@brief  ����z���擾����B
		@return ����z�B
	*/
	function GetSales()
	{
		if( 0 <= $_GET[ 'sales' ] )
		{
			return $_GET[ 'sales' ];
		}
		else if( 0 <= $_GET[ 'cost' ] )
		{
			return $_GET[ 'cost' ];
		}
		else
		{
			return 0;
		}
	}

	/**
		@brief  �p���ۋ��̑Ώۃ��R�[�h����������B
		@param  $payTable_ pay�̃e�[�u�����f���B
		@return ������̃e�[�u�����f���B
	*/
	function SearchContinuePay( $payTable_ )
	{
		$payTable_->search( 'continue_uid' , '=' , $_GET[ 'uid' ] );
		$payTable_->search( 'adwares' , '=' , $_GET[ 'adwares' ] );
		$payTable_->search( 'state' , '=' , 2 );

		return $payTable_;
	}
?>
