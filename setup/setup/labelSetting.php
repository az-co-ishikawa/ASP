<?php

	/*		���x���e���v���[�g		*/
	class LabelSetting
	{
		static private $Settings = Array();

		/*		�R���X�g���N�^		*/
		/*		p0 : �ݒ�z��		*/
		function LabelSetting( $_settings )
		{
			$labels = array_keys( self::$Settings );

			foreach( $_settings as $label => $file )
			{
				if( isset( self::$Settings[ $label ] ) )
					die( '���x�����d�����Ă��܂� : ' . $label );

				self::$Settings[ $label ] = $file;
			}
		}

		/*		�e���v���[�g�ݒ�z����擾		*/
		/*		��^���� : �����͂���܂���		*/
		static function GetTemplates()
		{
			foreach( self::$Settings as $label => $file )
			{
				$template                = Array();
				$template[ 'shadow_id' ] = '';
				$template[ 'deleted' ]   = '';
				$template[ 'id' ]        = '';
				$template[ 'user' ]      = '';
				$template[ 'target' ]    = '';
				$template[ 'activate' ]  = '15';
				$template[ 'owner' ]     = '3';
				$template[ 'label' ]     = $label;
				$template[ 'file' ]      = $file;
				$template[ 'regist' ]    = '0';

				$result[] = $template;
			}

			return $result;
		}
	}
?>