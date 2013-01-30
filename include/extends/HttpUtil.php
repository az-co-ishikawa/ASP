<?php
class HttpUtil{
	// -------------------------------------------------------------------------
	// array http_request(string Method, string URI, string Version [, array headers
	//                    [, string Server [, int Size [, int Timeout1 [, int Timeour2]]]]])
	// URI�ɑ΂���Method��HTTP���N�G�X�g���s���܂��BHTTP�̃o�[�W������Version�Ŏw
	// ��ł��܂��B�ǉ��̃��N�G�X�w�b�_�[�͘A�z�z��headers�Ŏw�肵�܂��B
	// �Ԃ�l�ɂ�HTTP-Version�AStatus-Code�AReason-Phrase���K���܂܂�A����ȊO
	// �ɃT�[�o���Ԃ������iindex: value�j���܂܂�܂��B
	// Status-Code��9xx�̏ꍇ�A����̓z�X�g�����݂��Ȃ��ꍇ�Ȃ�HTTP���N�G�X�g��
	// ����ɍs���Ȃ��������Ƃ��Ӗ����܂��B
	// -------------------------------------------------------------------------
	static function http_request($method, $uri, $version, $headers = array(), $server = "", $limit_size = 51200, $tos = 2, $tor = 4)
	{

		if ($server == "") {
			// server����̏ꍇ��uri����擾
			// absoluteURI�Ȃ炻������
			if ($uri && substr($uri, 0, 1) != "/") {
				$temp = parse_url($uri);
				$host = $temp["host"];
				$port = $temp["port"];
				// ����ȊO�Ȃ�Host�t�B�[���h����
			} else {
				// �T�[�o���z�X�g���ƃ|�[�g�ɕ���
				$temp = explode(":", $headers["Host"]);
				$host = $temp[0];
				$port = $temp[1];
			}
		} else {
			// �T�[�o���z�X�g���ƃ|�[�g�ɕ���
			$temp = explode(":", $server);
			$host = $temp[0];
			$port = $temp[1];
		}

		// �|�[�g����̎��̓f�t�H���g��80�ɂ��܂��B
		if (! $port) {
			$port = 80;
		}


		// ���N�G�X�g�t�B�[���h�𐧍�B
		$msg_req = $method . " " . $uri . " HTTP/". $version . "\r\n";
		foreach ($headers as $name => $value) {
			$msg_req .= $name . ": " . $value . "\r\n";
		}
		$msg_req .= "\r\n";

		$status = array();
		// �w��z�X�g�ɐڑ��B
		if ($handle = @fsockopen($host, $port, $errno, $errstr, $tos)) {
			if (socket_set_timeout($handle, $tor)) {
				fputs ($handle, $msg_req);
				$buffer = fread($handle, $limit_size);
				fclose ($handle);

				$status = array();
				$status["Raw-Data"] = $buffer;
				$temp = explode("\r\n\r\n", $buffer);
				$buffer_header = array_shift($temp);
				$entity_body = implode("\r\n\r\n", $temp);

				$temp_line = explode("\r\n", $buffer_header);
				foreach ($temp_line as $line_no => $line_contents) {
					if($line_no == 0) {
						$temp_status = explode(" ", $line_contents);
						$status["HTTP-Version"] = $temp_status[0];
						$status["Status-Code"] = $temp_status[1];
						$status["Reason-Phrase"] = $temp_status[2];
					} else {
						$temp_status = explode(":", $line_contents);
						$field_name = array_shift($temp_status);
						$status[$field_name] = ltrim(implode(":", $temp_status));
					}
				}
				if ($entity_body != "") {
					$status["entity-body"] = $entity_body;
				}
			} else {
				$status["HTTP-Version"] = "---";
				$status["Status-Code"] = "902";
				$status["Reason-Phrase"] = "Response Timeout";

			}
		} else {
			$status["HTTP-Version"] = "---";
			$status["Status-Code"] = "901";
			$status["Reason-Phrase"] = "Connection Timeout";
		}

		return $status;
	}

	/*
	 PHP5��p�֐��@POST�^https�Ή��y�[�W�f�[�^�̎擾

	 *	@param $url	�擾�y�[�W�t�q�k
	 *	@param $param �A�z�z��ł̈����iPOST�j���ȗ���

	 �擾�\�Ȍ`����
	 print getURL("http://www.hoge.com/hoge.php");
	 print getURL("http://www.hoge.com/hoge.php?param1=value1");
	 print getURL("http://www.hoge.com/hoge.php", array('param2'  => 'value2', 'param3'  => 'value3'));
	 print getURL("http://www.hoge.com/hoge.php?param1=value1", array('param2'  => 'value2', 'param3'  => 'value3'));
	 print getURL("https://www.hoge.com/hoge.php", array('param2'  => 'value2', 'param3'  => 'value3'));
	 print getURL("https://www.hoge.com/hoge.php?param1=value1", array('param2'  => 'value2', 'param3'  => 'value3'));
	 */
	static function getURL($url, $param = null){

		if(!is_null($param)) $post = http_build_query($param);

		$header = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $post
		)
		);

		$context  = stream_context_create($header);

		return file_get_contents($url, false, $context);
	}
}
?>