<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); // Remove line to use class outside of codeigniter
/*
* unraid library for use with codeigniter
*
* @author Chris Hunt <admin@coderior.com>
* @link http://www.coderior.com
* @package Codeigniter
* @subpackage Unraid
*
*/
class Unraid {
	public $CI;
	public $var;
	public $sec;
	public $devs;
	public $disks;
	public $users;
	public $shares;
	public $sec_nfs;
	public $sec_afp;

	public function __construct() {
		$this->CI =& get_instance();
		$this->var     = $this->var_details();
		$this->sec     = parse_ini_file( $this->CI->config->item("ini_path").'sec.ini',true );
		$this->devs    = parse_ini_file( $this->CI->config->item("ini_path").'devs.ini',true );
		$this->disks   = parse_ini_file( $this->CI->config->item("ini_path").'disks.ini',true );
		$this->users   = parse_ini_file( $this->CI->config->item("ini_path").'users.ini',true );
		$this->shares  = parse_ini_file( $this->CI->config->item("ini_path").'shares.ini',true );
		$this->sec_nfs = parse_ini_file( $this->CI->config->item("ini_path").'sec_nfs.ini',true );
		$this->sec_afp = parse_ini_file( $this->CI->config->item("ini_path").'sec_afp.ini',true );

	}


	public function var_details() {

		$check = shell_exec( '/root/mdcmd status' );
		if( trim($check) == "" ) { // fallback to reading the ini file if the command fails
			return parse_ini_file( $this->CI->config->item("ini_path").'var.ini' );
		} else {
			$check = str_replace(array("\n", "\r", "\r\n"), '&', $check);
    		parse_str($check, $response_array);
			return $response_array;
		}

	}


    /*
    * get_array_status
    *
    * Returns the status of the array
    *
    * @return string status
    */
	
	public function get_array_status() {
		$output = array();
		$output['percent'] = 100;
		if ($this->disks['parity']['status']=='DISK_NP') { // exit early if no parity disk, DISK_NP = Disk not present
			return array( 'status' => 'error', 'msg' => '<div class="indicator_details"><div class="title">'.__( 'Parity Status' ).'</div>'.__( 'Parity disk not present' ).'</div></div>', 'indicator' => 'error', 'percent' => 100 );
		}
		
		$parity = $this->parity_status();
		
		if ( $parity['running'] ) {

				$checktype = ( $this->var['mdNumInvalid']==0 ? __( 'Parity-Check is in progress' ) : ($this->var['mdInvalidDisk']==0 ? __( 'Parity-Sync is in progress' ) : __( 'Data-Rebuild is in progress' ) ) );
				$progress = $parity['percent'];

				$output['status'] = 'parity';
				$output['indicator'] = 'ok';
				$output['msg'] = '<div class="indicator_details"><div class="title">'.__( 'Status' ).' </div>'.$checktype.'</div>';
				$output['percent'] = $progress;


		} else {

	  		if ( $this->var['mdNumInvalid']==0 ) {
				$output['status'] = 'ok';
				$output['indicator'] = 'ok';
				
				
	    		if ( $this->var['sbSynced']==0 ) {
					$output['msg'] = __('Parity has not been checked yet');
	    		} else {
	      			unset( $time );
	      			exec( "awk '/sync completion/ {gsub(\"(time=|sec)\",\"\",x);print x;print \$NF};{x=\$NF}' /var/log/syslog|tail -2", $time );
	      			if ( !count( $time ) ) $time = array_fill( 0,2,0 );
	      			if ($time[1]==0) {
						$days_ago = day_count( $this->var['sbSynced'], true );
						if( $days_ago > 60 ) {
							$output['indicator'] = 'error';
						} elseif( $days_ago > 30 ) {
							$output['indicator'] = 'warning';
						}
						$output['msg'] = '<div class="indicator_details">';
						$output['msg'] .= '<div class="title">'.__('Last Parity check').'</div>';
						$output['msg'] .= time_ago( $this->var['sbSynced'], true, $diff=true, $granularity=2 ).' '.__('ago.').'<br />';
						$output['msg'] .= (!$time[0]) ? '' : $this->var['sbSyncErrs'].' '.( $this->var['sbSyncErrs']==1? __('Error'):__('Errors') ).'<br />';
						$output['msg'] .= (!$time[0]) ? __('Errors').': '.__('unavailable system reboot or log rotation') : time_ago( $time[0], true, $diff=false, $granularity=2 ).'<br />';
						$output['msg'] .= (!$time[0] || !isset( $this->disks['parity']['sizeSb'] ) ) ? '' : drive_speed( $this->disks['parity']['sizeSb']*1024, $time[0] );
						$output['msg'] .= '</div>';
						
	      			} else {
						$output['msg'] = '<div class="indicator_details">';
						$output['msg'] .= '<div class="title">'.__('Last Parity check').'</div>';
						$output['msg'] .= __('Incomplete').': <br />'.time_ago( $this->var['sbSynced'], true, $diff=true, $granularity=2 ).' '.__('ago').'<br />';
						$output['msg'] .= __('Error code').': '.$this->error_code( $time[1] );
	 					$output['msg'] .= '</div>';
	     			}
	    		}
	  		} else {
				$output['status'] = 'error';
				$output['indicator'] = 'error';
	    		if ( $this->var['mdInvalidDisk']==0 ) {
					$output['msg'] = __('Parity is invalid');
				} else {
					$output['msg'] = __('Data is invalid');
				}
	  		}
	  	}
		return $output;
	}
	

