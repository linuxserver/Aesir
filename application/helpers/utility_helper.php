<?php
function format_bytes($bytes, $is_drive_size=true, $beforeunit='<span>', $afterunit='</span>')
{
    $labels = array('B','KB','MB','GB','TB');
    for($x = 0; $bytes >= 1000 && $x < (count($labels) - 1); $bytes /= 1000, $x++); // use 1000 rather than 1024 to simulate HD size not real size
    if($labels[$x] == "TB") return(round($bytes, ($is_drive_size)?1:2).$beforeunit.$labels[$x].$afterunit);
    else return(round($bytes, ($is_drive_size)?0:2).$beforeunit.$labels[$x].$afterunit);
}

function isRunning($disk) {
	try {
		$CI =& get_instance();

		$pid = $CI->session->userdata('preclear_'.$disk);
		if(isset($pid) && !empty($pid)) {
			$result = shell_exec(sprintf('ps %d', $pid));
			if(count(preg_split("/\n/", $result)) > 2) {
				return true;
			} else {
				$CI->session->unset_userdata('preclear_'.$disk);
			}
		} else return false;
	} catch(Exception $e) {}

	return false;
}

function drive_speed( $size, $time ) {
	$bytes = $size/$time;
	return '<span>'.format_bytes( $bytes, true, '</span>', '' ).'/s';
}

function drive_details( $drive ) {
	if( isset( $drive["fsSize"] ) ) {
		$pdsize = $drive["fsSize"]*1024;
		$pfree = $drive["fsFree"]*1024;
		$pused = $pdsize-$pfree;
		$pused_size = ($pused/$pdsize)*100;
		$output['used'] = format_bytes($pused, true, '', '');
		$output['free'] = format_bytes($pfree, true, '', '');
		$output['percent'] = $pused_size;
		
	}
	$output['id'] = $drive["id"]." (".$drive["device"].")";
	$output['temp'] = $drive['temp'];
	$output['size'] = format_bytes( ( $drive["size"]*1024 ) );
	$output['errors'] = $drive['numErrors'];
	return $output;
}

function menu_active( $controller ) {
    $CI = get_instance();
    $class = $CI->router->fetch_class();
    return ($class == $controller) ? 'active' : '';		
}

function time_ago($date,$timestamp=false,$diff=true, $granularity=2) {
	$date = $timestamp===true ? $date : strtotime($date);
	$difference = ($diff === true) ? (time() - $date) : $date;
	$retval = '';
	$periods = array('decade' => 315360000,
		'year' => 31536000,
		'month' => 2628000,
		'week' => 604800, 
		'day' => 86400,
		'hour' => 3600,
		'minute' => 60,
		'second' => 1);
								 
	foreach ($periods as $key => $value) {
		if ($difference >= $value) {
			$time = floor($difference/$value);
			$difference %= $value;
			$retval .= ($retval ? ' ' : '').'<span>'.$time.'</span>'.' ';
			$retval .= (($time > 1) ? __($key.'s') : __($key));
			$granularity--;
		}
		if ($granularity == '0') { break; }
	}
	return $retval;      
}

function day_count($date,$timestamp=false) {
	$date = $timestamp===true ? $date : strtotime($date);
	$difference = (time() - $date);
	$seconds_in_day = 86400;
	$days = floor($difference/$seconds_in_day);
	return $days;      
}

function split_text($text, $len=18) {
	if(strlen($text) > $len) {
		return substr($text, 0, $len)."<br />".substr($text, $len);
	} else return $text;
}

function deleteDir($path)
{
    return is_file($path) ?
            unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == rmdir($path);
}

function spin_disk($disk, $idx, $type="url", $usb=false) {
	$CI =& get_instance();
	$disks = $CI->config->item("unraid_disks");
	$spinning = strpos($disks[$disk]["color"],"blink")===false ? true : false;
	$icon = ( $usb === true ) ? 'icon-usb-stick' : 'icon-hdd';
	$spindown = ( $usb === true ) ? '<span class="spin"><i class="icon-infinite spinico"></i></span>' : '<a title="'.__('Spin Down').'" href="/index.php/home/spin_disk/'.$idx.'/down/" class="spin"><i class="icon-shuffle2 spinico"></i></a>';
	switch($type) {
		case "url":
			if($spinning === true) return '<i class="'.$icon.' disk"></i>'.$spindown;
			else return '<i class="'.$icon.' disk spundown"></i><a title="'.__('Spin Up').'" href="/index.php/home/spin_disk/'.$idx.'/up/" class="spin"><i class="icon-loop2 spinico"></i></a>';
			break;
	}	
}
function mount_disk($disk, $type="url") {
	$CI =& get_instance();
	$mounted = $disk["mounted"]===1 ? true : false;
	switch($type) {
		case "url":
			if($mounted === true) return '<i class="icon-hdd disk"></i><a title="Un-Mount Disk" href="/index.php/home/unmount_disk/'.$disk["device"].'/" class="spin"><i class="icon-checkbox-checked spinico"></i></a>';
			else return '<i class="icon-hdd disk spundown"></i><a title="Mount Disk" href="/index.php/home/mount_disk/'.$disk["device"].'/" class="spin"><i class="icon-checkbox-unchecked spinico"></i></a>';
			break;
	}	
}


function scan($dir, $level=0){

	$files = array();
	//if( $level >= 1) return; // only scan 1 level down no need to go further until navigated to
	// Is there actually such a folder/file?
	
	if(file_exists($dir)){
	
		foreach(scandir($dir) as $f) {
		
			if(!$f || $f[0] == '.') {
				continue; // Ignore hidden files
			}

			if(is_dir($dir . '/' . $f)) {
				
				// The path is a folder

				$files[] = array(
					"name" => $f,
					"type" => "folder",
					"path" => $dir . '/' . $f,
					"items" => ($level >= 1) ? '0' : scan($dir . '/' . $f, $level++) // Recursively get the contents of the folder
				);
			}
			
			else {

				// It is a file

				$files[] = array(
					"name" => $f,
					"type" => "file",
					"path" => $dir . '/' . $f,
					"size" => filesize($dir . '/' . $f) // Gets the size of this file
				);
			}
		}
	
	}

	return $files;
}





?>