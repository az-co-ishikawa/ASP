<?php
	include_once './include/extends/SSLUtil.php';

	//SSL�ւ̃��_�C���N�g��L����
	$CONFIG_SSL_ENABLE = false;
	
	$CONFIG_SSL_MOBILE = false;
	
	$CONFIG_SSL_ON_CHECK_FILES = Array(
			'index.php', 'regist.php', 'edit.php', 'cart.php', 'login.php', 'reminder.php', 'link.php', 'add.php'
	);
	
	$CONFIG_SSL_OUT_CHECK_FILES = Array(
			'activate.php', 'info.php', 'other.php', 'page.php', 'report.php', 'search.php'
	);
