<?php

	//��true�̏ꍇ�A�G���[���b�Z�[�W���O�I�u�W�F�N�g�ɕϊ����ăX���[���܂�
	$EXCEPTION_CONF[ 'UseErrorToException' ] = false;

	//��true�̏ꍇ�A�V���b�g�_�E���֐��ŃG���[�����o�����ꍇ�Ƀ��O���o�͂��܂�
	//�@�����ɂ���Ă̓V���b�g�_�E���֐�����fopen���Ăяo���Ȃ����߁A�G���[���b�Z�[�W���o�͂���邱�Ƃ�����܂�
	$EXCEPTION_CONF[ 'UseShutdownErrorLog' ] = true;

	//���G���[���O���o�͂���t�@�C����
	$EXCEPTION_CONF[ 'ErrorLogFile' ] = './logs/error.log';

	//�����O�o�͂���ї�O�֕ϊ�����G���[���x��
	$EXCEPTION_CONF[ 'ErrorHandlerLevel' ] = E_ERROR | E_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE | E_RECOVERABLE_ERROR;

	include_once './include/extends/ConceptCheck.php';
	include_once './include/extends/ErrorManager.php';
	include_once './include/extends/Exception.php';
	include_once './include/extends/ExceptionUtil.php';
?>
