<?php

	include_once './include/extends/MobileUtil.php';

	//@Œg‘Ñ•ªŠò
	if( $mobile_flag ){
		$terminal_type = MobileUtil::getTerminal();
	}
    
    if($terminal_type)
	{
		switch($terminal_type){
			case MobileUtil::$TYPE_NUM_DOCOMO:
				header("Content-type: application/xhtml+xml;charset=Shift_JIS");
				include_once "./include/extends/mobile/EmojiDocomo.php";
				break;
			case MobileUtil::$TYPE_NUM_AU:
				include_once "./include/extends/mobile/EmojiAU.php";
				break;
			case MobileUtil::$TYPE_NUM_SOFTBANK:
				include_once "./include/extends/mobile/EmojiSoftbank.php";
				break;
		}

        ini_set("session.use_trans_sid", "1");
        if(ini_get("session.use_trans_sid") != "1")
		{
            output_add_rewrite_var('PHPSESSID',htmlspecialchars(SID));
        }
		
		if($mobile_flag){
			$template_path = "./template/mobile/";
			//$IMAGE_NOT_FOUND = '<img src="img/no_img_120x90.gif" />';
		}
    }

	$mobile_path = './template/mobile/';

?>