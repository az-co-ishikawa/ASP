<?php

	/*		�ݒ�t�@�C���ǂݍ��݁E���		*/
	class Option
	{
		var $fileName;
		var $handle;

		/*		�R���X�g���N�^		*/
		/*		p0 : �ݒ�L�q�t�@�C��		*/
		function Option( $_fileName )
		{
			if( !is_file( $_fileName ) )
				die( '�t�@�C����������܂��� : ' . $_fileName );

			$this->fileName = $_fileName;
			$this->handle   = fopen( $_fileName , 'rb' );

			if( !$this->handle )
				die( '�t�@�C�����I�[�v���ł��܂��� : ' . $_fileName );

			$this->load();
		}

		/*		�t�@�C����ǂݍ���		*/
		/*		��^���� : �����͂���܂���		*/
		private function load()
		{
			while( !feof( $this->handle ) )
			{
				$read = rtrim( fgets( $this->handle ) );
				$read = preg_replace( '/^[\t\s]*/' , '' , $read );
//				$read = preg_replace( '/\/\/.*$/' , '' , $read );
				$read = preg_replace( '/;[\t\s]*$/' , '' , $read );

				switch( $this->lineType( $read ) )
				{
					case 'beginTag' :
						$this->tagName = $this->getTagName( $read );
						break;

					case 'endTag' :
						$this->addSetting( $this->tagName );
						$this->tagName = null;
						break;

					case 'null' :
						if( $this->tagName )
							$this->addSetting( $this->tagName );
						break;

					default :
						if( $this->tagName )
							$this->stackSetting( $read );
						break;
				}
			}
		}

		/*		�s�̎�ނ𔻒f����		*/
		/*		p0 : �ǂݍ��񂾍s������		*/
		private function lineType( $_read )
		{
			if( preg_match( '/^<(\w*)>/' , $_read , $match ) )
				return 'beginTag';
			else if( preg_match( '/^<\\/(\w*)>/' , $_read , $match ) )
			{
				if( $this->tagName != $match[ 1 ] )
					die( '���^�O����v���܂���' );

				return 'endTag';
			}

			return ( $_read ? 'data' : 'null' );
		}

		/*		�^�O�����擾		*/
		/*		p0 : �^�O������		*/
		private function getTagName( $_read )
		{
			preg_match( '/^<(\w*)>/' , $_read , $match );
			return strtolower( $match[ 1 ] );
		}

		/*		�ݒ��ǉ�����		*/
		/*		p0 : �ǂݍ��񂾍s������		*/
		private function stackSetting( $_read )
		{
			if( !$this->tagName )
				return;

			$this->settings[] = $_read;
		}

		/*		�ݒ��ǉ�����		*/
		/*		p0 : �^�O��		*/
		private function addSetting( $_tagName )
		{
			if( 'csv' == $_tagName )
			{
				foreach( $this->settings as $value )
					$settings[] = explode( ',' , $value );
			}
			else
			{
				foreach( $this->settings as $value )
				{
					if( !preg_match( '/([^\s\t]*)[\s\t]*=[\s\t]*(.*)/' , $value , $match ) )
						die( '�ݒ�̏���������������܂��� : ' . $_tagName . ' : ' . $value );

					$settings[ $match[ 1 ] ] = $match[ 2 ];
				}
			}

			switch( $_tagName )
			{
				case 'conf' :
					new ConfSetting( $settings );
					break;

				case 'table' :
					new TableSetting( $settings );
					new PresetSetting( $settings );
					break;

				case 'label' :
					new LabelSetting( $settings );
					break;

				case 'csv' :
					new CSVSetting( $settings );
					break;
			}

			$this->settings = Array();
		}
	}

?>
