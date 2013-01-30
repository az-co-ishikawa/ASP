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
				
				//POSTがある場合はsessionに保存する。
				//※本来はHTTP status codeを307にする事により対処できる筈だが
				//　IE6,7,8のどれも対応していない。(将来的な対応に期待)
				if( count($_POST) ){
					SystemUtil::setDataStak( 'SSLUtil_post', true );
					SystemUtil::setDataStak( 'SSLUtil_stack', $_POST  );
					if( count($_FILES) ){
						foreach( $_FILES as $name => $file ){
							
							// 拡張子の取得
							preg_match( '/(\.\w*$)/', $file['name'], $tmp );
							$ext		 = strtolower(str_replace( ".", "", $tmp[1] ));
							
							// ファイルパスの作成
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
		
		/*		POSTがある時は解除しない		*/
		if( count( $_POST ) )
			return;
		
		if( isset( $_GET[ 'ssl_out' ] ) && 'off' == $_GET[ 'ssl_out' ] ) //SSL解除が拒否された場合
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