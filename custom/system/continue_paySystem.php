<?php

	include_once 'custom/system/BasePaySystem.php';

	/**
	 * �V�X�e���R�[���N���X
	 * 
	 * @author �O�H��q
	 * @version 1.0.0
	 * 
	 */
	class continue_paySystem extends BasePaySystem
	{
		//���f�[�^�擾

		/**
			@brief  �e�[�u�������擾����B
			@return �e�[�u�����B
		*/
		function GetType()
			{ return 'continue_pay'; }
	}
