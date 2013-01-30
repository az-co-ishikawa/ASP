<?PHP

	ob_start();

	try
	{
	include_once 'custom/head_main.php';

		//�p�����[�^�`�F�b�N
		ConceptCheck::IsScalar( $_GET , Array( 'action' , 'pass' , 'm' , 'd' ) );
		//�p�����[�^�`�F�b�N�����܂�
	
	//�y�[�W�J��
	switch($_GET['action']){
	    case 'report':
			SystemUtil::download( 'batch_returnss.csv' , 'report/batch_returnss.csv' );
			break;

	    case 'exe':
			
			if($loginUserType != "admin" && $_GET["pass"] != $cron_pass){ throw new IllegalAccessException( "ACCESS ERROR." ); }
	
        //nUser��pay�������\�z�ȏ�Ȃ̂�search
        $db = $gm['nUser']->getDB();
        
        $rdb = $gm['returnss']->getDB();
        
        
        $table = $db->searchTable( $db->getTable() , 'pay' , '>=' , $ADWARES_EXCHANGE );
        $table = $db->searchTable( $table , 'activate' , '=' , $ACTIVE_ACCEPT );
        $time = time();
        $csv_data = "";
        //���ʂ����[�v�ŉ񂵂đS�z�������āAreturnss�ɓ����Ă���
        $row = $db->getRow( $table );
        if($row==0){
            print System::getHead($gm,$loginUserType,$loginUserRank);
            Template::drawTemplate( $gm[ $loginUserType ], null, 'returnss', $loginUserRank, '', 'RETURNSS_EXECUTE_ERROR' );
            print System::getFoot($gm,$loginUserType,$loginUserRank);
            exit();
        }
        for($i=0 ; $row > $i ; $i++ ){
            $rec = $db->getRecord( $table , $i );
            
            $id  = $db->getData( $rec , 'id' );
            $pay = $db->getData( $rec , 'pay' );
            
            $rrec = $rdb->getNewRecord();
            $rid = md5( time(). $id );
            $rdb->setData(  $rrec, 'id',		$rid );
            $rdb->setData(  $rrec, 'owner',		$id  );
            $rdb->setData(  $rrec, 'state',		'�Ǘ��Ҋm�F�҂�'  );
            $rdb->setData(  $rrec, 'cost',		$pay  );
            $rdb->setData(  $rrec, 'regist',    $time );
            $rdb->addRecord( $rrec );
            
            $csv_data .= $rid.','.$id.','.$pay.','.$time."\n";
        }
        
        //�����\��������nUser��pay���[����(��̃��[�v�ł���getRecord���ς���Ă��܂�)
/*        for($i=0 ; $row > $i ; $i++ ){
            $rec = $db->getRecord( $table , $i );
            $rec  = $db->setData( $rec , 'cost' , 0 );
            $db->updateRecord( $rec );
        }*/
        $db->setTableDataUpdate( $table , 'pay' , 0 );
        
        //�S��csv�ɓ���Ƃ�
        $filename = "./report/batch_returnss.csv";
        $handle = fopen($filename, 'w');
        if(fwrite($handle, $csv_data) === FALSE){
				throw new FileIOException( "Cannot write to file ($filename)" );
        }
        fclose($handle);
    
        //�o�^����
        $gm[ $loginUserType ]->setVariable('cnt',$row);
        
        print System::getHead($gm,$loginUserType,$loginUserRank);
        Template::drawTemplate( $gm[ $loginUserType ], null, 'returnss', $loginUserRank, '', 'RETURNSS_EXECUTE_SUCCESS' );
        print System::getFoot($gm,$loginUserType,$loginUserRank);
        exit();
        
        break;
    case 'zenginkyo':
			if($loginUserType != "admin" && $_GET["pass"] != $cron_pass){ throw new IllegalAccessException( "ACCESS ERROR." ); }
		$db = $gm['zenginkyo']->getDB();
		$rec = $db->selectRecord('ADMIN');
		
		$branch_code = $db->getData( $rec, 'branch_code');
		$bank_type = $db->getData( $rec, 'bank_type');
		$number = $db->getData( $rec, 'number');
		
		//�w�b�_���R�[�h
		$head = '';
		$head .= '1';//�f�[�^�敪 1
		$head .= '21';//��ʃR�[�h(����) 2
		$head .= '0';//�R�[�h�敪(JIS) 1
		$head .= sprintf('%10s',$db->getData( $rec, 'commission_code'));//�ϑ��҃R�[�h 10
		$head .= sprintf('%40s',mb_convert_kana($db->getData( $rec, 'name_kana'), "k"));//�ϑ��Җ��J�i 40
		$head .= sprintf('%02d%02d',$_GET['m'],$_GET['d']);//��g�� 4
		$head .= sprintf('%4s',$db->getData( $rec, 'bank_code'));//�d�����Z�@�֔ԍ� 4
		$head .= sprintf('%15s',mb_convert_kana($db->getData( $rec, 'bank_name_kana'), "k"));//�d�����Z�@�֖� 15
		$head .= sprintf('%3s',$branch_code);//�d���x�X�ԍ� 3
		$head .= sprintf('%15s',mb_convert_kana($db->getData( $rec, 'branch_name_kana'), "k"));//�d���x�X��15
		$head .= $bank_type;//�a����� 1
		$head .= sprintf('%7s',$number);//�����ԍ� 7
		$head .= str_repeat(' ',17);//�_�~�[ 17
		
		//�f�[�^���R�[�h
		$data = '';
        $rdb = $gm['returnss']->getDB();
        $ndb = $gm['nUser']->getDB();
        $rtable = $rdb->searchTable( $rdb->getTable(), 'state', '=', '�����҂�' );
		
        $row = $rdb->getRow( $rtable );
        $cnt = 0;
        for($i=0;$i<$row;$i++){
        	$rrec = $rdb->getRecord( $rtable, $i );
        	$id = $rdb->getData( $rrec, 'owner' );
        	$nrec = $ndb->selectRecord( $id );
        	
        	$cost = $rdb->getData( $rrec, 'cost' );
			$data .= '2';//�f�[�^�敪 1
			$data .= sprintf('%4s',$ndb->getData( $nrec,'bank_code' ));//��d�����Z�@�֔ԍ� 4
//			$data .= sprintf('%15s',mb_convert_kana($ndb->getData( $nrec, 'bank'), "k"));//��d�����Z�@�֖� 15
			$data .= str_repeat(' ',15);//��d�����Z�@�֖� 15
			$data .= sprintf('%3s',$ndb->getData( $nrec,'branch_code' ));//��d���x�X�ԍ�3
//			$data .= sprintf('%15s',mb_convert_kana($ndb->getData( $nrec, 'branch'), "k"));//��d���x�X��15
			$data .= str_repeat(' ',15);//��d���x�X��15
			$data .= str_repeat(' ',4);//��`�������ԍ�4
			$data .= $ndb->getData( $nrec,'bank_type' );//�a�����1
			$data .= sprintf('%7s',$ndb->getData( $nrec,'number' ));//�����ԍ�7
			$data .= sprintf('%-30s',mb_convert_kana($ndb->getData( $nrec, 'bank_name'), "k"));//���l��30
			$data .= sprintf('%010d',$cost);//�U�����z10
			$data .= '1';//�V�K�R�[�h����1
			$data .= sprintf('%10s',$id);//�ڋq�R�[�h1 10
			$data .= str_repeat(' ',10);//�ڋq�R�[�h2 10
			$data .= ' ';//�U���敪 1
			$data .= ' ';//���ʕ\�� 1
			$data .= str_repeat(' ',7);//�_�~�[ 7
//			$data .= "\n";
			$cnt += $cost;
        }
        
        //�g���[���[���R�[�h
        $trailer ="";
		$trailer .= '8';//�f�[�^�敪 1
		$trailer .= sprintf('%06d',$row);//���v���� 6
		$trailer .= sprintf('%012d',$cnt);//���v���z 12
		$trailer .= str_repeat(' ',101);//�_�~�[ 101
        
        //�t�b�^�[���R�[�h
        $foot = "";
		$foot .= '9';//�f�[�^�敪 1
		$foot .= str_repeat(' ',119);//�_�~�[ 119
        
		$file_name = 'zenginkyo.txt';
		header('Cache-Control: public');
		header('Pragma:');
        header('Content-Disposition: attachment; filename="'.$file_name.'"');
		header('Content-type: application/x-octet-stream; name="'.$file_name.'"; charset=Shift_JIS');
		
		print $head;//."\n";
		print $data;
		print $trailer;//."\n";
		print $foot;
		
		break;
    default:
		print System::getHead($gm,$loginUserType,$loginUserRank);
        Template::drawTemplate( $gm[ $loginUserType ], null, 'returnss', $loginUserRank, '', 'RETURNSS_ACTION_INDEX' );
		print System::getFoot($gm,$loginUserType,$loginUserRank);
        break;
}
	}
	catch( Exception $e_ )
	{
		ob_end_clean();

		//�G���[���b�Z�[�W�����O�ɏo��
		$errorManager = new ErrorManager();
		$errorMessage = $errorManager->GetExceptionStr( $e_ );

		$errorManager->OutputErrorLog( $errorMessage );

		//��O�ɉ����ăG���[�y�[�W���o��
		$className = get_class( $e_ );
		ExceptionUtil::DrawErrorPage( $className );
	}

	ob_end_flush();
?>