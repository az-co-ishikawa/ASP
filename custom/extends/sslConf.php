<?php
	include_once './include/extends/SSLUtil.php';

	//SSLへのリダイレクトを有効に
	$CONFIG_SSL_ENABLE = false;
	
	$CONFIG_SSL_MOBILE = false;
	
	$CONFIG_SSL_ON_CHECK_FILES = Array(
			'index.php', 'regist.php', 'edit.php', 'cart.php', 'login.php', 'reminder.php', 'link.php', 'add.php'
	);
	
	$CONFIG_SSL_OUT_CHECK_FILES = Array(
			'activate.php', 'info.php', 'other.php', 'page.php', 'report.php', 'search.php'
	);
