<?php
/*****************************************************************************
 *
 * �萔�錾
 *
 ****************************************************************************/
	define( "WS_PACKAGE_ID", "affiliate_ASP" );				//�Z�b�V�����L�[prefix�ȂǂɎg���A�p�b�P�[�W�����ʂ���ׂ�ID

	$NOT_LOGIN_USER_TYPE							 = 'nobody';							// ���O�C�����Ă��Ȃ���Ԃ̃��[�U��ʖ�
	$NOT_HEADER_FOOTER_USER_TYPE					 = 'nothf';								// �w�b�_�[�E�t�b�^�[��\�����Ă��Ȃ���Ԃ̃��[�U��ʖ�

	$LOGIN_KEY_FORM_NAME							 = 'mail';								// ���O�C���t�H�[���̃L�[����͂���t�H�[����
	$LOGIN_PASSWD_FORM_NAME							 = 'passwd';							// ���O�C���t�H�[���̃p�X���[�h����͂���t�H�[����

	$ADD_LOG										 = true;								// DB�̐V�K�ǉ������L�^���邩
	$UPDATE_LOG										 = true;								// DB�̍X�V�����L�^���邩
	$DELETE_LOG										 = true;								// DB�̍폜�����L�^���邩

	$SESSION_NAME									 = WS_PACKAGE_ID.'loginid';							// ���O�C�������Ǘ�����SESSION �̖��O
	$COOKIE_NAME									 = WS_PACKAGE_ID.'loginid';							// ���O�C�������Ǘ�����COOKIE �̖��O

	$SESSION_PATH_NAME								 = WS_PACKAGE_ID.'__system_path__';						// �V�X�e���̐ݒu�p�X�����Ǘ�����SESSION �̖��O
	$COOKIE_PATH_NAME								 = WS_PACKAGE_ID.'__system_path__';						// �V�X�e���̐ݒu�p�X�����Ǘ�����COOKIE �̖��O

	$ACTIVE_NONE									 = 1;									// �A�N�e�B�x�[�g����Ă��Ȃ���Ԃ�\���萔
	$ACTIVE_ACTIVATE	 							 = 2;									// �A�N�e�B�x�[�g����Ă����Ԃ�\���萔
	$ACTIVE_ACCEPT		 							 = 4;									// ������Ă����Ԃ�\���萔
	$ACTIVE_DENY		 							 = 8;									// ���ۂ���Ă����Ԃ�\���萔
	$ACTIVE_ALL	 									 = 15;

	$IMAGE_NOT_FOUND								= '<span>No Image</span>';

	$terminal_type = 0;
	$sid = "";

    $template_path                                   = "./template/pc/";
	$system_path                          	         = "./custom/system/";
	$page_path										 = "./file/page/";
	$cron_pass										 = "10NU294Q";

	$FORM_TAG_DRAW_FLAG	 							 = 'buffer';					//  buffer/variable

	$DB_LOG_FILE									 = "./logs/dbaccess.log";					// �f�[�^�x�[�X�A�N�Z�X���O�t�@�C��
	$COOKIE_PATH 									 = '/';

	$ADWARES_LIMIT_TYPE_NONE             = 0;
	$ADWARES_LIMIT_TYPE_YEN              = 1;
	$ADWARES_LIMIT_TYPE_CNT              = 2;
	$ADWARES_LIMIT_TYPE_CNT_CLICK        = 3;
	$ADWARES_LIMIT_TYPE_CNT_CONTINUE     = 4;
	$ADWARES_MONEY_TYPE_YEN              = "yen";
	$ADWARES_MONEY_TYPE_PER              = "per";
	$ADWARES_MONEY_TYPE_RANK             = "rank";
	$ADWARES_MONEY_TYPE_PERSONAL         = "personal";
	$ADWARES_AUTO_ON                     = 1;
	$ADWARES_AUTO_OFF                    = 0;
	$RANK_AUTO_ON                        = 1;
	$RANK_AUTO_OFF                       = 0;
	$i_mode_id                           = true;
	$multimail_send_user['admin']        = true;
	$PAY_TYPE_CLICK                      = 1;//�N���b�N�L��
	$PAY_TYPE_NOMAL                      = 2;//���ʔF��
	$PAY_TYPE_CONTINUE                   = 4;//�p���ۋ�

/***************************
 ** �ݒ�t�@�C���̓ǂݍ���**
 ***************************/

	include_once "./custom/extends/sqlConf.php";
	include_once "./custom/extends/mobileConf.php";
	include_once "./custom/extends/tableConf.php";
	include_once "./custom/extends/exceptionConf.php";
	include_once "./custom/extends/exConf.php";
	include_once "./custom/extends/sslConf.php";

/*************************
 *  �g���N���X�̓ǂݍ��� *
 *************************/

	//include_once "./include/extends/";

/***************************
 ** LINK&JS IMPORT�֘A **
 ****************************/

    $js_file_paths['all']['jquery'] 		= './js/jquery.js';
	$js_file_paths['all']['selectboxes']	= './js/jquery.selectboxes.js';
	$js_file_paths['all']['lightbox']   	= './js/jquery.lightbox.js';
	
	$js_file_paths['admin']['pay']   	= './js/pay.js';

	$css_file_paths['all']['lightbox']   = './template/pc/css/jquery.lightbox.css';

?>