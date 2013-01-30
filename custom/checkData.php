<?PHP

	/*******************************************************************************************************
	 * <PRE>
	 * 
	 * ���͓��e�`�F�b�N�N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 * </PRE>
	 *******************************************************************************************************/

class CheckData
{
	var $gm;
	var $type;
	var $error_design;
	var $check;
	var $error_name;
	var $error_msg;
	var $edit;
	var $_DEBUG	 = false;

	function CheckData( &$gm, $edit, $loginUserType, $loginUserRank )
	{
		$this->gm			 = $gm;
		$this->type			 = $_GET['type'];
		$this->error_design	 = Template::getTemplate( $loginUserType , $loginUserRank , $this->type , 'REGIST_ERROR_DESIGN' );
		$this->check		 = true;
		$this->error_name  	 = Array();
		$this->error_msg   	 = Array();
		$this->edit			 = $edit;
	}
	
	function setErrorDesign( $design_file ){
		$this->error_design = $design_file;
	}
	
	function setType( $type ){
		$this->type = $type;
	}
	
	// ���K�\���ɂ��ėp�I�ȍ\���`�F�b�N
	function checkRegex()
	{
		for($i=0; $i<count($this->gm[ $this->type ]->colName); $i++)
		{
			if($this->gm[ $this->type ]->maxStep >= 2 && $this->gm[ $this->type ]->colStep[$this->gm[ $this->type ]->colName[$i]] != $_POST['step'])
				continue;

			$name		 = $this->gm[ $this->type ]->colName[$i];
			if( strlen( $this->gm[ $this->type ]->colRegex[ $name ] ) )
			{
				if( isset( $_POST[ $name ]) && $_POST[ $name ] != null )
				{
					if( !preg_match( $this->gm[ $this->type ]->colRegex[ $name ],$_POST[ $name ]) )
					{
						$this->addError( $name. '_REGEX' );
					}
				}
			}
		}
		return $this->check;
	}

	// �󗓃`�F�b�N
	function checkNull($name,$args)
	{
		if(    !isset(   $_POST[ $name ]   ) || is_null($_POST[  $name  ]) || $_POST[  $name  ] === '' )
		{
			if( $_FILES[  $name  ]['name'] )	 { return $this->check; }
			$this->addError( $name );
		}
		return $this->check;
	}
	
	// �󗓃`�F�b�N
	// Error���b�Z�[�W���w�肵��colm�Ƌ��ʗ��p����
	function checkNullset($name,$args)
	{
    	if( $this->error_name[$args[0]] )
    		return $this->check;
		
		if( !isset( $_POST[ $name ] ) || $_POST[ $name ] == null )
		{
			if( $_FILES[ $name ]['name'] ){ return $this->check; }
			$this->addError( $args[0] );
		}
		return $this->check;
	}
	
	// ����̃��[�U�[�̏ꍇ�ɋ󗓃`�F�b�N
	function checkNullAuthority($name,$args)
	{
		global $loginUserType;
		
		if(!count($args)){return;}
		
		if( array_search($loginUserType,$args) !== FALSE && ( !isset(   $_POST[ $name ]   ) || $_POST[  $name  ] == null ) )
		{
			if( $_FILES[  $name  ]['name'] )	 { return $this->check; }
			$this->addError( $name );
		}
		return $this->check;
	}

	// �����Ŏw�肵���������������ꍇ�Ƀ`�F�b�N(���������w��\
	function checkNullFlag($name,$args)
	{
		if( !isset($args[0]) || !isset($args[1]) ){
			return $this->check;
		}else{
			for($i=0;isset($args[$i]);$i+=2)
				if( !isset( $_POST[$args[$i]] ) || $_POST[$args[$i]] != $args[$i+1] )
					return $this->check;
		}
		return $this->checkNull($name,$args);
	}
	
	function checkSize($name,$args){
		if( strlen( $_POST[ $name ] ) > $args[0] )
		{
			$this->addError( $name.'_size' );
		}
		return $this->check;
	}

