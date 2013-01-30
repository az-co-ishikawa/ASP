<?PHP
/*******************************************************************************************************
 * <PRE>
 *
 * �ėp�֐��Q
 *
 * @version 1.0.0
 *
 * </PRE>
 *******************************************************************************************************/

class CleanGlobal
{
	private function escape($array)
	{
		$array = self::nullbyte($array);
		return $array;
	}

	private function nullbyte($array)
	{
		if(is_array($array)) return array_map( array('CleanGlobal', 'nullbyte'), $array );
		return str_replace( "\0", "", $array );
	}

	function action()
	{
		$_GET = self::escape($_GET);
		$_POST = self::escape($_POST);
		$_REQUEST = self::escape($_REQUEST);
		$_FILES = self::escape($_FILES);
		if(isset($_SESSION)) { $_SESSION = self::escape($_SESSION); }
		$_COOKIE = self::escape($_COOKIE);
	}
}

//�Љ�i�e�B�A�j�����B
function friendProc(){

	global $PARENT_MAX_ROW;
	global $PARENT_LIMIT_URL;

	if( isset( $_GET[ 'friend' ] ) ){

		$tgm	 = SystemUtil::getGM();
		$ndb	 = $tgm['nUser']->getDB();

		$table	 = $ndb->getTable();
		$table	 = $ndb->searchTable( $table, 'parent', '=', $_GET[ 'friend' ] );

		$row 	 = $ndb->getRow( $table );

		if( $row < $PARENT_MAX_ROW || $PARENT_MAX_ROW == "999"){
			$_SESSION[ 'friend' ] = $_GET[ 'friend' ];
		}else{
			header("Location:".$PARENT_LIMIT_URL);
			exit;
		}
	}
}

//��������N�X�V�`�F�b�N�B
function updateRank($id){

	global $ACTIVE_ACTIVATE;
	global $RANK_AUTO_ON;

	if( SystemUtil::getSystemData( 'sales_auto') == $RANK_AUTO_ON ){

		$pdb	 = GMList::getDB('pay');
		$ptable	 = $pdb->getTable();
		$ptable	 = $pdb->searchTable( $ptable, 'owner', '=', $id );
		$ptable	 = $pdb->searchTable( $ptable, 'state', '=', $ACTIVE_ACTIVATE );	//�F�؍ς݂̐��ʂɌ���

		$prow 	 = $pdb->getRow( $ptable );				//�l������
		$p_sales = $pdb->getSum( "sales", $ptable );	//������z���v

		//�����̉�������N������
		$sdb = GMList::getDB('sales');
		$stable	 = $sdb->getTable();

		//����z�݂̂Ŕ��f����O���[�v
		$salesTable = $sdb->searchTable( $stable , 'lot' , '=' , 0 );
		$salesTable = $sdb->searchTable( $salesTable , 'sales' , '>' , 0 );
		$salesTable = $sdb->searchTable( $salesTable , 'sales' , '<=' , $p_sales );

		//�����݂̂Ŕ��f����O���[�v
		$rowTable = $sdb->searchTable( $stable , 'sales' , '=' , 0 );
		$rowTable = $sdb->searchTable( $rowTable , 'lot' , '>' , 0 );
		$rowTable = $sdb->searchTable( $rowTable , 'lot' , '<=' , $prow );

		//�����g���O���[�v
		$tmp_table1	 = $sdb->searchTable( $stable, 'lot', '>', 0 );
		$tmp_table1	 = $sdb->searchTable( $tmp_table1, 'lot', '<=', $prow );
		$tmp_table2	 = $sdb->searchTable( $stable, 'sales', '>', 0 );
		$tmp_table2	 = $sdb->searchTable( $tmp_table2, 'sales', '<=', $p_sales );

		$stable		 = $sdb->andTable( $tmp_table1, $tmp_table2 );
		$s2table	 = $sdb->orTable( $salesTable , $rowTable );
		$stable		 = $sdb->orTable( $stable , $s2table );

		$sRow = $sdb->getrow( $stable );

		if( 0 >= $sRow ) //�����N��������Ȃ������ꍇ
			return;

		$stable	 = $sdb->sortTable( $stable, "rate", "desc");	//���[�g���������ɕ��ёւ�

		$srec = $sdb->getRecord( $stable, 0 );
		$sid = $sdb->getData( $srec, 'id' );	//�����̉�������NID

		//���݂̉�������N�Əƍ�
		$ndb = GMList::getDB('nUser');
		$nrec = $ndb->selectRecord( $id );
		$n_rank = $ndb->getData( $nrec, 'rank' );

		//�����N���قȂ�ꍇ�͍X�V
		if($n_rank != $sid){
			$nrec = $ndb->setData( $nrec, 'rank', $sid );
			$ndb->updateRecord($nrec);
		}
	}
}

