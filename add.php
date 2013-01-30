<?php

	/*******************************************************************************************************
	 * <PRE>
	 *
	 * add.php - ��p�v���O����
	 * ���ʂ̃g���b�L���O���s���v���O�����B
	 * 
	 * �ȉ��̌`���ŃA�N�Z�X���ꂽ�ꍇ�A���ʕ�V��ǉ����܂��B
	 * http://example.com/add.php?check=[check]
	 * check = �F�؃p�X
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
		RequestGUID();

		$access = GetAccess();

		if( !HasPay( $access ) ) //���̃A�N�Z�X�̐��ʂ��������Ă��Ȃ��ꍇ
		{
			$adwares = GetAdwares( $access );

			if( IsPassageWait( $adwares ) ) //�Œ�҂����Ԃ��߂��Ă���ꍇ
			{
				if( MatchReceptionMode( $adwares , $access ) ) //�F�؃��[�h����v����ꍇ
					AddSuccessReward( $adwares , $access );
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

	//���`�F�b�N //

	/**
		@brief   �N�G�������؂���B
		@details �N�G���ɕs���Ȓl���܂܂��ꍇ�A��O���X���[���܂��B
	*/
	function CheckQuery()
	{
		global $ADWARES_PASS;

		ConceptCheck::IsEssential( $_GET , Array( 'check' ) );
		ConceptCheck::IsNotNull( $_GET , Array( 'check' ) );
		ConceptCheck::IsScalar( $_GET , Array( 'aid' , 'adwares' , 'check' , 'cost' , 'eccube' , 'from' , 'from_sub' , 'guid' , 'ip' , 'sales' , 'uid' ) );
		ConceptCheck::IsScalar( $_COOKIE , Array( 'adwares_cookie' ) );

		if( $ADWARES_PASS != $_GET[ 'check' ] )
				throw new IllegalAccessException( '�F�؃p�X������������܂���' );
	}

	/**
		@brief  �A�N�Z�X�Ɋ֘A���鐬�ʕ�V�����݂��邩�m�F����B
		@param  $access_ RecordModel�I�u�W�F�N�g�B
		@return ���ʕ�V�����������ꍇ��true�B\n
		        ���ʕ�V��������Ȃ��ꍇ��false�B
	*/
	function HasPay( $access_ )
	{
		$pays = new TableModel( 'pay' );
		$pays->search( 'access_id' , '=' , $access_->getID() );
		$row = $pays->getRow();

		return ( $row ? true : false );
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

	/**
		@brief  �Œ�҂����Ԃ����؂���B
		@param  $adwares_ RecordModel�I�u�W�F�N�g�B
		@return ���ʗ������Ȃ����A���ʕ�V�̍Œ�҂����Ԃ��o�߂��Ă���ꍇ��true�B\n
		        ���ʕ�V�̍Œ�҂����Ԃ��o�߂��Ă��Ȃ��ꍇ��false�B
	*/
	function IsPassageWait( $adwares_ )
	{
		//���݂̌o�ߎ��Ԃ��擾����
		$passageTime = GetPassageTime( $adwares_ );

		if( 0 > $passageTime ) //�A�N�Z�X��������Ȃ��ꍇ
			return true;

		//�N���b�N��V�̍Œ�҂����Ԃ��擾����
		$waitNum  = $adwares_->getData( 'pay_span' );
		$waitUnit = $adwares_->getData( 'pay_span_type' );
		$magnify  = Array( 's' => 1 , 'm' => 60 , 'h' => 60 * 60 , 'd' => 60 * 60 * 24 , 'w' => 60 * 60 * 24 * 7, );
		$waitTime = $waitNum * $magnify[ $waitUnit ];

		return ( $waitTime < $passageTime );
	}

	/**
		@brief �L���̐��ʔF�ؐݒ肪���������m�F����B
		@param $adwares_ RecordModel�I�u�W�F�N�g�B
		@param $access_ RecordModel�I�u�W�F�N�g�B
	*/
	function MatchReceptionMode( $adwares_ , $access_ )
	{
		global $terminal_type;

		if( 0 < $terminal_type ) //�g�ђ[���̏ꍇ
			return true;

		//�F�ؐݒ���擾����
		$checkType = $adwares_->getData( 'check_type' );

		if( 'ip' == $checkType ) //ip�F�؂̏ꍇ
			return ( getenv( 'REMOTE_ADDR' ) == $access_->getData( 'ipaddress' ) );
		else //cookie�F�؂̏ꍇ
			return ( $_COOKIE[ 'adwares_cookie' ] == $access_->getData( 'cookie' ) );
	}

	/**
		@brief  ���̃��[�U�[�̃A�N�Z�X����������B
		@param  $access_ TableModel�I�u�W�F�N�g�B
		@return TableModel�I�u�W�F�N�g�B
	*/
	function SearchAccess( $access_ )
	{
		global $terminal_type; //�[�����

		if( 0 >= $terminal_type ) //PC����̃A�N�Z�X�̏ꍇ
			$access_->search( 'ipaddress' , '=' , getenv( 'REMOTE_ADDR' ) );
		else //�g�т���̃A�N�Z�X�̏ꍇ
		{
			$utn = MobileUtil::getMobileID();

			if( $utn ) //�̎��ʔԍ����擾�ł���ꍇ
				$access_->search( 'utn' , '=' , $utn );
			else //�̎��ʔԍ����擾�ł��Ȃ��ꍇ
				$access_->search( 'useragent' , '=' , getenv( 'HTTP_USER_AGENT' ) );
		}

		return $access_;
	}

	//���擾 //

	/**
		@brief  �A�N�Z�X���R�[�h���擾����B
		@return RecordModel�I�u�W�F�N�g�B
	*/
	function GetAccess()
	{
		global $terminal_type;
		global $ACCESS_LIMIT;

		//�A�N�Z�X����������
		$access = new TableModel( 'access' );

		$andTerms = Array();
		$orTerms  = Array();

		if( 0 < $ACCESS_LIMIT ) //�A�N�Z�X�L���������ݒ肳��Ă���ꍇ
			$andTerms[] = Array( 'regist' , '>' , time() - $ACCESS_LIMIT );

		if( $_GET[ 'aid' ] ) //�A�N�Z�XID���w�肳��Ă���ꍇ
			$andTerms[] = Array( 'id' , '=' , $_GET[ 'aid' ] );
		else
		{
			if( 0 >= $terminal_type ) //PC�[���̏ꍇ
			{
				$orTerms[] = Array( 'ipaddress' , '=' , getenv( 'REMOTE_ADDR' ) );
				$orTerms[] = Array( 'cookie' , '=' , $_COOKIE[ 'adwares_cookie' ] );
			}
			else //�g�ђ[���̏ꍇ
			{
				$utn = MobileUtil::GetMobileID();

				if( $utn ) //�̎��ʔԍ����擾�ł��Ă���ꍇ
					$andTerms[] = Array( 'utn' , '=' , $utn );
				else //�̎��ʔԍ����擾�ł��Ȃ��ꍇ
					$andTerms[] = Array( 'useragent' , '=' , $_SERVER[ 'HTTP_USER_AGENT' ] );
			}
		}

		//�A�N�Z�X���R�[�h���擾����
		$access->SearchOr( $orTerms );
		$access->SearchAnd( $andTerms );
		$access->sortDesc( 'regist' );
		$access->setLimitOffset( 0 , 1 );
		$row = $access->getRow();

		if( !$row ) //���R�[�h��������Ȃ��ꍇ
			throw new RuntimeException( '�A�N�Z�X���R�[�h��������܂���' );

		$access = $access->getRecordModel( 0 );

		return $access;
	}

	/**
		@brief  �L���f�[�^���擾����B
		@param  $access_ RecordModel�I�u�W�F�N�g�B
		@return RecordModel�I�u�W�F�N�g�B
	*/
	function GetAdwares( $access_ )
	{
		$adwType = $access_->getData( 'adwares_type' );
		$adwID   = $access_->getData( 'adwares' );

		if( $_GET[ 'adwares' ] ) //�L��ID���n����Ă���ꍇ
			if( $adwID != $_GET[ 'adwares' ] ) //�L��ID����v���Ȃ��ꍇ
				throw new IllegalAccessException( '�L��ID����v���܂���' );

		if( 'secretAdwares' == $adwType ) //�N���[�Y�h�L���̏ꍇ
			return new RecordModel( 'secretAdwares' , $adwID );
		else //�ʏ�̍L���̏ꍇ
			return new RecordModel( 'adwares' , $adwID );
	}

	/**
		@brief  ���̃��[�U�[�̍ŏI���ʂ���̌o�ߎ��Ԃ��擾����B
		@param  $adwares_ RecordModel�I�u�W�F�N�g�B
		@return �A�N�Z�X�����������ꍇ�͌o�ߎ��ԁB\n
		        �A�N�Z�X��������Ȃ������ꍇ��-1�B
	*/
	function GetPassageTime( $adwares_ )
	{
			//�A�N�Z�X����������
			$access = new TableModel( 'access' );
			$access = SearchAccess( $access );

			$access->search( 'adwares' , '=' , $adwares_->getID() );
			$access->sortDesc( 'regist' );

		$aDB    = $access->getDB();
		$aTable = $access->getTable();
		$aTable = $aDB->joinTableSQL( $aTable , 'access' , 'pay' , 'pay.access_id = access.id' );
		$row    = $aDB->getRow( $aTable );

			if( $row ) //���R�[�h�����������ꍇ
			{
			$aRec   = $aDB->getRecord( $aTable , 0 );
			$regist = $aDB->getData( $aRec , 'regist' );

				$passageTime = time() - $regist;
			}
			else //���R�[�h��������Ȃ��ꍇ
			{ $passageTime = -1; }

		return $passageTime;
	}

	/**
		@brief     ���ʕ�V�z���擾����B
		@exception RuntimeException �L���̕�V�ݒ�ɕs���Ȓl���i�[����Ă����ꍇ�B
		@param     $adwares_ RecordModel�I�u�W�F�N�g�B
		@param     $nUser_   RecordModel�I�u�W�F�N�g�B
		@param     $sales_   ����z�B
		@return    ��V�z�B
	*/
	function GetReward( $adwares_ , $nUser_ , $sales_ )
	{
		global $ADWARES_MONEY_TYPE_YEN;
		global $ADWARES_MONEY_TYPE_PER;
		global $ADWARES_MONEY_TYPE_RANK;
		global $ADWARES_MONEY_TYPE_PERSONAL;

		$rewardPoint   = $adwares_->getData( 'money' );
		$rewardType    = $adwares_->getData( 'ad_type' );
		$autoReception = $adwares_->getData( 'auto' );
		$magni         = $nUser_->getData( 'magni' );

		switch( $rewardType ) //��V�ݒ�ɉ����ĐU�蕪��
		{
			case $ADWARES_MONEY_TYPE_YEN : //�Œ��V
				return (int)( $rewardPoint * ( $magni / 100.0 ) );

			case $ADWARES_MONEY_TYPE_PER : //������V
				return (int)( $sales_ * ( $rewardPoint / 100.0 ) * ( $magni / 100.0 ) );

			case $ADWARES_MONEY_TYPE_RANK : //������V(��������N)
				$rank = new RecordModel( 'sales' , $nUser_->getData( 'rank' ) );
				$rate  = $rank->getData( 'rate' );

				return (int)( $sales_ * ( $rate / 100.0 ) * ( $magni / 100.0 ) );

			case $ADWARES_MONEY_TYPE_PERSONAL : //������V(�p�[�\�i�����[�g)
				$rate  = $nUser_->getData( 'personal_rate' );

				return (int)( $sales_ * ( $rate / 100.0 ) * ( $magni / 100.0 ) );

			default : //�s���ȕ�V�ݒ�
				throw new RuntimeException( '�s���ȕ�V�^�C�v���w�肳��Ă��܂� [' . $rewardType . ']' );
		}
	}

	/**
		@brief  ����z���擾����B
		@param $from_     �⑫�����󂯎��from�p�����[�^�B
		@param $from_sub_ �⑫�����󂯎��from_sub�p�����[�^�B
		@return ����z�B
	*/
	function GetSales( &$from_ , &$from_sub_ )
	{
		$sales = 0;

		if( $_GET[ 'eccube' ] ) //EC-CUBE�p�����[�^���w�肳��Ă���ꍇ
		{
			$count = preg_match( '/order_id=(\d+)/' , $_GET[ 'eccube' ] , $match );

			if( $count ) //����ID�����o�ł����ꍇ
				$from_ .= '(EC-CUBE�����ԍ�:' . $match[ 1 ] . ')';

			$count = preg_match( '/total=([^|]+)/' , $_GET[ 'eccube' ] , $match );

			if( $count ) //���v�z�����o�ł����ꍇ
			{
				$sales = $match[ 1 ];
				$sales = str_replace( ',' , '' , $sales );
			}
		}

		if( !$sales ) //����グ���ݒ肳��Ă��Ȃ��ꍇ
		{
			if( 0 < $_GET[ 'sales' ] ) //sales���Z�b�g����Ă���ꍇ
				$sales = $_GET[ 'sales' ];
			else if( 0 < $_GET[ 'cost' ] ) //cost���Z�b�g����Ă���ꍇ
				$sales = $_GET[ 'cost' ];
		}

		return $sales;
	}

	//������ //

	/**
		@brief ���ʕ�V��ǉ�����B
		@param $adwares_ RecordModel�I�u�W�F�N�g�B
		@param $access_ RecordModel�I�u�W�F�N�g�B
	*/
	function AddSuccessReward( $adwares_ , $access_ )
	{
		global $terminal_type;
		global $ACTIVE_NONE;
		global $ACTIVE_ACTIVATE;
		global $ADWARES_AUTO_ON;

		$nUser  = new RecordModel( 'nUser' , $access_->getData( 'owner' ) );
		$sales  = GetSales( $_GET[ 'from' ] , $_GET[ 'from_sub' ] );
		$reward = GetReward( $adwares_ , $nUser , $sales );

		if( 'secretAdwares' == $adwares_->getType() ) //�N���[�Y�h�L���̏ꍇ
		{
			$users = $adwares_->getData( 'open_user' );

			if( FALSE === strpos( $users , $nUser->getID() ) ) //���J���[�U�[�Ɋ܂܂�Ȃ��ꍇ
				return;
		}

		$pay = new FactoryModel( 'pay' );
		$pay->setData( 'access_id'    , $access_->getID() );
		$pay->setData( 'ipaddress'    , getenv( 'REMOTE_ADDR' ) );
		$pay->setData( 'cookie'       , $access_->getData( 'cookie' ) );
		$pay->setData( 'owner'        , $nUser->getID() );
		$pay->setData( 'adwares_type' , $adwares_->getType() );
		$pay->setData( 'adwares'      , $adwares_->getID() );
		$pay->setData( 'cuser'        , $adwares_->getData( 'cuser' ) );
		$pay->setData( 'cost'         , $reward );
		$pay->setData( 'sales'        , $sales );
		$pay->setData( 'froms'        , SafeString( $_GET[ 'from' ] ) );
		$pay->setData( 'froms_sub'    , SafeString( $_GET[ 'from_sub' ] ) );
		$pay->setData( 'state'        , $ACTIVE_NONE );
		$pay->setData( 'utn'          , SafeString( MobileUtil::GetMobileID() ) );
		$pay->setData( 'useragent'    , SafeString( getenv( 'HTTP_USER_AGENT' ) ) );
		$pay->setData( 'continue_uid' , SafeString( $_GET[ 'uid' ] ) );

		if( $ADWARES_AUTO_ON == $adwares_->getData( 'auto' ) ) //�����F�؂��L���ȏꍇ
		{
			//���ʂ�F�؏�Ԃɂ��Ĕz������ǉ�����
			$pay->setData( 'state' , $ACTIVE_ACTIVATE );
			$pay = $pay->register();

			$payDB = $pay->getDB();
			addPay( $nUser->getID() , $reward , $payDB , $pay->getRecord() , $tier );

			//�L���̗\�Z�Ɍv�シ��
			$currentReward = $adwares_->getData( 'money_count' );
			$currentClick  = $adwares_->getData( 'pay_count' );

			$adwares_->setData( 'money_count' , $currentReward + $reward_ );
			$adwares_->setData( 'pay_count' , $currentClick + 1 );
			$adwares_->update();

			sendPayMail( $pay->getRecord() , 'pay' );
			SendNoticeMail( $adwares_ );
		}
		else
			$pay = $pay->register();

		if( !IsEnoughBudget( $adwares_ ) ) //�\�Z���I�[�o�[�����ꍇ
		{
			$adwares_->setData( 'open' , false );
			$adwares_->update();
		}

		//��������N�X�V�`�F�b�N
		updateRank( $nUser->getID() );
	}

	/**
		@brief  ��������G�X�P�[�v����B
		@param  $str_ �C�ӂ̕�����B
		@return �G�X�P�[�v���ꂽ������B
	*/
	function SafeString( $str_ )
	{
		$str = substr( $str_ , 0 , 4096 );
		$str = htmlspecialchars( $str , ENT_QUOTES , 'SJIS' );

		return $str;
	}

	/**
		@brief cUser�ɗ\�Z�I�[�o�[�ʒm���[���𑗐M����B
		@param $adwares_ RecordModel�I�u�W�F�N�g�B
	*/
	function SendNoticeMail( $adwares_ )
	{
		global $gm;
		global $template_path;
		global $mobile_path;
		global $MAILSEND_ADDRES;
		global $MAILSEND_NAMES;

		if( class_exists( 'mod_cUser' ) ) //cuser���W���[�����L���ȏꍇ
		{
			if( !IsEnoughBudget( $adwares_ ) ) //�\�Z���I�[�o�[�����ꍇ
			{
				//cUser�Ƀ��[���ʒm
				$cUser   = new RecordModel( 'cUser' , $adwares_->getData( 'cuser' ) );
				$cMail   = $cUser->getData( 'mail' );
				$cMobile = $cUser->getData( 'is_mobile' );

				if( $cMobile ) //cUser�����o�C���[���̏ꍇ
					$template_path = $mobile_path;

				$template = Template::GetLabelFile( 'HIDDEN_NOTICE_MAIL' );
				Mail::Send( $template , $MAILSEND_ADDRES , $cMail , $gm[ 'adwares' ] , $adwares_->getRecord() , $MAILSEND_NAMES );
			}
		}
	}

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

	//�����_�C���N�g //

	/**
		@brief   uid�t��URL�փ��_�C���N�g����B
		@details DoCoMo�[������̎��ʔԍ����擾���邽�߁A�N�G����uid�����݂��Ȃ��ꍇ��uid��t�����ă��_�C���N�g���܂��B
	*/
	function RequestGUID()
	{
		global $terminal_type;

		if( MobileUtil::$TYPE_NUM_DOCOMO != $terminal_type ) //DoCoMo�[���łȂ��ꍇ
			return;

		if( 'on' == $_GET[ 'guid' ] ) //����guid�p�����[�^���Z�b�g����Ă���ꍇ
			return;

		//GET�p�����[�^�������p�����߂ɕ����񉻂���
		$paramStr = '';

		foreach( Array( 'aid' , 'adwares' , 'check' , 'cost' , 'eccube' , 'from' , 'from_sub' , 'sales' , 'uid' ) as $key )
		{
			if( array_key_exists( $key , $_GET ) )
				$paramStr .= '&' . $key . '=' . $_GET[ $key ];
		}

		//���_�C���N�g
		header( 'Location: add.php?guid=on' . $paramStr );
		exit();
	}
?>
