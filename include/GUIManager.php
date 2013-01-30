<?php

	if( $SQL ){
		include_once "./include/extends/".$SQL_MASTER.".php";
	}else{
		include_once "./include/extends/SQLiteDatabase.php";
	}

	/*******************************************************************************************************
	 * <PRE>
	 * 
	 * GUI�}�l�[�W���N���X�B
	 * 
	 * @author �O�H��q
	 * @version 3.0.0
	 * 
	 * </PRE>
	 *******************************************************************************************************/

	class GUIManager
	{
		var $colName;								// �J������
		var $colType;								// �J�����̌^
		var $colRegist;								// Regist���ɓK�p����o���f�[�^�֐��̗�
		var $colEdit;								// Edit���ɓK�p����o���f�[�^�֐��̗�
		var $colRegex;								// ���K�\���ɂ��`�F�b�N
		var $colStep;								// ���i�K�o�^�̃X�e�b�v
		var $maxStep;								// �ő�X�e�b�v
		var $db;									// Database �I�u�W�F�N�g
		var $design_tmp;							// �f�U�C���t�@�C���e���|����
		var $table_type;							// �e�[�u���^�C�va(all)/n(nomal)/d(delete)
		var $timeFormat = "Y/m/d";					// timestamp�o�̓t�H�[�}�b�g
			//"Y/m/d(D) G:i:s"
		
		/**
		* �t�H�[���o�͕��@���w��
		* �E�����I�ɏo��(�W��)
		*   buffer
		*   b
		* �E�o�͉ӏ���CC�Ŏw�� <!--# variable form_begin #-->,<!--# variable form_end #-->
		*   variable
		*   v
		*/
		var $form_flg = 'v';
		
		/**
		 * �R���X�g���N�^(SQLDatabase)�B
		 * @param a DB��
		 * @param b �e�[�u���̖��O
		 * @param c �e�[�u����`�t�@�C��
		 */
		function GUIManager($db_name, $table_name )
		{
			global $FORM_TAG_DRAW_FLAG;
			global $LST;
			global $ADD_LST;
			
			$LST_CLM_NAME		= 0;
			$LST_CLM_TYPE		= 1;
			$LST_CLM_SIZE		= 2;
			$LST_CLM_REGIST		= 3;
			$LST_CLM_EDIT		= 4;
			$LST_CLM_REGEX		= 5;
			$LST_CLM_STEP		= 6;
			
			$lst_file = $LST[ $table_name ];
			
			if( !is_file( $lst_file ) )	{ throw new FileIOException(  'DB��`�t�@�C�����J���܂���B->'. $lst_file  ); }
			
			$fps[0] = fopen ($lst_file, 'r');
            if($fps[0] ==  FALSE ){ throw new FileIOException('DB��`�t�@�C���̃I�[�v���Ɏ��s���܂����B->'. $db_name); }
		
            if( isset($ADD_LST[$table_name]) && is_array($ADD_LST[$table_name]) && count($ADD_LST[$table_name]) ){
            	foreach( $ADD_LST[$table_name] as $add ){
					$fp = fopen ($add, 'r');
            		if($fp !=  FALSE ){ $fps[] = $fp; }	
            	}
            }
            foreach( $fps as $fp ){
				while(!feof($fp))
				{
					if( function_exists( 'fgetcsv' ) )
						{ $tmp = fgetcsv( $fp , 20480 , ',' , '"' ); }
					else
					{
						$buffer	 = fgets( $fp , 20480 );
						$tmp	 = explode( "," , $buffer );
					}

					if(count($tmp) == 1)	{ continue; }
					else
					{
						$this->colName[]						 			= trim( $tmp[$LST_CLM_NAME] );
						$this->colType[  trim( $tmp[$LST_CLM_NAME] )  ]		= trim( $tmp[$LST_CLM_TYPE] );
						$this->colSize[ trim( $tmp[$LST_CLM_NAME] ) ]		= trim( $tmp[$LST_CLM_SIZE] );
						
						if(  isset( $tmp[$LST_CLM_REGIST] )  )	{ $this->colRegist[  trim( $tmp[$LST_CLM_NAME] )  ]	 = trim( $tmp[$LST_CLM_REGIST] ); }
						else						{ $this->colRegist[  trim( $tmp[$LST_CLM_NAME] )  ]	 = ""; }
						
						if(  isset( $tmp[$LST_CLM_EDIT] )  )	{ $this->colEdit[  trim( $tmp[$LST_CLM_NAME] )  ]	 = trim( $tmp[$LST_CLM_EDIT] ); }
						else						{ $this->colEdit[  trim( $tmp[$LST_CLM_NAME] )  ]	 = ""; }
						
						if(  isset( $tmp[$LST_CLM_REGEX] )  )
						{
							$tmp[$LST_CLM_REGEX] = str_replace( '<>' , ',' , $tmp[$LST_CLM_REGEX] );
							$tmp[$LST_CLM_REGEX] = str_replace( '\\<' , '<' , $tmp[$LST_CLM_REGEX] );
							$tmp[$LST_CLM_REGEX] = str_replace( '\\>' , '>' , $tmp[$LST_CLM_REGEX] );
							$this->colRegex[  trim( $tmp[$LST_CLM_NAME] )  ]	 = trim( $tmp[$LST_CLM_REGEX] );
						}
						else{ $this->colRegex[  trim( $tmp[$LST_CLM_NAME] )  ]	 = ""; }
	
						if( isset( $tmp[$LST_CLM_STEP] ) )	{ $this->colStep[  trim( $tmp[$LST_CLM_NAME] )  ]	 = trim( $tmp[$LST_CLM_STEP] ); }
						else					{ $this->colStep[  trim( $tmp[$LST_CLM_NAME] )  ]	 = 0; }
	
						if($this->maxStep < $this->colStep[  trim( $tmp[$LST_CLM_NAME] )  ])
							$this->maxStep = $this->colStep[  trim( $tmp[$LST_CLM_NAME] )  ];
					}
				}
				fclose($fp);
            }
			
			$this->db = new SQLDatabase($db_name, $table_name, $this->colName, $this->colType, $this->colSize);
			
			$this->form_flg = $FORM_TAG_DRAW_FLAG; 
		}
		
		/**
		 * �f�[�^�x�[�X���擾�B
		 * @return �f�[�^�x�[�X
		 */
		function getDB()	{	$this->db->cashReset();	return $this->db; }
		
		/**
		 * �f�[�^�x�[�X���擾�B
		 * 
		 * ���̊֐�����擾�����ꍇ�ADB�I�u�W�F�N�g�̃L���b�V���̓��Z�b�g����܂���B
		 * �f�[�^���Q�Ƃ��郌�R�[�h�����Ɏ擾�ł��Ă���ꍇ�ȂǂɎg�p���Ă��������B
		 * @return �f�[�^�x�[�X
		 */
		function getCachedDB()	{ return $this->db; }

		/**
		 * ���R�[�h�̓��e��POST�œ������Ă������Ƃɂ��܂��B
		 * @param $rec ���R�[�h�f�[�^
		 */
		function setForm($rec)
		{
			for($i=0; $i<count($this->colName); $i++)
			{
				$data	 = $this->db->getData( $rec, $this->colName[$i] );

				if(  isset( $data )  ){
					if( $this->colType[ $this->colName[$i] ] == 'boolean' )
					{
						if($data)	{ $_POST[ $this->colName[$i] ] = 'TRUE'; }
						else		{ $_POST[ $this->colName[$i] ] = 'FALSE'; }
					}
					else
					{
						$_POST[ $this->colName[$i] ] = ($data);
					}
				}
			}
		}
		
		/**
		 * GET�̓��e��POST�œ������Ă������Ƃɂ��܂��B
		 * @param $rec ���R�[�h�f�[�^
		 */
		function setFormGET($rec)
		{
			for($i=0; $i<count($this->colName); $i++)
			{
				$data	 = $this->db->getData( $rec, $this->colName[$i] );
				if( is_bool($data) )
				{
					if($data)	{ $_POST[ $this->colName[$i] ] = 'TRUE'; }
					else		{ $_POST[ $this->colName[$i] ] = 'FALSE'; }
				}
				else
				{
					if(  isset( $data )  ){ $_POST[ $this->colName[$i] ] = $data; }
				}
			}
		}
		
		var $aliasDB;
		
		/**
		 * �G�C���A�X�ŗp����GUIManager��ǉ��B
		 * $name �Ƃ������O��table��alias�\���p�e�[�u���Ƃ��Đ����A�L������B
		 * �R�}���h�R�����g�̃G�C���A�X�R�}���h�ɂ��`�悪�v�����ꂽ�ۂɂ�
		 * ����GUIManager��p���ĕ`�揈�����s���B
		 * @param $name ���O
		 * @param $gm GUIManager �I�u�W�F�N�g
		 */
		function addAlias($name)	{	global $gm;		$this->aliasDB[$name]	 = $gm[ $name ]->db; }
		
		/**
		 * timestamp�^�J�����̏o�͏����ݒ�B
		 * �R�}���h�R�����g �l�̏o�͂�
		 * timestamp�^�̒l���o�͂��悤�Ƃ����ۂɕ`�悳��鎞�Ԃ̕\���t�H�[�}�b�g���w�肵�܂��B
		 * �w����@��PHP��date() ���\�b�h�ɏ����܂��B
		 * @param $str ���Ԃ̕\���t�H�[�}�b�g
		 */
		function setTimeFormat($str)	{ $this->timeFormat = $str; }
		
		var $variable;
		/**
		 * variable ���߂ŌĂяo���ϐ����Z�b�g���܂��B
		 * @param name �ϐ���
		 * @param value �l
		 */
		 function setVariable($name, $value){ $this->variable[$name] = $value; }
		/**
		 * variable ���߂ŌĂяo���ϐ����Q�b�g���܂��B
		 * @param name �ϐ���
		 */
		 function getVariable($name){ return $this->variable[$name]; }
		 
		/**
		 * variable �����Z�b�g����
		 */
		 function clearVariable(){ $this->variable = Array(); }
		
		/**
		 * ���R�[�h�f�[�^����s���t�H�[���𐶐����܂��B
		 * @param name �ϐ���
		 * @param value �l
		 */
		 function setHiddenFormRecord( $rec )
		 {
			for($i=0; $i<count($this->colName); $i++)	{ $this->addHiddenForm(  $this->colName[$i], $this->db->getData( $rec, $this->colName[$i] )  ); }
		 }
		/**
		 * ���R�[�h�f�[�^����s���t�H�[���𐶐����܂��B
		 * @param name �ϐ���
		 * @param value �l
		 */
		 function setHiddenFormRecordEdit( $rec )
		 {
			for($i=0; $i<count($this->colName); $i++){
                $this->addHiddenForm(  $this->colName[$i], $this->db->getData( $rec, $this->colName[$i] )  );
                if( isset($_POST[$this->colName[$i].'_DELETE']) ){
                    $this->addHiddenForm(  $this->colName[$i].'_DELETE[]', $_POST[$this->colName[$i].'_DELETE'][0]);
                }
            }
		 }
		
		/**
		 * �t�H�[����HTML��`�悵�܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $rec=null ���R�[�h�f�[�^
		 * @param $jump=null submit�Ŕ�Ԑ�
		 * @param $partkey=null �����L�[
		 */
		function drawForm( $html, $rec = null, $jump = null, $partkey = null, $form_flg = null )
		{
			print $this->getFormString( $html, $rec, $jump, $partkey, $form_flg);
		}
		
		/**
		 * �t�H�[����HTML�f�[�^���擾���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $rec=null ���R�[�h�f�[�^
		 * @param $jump=null submit�Ŕ�Ԑ�
		 * @param $partkey=null �����L�[
		 */
		function getFormString( $html, $rec = null, $jump = null, $partkey = null, $form_flg = null )
		{
			if( !isset($form_flg) ) { $form_flg = $this->form_flg; }
			switch($form_flg)
			{
			case 'variable':
			case 'v':
				return $this->getFormStringSetVariable( $html, $rec, $jump, $partkey, $form_flg );
				break;
			case 'buffer':
			case 'b':
			default:
				return $this->getFormStringSetBuffer( $html, $rec, $jump, $partkey, $form_flg );
				break;
			}
		}
		
		/**
		 * �t�H�[����HTML�f�[�^���擾���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $rec=null ���R�[�h�f�[�^
		 * @param $jump=null submit�Ŕ�Ԑ�
		 * @param $partkey=null �����L�[
		 */
		function getFormStringSetBuffer( $html, $rec = null, $jump = null, $partkey = null )
		{
			global $terminal_type;
			
			$enctype	 = "";
			if(!$terminal_type) { $enctype = 'enctype="multipart/form-data"'; }
			
			$buffer	 = "";
			if(  isset( $jump )  )	{ $buffer	 .= '<form name="sys_form" method="post" action="'. $jump .'" '. $enctype .' style="margin: 0px 0px;">'. "\n"; }
			$buffer	 .= $this->addForm;
			$this->addForm = "";
			$buffer	 .= $this->getString($html, $rec, $partkey);
			if(  isset( $jump )  )	{ $buffer	 .= '</form>'. "\n"; }
			return $buffer;
		}

		/**
		 * �t�H�[����`�悵�܂��B
         *
         *  form�^�O����variable�ɃZ�b�g���ăe���v���[�g�ɓn���܂��B
         *  header������form���g���Ă��āAgetFormString����form���l�X�g���Ă��܂����ɂ��g�����������B
         *
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $rec=null ���R�[�h�f�[�^
		 * @param $jump=null submit�Ŕ�Ԑ�
		 * @param $partkey=null �����L�[
		 */
		function getFormStringSetVariable( $html,  $rec = null, $jump = null, $partkey = null )
		{
			global $terminal_type;
			
			$enctype	 = "";
			if(!$terminal_type) { $enctype = 'enctype="multipart/form-data"'; }
			
			if(  isset( $jump )  )	{ $this->setVariable('form_begin','<form name="sys_form" method="post" action="'. $jump .'" '. $enctype .' style="margin: 0px 0px;">'.$this->addForm); }
            else{ $this->setVariable('form_begin',$this->addForm); }
            $this->addForm = "";
            
			if(  isset( $jump )  )	{ $this->setVariable('form_end','</form>'); }
            else{ $this->setVariable(''); }
            
			$buffer	 = $this->getString($html, $rec, $partkey);
			return $buffer;
		}
		
		var $addForm;
		
		/**
		 * �s���t�H�[���̒ǉ��B
		 * @param $name INPUT��
		 * @param $val INPUT�̒l
		 */
		function addHiddenForm( $name, $val )
		{
			if( is_bool($val) )
			{
				if( $val )	{ $this->addForm .= '<input name="'. $name .'" type="hidden" value="TRUE" />'. "\n"; }
				else		{ $this->addForm .= '<input name="'. $name .'" type="hidden" value="FALSE" />'. "\n"; }
			}
			else	{ $this->addForm .= '<input name="'. $name .'" type="hidden" value="'. htmlspecialchars( $val ) .'" />'. "\n"; }
		}
		
		/**
		 * HTML��`�悵�܂��B
		 * HTML�Ɋ܂܂��R�}���h�R�����g�ɂ� $rec �œn�������R�[�h�f�[�^�̓��e�𔽉f���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $rec=null ���R�[�h�f�[�^
		 * @param $partkey=null �����L�[
		 */
		function draw($html, $rec = null, $partkey = null) { print $this->getString($html, $rec, $partkey); }
		
		/**
		 * �����`������s���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $partkey �����L�[
		 */
		function partRead($html, $partkey)	{ print GUIManager::partGetString( $html, $partkey ); }
		
		/**
		 * �����f�[�^�擾�����s���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $partkey �����L�[
		 */
		function partGetString($html, $partkey)
		{
			if(  !is_file( $html )  )	{ throw new FileIOException( 'HTML�t�@�C�����J���܂���B->'. $html ); }
			
			if(  isset( $partkey )  )	{ $state = $this->getDefState( false ); }
			else						{ throw new InvalidArgumentException( "GUIManager Error -> partRead() or partGetString() -> Not Set PartKey" ); }
			
			$str	 = "";
            $c_part = null;
			
			if( !isset($this->design_tmp[$html]) ) { $this->getFile($html); }
            
            $row = count($this->design_tmp[$html]);
            for($i=0;$row>$i;$i++){
				$str .= GUIManager::commandComment( $this->design_tmp[$html][$i], $this, null, $state , $c_part , $partkey );
            }
			
			$str	 = str_replace( array("&CODE001;","&CODE101;","&CODE000;","&CODE002;","&CODE005;","&CODE006;"), array(" ", " ", "/", "\\",'!--# ',' #--') , $str );
			return $str;
		}
		
		/**
		 * �e�[�u���̓��e�����X�g��`�悵�܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $table �e�[�u���f�[�^
		 * @param $partkey=null �����L�[
		 */
		function drawList($html, $table, $partkey = null)	{ print $this->getListString( $html, $table, $partkey ); }
		
		/**
		 * �e�[�u���̓��e�̃��X�g�`�挋�ʂ�HTML���擾���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $table �e�[�u���f�[�^
		 * @param $partkey=null �����L�[
		 */
		function getListString($html, $table, $partkey = null)
		{
			$buffer	 = "";
			$this->db->cashReset();
			$row	 = $this->db->getRow( $table );
			for($i=0; $i<$row; $i++)
			{
				$rec = $this->db->getRecord( $table, $i );
				
                if ($_SESSION['login_user_data']['magni']) {
                    $rec['money'] = (int)($rec['money'] * ($_SESSION['login_user_data']['magni'] / 100));
                }
                
				$buffer	 .= $this->getString( $html, $rec, $partkey );
			}
			
			return $buffer;
		}
		/**
		 * �e�[�u���̓��e�̃��X�g�`�挋�ʂ�HTML���擾���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $table �e�[�u���f�[�^
		 * @param $partkey=null �����L�[
		 */
		function getListNumString($html, $table, $partkey = null,$start)
		{
			$buffer	 = "";
			$row	 = $this->db->getRow( $table );
			for($i=0; $i<$row; $i++)
			{
				$rec = $this->db->getRecord( $table, $i );
				$this->setVariable('num',$start+$i);
				$buffer	 .= $this->getString( $html, $rec, $partkey );
			}
			
			return $buffer;
		}
		
		/**
		 * �e�[�u���̓��e���t�H�[���`���Ń��X�g�`�悵�܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $table �e�[�u���f�[�^
		 * @param $jump=null submit�Ŕ�Ԑ�
		 * @param $partkey=null �����L�[
		 */
		function drawFormList($html, $table, $jump, $partkey = null)
		{
			$row	 = $this->db->getRow( $table );
			for($i=0; $i<$row; $i++)
			{
				$rec = $this->db->getRecord( $table, $i );
				$this->drawForm( $html, $rec, $jump, $partkey );
			}
			
		}
		
		/**
		 * HTML���L���b�V�����܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 */		
		function getFile( $html )
		{
			$fp = fopen ( $html, 'r' );
			
			$str = "";
			while(  !feof( $fp )  )
			{
				$buffer	 = fgets( $fp, 20480 );
				
				$buffer = mb_convert_encoding( $buffer,'UTF-8','SJIS');
				
				$buffer		 = str_replace( "\\\\", "&CODE002;", $buffer );
				$buffer		 = str_replace( "\/", "&CODE000;", $buffer );
				$buffer		 = str_replace( "\ ", "&CODE001;", $buffer );
				
				$buffer = mb_convert_encoding( $buffer,'SJIS','UTF-8');
				
				$str[] = $buffer;
			}
			fclose( $fp );
			$this->design_tmp[$html] = $str;
		}
		
		/**
		 * HTML���擾���܂��B
		 * HTML�Ɋ܂܂��R�}���h�R�����g�ɂ� $rec �œn�������R�[�h�f�[�^�̓��e�𔽉f���܂��B
		 * @param $html �f�U�C��HTML�t�@�C��
		 * @param $rec=null ���R�[�h�f�[�^
		 * @param $partkey=null �����L�[
		 */
		function getString($html, $rec = null, $partkey = null)
		{
			if( !is_file( $html ) )	{ throw new FileIOException( 'HTML�t�@�C�����J���܂���B->'. $html ); }
			
		    $state = $this->getDefState( $partkey == null );
			$c_part = null;
			
			if( !isset($this->design_tmp[$html]) ) { $this->getFile($html); }
			
            $row = count($this->design_tmp[$html]);
            $str = "";
            for($i=0;$row>$i;$i++){
				$str .= GUIManager::commandComment( $this->design_tmp[$html][$i], $this, $rec, $state , $c_part , $partkey );
            }
			
			$str	 = str_replace( array("&CODE001;","&CODE101;","&CODE000;","&CODE002;","&CODE005;","&CODE006;"), array(" ", " ", "/", "\\",'!--# ',' #--') , $str );
			
			return $str;
		}
		
		function getCCResult($rec, $command)
		{
			$command = str_replace( "\\\\", "&CODE002;", $command );//\\�Ƀ}�b�`
			$command = str_replace( "\/", "&CODE000;", $command );
			$command = str_replace( "\ ", "&CODE001;", $command );
            
		    $state = $this->getDefState( true );
			$str	 = trim(  GUIManager::commandComment( $command. " ", $this, $rec, $state , $c_part = null )  );
			
			$str	 = str_replace( array("&CODE001;","&CODE101;","&CODE000;","&CODE002;","&CODE005;","&CODE006;"), array(" ", " ", "/", "\\",'!--# ',' #--') , $str );

                                    $stack[ $j ]		 = str_replace( array($CC_HEAD,$CC_FOOT), array(), $stack[ $j ] );
			return $str;
		}
		
        //$gm���s�p�ӂɏ����������鎖�ɂ��V�X�e���S�̂Ɏx����������ʈׁA$gm�͎Q�Ƃœn���Ȃ��B
		function commandComment($buffer, $gm, $rec, &$state , &$current_part , $partkey = null)
		{
			if( $state['draw'] <= 0){
                if( strpos( $buffer, '!--# read' ) === false &&
                		strpos( $buffer, '!--# endif' ) === false  &&
                		strpos( $buffer, '!--# else' ) === false  &&
                		strpos( $buffer, '!--# ifbegin' ) === false ){ return ""; }
            }
			$CC_HEAD	 = '!--# ';
			$CC_FOOT	 = ' #--';
			$buffer		 = str_replace(  Array(  "#--)","#-->","(!--#","<!--#"), Array( "#--", "#--", "!--#", "!--#" ), $buffer );

			$ret			 = "";
		
			// �܂��A�R�}���h�R�����g��������Ȃ��ꍇ�͂��̂܂ܕԂ��B
			if(  strpos( $buffer, $CC_HEAD ) === false  )	{ return $buffer; }
			
			// �R�}���h�R�����g�̃w�b�_������̂ɁA�t�b�^��������Ȃ��ꍇ�͍\���G���[
			if(  strpos( $buffer, $CC_FOOT ) === false  )	{ throw new RuntimeException(   "CommandComment Syntax Error [". htmlspecialchars(  trim( $buffer )  ) ."]"   ); }

			// �\����͊J�n
			$stack		 = array();
			$zStack		 = array();
			$counter	 = 0;
			$z			 = 0;
			$zMax		 = 0;
            $head_length = strlen( $CC_HEAD );
            $foot_length = strlen( $CC_FOOT );

            
            $stack[ $counter ]="";
			// �R�}���h�R�����g���w�b�_��t�b�^�ŕ������A�K�w�\���ɂ���B
			for( $pointer=0; $pointer<strlen( $buffer )+1; $pointer++ )
			{
				if( $foot_length <= $pointer &&  substr(  $buffer, $pointer - $foot_length, $foot_length  ) == $CC_FOOT   )
				{
					$zStack[ $counter ]	 = $z;
					$counter++;
					$z--;
                    $stack[ $counter ]="";
				}
                
				if(   substr(  $buffer, $pointer, $head_length  ) == $CC_HEAD   )
				{
					$zStack[ $counter ]	 = $z;
					$counter++;
					$z++;
					if( $zMax < $z )	{ $zMax	 = $z; }
                    $stack[ $counter ]="";
				}
				$stack[ $counter ]	  .= substr( $buffer, $pointer, 1 );
			}
			$zStack[ $counter ]	 = $z;
            
            //$part_off = false;
			// �ł��K�w�̐[���Ƃ��납��R�}���h�R�����g�����s���Ă����B
			for( $i=$zMax; $i>=0; $i-- )
			{
				for( $j=0; $j<count($stack); $j++ )
				{
					if(  $zStack[ $j ] == $i  )
					{
						if( $stack[ $j ] !== "\0" && strlen( $stack[ $j ] ) > 0  )
						{
							if(   strpos(  $stack[ $j ], $CC_HEAD  ) !== false && strpos(  $stack[ $j ], $CC_FOOT  ) !== false   )
							{ $command	 = substr(  $stack[ $j ], strlen( $CC_HEAD ), strlen( $stack[$j] ) - strlen( $CC_HEAD ) - strlen( $CC_FOOT )  ); }
							else if(   strpos(  $stack[ $j ], $CC_HEAD  ) !== false   )	{ $command	 = substr(  $stack[ $j ], strlen( $CC_HEAD )  ); }
							else if(   strpos(  $stack[ $j ], $CC_FOOT  ) !== false   )	{ $command	 = substr(  $stack[ $j ], 0, strlen( $stack[$j] ) - strlen( $CC_FOOT )  ); }
							else														{ $command	 = $stack[ $j ]; }
							$cc		 = explode( " ", $command );
                            
							switch( $cc[0] ){
                                case 'ifbegin':
                                    if($state['if']){$state['draw']--;}
                                    else if(!ccProc::ifbegin( $gm, $rec, $cc )){$state['draw']--;$state['if']=true;}
                                    break;
                                case 'elseif':
                                	if($state['in']){break;}
                                    if( $state['if'] && $state['draw'] === 0 ){
                                    	if(ccProc::ifbegin( $gm, $rec, $cc )){
                                    		$state['draw']++;
                                    		$state['if']=false;
                                    		$state['in']=true;
                                    	}
                                    }else if( ! $state['if'] ){
                                    	$state['draw']--;
                                    	$state['if']=true;
                                    	$state['in']=true;
                                    } 
                                	break;
                                case 'else':
                                	if($state['in']){break;}
                                    if( $state['if'] && $state['draw'] === 0 ){
                                    	$state['draw']++;
                                    	$state['if']=false;
                                    }else if( ! $state['if'] ){
                                    	$state['draw']--;
                                    	$state['if']=true;
                                    	$state['in']=true;
                                    } 
                                	break;
                                case 'readhead':
                                    if( $partkey != null && $partkey == $cc[1] ){ $state['draw']++; }
                                    else{ $state['draw']--; }
                                    $current_part = $cc[1];
                                    break;
                            }
                            
                            if( $state['draw'] > 0 ){
								if(  strpos( $stack[ $j ], $CC_HEAD ) !== false  )	{
                                    $stack[ $j ]		 = ccProc::controller( $gm, $rec, $cc );
                                    $stack[ $j ]		 = str_replace( array("&CODE001;","&CODE000;",$CC_HEAD,$CC_FOOT), array(" ", "/", "&CODE005;","&CODE006;") ,$stack[ $j ]);
                                }
							}
                            else{ $stack[ $j ] = "";}
							if( $zStack[ $j ] != 0 )							{ $zStack[ $j ]--; }
                            
                            switch( $cc[0] ){
                                case 'endif':
                                    if($state['if']){
                                    	$state['draw']++;
                                    	if($state['draw']>0){
                                    		$state['if']=false;
                                    		$state['in']=false;
                                    	}
                                    }
                                    break;
                                case 'readend':
                                    if( $partkey != null && $partkey == $current_part ){ $state['draw']--; /*$part_off = true;*/}
                                    else{ $state['draw']++;}
                                    $current_part = null;
                                    break;
                            }
						}
					}
				}
                //1�X�^�b�N�ɂ܂Ƃ߂�
                $z	 = -1;
                for( $k=0; $k<count($stack); $k++ )
                {
                    if(   trim(  $stack[ $k ], "\n\r"  ) == "\0" )	{ continue; }
                        
                    if( $z == $zStack[ $k ] )
                    {
                        $stack[ $k - 1 ]		 .= $stack[ $k ];
                        for( $l=$k; $l<count($stack); $l++ )
                        {
                            $stack[ $l ]		 = $stack[ $l + 1 ];
                            $zStack[ $l ]		 = $zStack[ $l + 1 ];
                        }
                        $stack[ count($stack) - 1 ]		 = "\0";
                        $zStack[ count($stack) - 1 ]	 = 0;
                        $k--;
                    }
                    $z	 = $zStack[ $k ];
                }
			}

			if( $state['draw'] > 0 ){ $ret	 = $stack[ 0 ];}
			
			return $ret;
		}
		
		function ccProc(&$gm, $rec, $cc)
		{
			return ccProc::controller($gm, $rec, $cc);
		}
        
        //alias���Ŏg��getTable�ŎQ�Ƃ���table�̃^�C�v���w�肷��B(n/d/a)
        function setTableType($type){
            $this->table_type = $type;
        }
        
        /**
         * �w��J������step��Ԃ�
         * 
         * @param $column �J������
         * @return step��
         * 
         */
        function getStep( $column ){
        	return $this->colStep[ $column ];
        }
        
        /**
         * �f�[�^���󂯎��p�����[�^�Ŏw�肳�ꂽ�u�����s�Ȃ�
         * @param $str �ϊ����s�Ȃ�������
         * @param $param �ϊ��p�����[�^
         * @return �ϊ���̕�����
         */
        static function replaceString( $str, $param ){
        	$before = Array('<','>','"',"'");
        	$after = Array('��','��','�h',"�f");
        	
        	switch($param){
        		default:
        			if( strlen($param) == 1 ){
	        			$before[] = $param;
    	    			$after[]  =  mb_convert_kana( $param, A, 'shift_jis' );
        			}
        		case '':
        			//html�^�O��F�߂Ȃ�
        			$tmp = str_replace( $before, $after, $str );
        			break;
        		case 'html':
        			$tmp = $str;
        			break;
        	}
        	return $tmp;
        }

        /**
         * 
         */
        private function getDefState( $draw ){
        	return Array( 
        		'draw'=>$draw?1:0	//1�ȏ�ŕ\���A0�ȉ��͔�\���A�}�C�i�X�̏ꍇ���K�w�ɐ����Ă���
        		,'if'=>false		//ifbegin�̕���Ŕ�\���Ƃ���Ă��鎞��true
        		,'in'=>false		//ifbegin�̕���Ŋ��ɂ��̊K�w��if�ŗL���Ȍo�H��ʉ߂�������true�Aendif��false
        	);
        }
	}
	
	/*******************************************************************************************************/
	
	function uni2utf8($uniescape)
	{
		$c = "";
		
		$n = intval(substr($uniescape, -4), 16);
		if ($n < 0x7F)  { $c .= chr($n); }
		elseif ($n < 0x800) 
		{
			$c .= chr(0xC0 | ($n / 64));
			$c .= chr(0x80 | ($n % 64));
		} 
		else 
		{
			$c .= chr(0xE0 | (($n / 64) / 64));
			$c .= chr(0x80 | (($n / 64) % 64));
			$c .= chr(0x80 | ($n % 64));
		}
		return $c;
	}
	
	function escuni2sjis($escunistr)
	{
		$eucstr = "";
		
		while(eregi("(.*)(%u[0-9A-F][0-9A-F][0-9A-F][0-9A-F])(.*)$", $escunistr, $fragment)) 
		{
			$eucstr = mb_convert_encoding(uni2utf8($fragment[2]).$fragment[3], 'SHIFT-JIS', 'UTF-8').$eucstr;
			$escunistr = $fragment[1];
		}
		return $fragment[1]. $eucstr;
	}

?>