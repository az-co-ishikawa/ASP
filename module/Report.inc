<?PHP

/*
 * report_list[ ���[�U�[RANK ]['table']
 *   table����key�ɂ��āA�v�f�͈ȉ�
 * tables[label] = ���x��
 * tables[column] = 1�s�ڂɕ\������J�����^�C�g��
 * tables[owner] = false / LOGIN_ID�ƈ�v���ׂ�column��
 *
 * report_list[ ���[�U�[RANK ]['report'] = reports;
 *
 * reports�͈ȉ��A�o�̓t�@�C������key�Ƃ���report�̔z��
 *
 * report[case_label] = ���x��
 * report[table_name] = �e�[�u����
 * report[head_name][] = �J������
 * report[colum_name][] = �J�����̓��e(�J���������֐�)
 * �ȉ��A���2�����[�v
 * 
 * ---�ȉ��͔C��---
 * report[owner] = false / LOGIN_ID�ƈ�v���ׂ�column��
 * report[search] = Array(  �J������ => Array( �����p�����[�^ ) / �w��J��������POST�p�����[�^���n����Ă���ƍi�荞��
 * report[pre]
 *

 //sample
 $report['case_label'] = '��ƃ��[�U�[ �ꗗ';
 $report['table_name'] = 'cUser';

 $report['head_name'][] = 'No';
 $report['colum_name'][] = 'f:no';

 $report['head_name'][] = 'ID';
 $report['colum_name'][] = 'id';

 $report['head_name'][] = '���O';
 $report['colum_name'][] = 'name';

 $head_name[$nameme][] = '�o�^����';
 $report['colum_name'][] = 'f:date:regist';

 $reports["cUser_list"] = $report;

 */

class mod_report extends command_base
{
	var $TIME_FORMAT = 'Y m/d H:i:s';
	var $report_list = Array();
	var $function_list = Array();

	//���̍s���Z�߂ďo�͂��鎞�͂����ɍs���������ꍞ��
	var $next = Array();
	
	//�S�̂��O�ɏo�͂��������e��������
	var $prefix = "";
	
	//�S�̂���ɏo�͂��������e�������ɐݒ肷��
	var $suffix = "";