//pay�ւ̉��Z�����B  tier���l������B
//$gm nUser
function addPay($user_id,$pay,&$pay_db,$pay_rec , &$_tierValue){
	global $gm;
	$ndb = $gm['nUser']->getDB();
	$rec = $ndb->selectRecord( $user_id );
	if( $rec ){
		$rec = $ndb->setCalc( $rec, 'pay' , '+' , $pay );
		$ndb->updateRecord($rec);

		$p = $ndb->getData( $rec , 'parent' );
		$g = $ndb->getData( $rec , 'grandparent' );
		$gg = $ndb->getData( $rec , 'greatgrandparent' );
		if( $p || $g || $gg ){
			$sdb = $gm['system']->getDB();
			$tdb = $gm['tier']->getDB();

			$srec = $sdb->getRecord( $sdb->getTable(), 0);
			$list = Array( $p, $g, $gg );
			$pers = Array( 'child_per', 'grandchild_per', 'greatgrandchild_per' );
			for($i=0;$i<3;$i++){
				if( ! strlen($list[$i]) ){ continue; }

				$per = $sdb->getData( $srec , $pers[$i] );

				if($per > 0){
					$trec = $ndb->selectRecord( $list[$i] );
					if($trec){
						$tpay = floor($pay * $per/100);
						$ndb->setCalc( $trec, 'pay' , '+' , $tpay );
						$ndb->setCalc( $trec, 'tier' , '+' , $tpay );
						$ndb->updateRecord($trec);

						$tier_rec = $tdb->getNewRecord();
						$tdb->setData(  $tier_rec, 'id', $pay_db->getData($pay_rec,'id') . ($i+1) );
						$tdb->setData(  $tier_rec, 'owner', $list[$i] );
						$tdb->setData(  $tier_rec, 'cuser', $pay_db->getData($pay_rec,'cuser') );
						$tdb->setData(  $tier_rec, 'tier', $pay_db->getData($pay_rec,'owner') );
						$tdb->setData(  $tier_rec, 'adwares', $pay_db->getData($pay_rec,'adwares') );
						$tdb->setData(  $tier_rec, 'cost', $tpay );
						$tdb->setData(  $tier_rec, 'tier'.($i+1), 1 );
						$tdb->setData(  $tier_rec, 'regist', time()  );
						$tdb->addRecord( $tier_rec );

						$_tierValue += $tpay;
					}
				}
			}
		}

		//��������N�̍X�V�`�F�b�N
		updateRank( $user_id );

		return true;
	}
	return false;
}
function subPay($user_id,$pay,&$pay_db,$pay_rec){
	global $gm;
	$ndb = $gm['nUser']->getDB();
	$rec = $ndb->selectRecord( $user_id );
	if( $rec ){
		$rec = $ndb->setCalc( $rec, 'pay' , '-' , $pay );
		$ndb->updateRecord($rec);

		$p = $ndb->getData( $rec , 'parent' );
		$g = $ndb->getData( $rec , 'grandparent' );
		$gg = $ndb->getData( $rec , 'greatgrandparent' );
		if( $p || $g || $gg ){
			$sdb = $gm['system']->getDB();
			$tdb = $gm['tier']->getDB();

			$srec = $sdb->getRecord( $sdb->getTable(), 0);

			$list = Array( $p, $g, $gg );
			$pers = Array( 'child_per', 'grandchild_per', 'greatgrandchild_per' );
			for($i=0;$i<3;$i++){
				if( ! strlen($list[$i]) ){ continue; }

				$per = $sdb->getData( $srec , $pers[$i] );

				if($per > 0){
					$trec = $ndb->selectRecord( $list[$i] );
					if($trec){
						$tpay = floor($pay * $per / 100);
						$ndb->setCalc( $trec, 'pay' , '-' , $tpay );
						$ndb->setCalc( $trec, 'tier' , '-' , $tpay );
						$ndb->updateRecord($trec);

						$tier_rec = $tdb->selectRecord( $pay_db->getData( $pay_rec, 'id' ) . ($i+1) );
						$tdb->deleteRecord( $tier_rec );
					}
				}
			}
		}
		return true;
	}
	return false;
}

