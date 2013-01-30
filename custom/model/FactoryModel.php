<?php

	class FactoryModel
	{
		protected $type     = null; ///<�e�[�u����
		protected $db       = null; ///<�f�[�^�x�[�X�I�u�W�F�N�g
		protected $rec      = null; ///<���R�[�h�I�u�W�F�N�g
		protected $originID = null; ///<�Ǝ�ID

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

			$this->type = $type_;
			$this->db   = $gm[ $type_ ]->getDB();
			$this->rec  = $this->db->getNewRecord();
		}

		//������

		function register()
		{
			if( $this->originID ) //�Ǝ�ID���ݒ肳��Ă���ꍇ
				$this->db->setData( $this->rec , 'id' , $this->originID );
			else //�Ǝ�ID���ݒ肳��Ă��Ȃ��ꍇ
				$this->db->setData( $this->rec , 'id' , SystemUtil::GetNewID( $this->db , $this->type ) );

			if( in_array( 'regist' , $this->db->colName ) ) //regist�J���������݂���ꍇ
				$this->db->setData( $this->rec , 'regist' , time() );

			$this->db->addRecord( $this->rec );

			return new RecordModel( $this->type , $this->db->getData( $this->rec , 'id' ) );
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
			@brief ID��ύX����B
			@param $id_ �Ǝ�ID�B
		*/
		function setID( $id_ )
		{
			$this->originID = $id_;
		}

		/**
			@brief     �C�ӂ̃J�����̒l��ύX����B
			@exception InvalidArgumentException ���� $name_ �̃J���������݂��Ȃ��ꍇ�B
			@param     $name_  �J�������B
			@param     $value_ �J�����̒l�B
		*/
		function setData( $column_ , $value_ )
		{
			if( !in_array( $column_ , $this->db->colName ) ) //�J������������Ȃ��ꍇ
				throw new InvalidArgumentException( $this->type . ' �� [' . $name_ . '] �J�����͂���܂���' );

			return $this->db->setData( $this->rec , $column_ , $value_ );
		}
	}

?>