    /*
    * get_array_status
    *
    * Returns the status of the array
    *
    * @param string $code to test against
    *
    * @return string gratavar url
    */


	public function error_code( $code ) {
		switch ( $code ) {
			case -4:
			return "<em>user abort</em>";
			break;
	  	default:
			return "<strong>$code</strong>";
			break;
		}
	}
	
	public function parity_status() {

		$check = $this->query_mdcmd( 'parity_check' );
		if( $check['return']!==0) { // fallback to reading the ini file if the command fails
			$running = ($this->var['mdResync']>0) ? true : false;
			$total = $this->var['mdResync'];
			$current_pos = $this->var['mdResyncPos'];
		} else {
			$mdResync = explode( '=', $check['output'][0] );
			$mdResyncPos = explode( '=', $check['output'][1] );
			$mdResync = end( $mdResync );
			$mdResyncPos = end( $mdResyncPos );

			$running = ( $mdResync > 0) ? true : false;
			$total = $mdResync;
			$current_pos = $mdResyncPos;			
		}
		$percent = ( $running ) ? $this->parity_percent( $total, $current_pos ) : 0;
		return array( 'running' => $running, 'total' => $total, 'current' => $current_pos, 'percent' => $percent );
	}
	
	public function parity_percent( $total, $current ) {
		return number_format( ( $current / ( $total/100+1 ) ),1,'.','' );
	}
	
    /*
    * query_mdcmd
    *
    * Query the mdcmd 
    *
    * @param string $type command to perform a specific request
    *
    * @return array of requested information if any, non 0 $return gererally indicates an error
    */
	