function getDefaultActivate( $_type )
{
	global $ACTIVE_ACTIVATE;

	$gm  = SystemUtil::getGMforType( 'system' );
	$db  = $gm->getDB();
	$rec = $db->selectRecord( 'ADMIN' );

	if( 'nUser' == $_type ){
		$def = $db->getData( $rec , 'nuser_default_activate' );
		if( $def == $ACTIVE_ACTIVATE ){ $def = $db->getData( $rec , 'nuser_accept_admin' ); }
	}else{
		$def = $db->getData( $rec , 'cuser_default_activate' );
	}

	return $def;
}

function fileWrite( $file_name , $html ){
	if(!$f = fopen($file_name,'w')){
		return;
	}

	if(fwrite($f,$html) === FALSE ){
		fclose($f);
		return;
	}

	fclose($f);
}
function fileRead( $file_name ){
	$html = file_get_contents($file_name);
	return $html;
}

function createEpochTime( $t, $f ){
	switch( $f ){
		case 'now': case 'n':
			break;
		case 'monthtop': case 'mt':
			$t = mktime( 0, 0, 0, date("m",$t)  , 1, date("Y",$t));
			break;
		case 'monthend': case 'me':
			$t = mktime( 0, 0,-1, date("m",$t)+1  , 1, date("Y",$t));
			break;
		case 'premonthtop': case 'mt-1':
			$t = mktime( 0, 0, 0, date("m",$t)-1  , 1, date("Y",$t));
			break;
		case 'premonthend': case 'me-1':
			$t = mktime( 0, 0,-1, date("m",$t)  , 1, date("Y",$t));
			break;
		case 'daytop': case 'dt':
			$t = mktime( 0, 0, 0, date("m",$t) , date("d",$t) , date("Y",$t));
			break;
		case 'dayend': case 'de':
			$t = mktime( 0, 0, -1, date("m",$t)  ,date("d",$t)+1, date("Y",$t));
			break;
		default:
			break;
	}
	return $t;
}

	/**
		@brief �ʒm�ΏۂɃ��[���𑗐M����B
	*/
	function sendPayMail( $iRec_ , $iType_ )
	{
		global $ACTIVE_ACTIVATE;
		global $PAY_TYPE_CLICK;
		global $PAY_TYPE_NOMAL;
		global $PAY_TYPE_CONTINUE;
		global $MAILSEND_ADDRES;
		global $MAILSEND_NAMES;
		global $gm;

		$db     = $gm[ $iType_ ]->getDB();
		$notice = $db->getData( $iRec_ , 'is_notice' );
		$state  = $db->getData( $iRec_ , 'state' );

		if( $notice ) //���ɒʒm�ς݂̏ꍇ
			{ return; }

		if( $ACTIVE_ACTIVATE != $state ) //�F�؂ł͂Ȃ��ꍇ
			{ return; }

		$gm[ $iType_ ]->setVariable( 'pay_type' , $iType_ );

		$typeIndexs = Array(
			'click_pay'    => ( string )$PAY_TYPE_CLICK ,
			'pay'          => ( string )$PAY_TYPE_NOMAL ,
			'continue_pay' => ( string )$PAY_TYPE_CONTINUE
		);

		$typeIndex = $typeIndexs[ $iType_ ];

		//�Ǘ��҂ɑ��M
		$enableAdmin = SystemUtil::getSystemData( 'send_mail_admin' );

		if( FALSE !== strpos( $enableAdmin , $typeIndex ) ) //�Ǘ��҂ւ̑��M���L���ȏꍇ
		{
			$template = Template::getTemplate( 'admin' , 1 , '', 'PAY_MAIL' );
			Mail::send( $template , $MAILSEND_ADDRES , $MAILSEND_ADDRES , $gm[ $iType_ ] , $iRec_ , $MAILSEND_NAMES );
		}

		//cUser�ɑ��M
		$enableCUser = SystemUtil::getSystemData( 'send_mail_cuser' );

		if( FALSE !== strpos( $enableCUser , $typeIndex ) ) //cUser�ւ̑��M���L���ȏꍇ
		{
			$cID  = $db->getData( $iRec_ , 'cuser' );
			$cDB  = $gm[ 'cUser' ]->getDB();
			$cRec = $cDB->selectRecord( $cID );

			if( $cRec ) //���R�[�h�����݂���ꍇ
			{
				$reception = $cDB->getData( $cRec , 'mail_reception' );
				$isMobile  = $cDB->getData( $cRec , 'is_mobile' );
				$cMail     = $cDB->getData( $cRec , 'mail' );

				if( FALSE === strpos( $reception , $typeIndex ) ) //�ʒm���ۑΏۂł͂Ȃ��ꍇ
				{
					if( $isMobile ) //�g�т���o�^���ꂽ���[�U�[�̏ꍇ
					{
						$currentPath   = $template_path;
						$template_path = $mobile_path;
						$template      = Template::getTemplate( 'cUser' , 1 , '' , 'PAY_MAIL' );
						$template_path = $currentPath;
					}
					else //���̑��̒[������o�^���ꂽ���[�U�[�̏ꍇ
						{ $template = Template::getTemplate( 'cUser' , 1 , '' , 'PAY_MAIL' ); }
						
					Mail::send( $template , $MAILSEND_ADDRES , $cMail , $gm[ $iType_ ] , $iRec_ , $MAILSEND_NAMES );
				}
			}
		}

		//nUser�ɑ��M
		$enableNUser = SystemUtil::getSystemData( 'send_mail_nuser' );

		if( FALSE !== strpos( $enableNUser , $typeIndex ) ) //nUser�ւ̑��M���L���ȏꍇ
		{
			$nID  = $db->getData( $iRec_ , 'owner' );
			$nDB  = $gm[ 'nUser' ]->getDB();
			$nRec = $nDB->selectRecord( $nID );

			if( $nRec ) //���R�[�h�����݂���ꍇ
			{
				$reception = $nDB->getData( $nRec , 'mail_reception' );
				$isMobile  = $nDB->getData( $nRec , 'is_mobile' );
				$nMail     = $nDB->getData( $nRec , 'mail' );

				if( FALSE === strpos( $reception , $typeIndex ) ) //�ʒm���ۑΏۂł͂Ȃ��ꍇ
				{
					if( $isMobile ) //�g�т���o�^���ꂽ���[�U�[�̏ꍇ
					{
						$currentPath   = $template_path;
						$template_path = $mobile_path;
						$template      = Template::getTemplate( 'nUser' , 1 , '' , 'PAY_MAIL' );
						$template_path = $currentPath;
					}
					else //���̑��̒[������o�^���ꂽ���[�U�[�̏ꍇ
						{ $template = Template::getTemplate( 'nUser' , 1 , '' , 'PAY_MAIL' ); }
						
					Mail::send( $template , $MAILSEND_ADDRES , $nMail , $gm[ $iType_ ] , $iRec_ , $MAILSEND_NAMES );
				}
			}
		}

		//�ʒm�ς݃t���O��ݒ肷��
		$db->setData( $iRec_ , 'is_notice' , true );
		$db->updateRecord( $iRec_ );
	}

?>