	// �����Ŏw�肵���������������ꍇ�Ƀ`�F�b�N(���������w��\
	function checkFlag($name,$args)
	{
		if( !isset($args[0]) || !isset($args[1]) ){
			return $this->check;
		}else{
			if( !isset( $_POST[$args[0]] ) || $_POST[$args[0]] != $args[1] )
				return $this->check;
		}
		return call_user_func(array($this,'check'.$args[2]), $name, array_slice($args,3) );
	}

	// �l���ύX����Ă��鎞�Ƀ`�F�b�N
	function checkChangeFlag($name,$args)
	{
		if( !isset( $_POST[$name] ) || !strlen($_POST[$name]) ){
			return $this->check;
		}
		
		if( isset($_GET['id']) && strlen( $_GET['id'] ) ){
			$db = $this->gm[ $this->type ]->getDB();
			
			$rec = $db->selectRecord( $_GET['id'] );
			if( $rec ){
				$old = $db->getData( $rec, $name );
				
				if( $_POST[$name] == $old )
					return $this->check;
			}
			return call_user_func(array($this,'check'.$args[0]), $name, array_slice($args,1) );
		}
		return call_user_func(array($this,'check'.$args[0]), $name, array_slice($args,1) );
	}
	
	// �C�ӂ�table��id�Ƃ��đ��݂��Ă���
	function checkIntable($name,$args)
	{
		$type = $args[0];
		
		if( isset( $_POST[ $name ] ) && $_POST[ $name ] != null )
		{
			$db = $this->gm[ $type ]->getDB();
			if(! $db->getRow( $db->searchTable( $db->getTable(), 'id' , '=' , $_POST[ $name ] ) ) )
				$this->addError( $args[0].'_in_table' );
		}
		return $this->check;
	}

	/**
		@brief   �ҏW�֎~�J�����`�F�b�N�B
		@details POST�f�[�^�����R�[�h�̌��̒l�ƈقȂ�ꍇ�A�G���[����ǉ����܂��B
	*/
	function checkConst( $name , $args )
	{
		if( !isset( $_POST[ $name ] ) ) //POST����ĂȂ��Ȃ�`�F�b�N�s�v
			return $this->check;

		//�I���W�i���f�[�^���擾
		$db     = SystemUtil::getGMforType( $_GET[ 'type' ] )->getDB();
		$rec    = $db->selectRecord( $_GET[ 'id' ] );
		$origin = $db->getData( $rec , $name );

		if( $origin != $_POST[ $name ] )
		{
			//�ʃ��b�Z�[�W�p�G���[�p�[�g
			$this->addError( $name . '_isConst' );

			//�P�ꃁ�b�Z�[�W�p�G���[�p�[�g
			if( !$this->error_name[ 'Const' ] )
				$this->addError( 'Const' );
		}

		return $this->check;
	}

	/**
		@brief   �Ǘ��҃f�[�^�`�F�b�N�B
		@details �Ǘ��҈ȊO�̃��[�U�[���ҏW���悤�Ƃ����ꍇ�A�G���[����ǉ����܂��B
	*/
	function checkAdminData( $name , $args )
	{
		global $loginUserType;

		if( 'admin' == $loginUserType ) //�Ǘ��҂̓p�X
			return $this->check;

		if( !isset( $_POST[ $name ] ) ) //POST����ĂȂ��Ȃ�`�F�b�N�s�v
			return $this->check;

		//�I���W�i���f�[�^���擾
		$db     = SystemUtil::getGMforType( $_GET[ 'type' ] )->getDB();
		$rec    = $db->selectRecord( $_GET[ 'id' ] );
		$origin = $db->getData( $rec , $name );

		if( $origin != $_POST[ $name ] )
		{
			//�ʃ��b�Z�[�W�p�G���[�p�[�g
			$this->addError( $name . '_isAdminData' );

			//�P�ꃁ�b�Z�[�W�p�G���[�p�[�g
			if( !$this->error_name[ 'AdminData' ] )
				$this->addError( 'AdminData' );
		}

		return $this->check;
	}
    
