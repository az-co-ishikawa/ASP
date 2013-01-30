<?php

	class RecordModel
	{
		protected $type = null; ///<�e�[�u����
		protected $db   = null; ///<�f�[�^�x�[�X�I�u�W�F�N�g
		protected $rec  = null; ///<���R�[�h�I�u�W�F�N�g

		//���R���X�g���N�^�E�f�X�g���N�^

		/**
			@brief     �R���X�g���N�^�B
			@exception InvalidArgumentException ���� $tyoe_ �ɑ��݂��Ȃ��e�[�u�������w�肵���ꍇ�B
			@exception RuntimeException         ���� $id_ �Ɏw�肳�ꂽID�������R�[�h��������Ȃ��ꍇ�B
			@param     $type_ �e�[�u�����B
			@param     $id_   ���R�[�hID�B
		*/
		function __construct( $type_ , $id_ )
		{
			global $gm;

			if( !array_key_exists( $type_ , $gm ) ) //GUIManager�����݂��Ȃ��ꍇ
				throw new InvalidArgumentException( '�e�[�u��[' . $type_ . ']�͑��݂��܂���' );

			$this->type = $type_;
			$this->db   = $gm[ $type_ ]->getDB();
			$this->rec  = $this->db->selectRecord( $id_ );

			if( !$this->rec ) //���R�[�h��������Ȃ��ꍇ
				throw new RuntimeException( '[' . $type_ . ']' . ' �� ID[ ' . $id_ . ' ]�������R�[�h�͂���܂���' );
		}

		//������

		/**
			@brief ���R�[�h���X�V����B
		*/
		function update()
		{
			$this->db->updateRecord( $this->rec );
		}

		//���p�����[�^

		/**
			@brief     �C�ӂ̃J�����̒l���擾����B
			@exception InvalidArgumentException ���� $name_ �̃J���������݂��Ȃ��ꍇ�B
			@param     $name_ �J�������B
			@return    �J�����̒l�B
		*/
		function getData( $name_ )
		{
			if( !in_array( $name_ , $this->db->colName ) ) //�J������������Ȃ��ꍇ
				throw new InvalidArgumentException( $this->type . ' �� [' . $name_ . '] �J�����͂���܂���' );

			return $this->db->getData( $this->rec , $name_ );
		}

		/**
			@brief  �f�[�^�x�[�X���擾����B
			@return Database�I�u�W�F�N�g�B
		*/
		function getDB()
		{
			return $this->db;
		}

		/**
			@brief  ID���擾����B
			@return ���R�[�hID�B
		*/
		function getID()
		{
			return $this->db->getData( $this->rec , 'id' );
		}

		/**
			@brief  ���R�[�h�f�[�^���擾����B
			@return ���R�[�h�f�[�^�B
		*/
		function getRecord()
		{
			return $this->rec;
		}

		/**
			@brief  �e�[�u�������擾����B
			@return �e�[�u�����B
		*/
		function getType()
		{
			return $this->type;
		}

		/**
			@brief     �C�ӂ̃J�����̒l��ύX����B
			@exception InvalidArgumentException ���� $name_ �̃J���������݂��Ȃ��ꍇ�B
			@param     $name_  �J�������B
			@param     $value_ �J�����̒l�B
		*/
		function setData( $name_ , $value_ )
		{
			if( !in_array( $name_ , $this->db->colName ) )
				throw new InvalidArgumentException( $this->type . ' �� [' . $name_ . '] �J�����͂���܂���' );

			return $this->db->setData( $this->rec , $name_ , $value_ );
		}
	}

?>