	public function array_details() {
		
		$data = array();
		$disks = $this->disks;

		$ignore_disks = array("parity" => '', "cache" => '', "flash" => '');
		$use_disks = array_diff_key( $disks, $ignore_disks );	
				
		$data["disks"] = $disks;
		$data["ignore_disks"] = $ignore_disks;

		
		//print_r($data["disks"]);
		$array_total = 0;
		$array_free = 0;
		
		$warn_count = 0;
		$warning_list = '';
		$all_list = '';
		$narray_disk_count = 0;
		$narray_list = '';
		foreach($use_disks as $diskname => $disk) {
			
			$array_total += $disk["fsSize"];
			$array_free += $disk["fsFree"];
			
			$tempwarning = "33";
			$errors = $disk["numErrors"];
			if($errors > "0" || $disk["temp"] >= $tempwarning) { // add to the warning list
				$warn_count++;
				$warning_list .= $this->build_disk_line($disk, $tempwarning);
			}
			$all_list .= $this->build_disk_line($disk, $tempwarning);
		} 
		
		$narray_disks = array(
			array("id" => "TOSHIBA_DT01ACA300_73H0DPHGS", "device" => "sdx", "fsSize" => "2930177100", "fsFree" => "2139029283", "temp" => "32", "mounted" => 1),
			array("id" => "TOSHIBA_DT01ACA300_73H0DPHGS", "device" => "sdy", "fsSize" => "2930177100", "fsFree" => "2139029283", "temp" => "30", "mounted" => 0),
			array("id" => "TOSHIBA_DT01ACA300_73H0DPHGS", "device" => "sdz", "fsSize" => "2930177100", "fsFree" => "2139029283", "temp" => "27", "mounted" => 0)
		);
		foreach($narray_disks as $ndisk) {
			$narray_disk_count++;
			$narray_list .= $this->build_narray_disk_line($ndisk, $tempwarning);
		}
		
		$warning_list = (!empty($warning_list)) ? $warning_list : '<p class="nothingtosee">'.__('There are currently no disks needing attention').'</p>';
		$narray_list = (!empty($narray_list)) ? $narray_list : '<p class="nothingtosee">'.__('There are currently no non-array disks detected').'</p>';
		
		$data["warn_count"] = $warn_count;

		$warn1 = __('There is 1 disk needing attention');
		$warnelse = __('There are x disks needing attention', array( $warn_count ));

		$data["disk_status_msg"] = ( $warn_count > 0 ) ? ( $warn_count == 1 ? $warn1 : $warnelse ) : __('All disks are within defined parameters');
		$data["all_count"] = count( $use_disks );
		$data["narray_disk_count"] = $narray_disk_count;
		$data["array_total"] = $array_total;
		$data["array_free"] = $array_free;
		$data["array_percent_used"] = ( $array_total > 0 ) ? floor(($array_total-$array_free)/$array_total*100) : 0;
		
		$data['array_status'] = $this->get_array_status();

		$data["warning_list"] = $warning_list;
		$data["all_list"] = $all_list;
		$data["narray_list"] = $narray_list;


		
		return $data;
		
	}
	
	
	public function build_disk_line($disk, $tempwarning) {
		$dsize = $disk["fsSize"]*1024;
		$free = $disk["fsFree"]*1024;
		$used = $dsize-$free;
		$dsize = ( $dsize > 0 ) ? $dsize : 1;
		$used = ( $used > 0 ) ? $used : 1;
		$used_size = ($used/$dsize)*100;
		$temp_warning_class = ($disk["temp"] >= $tempwarning) ? ' redtext' : '';
		
		$errors = $disk["numErrors"];
		$error_warning_class = ($errors > "0") ? ' redtext' : '';
		
		$errorline = ( $disk["temp"] >= $tempwarning || $errors > "0" ) ? ' disk_errors' : '';

		return '
			<div class="table-box'.$errorline.'">
				<div class="col col1"><div class="col-table">'.spin_disk($disk["name"],$disk["idx"],"url").'<div class="disk-ref">'.$disk["name"].'<div class="disk_size">'.format_bytes($dsize).'</div><br /><div class="disk-name">'.$disk["id"].' ('.$disk["device"].')</div></div></div></div>
				<div class="col col2"><div class="temp'.$temp_warning_class.'">'.$disk["temp"].'<span>&deg;C</span></div></div>
				<div class="col overcol1">
					<div class="col col4"><div class="colspace">'.format_bytes($used).' <span> '.__('Used').'</span></div></div>
					<div class="col col5"><div class="colspace">'.format_bytes($free).' <span> '.__('Free').'</span></div></div>
					<div class="space-info">
						<div class="space"><div class="used" style="width: '.$used_size.'%"></div></div>
					</div>
				</div>
				<div class="col col6"><div class="temp'.$error_warning_class.'">'.$errors.' <span>'.__('Errors').'</span></div></div>
				<div style="clear:both;"></div>
			</div>';

	}
	
