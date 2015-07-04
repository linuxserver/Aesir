<?php

/**
 *  This class retrive information about a Linux machine
 *
 *  @author César D. Rodas <mail@cesarodas.com>
 *  @copyright Copyleft (c) 2008, César Rodas
 */
class Linuxstat {
    var $ldir = "/proc";
    
    /**
     *  Return an array containing information about the CPU
     *  of a given machine.
     *  @return array  
     */
    function getCpuInfo() {
        return $this->parsefile($this->ldir."/cpuinfo");
    }
    
    /**
     *  Return the memory stats
     *  @return array
     */
    function getMemStat() {
        return $this->parsefile($this->ldir."/meminfo");
    }
    
    /**
     *  Return the memory stats
     *  @return array
     */
    function getUptime() {
        //GET SERVER LOADS 
		$loadresult = @exec('uptime');  
		preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/",$loadresult,$avgs); 
		
		
		//GET SERVER UPTIME 
		$uptime = @explode(' up ', $loadresult); 
		$uptime = @explode(',', $uptime[1]); 
		$uptime = @$uptime[0].', '.@$uptime[1]; 
		
		if ( !count( $avgs ) ) $avgs = array_fill( 1,3,0 );
		
		return array("load" => $avgs[1].", ".$avgs[2].", ".$avgs[3], "uptime" => $uptime);

    }
	
	function getServiceStatus() {
		$services = array();
		$errno = false; $errstr = false; $timeout = 1;
		$port["Apache2"] = "80";
		$port["MySQL"] = "3306";
		$port["Memcached"] = "11211";
		foreach($port as $name => $p) {
			$fp = fsockopen("localhost", $p, $errno, $errstr, $timeout); 
			if (!$fp) { 
				$services[$name] = "Offline"; 
			} else { 
				$services[$name] = "Online"; 
				
			} 
			fclose($fp); 
		}
		return $services;
	}
	
	function getPortLink() {
		// Not sure how to do this so return manually for now
		return "200Mb/s";
	}
    
    /**
     *  Return a list of all process that can be visible
     *  by the user which is executing PHP (usually apache)
     *
     *  @return array
     */
    function getProcesses() {
        /*$files = $this->getDirFiles($this->ldir);
        $processes=array();
        foreach($files as $file) {
            if ( $file['dir'] && is_numeric($file['name']) ) {
                $process['pid'] = $file['name'];
                $process['cmd'] = file_get_contents($this->ldir."/".$file['name']."/cmdline");
                if ( $process['cmd']=="") continue;
                $processes[] = $process;
            }
        }*/
        $processes = shell_exec("ps ax -o stat,args |wc -l");
        return $processes;
    }

    
    /**
     *  Return process details
     *      - cmd : Command that launch this process
     *      - cwd : Current working directory *
     *      - exe : Executable path *
     *      - root: Root dir, usually / or anyother if the process
     *        run with chroot. *
     *      - write bytes
     *      - read bytes
     *      - opened_files: A list of files opened by this process
     *
     *  * = Only avaiable if the user can access this information. 
     *  
     *  @return array|false
     */
    function getProcessDetail($pid) {
        $dir = $this->ldir."/".$pid;
        if ( !is_dir($dir) ) return false;
        $info=array();
        $info['cmd'] = file_get_contents("${dir}/cmdline");
        foreach(array('cwd','exe','root') as $proc) {
            $r = @readlink("${dir}/$proc");;
            if ( !$r) continue;
            $info[$proc] = $r;
        }
        /* IO stats */
        $io_stat = $this->parsefile("${dir}/io");
        $info['write bytes'] = $io_stat['write_bytes'];
        $info['read bytes']  = $io_stat['read_bytes'];
        /* opened files */
        $info['opened_files'] = &$fd;
        $fd = array();
        for($i=0; $i < 2000;$i++){
            $rl = @readlink("${dir}/fd/${i}");
            if ( !$rl ) continue;
            $fd[$i] = $rl;
        }
        return $info;
    }
    
    /**
     *  Parse a file by key : value
     *  @private
     */
    function parsefile($file) {
        //$content = file_get_contents($file);
        $content = shell_exec("cat ".$file);
        $info=array();
        foreach( explode("\n",$content) as $line) {
            $pos = strpos($line,":");
            $key = trim( substr($line,0,$pos) );
            $val = trim( substr($line,$pos+1) );
            if ( $key=="") continue;
            $info[$key] = $val;
        }
        return $info;        
    }
    /**
     * @private
     */
    function getDirFiles($pdir) {
        $dir = opendir($pdir);
        $files = array();
        while ( $f = readdir($dir) ) {
            if ( $f=="." || $f == "..") continue;
            $files[] = array('name'=>$f,'dir'=>is_dir("${pdir}/${f}"));
        }
        closedir($dir);
        return $files;
    }
}

//$stats = new linuxstat;
//$output["cpu"] = $stats->getCpuInfo();
//$output["memory"] = $stats->getMemStat();
//$output["uptime"] = $stats->getUptime();
//$output["services"] = $stats->getServiceStatus();
//$output["speed"] = $stats->getPortLink();
//$output["processes"] = count($stats->getProcesses());

?>