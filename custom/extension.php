<?php

	/**
	 * �g�����߃N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class Extension extends command_base
	{
		/**********************************************************************************************************
		 *�@�A�v���P�[�V�����ŗL���\�b�h
		 **********************************************************************************************************/

		/**
		 * arg[0]�Ŏ擾�����l���ADATE�^�ɕϊ����܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param args �R�}���h�R�����g�����z��ł��B�������ɃX�e�[�^�X��n���܂��B
		 */
		function drawDate( &$gm, $rec, $args )
		{
			$time = $args[0];
			$this->addBuffer( date("Y/m/d/ G:i:s",$time) );
		}

		/**
		 * ���ʂ̔F�؃��W�I�{�^����`�悵�܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param args �R�}���h�R�����g�����z��ł��B�������ɃX�e�[�^�X��n���܂��B
		 */
		function drawStateRadio( &$gm, $rec, $args )
		{

			$checked[$args[0]] = ' checked="true"';
   			$tmp = '
				<label><input type="radio" name="state" value="2"'.$checked[2].' />�F��</label>
				<label><input type="radio" name="state" value="1"'.$checked[1].' />��F��</label>
			';
		
			$this->addBuffer( $tmp );
		}


		/**
		 * ���ҏ�̎c���M����\�����܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param args �R�}���h�R�����g�����z��ł��B���̃��\�b�h�ł͗��p���܂���B
		 */
		function invitationRow( &$gm, $rec, $args )
		{
			global $PARENT_MAX_ROW;
			global $LOGIN_ID;
			
			$tgm	 = SystemUtil::getGM();
			$ndb	 = $tgm['nUser']->getDB();
	
			$table	 = $ndb->getTable();
			
			if(isset($args[0]))
				$table	 = $ndb->searchTable( $table, 'parent', '=', $args[0] );
			else
				$table	 = $ndb->searchTable( $table, 'parent', '=', $LOGIN_ID );
		
			$tmp = $PARENT_MAX_ROW - $ndb->getRow( $table );
			if($tmp < 0) $tmp = 0;
		
			$this->addBuffer( $tmp );
		}


		/**
		 * ���ҏ󐧌��̃`�F�b�N���s���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param args �R�}���h�R�����g�����z��ł��B���̃��\�b�h�ł͗��p���܂���B
		 */
		function invitationCheck( &$gm, $rec, $args )
		{
	        global $PARENT_MAX_ROW;
			global $LOGIN_ID;
			
			$tgm	 = SystemUtil::getGM();
			$ndb	 = $tgm['nUser']->getDB();
	
			$table	 = $ndb->getTable();
			$table	 = $ndb->searchTable( $table, 'parent', '=', $LOGIN_ID );
		
			if( $ndb->getRow( $table ) < $PARENT_MAX_ROW || $PARENT_MAX_ROW == "999"){
				$this->addBuffer( "1" );
			}else{
				$this->addBuffer( "0" );
			}
		}

		/**
		 * ���Ԃɉ��������A��\�����܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B���̃��\�b�h�ł͗��p���܂���B
		 * @param args �R�}���h�R�����g�����z��ł��B���̃��\�b�h�ł͗��p���܂���B
		 */
		function hello( &$gm, $rec, $args )
		{
		
			// ** conf.php �Œ�`�����萔�̒��ŁA���p�������萔���R�R�ɗ񋓂���B *******************
			// **************************************************************************************
			
			$message	 = "";
			switch(  date( "G", time() )  )
			{
				case '0':
				case '1':
				case '2':
				case '3':
					$message = '�x���܂ł���J�l�ł��B';
					break;
					
				case '4':
				case '5':
				case '6':
				case '7':
				case '8':
				case '9':
				case '10':
					$message = '���͂悤�������܂��B';
					break;
					
				case '11':
				case '12':
				case '13':
				case '14':
				case '15':
				case '16':
					$message = '����ɂ��́B';
					break;
					
				case '17':
				case '18':
				case '19':
					$message = '����΂�́B';
					break;
					
				case '20':
				case '21':
				case '22':
				case '23':
					$message = '�x���܂ł���J�l�ł��B';
					break;
			}
			
			$this->addBuffer( $message );
		}

        /**
         * �T�C�g�ŗL���̏o��
         *
         * args �f�[�^������������
         */
		function getSiteProfile( &$gm, $rec, $args ){
        global $HOME;
        global $PARENT_MAX_ROW;
        
            switch($args[0]){
                case 'home':
                    $this->addBuffer( $HOME );
                    break;
				case 'parent_limit':
                    $this->addBuffer( $PARENT_MAX_ROW );
                    break;
				case 'adwares_pass':
                case 'site_title':
                case 'keywords':
                case 'description':
                case 'uuid':
                case 'nuser_default_activate':
                case 'nuser_accept_admin':
                case 'cuser_default_activate':
                case 'sales_auto':
                    $sgm = SystemUtil::getGMforType('system');
                    $sdb = $sgm->getDB();
                    $rec = $sdb->getRecord( $sdb->getTable() , 0 );
                    $this->addBuffer( $sdb->getData( $rec , $args[0] ) );
                    break;
            }
        }
		
		/**
		 * �}�[�W���Ɋւ�������擾���܂��B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B
		 * @param args �R�}���h�R�����g�����z��ł��B���̃��\�b�h�ł͗��p���܂���B
		 */
		function getCostType( &$gm, $rec, $args ){
		
			global $ADWARES_EXCHANGE;
		
			switch( $args[0] ){
				case 'name':
					$tgm		 = SystemUtil::getGM();
					$adb	 = $tgm['adwares']->getDB();
					$atable	 = $adb->searchTable( $adb->getTable(), 'id', '=', $args[1] );
					if(  $adb->getRow( $atable ) != 0  ){
						$arec	 = $adb->getRecord( $atable, 0 );
						$this->addBuffer(  $adb->getData( $arec, 'name' ) );
					}
					break;
				case 'money':

					if($args[2] == "yen"){
						$this->addBuffer( $gm->getCCResult( $rec , '<!--# code comma ' . $args[1] . ' #-->' )."�~" );
					}else if($args[2] == "per"){
						$this->addBuffer( $args[1]."��" );
					}else if( $args[ 2 ] == 'rank' ){
						$this->addBuffer( "��������N�K�p" );
					}else{
						$this->addBuffer( "�p�[�\�i�����[�g�K�p" );
					}
					
					break;
			
				case 'exchangeLimit':
					$this->addBuffer( $ADWARES_EXCHANGE );
					break;
			}
		
		}
	
	
	
	
		
		/**
		 * ��������B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B
		 * @param args �R�}���h�R�����g�����z��ł��B�������Ƒ������̓��e����v�����ꍇ�́@��O�������A��v���Ȃ������ꍇ�͑�l������\�����܂��B
		 */
		function ifelse( &$gm, $rec, $args ){
			if( $args[0] == $args[1] ){
				$this->addBuffer( $args[2] );
			}else{
				$this->addBuffer( $args[3] );
			}
		}
	
	
	
	
		
		/**
		 * �z���z���擾�B
		 *
		 * @param gm GUIManager�I�u�W�F�N�g�ł��B
		 * @param rec �o�^���̃��R�[�h�f�[�^�ł��B
		 * @param args �R�}���h�R�����g�����z��ł��B�B
		 */
		function getExchange( &$gm, $rec, $args ){
		
			global $COOKIE_NAME;
			global $loginUserType;
            global $LOGIN_ID;
            global $ACTIVE_ACTIVATE;
		
			$tgm		 = SystemUtil::getGM();
			
            if( $loginUserType != 'admin' ){
                $id = $LOGIN_ID;
            }else if(  isset( $args[1] )  ){
                $id = $args[1];
            }
            
			switch($args[0]){
                case 'past':
                    $db		 = $tgm['returnss']->getDB();
                
                    $table	 = $db->getTable();
                    if( $id ){
                        $table	 = $db->searchTable( $table, 'owner', '=', $id );
                    }
                    $table	 = $db->searchTable( $table, 'state', '=', '�����ς�' );
                    $sum = $db->getSum( 'cost' , $table );
                    break;
                case 'proc':
                    $db		 = $tgm['returnss']->getDB();
                
                    $table	 = $db->getTable();
                    if( $id ){
                        $table	 = $db->searchTable( $table, 'owner', '=', $id );
                    }
                    $table	 = $db->searchTable( $table, 'state', '!', '�����ς�' );
                    $table	 = $db->searchTable( $table, 'state', '!', '�����߂�' );
                    $sum = $db->getSum( 'cost' , $table );
                    break;
                case 'unattestation':
                    $db		 = $tgm['pay']->getDB();
                
                    $table	 = $db->getTable();
                    if( $id ){
                        $table	 = $db->searchTable( $table, 'owner', '=', $id );
                    }
                    $table	 = $db->searchTable( $table, 'state', '=', 1 );//��F��
                    $sum = $db->getSum( 'cost' , $table );
                    
                    $db		 = $tgm['click_pay']->getDB();
                    
                    $table	 = $db->getTable();
                    if( $id ){
                        $table	 = $db->searchTable( $table, 'owner', '=', $id );
                    }
                    $table	 = $db->searchTable( $table, 'state', '=', 1 );//��F��
                    $sum += $db->getSum( 'cost' , $table );
                    break;
                default:
                    $db		 = $tgm['nUser']->getDB();
                
                    if($id){
                        $rec	 = $db->selectRecord( $id );
                        $sum = $db->getData( $rec , 'pay' );
                    }else{
                        $sum = $db->getSum( 'pay' , $db->getTable() );
                    }
                    break;
			}
			
			$this->addBuffer( $sum );
		}
        
        //�e�B�A���擾
		function getTier( &$gm, $rec, $args ){
            global $LOGIN_ID;
            global $loginUserType;
		
			$tgm		 = SystemUtil::getGM();
			
            $db = SystemUtil::getGMforType('nUser')->getDB();
            $sum = 0;
            
            if( $loginUserType != 'admin' ){
      		    $id = $LOGIN_ID;
            }else{
                if(  isset( $args[0] )  )
                    $id = $args[0];
                else{
                    $id =false;
                }
            }
            
            if($id){
                $rec = $db->selectRecord( $id );
                $sum = $db->getData( $rec , 'tier' );
            }else{
                $sum = $db->getSum( 'tier' , $db->getTable() );
            }
            $this->addBuffer( $sum );
        }
		
         
         function addParentKey( &$gm, $rec, $args ){
             if( isset($_GET['friend']) ){
                 $this->addBuffer( '&parent='.$_GET['friend'] );
             }
         }
		
		function plusNum( &$gm, $rec, $args ){
            $this->addBuffer( $args[0]+$args[1] );
         }
		
		
        function drawMailLink( &$gm, $rec, $args ){
            global $terminal_type;
            global $loginUserType;
            global $loginUserRank;
            
            
            $HTML = Template::getTemplate( $loginUserType , $loginUserRank , 'drawMailLink' , 'EXTENSION_PART_DESIGN' );
            
//            $HTML = "./html/mobile/Extension/friends_mail_link.html";
            switch($terminal_type){
                case 0:
                case 3:
                    //���G���R�[�h
                    $this->addBuffer( $gm->getString( $HTML , $rec , 'nomal' ) );
                    break;
                case 1:
                case 2:
                    //Urlencode�ς�
                    $this->addBuffer( $gm->getString( $HTML , $rec ,  'encode' ) );
                    break;
            }
        }
        
        /*
          �ǉ��p��JS�Ăяo�����܂ރJ�e�S���̑I���v���_�E���̕\��
        */
        function addCategorySelectForm( &$gm, $rec, $args ){
            $tgm = SystemUtil::getGMforType( 'category' );
            $db = $tgm->getDB();
            
            $table = $db->getTable();
            $row = $db->getRow( $table );
            
            $index = '���I��/';
            $value = '/';
            
            for($i=0;$i<$row;$i++){
                $rec = $db->getRecord( $table , $i );
                $index .= SystemUtil::systemArrayEscape($db->getData( $rec , 'name' ))."/";
                $value .= $db->getData( $rec , 'id' )."/";
            }
            $index .= "�L���J�e�S���[��ǉ�����";
            $value .= "add";
            
            $this->addBuffer( $gm->getCCResult( $rec, '<!--# form option category '.$check.' '.$value.' '.$index.' onchange="changeCategory(this)" #-->' ) );
        }

		function self(&$argGUIManager , $argRecord , $argParams)
		{
			global $loginUserType;
			global $LOGIN_ID;

			if('nobody' == $loginUserType)
				return;

			$guiManager = SystemUtil::getGMforType($loginUserType);
			$database   = $guiManager->getDB();
			$record     = $database->selectRecord($LOGIN_ID);

			$this->addBuffer($database->getData($record , $argParams[0]));
		}
        

		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
	
	
		/**********************************************************************************************************
		 * �T�C�g�V�X�e���p���\�b�h
		 **********************************************************************************************************/
	
	
	

		
		//urlencode �� charencode�ł��ꏏ�ɂ�����Ⴄ��������B
        function ucencode( &$gm, $rec, $args ){
            if(isset($args[1])){
                $atgs[0] = mb_convert_encoding( $atgs[0] , $args[1] );
            }
            $this->addBuffer( urlencode( $args[0] ) );
        }
        
		
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/
		/*******************************************************************************************************/

        
	function tierList4day( &$gm, $rec , $args ){
		global $loginUserType;
		global $LOGIN_ID;

		$HTML = Template::getTemplate(  "" , 15 ,'tier4' , 'EXTENSION_PART_DESIGN' );
		
		$pgm = SystemUtil::getGMforType('tier');
		$db = $pgm->getDB();
		$table = $db->getTable();

		if( isset($_GET['registA']) ){
			$table = $db->searchTable( $table , 'regist' , '>' , $_GET['registA'] );
		}
		if( isset($_GET['registB']) ){
			$table = $db->searchTable( $table , 'regist' , '<' , $_GET['registB'] );
		}
		if( $loginUserType == 'nUser'){
			$table = $db->searchTable( $table , 'owner' , '=' , $LOGIN_ID );
		}else if( isset($_GET['id']) && strlen($_GET['id']) ){
			$table = $db->searchTable( $table , 'owner' , '=' , $_GET['id']  );
		}
		if( $loginUserType == 'cUser'){
			$table = $db->searchTable( $table , 'cuser' , '=' , $LOGIN_ID );
		}else if( isset($_GET['cuser']) && strlen($_GET['cuser']) ){
			$table = $db->searchTable( $table , 'cuser' , '=' , $_GET['cuser'] );
		}
		if( isset($_GET['tier']) && strlen($_GET['tier']) ){
			$table = $db->searchTable( $table , 'tier' , '=' , $_GET['tier'] );
		}
		if( isset($_GET['adwares']) && strlen($_GET['adwares']) ){
			$table = $db->searchTable( $table , 'adwares' , '=' , $_GET['adwares'] );
		}
		
		$table = $db->dateGroup( $table, 'regist','d' );
		$table = $db->addSelectColumn($table,'count(*) as cnt',false);
		$table = $db->addSelectColumn($table,'sum(cost) as sum',false);
		$table = $db->addSelectColumn($table,'sum(tier1) as tier1',false);
		$table = $db->addSelectColumn($table,'sum(tier2) as tier2',false);
		$table = $db->addSelectColumn($table,'sum(tier3) as tier3',false);

		$row = $db->getRow( $table );

		$list = Array();
			
		for( $i=0 ; $row>$i ; $i++ ){
			$payrec = $db->getRecord( $table, $i );
			$dategroup = $db->getData( $payrec, 'date_group' );
			$datelist = explode('-',$dategroup);
			$list[ (int)$datelist[2] ] = $payrec;
		}
			
		$return = $pgm->getString( $HTML , null , 'list_day_return' );
			
		$str = Array();
		
		$w = date('w',$_GET['registA']);
			
		if( $w ){
				
			for( $i= 0; $i < $w ; $i++ ){
				$str[0] .= $pgm->getString( $HTML , null , 'blank_head' );
				$str[1] .= $pgm->getString( $HTML , null , 'blank_body' );
			}
		}
		
		$max = (int)date('t',$regist);
		for($i=1; $max>=$i;$i++){
			$pgm->setVariable( 'day', $i );
			if( false ){
				$pgm->setVariable('class',' class="today"');
			}elseif( ($i-1+$w) % 7 == 6 ){
				$pgm->setVariable('class',' class="day_sat"');
			}elseif( ($i-1+$w) % 7 == 0 ){
				$pgm->setVariable('class',' class="day_sun"');
			}else{
				$pgm->setVariable('class','');
			}

			$str[0+(int)(($i-1+$w)/7)*2] .= $pgm->getString( $HTML , $list[$i] , 'list_day_head' );
			$str[1+(int)(($i-1+$w)/7)*2] .= $pgm->getString( $HTML , $list[$i] , 'list_day_body' );
		}
			
		for( $i= ($max+$w)%7; 7>$i && $i ; $i++ ){
			$str[0+(int)((($max+$w)-1)/7)*2] .= $pgm->getString( $HTML , null , 'blank_head' );
			$str[1+(int)((($max+$w)-1)/7)*2] .= $pgm->getString( $HTML , null , 'blank_body' );
		}
			
		$this->addBuffer( implode($return,$str) );
	}
	
	function tierList4month( &$gm, $rec , $args ){
		global $loginUserType;
		global $LOGIN_ID;

		$HTML = Template::getTemplate(  "" , 15 ,'tier4' , 'EXTENSION_PART_DESIGN' );

		$pgm = SystemUtil::getGMforType('tier');
		$db = $pgm->getDB();
		$table = $db->getTable();

		if( isset($_GET['registA']) ){
			$table = $db->searchTable( $table , 'regist' , '>' , $_GET['registA'] );
		}
		if( isset($_GET['registB']) ){
			$table = $db->searchTable( $table , 'regist' , '<' , $_GET['registB'] );
		}
		if( isset($_GET['id']) ){
			$table = $db->searchTable( $table , 'owner' , '=' , $_GET['id']  );
		}

		$table = $db->dateGroup( $table, 'regist','m' );
		$table = $db->addSelectColumn($table,'count(*) as cnt',false);
		$table = $db->addSelectColumn($table,'sum(cost) as sum',false);

		$row = $db->getRow( $table );
		
		if($row){
			$list = Array();
				
			for( $i=0 ; $row>$i ; $i++ ){
				$payrec = $db->getRecord( $table, $i );
				$dategroup = $db->getData( $payrec, 'date_group' );
				
				$datelist = explode('-',$dategroup);
				$list[ (int)$datelist[0] ][ (int)$datelist[1] ] = $payrec;
			}
			$return = $pgm->getString( $HTML , null , 'list_month_return' );
				
			foreach( $list as $year => $months ){
				$pgm->setVariable( 'year', $year );
				$this->addBuffer( $pgm->getString( $HTML , $rec , 'list_month_head' ) );

				$str = Array();
				for($i=1; 12>=$i;$i++){
					$pgm->setVariable( 'month', $i );
					$str[0+(int)(($i-1)/6)*2] .= $pgm->getString( $HTML , $months[$i] , 'list_month_row_head' );
					$str[1+(int)(($i-1)/6)*2] .= $pgm->getString( $HTML , $months[$i] , 'list_month_row_body' );
				}
				$this->addBuffer( implode($return,$str) );

				$this->addBuffer( $pgm->getString( $HTML , $rec , 'list_month_foot' ) );
			}
		}
	}

	function accessList4day( &$gm, $rec , $args ){
		global $loginUserType;
		global $LOGIN_ID;

		$HTML = Template::getTemplate(  "" , 15 ,'access4' , 'EXTENSION_PART_DESIGN' );

		$pgm = SystemUtil::getGMforType('access');
		$db = $pgm->getDB();
		$table = $db->getTable();
		
		if( isset($_GET['registA']) ){
			$table = $db->searchTable( $table , 'regist' , '>' , $_GET['registA'] );
		}
		if( isset($_GET['registB']) ){
			$table = $db->searchTable( $table , 'regist' , '<' , $_GET['registB'] );
		}
		if( $loginUserType == 'cUser'){
			$table = $db->searchTable( $table , 'cuser' , '=' , $LOGIN_ID );
		}else if( isset($_GET['cuser']) && strlen($_GET['cuser']) ){
			$table = $db->searchTable( $table , 'cuser' , '=' , $_GET['cuser'] );
		}
		if( $loginUserType == 'nUser'){
			$table = $db->searchTable( $table , 'owner' , '=' , $LOGIN_ID );
		}else if( isset($_GET['owner']) && strlen($_GET['owner']) ){
			$table = $db->searchTable( $table , 'owner' , '=' , $_GET['owner'] );
		}
		if( isset($_GET['adwares']) && strlen($_GET['adwares']) ){
			$table = $db->searchTable( $table , 'adwares' , '=' , $_GET['adwares'] );
		}
		
		$table = $db->dateGroup( $table, 'regist','d' );
		$table = $db->addSelectColumn($table,'count(*) as cnt',false);

		$row = $db->getRow( $table );
		
		if($row){
			$list = Array();
			
			for( $i=0 ; $row>$i ; $i++ ){
				$payrec = $db->getRecord( $table, $i );
				$dategroup = $db->getData( $payrec, 'date_group' );
				$datelist = explode('-',$dategroup);
				$list[ (int)$datelist[2] ] = $payrec;
			}
			$pgm->setVariable( 'year', date('Y',$_GET['registA']) );
			$pgm->setVariable( 'month', date('m',$_GET['registA']) );
			
			$return = $pgm->getString( $HTML , null , 'list_day_return' );
				
			$str = Array();
			$max = (int)date('t',$regist);
			for($i=1; $max>=$i;$i++){
				$pgm->setVariable( 'day', $i );
				$str[0+(int)(($i-1)/6)*2] .= $pgm->getString( $HTML , $list[$i] , 'list_day_head' );
				$str[1+(int)(($i-1)/6)*2] .= $pgm->getString( $HTML , $list[$i] , 'list_day_body' );
			}
				
			for( $i= $max%6; 6>$i && $i ; $i++ ){
				$str[0+(int)(($max-1)/6)*2] .= $pgm->getString( $HTML , null , 'blank_head' );
				$str[1+(int)(($max-1)/6)*2] .= $pgm->getString( $HTML , null , 'blank_body' );
			}
			$this->addBuffer( implode($return,$str) );
		}
	}
	function accessList4month( &$gm, $rec , $args ){
		global $loginUserType;
		global $LOGIN_ID;

		$HTML = Template::getTemplate(  "" , 15 ,'access4' , 'EXTENSION_PART_DESIGN' );

		$pgm = SystemUtil::getGMforType('access');
		$db = $pgm->getDB();
		$table = $db->getTable();

		if( isset($_GET['registA']) ){
			$table = $db->searchTable( $table , 'regist' , '>' , $_GET['registA'] );
		}
		if( isset($_GET['registB']) ){
			$table = $db->searchTable( $table , 'regist' , '<' , $_GET['registB'] );
		}
		if( $loginUserType == 'cUser'){
			$table = $db->searchTable( $table , 'cuser' , '=' , $LOGIN_ID );
		}else if( isset($_GET['cuser']) && strlen($_GET['cuser']) ){
			$table = $db->searchTable( $table , 'cuser' , '=' , $_GET['cuser'] );
		}
		if( $loginUserType == 'nUser'){
			$table = $db->searchTable( $table , 'owner' , '=' , $LOGIN_ID );
		}else if( isset($_GET['owner']) && strlen($_GET['owner']) ){
			$table = $db->searchTable( $table , 'owner' , '=' , $_GET['owner'] );
		}
		if( isset($_GET['adwares']) && strlen($_GET['adwares']) ){
			$table = $db->searchTable( $table , 'adwares' , '=' , $_GET['adwares'] );
		}

		$table = $db->dateGroup( $table, 'regist','m');
		$table = $db->addSelectColumn($table,'count(*) as cnt',false);

		$row = $db->getRow( $table );

		if($row){
			$list = Array();
				
			for( $i=0 ; $row>$i ; $i++ ){
				$payrec = $db->getRecord( $table, $i );
				$dategroup = $db->getData( $payrec, 'date_group' );
				$datelist = explode('-',$dategroup);
				$list[ (int)$datelist[0] ][ (int)$datelist[1] ] = $payrec;
			}
			$return = $pgm->getString( $HTML , null , 'list_month_return' );
				
			foreach( $list as $year => $months ){
				$pgm->setVariable( 'year', $year );
				$this->addBuffer( $pgm->getString( $HTML , $rec , 'list_month_head' ) );

				$str = Array();
				for($i=1; 12>=$i;$i++){
					$pgm->setVariable( 'month', $i );
					$str[0+(int)(($i-1)/6)*2] .= $pgm->getString( $HTML , $months[$i] , 'list_month_row_head' );
					$str[1+(int)(($i-1)/6)*2] .= $pgm->getString( $HTML , $months[$i] , 'list_month_row_body' );
				}
				$this->addBuffer( implode($return,$str) );

				$this->addBuffer( $pgm->getString( $HTML , $rec , 'list_month_foot' ) );
			}
		}
	}
        

	
	function createEpochTime( &$gm, $rec , $args ){
		if(!strlen($args[0])){
			$t = time( );
		}else if(is_numeric($args[0])){
			$t = $args[0];
		}else{
			$t = $gm->getCachedDB()->getData( $rec, $args[0] );
		}
		$this->addBuffer( createEpochTime($t,$args[1]) );
	}

	function createEpochTime4ymd( &$gm, $rec , $args ){
		if( isset( $args[1] ) && strlen( $args[1] ) ){	$y = $args[1];	}
		else{	$y = date("Y");	}
		
		if( isset( $args[2] ) && strlen( $args[2] ) ){	$m = $args[2];	}
		else{	$m = 1;	}
		
		if( isset( $args[3] ) && strlen( $args[3] ) ){	$d = $args[3];	}
		else{	$d = 1;	}
		
		switch( $args[0] ){
			case 'now': case 'n':
				break;
			case 'monthtop': case 'mt':
				$t = mktime(0, 0, 0, $m   , 1, $y);
				break;
			case 'monthend': case 'me':
				$t = mktime(0, 0,-1, $m+1 , 1, $y);
				break;
			case 'premonthtop': case 'mt-1':
				$t = mktime(0, 0, 0, $m-1  , 1, $y);
				break;
			case 'premonthend': case 'me-1':
				$t = mktime(0, 0,-1, $m , 1, $y);
				break;
			case 'daytop': case 'dt':
				$t = mktime(0, 0, 0, $m , $d , $y);
				break;
			case 'dayend': case 'de':
				$t = mktime(0, 0, -1, $m  ,$d+1, $y);
				break;
			default:
				break;
		}
		$this->addBuffer( $t );
	}
	
	/**
	 * �w�肵��get�������p������B
	 * @param $gm
	 * @param $rec
	 * @param $args �������͍ŏ��ɕt����ڑ�(?/&)�A�������ڍs�Ɍp������GET�p�����[�^�̖��O
	 * @return String
	 */
	function getParam( &$gm, $rec , $args ){
		$sep = array_shift($args);
		$param = Array();
		foreach( $args as $get){
			if( isset($_GET[ $get ])){
				$param[] = $get.'='.$_GET[ $get ];
			}
		}
		if(count($param)){ $ret = $sep.join('&',$param); }
		$this->addBuffer( $ret );
	}
	
	/**
	 * �e���[�U�[�̃��[����M�ݒ�̐ݒ荀�ڂ��V�X�e���ݒ�ɍ��킹�ďo��
	 */
	function drawMailReceptionEdit( &$gm, $rec , $args ){
		global $loginUserType;
		global $LOGIN_ID;
		
		$HTML = Template::getTemplate(  "" , 15 ,'mail_reception' , 'EXTENSION_PART_DESIGN' );
		
		$set = Array( 1 => '�N���b�N�L��', 2 => '���ʔF��', 4 => '�p���ۋ�' );
		
		switch( $_GET['type'] ){
			case 'cUser':
				$lists = SystemUtil::getSystemData('send_mail_cuser');
				break;
			case 'nUser':
				$lists = SystemUtil::getSystemData('send_mail_nuser');
				break;
		}
		if( !strlen($lists) ){ return; }
		
		if( isset($args[0]) && $args[0] == 'check'){
			$this->addBuffer( $gm->getString( $HTML , $rec , 'check' ) );
			return;
		}

		$names = Array();
		foreach( explode('/',$lists ) as $key ){
			$names[] = $set[$key].'�̒ʒm���L�����Z��';
		}
		$this->addBuffer( $gm->getString( $HTML , $rec , 'head' ) );
		$this->addBuffer( $gm->getCCResult( $rec , '<!--# form checkbox mail_reception  <br/><br/> '.$lists.' '.join('/',$names).' #-->' ) );
		$this->addBuffer( $gm->getString( $HTML , $rec , 'foot' ) );
	}

	/**
		@brief   �������G�X�P�[�v���ďo�͂���B
		@remarks CC�����͔��p�󔒂Ō�������܂��B
	*/
	function htmlEscape( &$a_gm , $a_rec , $a_args )
	{
		$this->addBuffer( htmlspecialchars( implode( ' ' , $a_args ) , ENT_QUOTES , 'SJIS' ) );
	}

	function getActiveHome( $iGM_ , $iRec_ , $iArgs_ )
	{
		global $HOME;

		$result = $HOME;

		if( 'on' == $_SERVER[ 'HTTPS' ] )
			{ $result = str_replace( 'http://' , 'https://' , $result ); }

		$this->addBuffer( $result );
	}

	function query( $iGM_ , $iRec_ , $iArgs_ )
	{
		$this->addBuffer( htmlspecialchars( $_SERVER[ 'QUERY_STRING' ] , ENT_QUOTES , 'SJIS' ) );
	}
}


?>