	public function build_narray_disk_line($disk, $tempwarning) {
		$dsize = $disk["fsSize"]*1024;
		$free = $disk["fsFree"]*1024;
		$used = $dsize-$free;
		$used_size = ($used/$dsize)*100;
		$temp_warning_class = ($disk["temp"] >= $tempwarning) ? ' redtext' : '';
		
		$show_used = ($disk["mounted"] === 1) ? format_bytes($used) : 0;
		$show_free = ($disk["mounted"] === 1) ? format_bytes($dsize) : 0;
		$show_used_size = ($disk["mounted"] === 1) ? $used_size : 0;
		
		return '
			<div class="table-box">
				<div class="col col1"><div class="col-table">'.mount_disk($disk).'<div class="disk-ref">'.$disk["device"].'<br /><div class="disk-name">'.$disk["id"].'</div></div></div></div>
				<div class="col col2"><div class="temp'.$temp_warning_class.'">'.$disk["temp"].'<span>&deg;C</span></div></div>
				<div class="col col3"><div class="temp">'.format_bytes($dsize).'</div></div>
				<div class="col overcol1">
					<div class="col col4"><div class="colspace">'.$show_used.' <span> '.__('Used').'</span></div></div>
					<div class="col col5"><div class="colspace">'.$show_free.' <span> '.__('Free').'</span></div></div>
					<div class="space-info">
						<div class="space"><div class="used" style="width: '.$show_used_size.'%"></div></div>
					</div>
				</div>
				<div class="col col6"></div>
				<div style="clear:both;"></div>
			</div>';

	}

	public function start_array() {

		$this->query_mdcmd( 'start_array' );

	}
	
	public function stop_array() {

		$this->query_mdcmd( 'stop_array' );

	}
	
	protected function query_mdcmd( $type, $shell = false ) {

		switch( $type ) {
			
			case 'parity_check': $cmd = '/root/mdcmd status | egrep "mdResync=|mdResyncPos"'; break;
			case 'parity_check_start': $cmd = '/root/mdcmd check'; break;
			case 'parity_check_start_noncorrecting': $cmd = '/root/mdcmd check NOCORRECT'; break;
			case 'start_array': $cmd = '/root/mdcmd start'; break;
			case 'stop_array': $cmd = '/root/mdcmd stop'; break;
			case 'full_status': $cmd = '/root/mdcmd status'; break;
				
		}
		if( !isset( $cmd ) ) {
			$output = array();
			$return = 1;
		} else {
			unset( $output );
			exec( $cmd, $output, $return );
		}
		
		return array( 'output' => $output, 'return' => $return );
	}
	
	public function status_button( $status, $type ) {
		if( $type == 'array' ) {
			switch( $status ) {
				case 'ok':
					return '<div class="status_container"><div class="status_button"><form class="ajaxupdate" method="post" action="http://'.current(explode(":", $_SERVER['HTTP_HOST'])).'/update.htm"><div class="title">'.__('Perform parity check').'</div><input type="checkbox" name="optionCorrect" value="correct" checked=""><label for="non_correcting">'.__('Do not correct errors').'</label><input type="hidden" name="cmdCheck" value="Check" /><button type="submit">'.__('Start check').'</button></form></div></div>';
					break;	
				case 'parity':
					return '<div class="status_container"><div class="status_button"><form class="ajaxupdate" method="post" action="http://'.current(explode(":", $_SERVER['HTTP_HOST'])).'/update.htm"><div class="title">'.__('Cancel parity check').'</div><input type="hidden" name="cmdNoCheck" value="Cancel" /><button type="submit">Stop check</button></form></div></div>';
					break;	
			}
		}
		if( $type == 'disks' ) {
			switch( $status ) {
				case 'error':
					return '<div class="status_container"><div class="status_button"><div class="title">'.__('Show disks').'</div><a href="#problem">'.__('Show disks that require attention').'</a></div></div>';
					break;	
			}
			
		}
	}

}

?>