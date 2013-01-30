<?php
class ImageUtil{

	/**
	 * �摜���w�肳�ꂽ�T�C�Y�ŕ\������^�O�𐶐�����
	 *
	 * @param img ���T�C�Y����摜�̃t�@�C���p�X�ł��B
	 * @param newWidth ���T�C�Y��̉����ł��B
	 * @param newHeight ���T�C�Y��̏c���ł��B
	 * @param ratio ���T�C�Y��̔䗦���ێ����邩�w�肵�܂��B
	 * @param small �w��T�C�Y��菬���������ꍇ���̂܂܂̃T�C�Y�ŕ\�����邩�w�肵�܂��B 
	 */
	function getImageTag( $img, $newWidth, $newHeight, $ratio = 'variable', $small = false )
	{
		if( !is_file( $img ) )	{ return '�C���[�W�͓o�^����Ă��܂���'; }
		list($width, $height) = getimagesize($img);

		// �w��T�C�Y��菬�������̓I���W�i���T�C�Y�ŕ\������ꍇ
		if( $small && $newWidth > $width && $newHeight > $height )
		{ $newWidth  = $width; $newHeight = $height;  }

		switch($ratio)
		{
			case 'variable':
				// �c�����T�C�Y��̃T�C�Y�ɋ߂��������ɔ䗦���ێ�
				if( (double)$width/$newWidth > (double)$height/$newHeight )
				{ $newHeight = ($height/$width)*$newWidth; }
				else
				{ $newWidth	 = ($width/$height)*$newHeight; }
				break;
			case 'width':
				// ���������ɔ䗦���ێ�
				$newHeight = ($height/$width)*$newWidth;
				break;
			case 'height':
				// �c�������ɔ䗦���ێ�
				$newWidth	 = ($width/$height)*$newHeight;
				break;
			case 'fix':
			default:
				// �䗦�𖳎����Ďw��T�C�Y�ŕ\��
				break;
		}
		return '<img src="'. $img .'" width="'.$newWidth.'" height="'.$newHeight.'" border="0">';

	}



	/**
	 * �w�肳�ꂽ�摜���w�肳�ꂽ�T�C�Y�ɕύX���ĕۑ����܂��B
	 *
	 * @param img ���T�C�Y����摜�̃t�@�C���p�X�ł��B
	 * @param fileName ���T�C�Y��̉摜�̃t�@�C���p�X�ł��B
	 * @param newWidth ���T�C�Y��̉����ł��B
	 * @param newHeight ���T�C�Y��̏c���ł��B
	 * @param ratio ���T�C�Y��̔䗦���ێ����邩�w�肵�܂��B
	 */
	function resizeImage( $img, $fileName, $newWidth, $newHeight, $ratio = true  )
	{
		if( !is_file( $img ) )	{ throw new FileIOException(  '�t�@�C�������݂��܂��� ->'. $img  ); }
		list($width, $height,$type) = getimagesize($img);

		if($ratio)
		{// �䗦���ێ�����ꍇ
			if( (double)$width/$newWidth > (double)$height/$newHeight )
			{ $newHeight = ($height/$width)*$newWidth; }
			else
			{ $newWidth	 = ($width/$height)*$newHeight; }
		}

		// ���T�C�Y�摜�̐���
		$outImage		 =  @imagecreatetruecolor( (int)$newWidth, (int)$newHeight );
		switch( $type )
		{
			case '1':// gif
				$image	 = @imagecreatefromgif( $img );
				imagecopyresized($outImage,$image,0,0,0,0,$newWidth,$newHeight,$width,$height);
				imagegif( $outImage, $fileName );
				break;
			case '2':// jpg
				$image	 = @imagecreatefromjpeg( $img );
				imagecopyresized($outImage,$image,0,0,0,0,$newWidth,$newHeight,$width,$height);
				imagejpeg( $outImage, $fileName );
				break;
			case '3':// png
				$image	 = @imagecreatefrompng( $img );
				imagecopyresized($outImage,$image,0,0,0,0,$newWidth,$newHeight,$width,$height);
				imagepng( $outImage, $fileName );
				break;
		}

		return $fileName;
	}

}
?>