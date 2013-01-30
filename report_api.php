<?php

	ob_start();

	try
	{
		include_once 'custom/head_main.php';

		$report     = new ReportEx();
		$case       = $_POST[ 'case' ];
		$exportName = $report->getExportName( $case );

		$report->downloadReport( $case , $exportName );
	}
	catch( Exception $e_ )
	{
		ob_clean();

		//�G���[���b�Z�[�W�����O�ɏo��
		$errorManager = new ErrorManager();
		$errorMessage = $errorManager->GetExceptionStr( $e_ );

		$errorManager->OutputErrorLog( $errorMessage );

		//��O�ɉ����ăG���[�y�[�W���o��
		$className = get_class( $e_ );
		ExceptionUtil::DrawErrorPage( $className );
	}

	ob_end_flush();