	//�R���X�g���N�^
	function mod_report(){
		$reports = Array();
		
		//���Ǘ���

		//�X�ܒP��
		$report['case_label'] = '�L����P�� ���ʃ��|�[�g';
		$report['file_name'] = '�L����P�ʐ��ʃ��|�[�g.csv';
		$report['table_name'] = 'cUser';
		$report['dm'] = false;
		$report['owner'] = false;
		$report['pre'] = Array('draw_time','draw_search');
		
		$report['head_name'][] = 'No';
		$report['colum_name'][] = 'f:no';

		$report['head_name'][] = 'ID';
		$report['colum_name'][] = 'id';

		$report['head_name'][] = '���O';
		$report['colum_name'][] = 'name';

		$report['head_name'][] = '�X�֔ԍ�';
		$report['colum_name'][] = 'in:(:zip1:):(:s:-:):(:zip2:)';

		$report['head_name'][] = '�s���{��';
		$report['colum_name'][] = 'r:prefectures:adds:id:name';
		$report['head_name'][] = '�Z��';
		$report['colum_name'][] = 'add_sub';
		
		$report['head_name'][] = '�d�b�ԍ�';
		$report['colum_name'][] = 'tel';
		
		$report['head_name'][] = '���[���A�h���X';
		$report['colum_name'][] = 'mail';
		
		$report['head_name'][] = '�L���A�N�Z�X��';
		$report['colum_name'][] = 'f:count_time:access:id:cuser';
		
		$report['head_name'][] = '�N���b�N�L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:click_pay:id:cuser';
		$report['head_name'][] = '�N���b�N�L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:click_pay:id:cuser:cost';
		
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:pay:id:cuser';
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:pay:id:cuser:cost';
		
		$report['head_name'][] = '�p���L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:continue_pay:id:cuser';
		$report['head_name'][] = '�p���L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:continue_pay:id:cuser:cost';
		
		$report['head_name'][] = '�L�����ʑ��z';
		$report['colum_name'][] = 'in:(:calc:(:f:sum_time:click_pay:id:cuser:cost:):(:s:+:):(:f:sum_time:pay:id:cuser:cost:):(:s:+:):(:f:sum_time:continue_pay:id:cuser:cost:):)';
		
		$report['head_name'][] = '�e�B�A���z';
		$report['colum_name'][] = 'f:sum_time:tier:id:cuser:cost';
		
		$reports["cUser"] = $report;
		
		//�L���P��
		$report = Array();
		$report['case_label'] = '�L���P�ʁ@���ʃ��|�[�g';
		$report['file_name'] = '�L���P�ʐ��ʃ��|�[�g.csv';
		$report['table_name'] = 'adwares';

		if( class_exists( 'mod_cUser' ) ) //cUser���W���[�����L���ȏꍇ
			{ $report['search'] = Array( 'cuser' => Array('match','like' ) ); }
		else //cUser���W���[���������̏ꍇ�B
			{ $report['search'] = Array(); }

		$report['dm'] = false;
		$report['owner'] = false;
		$report['pre'] = Array('draw_time','draw_search');
		
		$report['head_name'][] = 'No';
		$report['colum_name'][] = 'f:no';

		$report['head_name'][] = '�L��ID';
		$report['colum_name'][] = 'id';

		$report['head_name'][] = '�L����';
		$report['colum_name'][] = 'name';

		if( class_exists( 'mod_cUser' ) ) //cUser���W���[�����L���ȏꍇ
		{
			$report['head_name'][] = '�L����ID';
			$report['colum_name'][] = 'cuser';

			$report['head_name'][] = '��Ж�';
			$report['colum_name'][] = 'r:cUser:cuser:id:name';
		}
		
		$report['head_name'][] = '�L���A�N�Z�X��';
		$report['colum_name'][] = 'f:count_time:access:id:adwares';
		
		$report['head_name'][] = '�N���b�N�L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:click_pay:id:adwares';
		$report['head_name'][] = '�N���b�N�L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:click_pay:id:adwares:cost';
		
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:pay:id:adwares';
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:pay:id:adwares:cost';
		
		$report['head_name'][] = '�p���L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:continue_pay:id:adwares';
		$report['head_name'][] = '�p���L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:continue_pay:id:adwares:cost';
		
		$report['head_name'][] = '�L�����ʑ��z';
		$report['colum_name'][] = 'in:(:calc:(:f:sum_time:click_pay:id:adwares:cost:):(:s:+:):(:f:sum_time:pay:id:adwares:cost:):(:s:+:):(:f:sum_time:continue_pay:id:adwares:cost:):)';
		
		$report['head_name'][] = '�e�B�A���z';
		$report['colum_name'][] = 'f:sum_time:tier:id:adwares:cost';
		
		$reports["adwares"] = $report;
		
		$this->report_list['admin']['report'] = $reports;
		$reports = Array();
		
		//���L����
		
		//�L���P��
		$report = Array();
		$report['case_label'] = '�L���P�ʁ@���ʃ��|�[�g';
		$report['file_name'] = '�L���P�ʐ��ʃ��|�[�g.csv';
		$report['table_name'] = 'adwares';
		$report['dm'] = false;
		$report['owner'] = 'cuser';
		$report['pre'] = Array('draw_time','draw_search');
		
		$report['head_name'][] = 'No';
		$report['colum_name'][] = 'f:no';

		$report['head_name'][] = '�L��ID';
		$report['colum_name'][] = 'id';

		$report['head_name'][] = '�L����';
		$report['colum_name'][] = 'name';
		
		$report['head_name'][] = '�L���A�N�Z�X��';
		$report['colum_name'][] = 'f:count_time:access:id:adwares';
		
		$report['head_name'][] = '�N���b�N�L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:click_pay:id:adwares';
		$report['head_name'][] = '�N���b�N�L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:click_pay:id:adwares:cost';
		
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:pay:id:adwares';
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:pay:id:adwares:cost';
		
		$report['head_name'][] = '�p���L�����ʐ�';
		$report['colum_name'][] = 'f:count_time:continue_pay:id:adwares';
		$report['head_name'][] = '�p���L�����ʊz';
		$report['colum_name'][] = 'f:sum_time:continue_pay:id:adwares:cost';
		
		$report['head_name'][] = '�L�����ʑ��z';
		$report['colum_name'][] = 'in:(:calc:(:f:sum_time:click_pay:id:adwares:cost:):(:s:+:):(:f:sum_time:pay:id:adwares:cost:):(:s:+:):(:f:sum_time:continue_pay:id:adwares:cost:):)';
		
		$report['head_name'][] = '�e�B�A���z';
		$report['colum_name'][] = 'f:sum_time:tier:id:adwares:cost';
		
		$reports["adwares"] = $report;
		
		$this->report_list['cUser']['report'] = $reports;
		$reports = Array();
		
		//���A�t�B���G�C�^�[
		
		$report['owner'] = false;
		$report['head_name']   = Array();
		$report['colum_name']  = Array();
		
		$report['head_name'][] = 'No';
		$report['colum_name'][] = 'f:no';

		$report['head_name'][] = '�L��ID';
		$report['colum_name'][] = 'id';

		$report['head_name'][] = '�L����';
		$report['colum_name'][] = 'name';
		
		$report['head_name'][] = '�L���A�N�Z�X��';
		$report['colum_name'][] = 'in:(:f:count_time:access:id:adwares:owner:(:s:=:):(:g:LOGIN_ID:):)';
		
		$report['head_name'][] = '�N���b�N�L�����ʐ�';
		$report['colum_name'][] = 'in:(:f:count_time:click_pay:id:adwares:owner:(:s:=:):(:g:LOGIN_ID:):)';
		$report['head_name'][] = '�N���b�N�L�����ʊz';
		$report['colum_name'][] = 'in:(:f:sum_time:click_pay:id:adwares:cost:owner:(:s:=:):(:g:LOGIN_ID:):)';
		
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʐ�';
		$report['colum_name'][] = 'in:(:f:count_time:pay:id:adwares:owner:(:s:=:):(:g:LOGIN_ID:):)';
		$report['head_name'][] = '�A�t�B���G�C�g�L�����ʊz';
		$report['colum_name'][] = 'in:(:f:sum_time:pay:id:adwares:cost:owner:(:s:=:):(:g:LOGIN_ID:):)';
		
		$report['head_name'][] = '�p���L�����ʐ�';
		$report['colum_name'][] = 'in:(:f:count_time:continue_pay:id:adwares:owner:(:s:=:):(:g:LOGIN_ID:):)';
		$report['head_name'][] = '�p���L�����ʊz';
		$report['colum_name'][] = 'in:(:f:sum_time:continue_pay:id:adwares:cost:owner:(:s:=:):(:g:LOGIN_ID:):)';
		
		$report['head_name'][] = '�L�����ʑ��z';
		$report['colum_name'][] = 'in:(:calc:'.
									'(:f:sum_time:click_pay:id:adwares:cost:owner:(:s:=:):(:g:LOGIN_ID:):):(:s:+:):'.
									'(:f:sum_time:pay:id:adwares:cost:owner:(:s:=:):(:g:LOGIN_ID:):):(:s:+:):'.
									'(:f:sum_time:continue_pay:id:adwares:cost:owner:(:s:=:):(:g:LOGIN_ID:):):)';
		
		$report['head_name'][] = '�e�B�A���z';
		$report['colum_name'][] = 'in:(:f:sum_time:tier:id:adwares:cost:tier:(:s:=:):(:g:LOGIN_ID:):)';
		
		
		$reports["adwares"] = $report;
		$this->report_list['nUser']['report'] = $reports;
		
		
		//tdb����̃R�s�[��������table
        $this->report_list['admin']['table'] = Array( 
        	'nUser' => Array('label' => '��ʃ��[�U�['),
			'access' => Array('label' => '�N���b�N�Ǘ�'),
			'click_pay' => Array('label' => '�N���b�N��V�Ǘ�'),
			'pay' => Array('label' => '������V�Ǘ�'),
			'returnss' => Array('label' => '�����Ǘ�'),
			'adwares' => Array('label' => '�L���Ǘ�')
        );
        
		$this->function_list['s'] = 'string';
		$this->function_list['r'] = 'relational';
		$this->function_list['n'] = 'nextRelational';
		$this->function_list['rep'] = 'repeat';
		$this->function_list['nrep'] = 'nextRepeat';
		$this->function_list['rm'] = 'mrelational';
		$this->function_list['cnt'] = 'count';
		$this->function_list['sum'] = 'sum';
		$this->function_list['ar'] = 'arrayReplace';
		$this->function_list['br'] = 'boolReplace';
		$this->function_list['cct'] = 'concatenation';
		$this->function_list['in'] = 'innerFunction';
		$this->function_list['calc'] = 'calculate';
		$this->function_list['g'] = 'globals';
		
	}

