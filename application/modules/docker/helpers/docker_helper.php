<?php
	function unchunk($result) {
		return preg_replace_callback(
			'/(?:(?:\r\n|\n)|^)([0-9A-F]+)(?:\r\n|\n){1,2}(.*?)'
			.'((?:\r\n|\n)(?:[0-9A-F]+(?:\r\n|\n))|$)/si',
			create_function('$matches','return hexdec($matches[1]) == strlen($matches[2]) ? $matches[2] :$matches[0];'), $result);
	}
function CallAPI($method, $url, $data = false)
{
	$url = 'unix:///var/run/docker.sock'.$url;
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
if(curl_errno($curl))
{
    echo 'error:' . curl_error($curl);
}
    curl_close($curl);

    return $result;
}




	function getDockerJSON( $url, $method = "GET", $data=false ){
		if( $data !== false ) {
			$data = json_encode( $data );
			
		}
		$fp = stream_socket_client('unix:///var/run/docker.sock', $errno, $errstr);
		if ($fp === false) {
			echo "Couldn't create socket: [$errno] $errstr";
			return NULL;
		}
		//die($out);
		//fwrite($fp, $out);

        fputs($fp, "$method $url HTTP/1.1\r\n");
        if( $data !== false ) fputs($fp, "Content-type: application/json\r\n");
        if( $data !== false ) fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        if( $data !== false ) fputs($fp, $data);

		// Strip headers out
		while (($line = fgets($fp)) !== false) {
			if (rtrim($line) == '') {
				break;
			}
		}
		$data = '';
		while (($line = fgets($fp)) !== false) {
			$data .= $line;
		}
		fclose($fp);
		//die($data);
		$data = unchunk($data);
		$json = json_decode( $data, true );
		if ($json === null) {
			$json = array();
		} else if (!array_key_exists(0, $json) && !empty($json)) {
			$json = [ $json ];
		}
		return $json;
	}
