<?php

	/**
		@brief   �e�[�u���̏����Z�b�g�B
		@ingroup SystemAPI
	*/
	class TableLogic
	{
		//���f�[�^�擾

		/**
			@brief     �e�[�u���Ɏw�肳�ꂽID�̃��R�[�h�����݂��邩�m�F����B
			@exception InvalidArgument $iTablename_ , $iRecordID_ �̂����ꂩ�ɖ����Ȓl���w�肵���ꍇ�B
			@param[in] $iTableName_ �e�[�u�����B
			@param[in] $iRecordID_  ���R�[�hID�B
			@retval    true  ���R�[�h�����݂���ꍇ�B
			@retval    false ���R�[�h�����݂��Ȃ��ꍇ�B
		*/
		static function ExistsRecord( $iTableName_ , $iRecordID_ )
		{
			if( !$iRecordID_ )
				{ return false; }

			$db    = SystemUtil::getGMforType( $iTableName_ )->getDB();
			$table = $db->getTable();
			$table = $db->searchTable( $table , 'id' , '=' , $iRecordID_ );
			$row   = $db->getRow( $table );

			if( $row ) //���R�[�h�����������ꍇ
				{ return true; }
			else //���R�[�h��������Ȃ������ꍇ
				{ return false; }
		}
	}