	/***************	 ���[�U�[��`���\�b�h	******************/
	
	//count��relational����table��timestamp�ɂ��͈͌������������́B
	function count_time( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);
		$table = $rdb->getTable();
	
		$args = &$_POST;
		$a = 'registA';		$b = 'registB';
		$y_a = 'yearA';		$m_a = 'monthA';		$d_a = 'dayA';
		$y_b = 'yearB';		$m_b = 'monthB';		$d_b = 'dayB';
		if( strlen($args[$a])){
			$table = $rdb->searchTable( $table, 'regist', '>', $args[$a] );
		}else if( strlen($args[$y_a]) || strlen($args[$m_a]) || strlen($args[$d_a]) ){
			$time = mktime(0,0,0,
			strlen($args[$m_a]) ? $args[$m_a]-0 : 1,
			strlen($args[$d_a]) ? $args[$d_a]-0 : 1,
			strlen($args[$y_a]) ? $args[$y_a]-0 : 1970
			);
			$table = $rdb->searchTable( $table, 'regist', '>', $time );
			$args[$a] = $time;
		}

		if( strlen($args[$b])){
			$table = $rdb->searchTable( $table, 'regist', '<', $args[$b] );
		}else if( strlen($args[$y_b]) || strlen($args[$m_b]) || strlen($args[$d_b]) ){
			$time = mktime(0,0,0,
			strlen($args[$m_b]) ? $args[$m_b]-0 : 12,
			strlen($args[$d_b]) ? $args[$d_b]+1 : 31,
			strlen($args[$y_b]) ? $args[$y_b]-0 : 2069
			);
			$table = $rdb->searchTable( $table, 'regist', '<', $time );
			$args[$b] = $time;
		}
		
