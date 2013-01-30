<?php
class SSLUtil{
	
	static function ssl_check(){
		global $CONFIG_SSL_ON_CHECK_FILES;
		global $CONFIG_SSL_OUT_CHECK_FILES;
		global $script;
		
		if( SystemUtil::getDataStak( 'SSLUtil_post' ) ){
			$_POST = SystemUtil::getDataStak( 'SSLUtil_stack' );
			$_FILES = SystemUtil::getDataStak( 'SSLUtil_file' );
			
			SystemUtil::deleteDataStak( 'SSLUtil_post' );
			SystemUtil::deleteDataStak( 'SSLUtil_stack' );
			SystemUtil::deleteDataStak( 'SSLUtil_file' );
		}
		
		if( array_search( $script, $CONFIG_SSL_ON_CHECK_FILES ) !== FALSE ){
			self::scheme_check();
		}else if( array_search( $script, $CONFIG_SSL_OUT_CHECK_FILES ) !== FALSE ){
			self::ssl_out();
		}
	}
	
	static function scheme_check(){
		global $CONFIG_SSL_ENABLE;
		global $CONFIG_SSL_MOBILE;
		global $terminal_type;
		
		if( $CONFIG_SSL_ENABLE && $_SERVER['HTTPS'] != 'on' ){
			if( $CONFIG_SSL_MOBILE || !$terminal_type ){
				
				//POST������ꍇ��session�ɕۑ�����B
				//���{����HTTP status code��307�ɂ��鎖�ɂ��Ώ��ł��锤����
				//�@IE6,7,8�̂ǂ���Ή����Ă��Ȃ��B(�����I�ȑΉ��Ɋ���)
				if( count($_POST) ){
					SystemUtil::setDataStak( 'SSLUtil_post', true );
					SystemUtil::setDataStak( 'SSLUtil_stack', $_POST  );
					if( count($_FILES) ){
						foreach( $_FILES as $name => $file ){
							
							// �g���q�̎擾
							preg_match( '/(\.\w*$)/', $file['name'], $tmp );
							$ext		 = strtolower(str_replace( ".", "", $tmp[1] ));
							
							// �t�@�C���p�X�̍쐬
							$directory	 = 'file/tmp/';
							$fileName	 = $directory.'SSLUtil_stack_'.md5( time(). $file['name'] ).'.'.$ext;
							move_uploaded_file( $file['tmp_name'], $fileName );
							
							$_FILES[ $name ]['tmp_name'] = $fileName;
						}
						SystemUtil::setDataStak( 'SSLUtil_file', $_FILES  );
					}
				}
				
				SSLUtil::sessionLocation('https://' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
				exit();
			}
		}
	}
	
	static function ssl_out(){
		global $CONFIG_SSL_ENABLE;
		global $CONFIG_SSL_MOBILE;
		global $terminal_type;
		
		/*		POST�����鎞�͉������Ȃ�		*/
		if( count( $_POST ) )
			return;
		
		if( isset( $_GET[ 'ssl_out' ] ) && 'off' == $_GET[ 'ssl_out' ] ) //SSL���������ۂ��ꂽ�ꍇ
			{ return; }

		if( $_SERVER['HTTPS'] == 'on' ){
			SSLUtil::sessionLocation('http://' . $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
			exit();
		}
	}
	
	static function change_ssl($url){
		$parses = parse_url($url);
	}

	private function sessionLocation( $url ){
		global $terminal_type;
		global $sid;
		
		if($terminal_type && strpos($url, "PHPSESSID") === false){
			if( strpos($url, "?") === false)
				header( "Location: ".$url."?".$sid );
			else
				header( "Location: ".$url."&".$sid );
		}else{
			header( "Location: ".$url );
		}
	}
}
?>