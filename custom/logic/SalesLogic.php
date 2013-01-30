<?php

	/**
		@brief   Sales�e�[�u���̏����Z�b�g�B
		@ingroup SystemAPI
	*/
	class SalesLogic
	{
		//���f�[�^�擾

		/**
			@brief     �ł��Ⴂ���[�U�[�����N�̃��R�[�hID���擾����B
			@return    ���R�[�hID�B
			@attention ���[�U�[�����N�e�[�u������̏ꍇ�́A�󕶎����Ԃ��܂��B
		*/
		static function GetLowestRankID()
		{
			$db    = SystemUtil::getGMforType( self::$Type )->getDB();
			$table = $db->getTable();
			$row   = $db->getRow( $table );

			if( !$row ) //���[�U�[�����N���Ȃ��ꍇ
				{ return ''; }

			$table = $db->sortTable( $table , 'shadow_id' , 'asc' );
			$rec   = $db->getRecord( $table , 0 );
			$id    = $db->getData( $rec , 'id' );

			return $id;
		}

		//���ϐ�
		private static $Type = 'sales'; ///<�e�[�u�����B
	}