		$table = $rdb->searchTable( $table , $arg[2] , '=' , $db->getData( $rec , $arg[1] ) );
	
		for( $i=0 ; isset( $arg[3+$i] ) ; $i+=3 ){
			$table = $rdb->searchTable( $table , $arg[3+$i] , $arg[4+$i] , $arg[5+$i] );
		}
		
		return (string)$rdb->getRow( $table );
	}
	
	//count��relational����table��timestamp�ɂ��͈͌������������́B
	function sum_time( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);
		$table = $rdb->getTable();
		
		$args = &$_POST;
		$a = 'registA';		$b = 'registB';
		$y_a = 'yearA';		$m_a = 'monthA';		$d_a = 'dayA';
		$y_b = 'yearB';		$m_b = 'monthB';		$d_b = 'dayB';
		if( strlen($args[$a])){
			$table = $rdb->searchTable( $table, 'regist', '>', $args[$a] );
		}else if( strlen($args[$y_a]) || strlen($args[$m_a]) || strlen($args[$d_a]) ){
			$time = mktime(0,0,0,
			strlen($args[$m_a]) ? $args[$m_a]-0 : 1,
			strlen($args[$d_a]) ? $args[$d_a]-0 : 1,
			strlen($args[$y_a]) ? $args[$y_a]-0 : 1970
			);
			$table = $rdb->searchTable( $table, 'regist', '>', $time );
			$args[$a] = $time;
		}

		if( strlen($args[$b])){
			$table = $rdb->searchTable( $table, 'regist', '<', $args[$b] );
		}else if( strlen($args[$y_b]) || strlen($args[$m_b]) || strlen($args[$d_b]) ){
			$time = mktime(0,0,0,
			strlen($args[$m_b]) ? $args[$m_b]-0 : 12,
			strlen($args[$d_b]) ? $args[$d_b]+1 : 31,
			strlen($args[$y_b]) ? $args[$y_b]-0 : 2069
			);
			$table = $rdb->searchTable( $table, 'regist', '<', $time );
			$args[$b] = $time;
		}
		
		
		$table = $rdb->searchTable( $table , $arg[2] , '=' , $db->getData( $rec , $arg[1] ) );
		
		for( $i=0 ; isset( $arg[4+$i] ) ; $i+=3 ){
			$table = $rdb->searchTable( $table , $arg[4+$i] , $arg[5+$i] , $arg[6+$i] );
		}
		
		return (string)$rdb->getSum( $arg[3], $table );
	}
	
	
	function draw_time(){
		$this->prefix .= '�������F'.date('Y m/d').',';
	}
	
	function draw_search(){
		$times = "�`";
		$args = &$_POST;
		$a = 'registA';		$b = 'registB';
		$y_a = 'yearA';		$m_a = 'monthA';		$d_a = 'dayA';
		$y_b = 'yearB';		$m_b = 'monthB';		$d_b = 'dayB';
		
		if( strlen($args[$a]) ){
			$times = date('Y m/d',$args[$a]).'�`';
		}else if( strlen($args[$y_b]) || strlen($args[$m_b]) || strlen($args[$d_b]) ){
			$time = mktime(0,0,0,
			strlen($args[$m_b]) ? $args[$m_b]-0 : 12,
			strlen($args[$d_b]) ? $args[$d_b]+1 : 31,
			strlen($args[$y_b]) ? $args[$y_b]-0 : 2069
			);
			$times = date('Y m/d',$time).'�`';
		}
		
		if( strlen($args[$b]) ){
			$times .= date('Y m/d',$args[$b] - 1);
		}else if( strlen($args[$y_b]) || strlen($args[$m_b]) || strlen($args[$d_b]) ){
			$time = mktime(0,0,0,
			strlen($args[$m_b]) ? $args[$m_b]-0 : 12,
			strlen($args[$d_b]) ? $args[$d_b]+1 : 31,
			strlen($args[$y_b]) ? $args[$y_b]-0 : 2069
			);
			$times .= date('Y m/d',$time);
		}
		
		if( $times != "�`" ){
			$this->prefix .= '���Ԃɂ��i�荞�݁F'.$times.',';
		}
		if( strlen($args['cuser'])){
			$this->prefix .= '�L����ɂ��i�荞�݁F'.$args['cuser'].',';
		}
	}
	
	/************	 Report���W���[����`���\�b�h	**************/

	function date(&$db , $rec , &$arg ){
		if( isset($arg[0]) && strlen($arg[0]) ){
			$colum = $arg[0];
		}else{
			$colum = 'regist';
		}
		return date( $this->TIME_FORMAT , $db->getData( $rec , $colum ) );
	}

	function no( &$db , $rec , &$arg ){
		return ++$arg[0];
	}
	function no_init( $arg ){
		$arg[0]=0;
		return $arg;
	}

	//�J������Ԃ�
	function nomal( &$db , $rec , $colum ){
		return $db->getData( $rec , $colum);
	}

	function concatenation( &$db , $rec , $arg ){
		$str = "";
		foreach( $arg as $colum ){
			$str .= $db->getData( $rec , $colum);
		}
		return $str;
	}

	function relational( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);

		$rTable = $rdb->getTable();
		$rTable = $rdb->searchTable( $rTable , $arg[ 2 ] , '=' , $db->getData( $rec , $arg[ 1 ] ) );

		if( !$rdb->getRow( $rTable ) )
			return '';

		$rrec = $rdb->getRecord( $rTable , 0 );

		$discrimination = array_slice( $arg, 3);
		$func = $this->checkFunction($discrimination);
		$ret = $this->{$func['f']}( $rdb, $rrec, $func['v'] );
		 
		return $ret ? $ret : '';
	}

	function nextRelational( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);
		
		$table = $rdb->searchTable( $rdb->getTable() , $arg[2] , '=' , $db->getData( $rec , $arg[1] ) );
		$rrec = $rdb->getRecord( $table, 0 );
		
		$discrimination = array_slice( $arg, 4);
		$func = $this->checkFunction($discrimination);
		$ret = $this->{$func['f']}( $rdb, $rrec, $func['v'] );
		
		$row = $rdb->getRow( $table );
		if( $row > 1 ){
			for( $i=1; $i<$row; $i++ ){
				$rrec = $rdb->getRecord( $table, $i );
				
				if(!isset($this->next[$i-1])){$this->next[$i-1]=Array();}
				$this->next[$i-1] = array_pad( $this->next[$i-1],$arg[3],'' );
				 
				$this->next[$i-1][] = $this->{$func['f']}( $rdb, $rrec, $func['v'] );
			}
		}
		
		return $ret ? $ret : '';
	}
	
	//multiple relational
	function mrelational( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);
		 
		$datas = $db->getData( $rec , $arg[1] );
		$datas_array = explode( '/' , $datas );
		$str = '';
		foreach( $datas_array as $data ){

			$str .= $arg[0]->getData(
			$rdb->getRecord(
			$rdb->searchTable( $rdb->getTable() , $arg[2] , '=' , $data )
			, 0 )
			, $arg[3] ).'/';

		}
		return substr($str , 0 , -1);
	}

	//relational�̋L�@�Ŏw�肳�ꂽ�e�[�u���E�J�����Ɉ�v���錋�ʂ̎w�肵���J�������J��Ԃ��o�͂���
	function repeat( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);
		$columns = array_slice($arg,3);

		$table = $rdb->searchTable( $rdb->getTable() , $arg[2] , '=' , $db->getData( $rec , $arg[1] ) );

		$row = $rdb->getRow( $table );
		if( !$row ){return;}

		$ret = Array();

		for($i;$i<$row;$i++){
			$rec = $rdb->getRecord( $table, $i );
			foreach( $columns as $col ){
				$ret[] = $rdb->getData($rec,$col);
			}
		}
		return $ret;
	}

	//relational�̋L�@�Ŏw�肳�ꂽ�e�[�u���E�J�����Ɉ�v���錋�ʂ̐���Ԃ�
	function count( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);
		 
		return (string)$rdb->getRow(
		$rdb->searchTable(
		$rdb->getTable() , $arg[2] , '=' , $db->getData( $rec , $arg[1] )
		) );
	}

	//relational�̋L�@�Ŏw�肳�ꂽ�e�[�u���E�J�����Ɉ�v���錋�ʂ̍��v��Ԃ�
	function sum( &$db , $rec , $arg ){
		$rdb = GMList::getDB($arg[0]);
		 
		return (string)$rdb->getSum(
		$arg[3],
		$rdb->searchTable(
		$rdb->getTable() , $arg[2] , '=' , $db->getData( $rec , $arg[1] )
		) );
	}

	//cc��arrayValueReplace�ɋ߂�
	function arrayReplace( &$db , $rec , $arg ){
		$datas = $db->getData( $rec , $arg[0] );
		$data_array = explode( '/' , $datas );

		$keys = array_flip( explode( '/' , $arg[1] ));
		$vals = explode( '/' , $arg[2] );

		$ret = Array();;
		foreach( $data_array as $data ){
			$ret[] = $vals[ $keys[$data] ];
		}
		return implode('/',$ret);
	}

	//bool�l�����Ĉ���1������2���o��
	function boolReplace( &$db , $rec , $arg ){
		if($db->getData( $rec , $arg[0] )){ return $arg[1]; }
		else{return $arg[2];}
	}

	//�v�Z
	function calculate( &$db , $rec , $arg ){
		$expression = join($arg,'');
		eval('$ret = '.$expression.';');
		return $ret;
	}
	
	//in���̘A���\�����ŕ���������̂܂ܓf������
	function string( &$db , $rec , $arg ){
		return $arg[0];
	}
	
	function globals( &$db , $rec , $arg ){
		$ret = "";
		eval( 'global $'.$arg[0].'; $ret = $'.$arg[0].';' );
		return $ret;
	}

	/////////////////////mod tag


	function draw_case_list( &$gm, $rec, $args ){
		global $loginUserType;
		global $loginUserRank;

		$html = Template::getTemplateString( $gm, null, 'report', $loginUserRank, '', 'REPORT_PARTS' , false, null, 'case_list_head' );

		foreach( $this->report_list[$loginUserType]['report'] as $name  => $report ){
			$gm->setVariable( 'name' , $report['case_label'] );
			$gm->setVariable( 'id' , $name );

			if($report['dm'])
			$html .= Template::getTemplateString( $gm, null, 'report', $loginUserRank, '', 'REPORT_PARTS' , false, null, 'case_list_dm');
			else
			$html .= Template::getTemplateString( $gm, null, 'report', $loginUserRank, '', 'REPORT_PARTS' , false, null, 'case_list');

		}
		$html .= Template::getTemplateString( $gm, null, 'report', $loginUserRank, '', 'REPORT_PARTS' , false, null,'case_list_foot');

		$this->addBuffer( $html );
	}

	function draw_type_list( &$gm, $rec, $args ){
		global $loginUserType;
		global $loginUserRank;

		$html = Template::getTemplateString( $gm, null, 'report', $loginUserRank, '', 'REPORT_PARTS' , false, null, 'type_list_head');

		foreach( $this->report_list[$loginUserType]['table'] as $table_name  => $table_data ){
			$gm->setVariable( 'name' , $table_data['label'] );
			$gm->setVariable( 'id' , $table_name );
			 
			$html .= Template::getTemplateString( $gm, null, 'report', $loginUserRank, '', 'REPORT_PARTS' , false, null, 'type_list');
			 
		}
		$html .= Template::getTemplateString( $gm, null, 'report', $loginUserRank, '', 'REPORT_PARTS' , false, null,'type_list_foot');

		$this->addBuffer( $html );
	}

	function user_type_check($user_type){
		return isset($this->report_list[$user_type]);
	}

	//������$&begin�Ƃ��������ŕ������Čďo�����s�Ȃ��B
	//�����֐��̖����ɂ�&end����������B
	function innerFunction( &$db , $rec , $arg ){
		//���Ă����[��[�D�L�������]�܂����Ȃ��̂ŁAreport.php���̌ďo���̍\�������C�������B
		$_BEGIN = '(';
		$_BEGIN_BUF = ':begin_buf:';
		$_END = ')';
		
		$begin_stack = Array();
		while( ( $end = array_search( $_END, $arg ) ) !== FALSE ){
			while( ( $begin = array_search( $_BEGIN, $arg ) ) !== FALSE ){
				if($begin>$end){ break; }
				array_push( $begin_stack, $begin );
				$arg[ $begin ] = $_BEGIN_BUF;
			}
			$begin = array_pop($begin_stack);
			
			$discrimination = array_slice( $arg, $begin+1,$end-$begin-1);
			
			$func = $this->checkFunction($discrimination);
			
			$ret = (array)$this->{$func['f']}( $db , $rec , $func['v'] );
			
			array_splice( $arg, $begin, $end-$begin+1, $ret );
		}
		return join($arg,'');
	}

	function checkFunction( $discrimination ){
		if( $discrimination[0] == 'f'){
			$valiable = array_slice($discrimination,2);
			$function = $discrimination[1];
			
			if( method_exists($this,$function.'_init') ){
				$valiable = $this->{$function.'_init'}($valiable);
			}
		}else if( isset( $this->function_list[$discrimination[0]] ) ){
			$valiable = array_slice($discrimination,1);
			$function = $this->function_list[$discrimination[0]];
				
			if( method_exists($this,$function.'_init') ){
				$valiable = $this->{$function.'_init'}($valiable);
			}
		}else{
			$valiable = $discrimination[0];
			$function = 'nomal';
		}
		return Array( 'v' => $valiable, 'f' => $function );
	}
}

?>