    function is_uri($text,$level = 1){
        switch($level){
            case 0: default:
            //�ړ��Ǝg�p�����̈�v
                if (!preg_match("/https?:\/\/[-_.!~*'()a-zA-Z0-9;\/?:@&=+$,%#]+/", $text)){ return FALSE; }
                break;
            case 1:
            //http URL �̐��K�\��
                $re = "/\b(?:https?|shttp):\/\/(?:(?:[-_.!~*'()a-zA-Z0-9;:&=+$,]|%[0-9A-Fa-f" .
                      "][0-9A-Fa-f])*@)?(?:(?:[a-zA-Z0-9](?:[-a-zA-Z0-9]*[a-zA-Z0-9])?\.)" .
                      "*[a-zA-Z](?:[-a-zA-Z0-9]*[a-zA-Z0-9])?\.?|[0-9]+\.[0-9]+\.[0-9]+\." .
                      "[0-9]+)(?::[0-9]*)?(?:\/(?:[-_.!~*'()a-zA-Z0-9:@&=+$,]|%[0-9A-Fa-f]" .
                      "[0-9A-Fa-f])*(?:;(?:[-_.!~*'()a-zA-Z0-9:@&=+$,]|%[0-9A-Fa-f][0-9A-" .
                      "Fa-f])*)*(?:\/(?:[-_.!~*'()a-zA-Z0-9:@&=+$,]|%[0-9A-Fa-f][0-9A-Fa-f" .
                      "])*(?:;(?:[-_.!~*'()a-zA-Z0-9:@&=+$,]|%[0-9A-Fa-f][0-9A-Fa-f])*)*)" .
                      "*)?(?:\?(?:[-_.!~*'()a-zA-Z0-9;\/?:@&=+$,]|%[0-9A-Fa-f][0-9A-Fa-f])" .
                      "*)?(?:#(?:[-_.!~*'()a-zA-Z0-9;\/?:@&=+$,]|%[0-9A-Fa-f][0-9A-Fa-f])*)?/";
                
                if (!preg_match($re, $text)) { return FALSE; }
                break;
        }
        return TRUE;
    }
    
    function checkUri($name,$args){
    	if( $this->error_name[$name] || !strlen( $_POST[$name] ) )
    		return $this->check;
    
    	if(count($args))
    		$level=$args[0];
    	else
    		$level=1;
    	
        if(!$this->is_uri($_POST[$name],$level)){
			$this->addError($name. '_URI');
        }
        return $this->check;
    }
    
	function is_mail($text,$level = 3,$dns_check = false) 
	{
        switch($level){
            case 0: default:
                if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $text)){ return FALSE; }
                break;
            case 1:
            //http://www.tt.rim.or.jp/~canada/comp/cgi/tech/mailaddrmatch/
            //�u�Ȃ�ׂ��v�������ȃA�h���X��e�����K�\��
                if (!preg_match("/^[\x01-\x7F]+@(([-a-z0-9]+\.)*[a-z]+|\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])/", $text)){ return FALSE; }
                break;
            case 2:
            //PEAR::Mail_RFC822
                if (!preg_match("/^([*+!.&#$|\'\\%\/0-9a-z^_`{}=??:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})$/i", $text)){ return FALSE; }
                break;
            case 3:
            //CakePHP
                if (!preg_match("/\\A(?:^([a-z0-9][a-z0-9_\\-\\.\\+]*)@([a-z0-9][a-z0-9\\.\\-]{0,63}\\.(com|org|net|biz|info|name|net|pro|aero|coop|museum|[a-z]{2,4}))$)\\z/i", $text)){ return FALSE; }
                break;
            case 4:
            //symfony
                if (!preg_match("/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i", $text)){ return FALSE; }
                break;
            case 5:
            //Cal Henderson: http://iamcal.com/publish/articles/php/parsing_email/pdf/
            //Parsing Email Adresses in PHP
                $re = '/^([^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-'
                   .'\\x5d\\x7f-\\xff]+|\\x22([^\\x0d\\x22\\x5c\\x80-\\xff]|\\x5c\\x00-'
                   .'\\x7f)*\\x22)(\\x2e([^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-'
                   .'\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+|\\x22([^\\x0d\\x22\\x5c\\x80'
                   .'-\\xff]|\\x5c\\x00-\\x7f)*\\x22))*\\x40([^\\x00-\\x20\\x22\\x28\\x29'
                   .'\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+|\\x5b([^'
                   .'\\x0d\\x5b-\\x5d\\x80-\\xff]|\\x5c\\x00-\\x7f)*\\x5d)(\\x2e([^\\x00-'
                   .'\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c\\x3e\\x40\\x5b-\\x5d\\x7f-'
                   .'\\xff]+|\\x5b([^\\x0d\\x5b-\\x5d\\x80-\\xff]|\\x5c\\x00-\\x7f)*'
                   .'\\x5d))*$/';
                
