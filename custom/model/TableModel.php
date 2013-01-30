<?php

	class TableModel
	{
		protected $type  = null; //�e�[�u����
		protected $db    = null; //Database�I�u�W�F�N�g
		protected $table = null; //Table�I�u�W�F�N�g

		//���R���X�g���N�^�E�f�X�g���N�^

		/**
			@brief     �R���X�g���N�^�B
			@exception InvalidArgumentException ���� $tyoe_ �ɑ��݂��Ȃ��e�[�u�������w�肵���ꍇ�B
			@param     $type_ �e�[�u�����B
		*/
		function __construct( $type_ )
		{
			global $gm;

			if( !array_key_exists( $type_ , $gm ) ) //GUIManager�����݂��Ȃ��ꍇ
				throw new InvalidArgumentException( '�e�[�u��[' . $type_ . ']�͑��݂��܂���' );

			$this->type  = $type_;
			$this->db    = $gm[ $type_ ]->getDB();
			$this->table = $this->db->getTable();
		}

		//������

		/**
			@brief     �e�[�u������������B
			@exception InvalidArgumentException ���� $column_ �ɑ��݂��Ȃ��J�������w�肵���ꍇ�B
			@param     $column_ �J�������B
			@param     $or_     ���Z�q�B
			@param     $value_  �l�B
		*/
		function search( $column_ , $op_ , $value_ )
		{
			if( !in_array( $column_ , $this->db->colName ) ) //�J������������Ȃ��ꍇ
				throw new InvalidArgumentException( '[' . $this->type . '] �� [' . $column_ . '] �J�����͂���܂���' );

			$this->table = $this->db->searchTable( $this->table , $column_ , $op_ , $value_ );
		}

		/**
			@brief     �e�[�u����and��������B
			@exception InvalidArgumentException �����ɑ��݂��Ȃ��J�������w�肵���ꍇ�B
			@param     $terms_ �����z��B�ȉ��̍\���̔z���C�ӂ̐������i�[����
				@li 0 �J�������B
				@li 1 ���Z�q�B
				@li 2 �l�B
		*/
		function searchAnd( $terms_ )
		{
			foreach( $terms_ as $term )
			{
				List( $column , $op , $value ) = $term;

				if( !in_array( $column , $this->db->colName ) ) //�J������������Ȃ��ꍇ
					throw new InvalidArgumentException( '[' . $this->type . '] �� [' . $column . '] �J�����͂���܂���' );

				$this->table = $this->db->searchTable( $this->table , $column , $op , $value );
			}
		}

		/**
			@brief     �e�[�u����or��������B
			@exception InvalidArgumentException �����ɑ��݂��Ȃ��J�������w�肵���ꍇ�B
			@param     $terms_ �����z��B�ȉ��̍\���̔z���C�ӂ̐������i�[����
				@li 0 �J�������B
				@li 1 ���Z�q�B
				@li 2 �l�B
		*/
		function searchOr( $terms_ )
		{
			if( !count( $terms_ ) ) ///�p�����[�^����̏ꍇ
				return;

			//or�������̖��ʂȃN�G��������h�����߁A��̃e�[�u�����x�[�X�Ɍ�������
			$baseTable = $this->db->getTable();
			$results   = Array();

			foreach( $terms_ as $term )
			{
				List( $column , $op , $value ) = $term;

				if( !in_array( $column , $this->db->colName ) ) //�J������������Ȃ��ꍇ
					throw new InvalidArgumentException( '[' . $this->type . '] �� [' . $column . '] �J�����͂���܂���' );

				$results[] = $this->db->searchTable( $baseTable , $column , $op , $value );
			}

			$baseTable = array_shift( $results );

			foreach( $results as $result )
				$baseTable = $this->db->orTable( $baseTable , $result );

			$this->table = $this->db->andTable( $this->table , $baseTable );
		}

		/**
			@brief ���R�[�h�̎擾�͈͂�ݒ肷��B
			@param $ofset_ �J�n�ʒu�B
			@param $limit_ �ő�s���B
		*/
		function setLimitOffset( $ofset_ , $limit_ )
		{
			$this->db->limitOffset( $this->table , $ofset_ , $limit_ );
		}

		/**
			@brief     �e�[�u���������\�[�g����B
			@exception InvalidArgumentException ���� $column_ �ɑ��݂��Ȃ��J�������w�肵���ꍇ�B
			@param     $column_ �J�������B
		*/
		function sortAsc( $column_ )
		{
			if( !in_array( $column_ , $this->db->colName ) ) //�J������������Ȃ��ꍇ
				throw new InvalidArgumentException( '[' . $this->type . '] �� [' . $column_ . '] �J�����͂���܂���' );

			$this->table = $this->db->sortTable( $this->table , $column_ , 'asc' );
		}

		/**
			@brief     �e�[�u�����~���\�[�g����B
			@exception InvalidArgumentException ���� $column_ �ɑ��݂��Ȃ��J�������w�肵���ꍇ�B
			@param     $column_ �J�������B
		*/
		function sortDesc( $column_ )
		{
			if( !in_array( $column_ , $this->db->colName ) ) //�J������������Ȃ��ꍇ
				throw new InvalidArgumentException( '[' . $this->type . '] �� [' . $column_ . '] �J�����͂���܂���' );

			$this->table = $this->db->sortTable( $this->table , $column_ , 'desc' );
		}

		//���p�����[�^

		/**
			@brief  �f�[�^�x�[�X���擾����B
			@return Database�I�u�W�F�N�g�B
		*/
		function getDB()
		{
			return $this->db;
		}

		/**
			@brief  �e�[�u���̍s�����擾����B
			@return �s���B
		*/
		function getRow()
		{
			return $this->db->getRow( $this->table );
		}

		/**
			@brief  �e�[�u�����擾����B
			@return Table�I�u�W�F�N�g�B
		*/
		function getTable()
		{
			return $this->table;
		}

		/**
			@brief  �e�[�u�������擾����B
			@return �e�[�u�����B
		*/
		function getType()
		{
			return $this->type;
		}

		//���擾

		/**
			@brief     RecordModel�I�u�W�F�N�g���擾����B
			@exception OutOfRangeException ���� $row_ �ɗL���͈͊O�̍s���w�肵���ꍇ�B
			@return    RecordModel�I�u�W�F�N�g�B
		*/
		function getRecordModel( $row_ )
		{
			$row = $this->getRow();

			if( $row <= $row_ ) //�s���𒴂��Ă���ꍇ
				throw new InvalidArgumentException( "�s���w��𒴂��Ă��܂�" );

			$rec = $this->db->getRecord( $this->table , $row_ );
			$id  = $this->db->getData( $rec , 'id' );

			return new RecordModel( $this->type , $id );
		}
	}

?>