                if (!preg_match($re, $text)) { return FALSE; }
                break;
        }
        
        //���݂��郁�[���A�h���X���ǂ������m�F���邽��dns�̃`�F�b�N
        if($dns_check){
             if (function_exists('checkdnsrr')) {
                 $tokens = explode('@', $text);
                 if (!checkdnsrr($tokens[1], 'MX') && !checkdnsrr($tokens[1], 'A')) 
                 {
                     return FALSE;
                 }
             }
        }
        return TRUE;
    }
    
    function checkMail($name,$args){
    	if( $this->error_name[$name] || !strlen( $_POST[$name] ) )
    		return $this->check;
    
    	if(isset($args[0]) && strlen($args[0]) ){
    		$level=$args[0];
    	}else{ $level=3; }
    	
    	if(isset($args[1]) && strlen($args[1]) ){
    		$dns_check=(boolean)$args[1];
    	}else{ $dns_check=false; }
    	
        if( !$this->is_mail($_POST[$name]) ){
			$this->addError($name. '_MAIL');
        }
        return $this->check;
    }
    
	// �d���`�F�b�N����
	function checkDuplication( $name ,$args){
		// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
		// **************************************************************************************
		if(  isset( $_POST[$name] )  )
		{
            $db		 = $this->gm[ $this->type ]->getDB();
            $table	 = $db->getTable();
            if( isset( $_POST['id'] ) ) { $table	 = $db->searchTable($table, 'id', '!', $_POST['id']); }
            $table	 = $db->searchTable($table, $name, '=', $_POST[$name]);
            $row	 = $db->getRow($table);
            if( $row > 0 )
            {
				$this->addError($name.'_dup');
            }
		}
		return $this->check;
    }
        
	// ���[���̏d���`�F�b�N����
	function checkMailDup($name,$args)
	{
		// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
		   global $THIS_TABLE_IS_USERDATA;
		   global $TABLE_NAME;
		// **************************************************************************************

		if( isset( $_POST[$name] ) )
		{// ���[���A�h���X�d���`�F�b�N

			$cnt	 = 0;
			$max	 = count($TABLE_NAME);
			for($i=0; $i<$max; $i++)
			{
				if(  $THIS_TABLE_IS_USERDATA[ $TABLE_NAME[$i] ]  )
				{
					$db		 = $this->gm[ $TABLE_NAME[$i] ]->getDB();
					$table	 = $db->getTable();
					if( isset( $_POST['id'] ) ) { $table	 = $db->searchTable($table, 'id', '!', $_POST['id']); }
					$table	 = $db->searchTable($table, 'mail', '=', $_POST[$name]);
					$cnt	 += $db->getRow($table);
					if( $cnt > 0 )
					{
						$this->addError('mail_dup');
						break;
					}
				}
			}
		}
		return $this->check;
	}

	//���t�̐����`�F�b�N
	function checkDate($name,$args)
	{
		$y = $name;
		$m = $args[0];
		$d = $args[1];
		if( strlen($_POST[$y]) && strlen($_POST[$m]) && strlen($_POST[$d]) ){
			if( ! checkdate($_POST[$m],$_POST[$d] , $_POST[$y]) ){
				$this->addError('date_format');
			}
		}
		
		return $this->check;
	}
	
	//���t�����ݎ������ߋ����ǂ���(checkData�ƃZ�b�g�Ŏg��
	function checkOlddate($name,$args)
	{
		
		$y = $name;
		$m = $args[0];
		$d = $args[1];
		if(isset($args[2]))
			$h = $args[2];
		if(isset($args[3]))
			$min = $args[3];
		if( strlen($_POST[$y]) && strlen($_POST[$m]) && strlen($_POST[$d]) ){
			if(!$this->error_name[ 'date_format' ]){
				
				if( mktime( $h?$_POST[$h]:0 , $min?$_POST[$min]:0 ,0,$_POST[$m],$_POST[$d] , $_POST[$y]) < time() ){
					$this->addError('old_date');
				}	
			}
		}

		return $this->check;
	}
	
	function checkExistsUser($name,$args)
	{
		global $THIS_TABLE_IS_USERDATA;

		$id = $_POST[ 'parent' ];

		if( !$id )
			return;

		foreach( $THIS_TABLE_IS_USERDATA as $key => $value )
		{
			if( !$value )
				continue;

			$gm    = SystemUtil::getGMforType( $key );
			$db    = $gm->getDB();
			$table = $db->searchTable( $db->getTable() , 'id' , '=' , $id );

			if( $db->getRow( $table ) )
				return $this->check;
		}

		$this->addError( $name . '_ExistsUser');
		return $this->check;
	}
	
	// �p�X���[�h��v�`�F�b�N
	function checkConfirmInput($name,$args)
	{
		$val1 = $name;
		$val2 = $args[0];
		
		 if( isset( $_POST[ $val1 ] ) ){
			if( !isset( $_POST[ $val2 ] ) || !strlen( $_POST[ $val2 ] ) ){
				$this->addError($name.'_CONFIRM_NOT');
			}else if( $_POST[ $val1 ] != $_POST[ $val2 ]  )
			{
				$this->addError($name.'_CONFIRM_CHECK');
			}
		 }
		
		return $this->check;
	}
	
	//�������Ȃ����`�F�b�N
	function checkLong($name,$args){
		$max = $args[0];
		
		if( strlen( $_POST[ $name ]) > $max ){
			$this->addError($name.'_long');
		}
		return $this->check;
	}
	
	//�Z�������Ȃ����`�F�b�N
	function checkShort($name,$args){
		$min = $args[0];
		
		if( strlen( $_POST[ $name ]) < $min ){
			$this->addError($name.'_short');
		}
		return $this->check;
		
	}
	
	//�w��table�̎w��column�Ɏ��g��id�����݂��邩�ǂ���
	function checkChild($id, $type, $column ){
		
		$cdb = $this->gm[ $type ]->getDB();
		$ctable = $cdb->searchTable( $cdb->getTable(), $column, '=', $id );
		
		if( $cdb->getRow( $ctable ) ){
			$this->addError($type.'_CHILD');
		}
		return $this->check;
	}

	/**
		@brief ���ʂ��}�C�i�X�ɂȂ�Ȃ����m�F����B
	*/
	function checkMinusReturnss( $iName_ , $iArgs_ )
	{
		$db  = GMList::getDB( $_GET[ 'type' ] );
		$rec = $db->selectRecord( $_GET[ 'id' ] );

		if( !PayLogic::Editable( $rec , $_POST[ 'cost' ] , $_POST[ 'state' ] ) ) //���ʂ��}�C�i�X�ɂȂ�ꍇ
		{
			$this->addError( 'minus_error' );
		}

		return $this->check;
	}

	// �ėp�`�F�b�N�������ꊇ�ōs��
	function generalCheck($edit)
	{
		$this->checkRegex();
		
		$row = count($this->gm[ $this->type ]->colName);
		for($i=0; $i<$row; $i++)
		{
			if($this->gm[ $this->type ]->maxStep >= 2 && $this->gm[ $this->type ]->colStep[$this->gm[ $this->type ]->colName[$i]] != $_POST['step'])
				continue;
			
			$faled		 = false;
			$name		 = $this->gm[ $this->type ]->colName[$i];
			
			//Null,Uri,Mail,Duplication,MailDup,Pass,Birth,
			
			if( !$edit )	{	$pal = $this->gm[ $this->type ]->colRegist[ $name ];	}
			else			{	$pal = $this->gm[ $this->type ]->colEdit[ $name ];	}
			
			if( strlen($pal) ){
				$checks = explode('/', $pal );
				
				foreach( $checks as $check ){
					if( strpos($check,':') === FALSE ){
						call_user_func(array($this,'check'.$check), $name, Array() );
					}
					else{
						$val = explode(':', $check );
						call_user_func(array($this,'check'.$val[0]), $name, array_slice($val,1));
					}
					if( $this->_DEBUG ){ d('generalCheck: column('.$name.')  check('.$checks.')');}
				}
			}
		}
		return $this->check;
	}

	// �G���[���e���擾
	function getError( $label = null )
	{
		$tmp = '';
		
		if( !$this->check )
		{// �G���[���e������ꍇ$
			
			if(is_null($label)){
				$is_error	 = $this->error_msg['is_error'];
				$error		.= $is_error ."\n";
				unset($this->error_msg['is_error']);
				$error	.= join($this->error_msg,"\n");
				$this->error_msg['is_error'] = $is_error;
			}
			else if($this->error_name[ $label ] || $label == 'is_error' )
			{
				$error	.= $this->error_msg[ $label ];
			}

			if( strlen($error) && $label !== 'is_error' )
			{
				$tmp	.= $this->gm[ $this->type ]->partGetString( $this->error_design , 'head');
				$tmp	.= $error;
				$tmp	.= $this->gm[ $this->type ]->partGetString( $this->error_design , 'foot');
			}
		}
		
		return $tmp;
	}

	// �G���[�t���O���擾
	function isError( $label = null, $data )
	{
		$tmp = '';
		if( !strlen($data) ) { $data = 'validate'; }
		if( $this->error_name[ $label ] ) { $tmp = $data;  }
		return $tmp;
	}
	
	//�w��J���������݂�step�̂��̂��ǂ�����Ԃ�
	function checkStep( $name ){
		if($this->gm[ $this->type ]->maxStep >= 2 && $this->gm[ $this->type ]->colStep[$this->gm[ $this->type ]->colName[$i]] != $_POST['step'])
			continue;
	}
    
    function getCheck(){
        return $this->check;
    }
    
    function addError( $part ){
        $this->error_msg[ $part ] .= $this->gm[ $this->type ]->partGetString(  $this->error_design , $part );
		$this->error_name[ $part ] = true;
        if($this->check) { $this->error_msg[ 'is_error' ] .= $this->gm[ $this->type ]->partGetString(  $this->error_design , 'is_error' ); }
		$this->check = false;
		if( $this->_DEBUG ){ d('addError:'.$part);}
	}
	
	function addErrorString($str){
        $this->error_msg[ 'string' ] .= $str;
        if($this->check) { $this->error_msg[ 'is_error' ] .= $this->gm[ $this->type ]->partGetString(  $this->error_design , 'is_error' ); }
		$this->check = false;
		if( $this->_DEBUG ){ d('addError:'.$part);}
	}

	//debug�t���O����p
	function onDebug(){ $this->_DEBUG = true; }
	function offDebug(){ $this->_DEBUG = false; }
}

?>