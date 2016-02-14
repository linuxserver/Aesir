<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Aesir
 *
 * A redesigned WebGUI for unRAID
 *
 * @package     Aesir
 * @author      Kode
 * @copyright   Copyright (c) 2014 Kode (admin@coderior.com)
 * @link        http://coderior.com
 * @since       Version 1.0
 */

 // --------------------------------------------------------------------

/**
 * Backups class
 *
 * Simple solution to backup remote servers
 *
 * @package     Aesir
 * @subpackage  Modules
 * @author      Kode
 */
class Backups extends MY_Controller {

	public $container = false; // docker ps and find the rsnapshot image maybe?
	public $stoperrors = false; // errors that will stop the module from working, don't display anything else
	public $notices = false; // errors or notices that are informational
	public $servers;

    function __construct()
    {
        parent::__construct();
        $this->load->language('backups');
        $this->load->model('backups_model');
        $this->load->helper( 'docker/docker' );
        
	$defercreate = false;
	if( !file_exists( APPPATH.'database/language.db' ) ) $defercreate = true;
		
	$config['database'] = APPPATH.'database/backups.db';
	$config['dbdriver'] = 'sqlite3';
	$setdb = $this->load->database($config, true);
	$this->backups_model->db = $setdb;
	$this->db = $setdb; // without this dbforge doesn't work
		
	if( $defercreate ) $this->backups_model->create_backups_table(); // datbase is silently created if it doesn't exist, $defercreate ensures it's populated if it didn't exist to begin with

	$this->servers = $this->backups_model->get_servers();

    }	

    public function get_container()
    {
    	
    	$containers = getDockerJSON( '/containers/json' );
    	foreach ( $containers as $container ) {
    		if ( $container['Image'] == 'lsiodev/rsnapshot' ) return $container['Id'];
    	}
    	return false;
    }

    public function install_docker()
    {
    	if( $_POST ) {
    		//docker run -d --name=rsnapshot -v /mnt/cache/appdata/rsnapshot:/data  lsiodev/rsnapshot
    		$image = "lsiodev/rsnapshot";
    		$backup_path = $this->input->post('backup_location');
    		/*$pull = (object)array(
    			"Image" => $image,
    			"Volumes" => (object)array( 
    				"/backups" => (object)array( $backup_path ),
    				"/config" => (object)array( "/mnt/appdata/aesir-snapshot" )
    			)

    		);


    		//$build = do_post_request( '/containers/create?name=aesir-rsnapshot', $pull );
    		//$build = CallAPI( 'POST', '/containers/create?name=aesir-rsnapshot', $pull );
    		getDockerJSON( '/images/create?fromImage='.$image.'&tag=latest', 'POST' );
    		$build = getDockerJSON( '/containers/create?name=aesir-rsnapshot', 'POST', $pull );
    		$build_json = json_decode( $build );
    		getDockerJSON( '/containers/'.$build_json['Id'].'/start', 'POST' );*/
    		exec( 'docker run -d --name=aesir-rsnapshot -v /mnt/cache/appdata/aesir-rsnapshot:/config -v '.$backup_path.':/backups  '.$image );
    		$this->backups_model->set_backup_location( $backup_path );
    		redirect( 'backups' );
    	}
 		$header_data['page_title'] = __( 'Install Module' );
		$this->load->view( 'header', $header_data );

		$this->load->view( 'install_docker' );

		$this->load->view( 'footer' );   	
    }
   public function settings()
    {
    	if( $_POST ) {
    	}
    	$data['settings'] = $this->backups_model->get_settings();
 		$header_data['page_title'] = __( 'Backups Settings' );
		$this->load->view( 'header', $header_data );
		$this->load->view( 'settings', $data );
		$this->load->view( 'footer' );   	
    }

	public function index()
	{
		$this->container = $this->get_container();
        if( $this->container === false ) {
        	$this->stoperrors[] = 'Required backup module is missing, <a href="/backups/install_docker">Install now?</a>';
        }

		$header_data['page_title'] = __( 'Backups' );
		$this->load->view( 'header', $header_data );

		if( $this->stoperrors !== false ) $this->load->view( 'stoperrors', [ 'stoperrors' => $this->stoperrors ] );
		else $this->load->view( 'index' );

		$this->load->view( 'footer' );
	}

	public function add_server()
	{
	    $this->container = $this->get_container();
        if( $this->container === false ) {
        	//$this->stoperrors[] = 'Required backup module is missing, <a href="/backups/install_docker">Install now?</a>';
        }

		$header_data['page_title'] = __( 'Add Server' );
		if( $_POST ) {
			$server_ip = $this->input->post('server_address');
			$server_password = "'".$this->input->post('server_password')."'";
			unset( $output );

			$cmd = 'docker exec -t '.$this->container.' /usr/bin/expect ~/.ssh/addserver.expect root '.$server_ip.' '.$server_password;
			
			exec( $cmd, $output );
			
			if( $this->successfully_added( $output ) ) {
				// add server to database
				$name = $this->input->post('server_name');
				$address = $this->input->post('server_address');
				$server_id = $this->backups_model->add_server( $name, $address );
				redirect('backups/server_backups/'.$server_id);
				exit;
			} else {
				// something went wrong
			}
			echo "c: ".$cmd;
			print_r( $output );
		}
		$this->load->view( 'header', $header_data );
		if( $this->stoperrors !== false ) $this->load->view( 'stoperrors', [ 'stoperrors' => $this->stoperrors ] );
		else $this->load->view( 'add_server' );
		$this->load->view( 'footer' );

	}

	public function server_backups( $server_id )
	{
		if( $_POST ) {
			$backups = $this->input->post('backup');
			$this->backups_model->set_backups( $server_id, $backups );
			redirect( 'backups' );
		}
		$data['server'] = $this->backups_model->get_server( $server_id );
		$data['backup_list'] = $this->backups_model->backup_list( $server_id );

		$header_data['page_title'] = __( 'Backup ' ).' '.$data['server']->server_name;

		$this->load->view( 'header', $header_data );
		$this->load->view( 'server_backups', $data );
		$this->load->view( 'footer' );		
	}

	private function successfully_added( $output ) {
		$success = array(
			'Number of key(s) added: 1',
			'/usr/bin/ssh-copy-id: WARNING: All keys were skipped because they already exist on the remote system.'
		);
		return (bool)array_intersect( $success, $output );
	}
	private function create_config()
	{
		$config = '
config_version	1.2
snapshot_root	'.$settings->settings_backup_path.'
no_create_root	1
cmd_cp		/bin/cp
cmd_rm		/bin/rm
cmd_rsync	/usr/bin/rsync
cmd_ssh	/usr/bin/ssh
'.$settings->settings_preexec.'
'.$settings->settings_postexec.'
retain	alpha	'.$settings->settings_alpha.'
retain	beta	'.$settings->settings_beta.'
retain	gamma	'.$settings->settings_gamma.'
retain	delta	'.$settings->settings_delta.'

############################################
#              GLOBAL OPTIONS              #
# All are optional, with sensible defaults #
############################################

# Verbose level, 1 through 5.
# 1     Quiet           Print fatal errors only
# 2     Default         Print errors and warnings only
# 3     Verbose         Show equivalent shell commands being executed
# 4     Extra Verbose   Show extra verbose information
# 5     Debug mode      Everything
#
verbose		2

# Same as "verbose" above, but controls the amount of data sent to the
# logfile, if one is being used. The default is 3.
#
loglevel	3

# If you enable this, data will be written to the file you specify. The
# amount of data written is controlled by the "loglevel" parameter.
#
#logfile	/var/log/rsnapshot

# If enabled, rsnapshot will write a lockfile to prevent two instances
# from running simultaneously (and messing up the snapshot_root).
# If you enable this, make sure the lockfile directory is not world
# writable. Otherwise anyone can prevent the program from running.
#
lockfile	/var/run/rsnapshot.pid

# By default, rsnapshot check lockfile, check if PID is running
# and if not, consider lockfile as stale, then start
# Enabling this stop rsnapshot if PID in lockfile is not running
#
#stop_on_stale_lockfile		0

# Default rsync args. All rsync commands have at least these options set.
#
#rsync_short_args	-a
#rsync_long_args	--delete --numeric-ids --relative --delete-excluded

# ssh has no args passed by default, but you can specify some here.
#
#ssh_args	-p 22

# Default arguments for the "du" program (for disk space reporting).
# The GNU version of "du" is preferred. See the man page for more details.
# If your version of "du" doesnt support the -h flag, try -k flag instead.
#
#du_args	-csh

# If this is enabled, rsync wont span filesystem partitions within a
# backup point. This essentially passes the -x option to rsync.
# The default is 0 (off).
#
#one_fs		0

# The include and exclude parameters, if enabled, simply get passed directly
# to rsync. If you have multiple include/exclude patterns, put each one on a
# separate line. Please look up the --include and --exclude options in the
# rsync man page for more details on how to specify file name patterns. 
# 
#include	???
#include	???
#exclude	???
#exclude	???

# The include_file and exclude_file parameters, if enabled, simply get
# passed directly to rsync. Please look up the --include-from and
# --exclude-from options in the rsync man page for more details.
#
#include_file	/path/to/include/file
#exclude_file	/path/to/exclude/file

# If your version of rsync supports --link-dest, consider enabling this.
# This is the best way to support special files (FIFOs, etc) cross-platform.
# The default is 0 (off).
#
#link_dest	0

# When sync_first is enabled, it changes the default behaviour of rsnapshot.
# Normally, when rsnapshot is called with its lowest interval
# (i.e.: "rsnapshot alpha"), it will sync files AND rotate the lowest
# intervals. With sync_first enabled, "rsnapshot sync" handles the file sync,
# and all interval calls simply rotate files. See the man page for more
# details. The default is 0 (off).
#
#sync_first	0

# If enabled, rsnapshot will move the oldest directory for each interval
# to [interval_name].delete, then it will remove the lockfile and delete
# that directory just before it exits. The default is 0 (off).
#
#use_lazy_deletes	0

# Number of rsync re-tries. If you experience any network problems or
# network card issues that tend to cause ssh to fail with errors like
# "Corrupted MAC on input", for example, set this to a non-zero value
# to have the rsync operation re-tried.
#
#rsync_numtries 0

# LVM parameters. Used to backup with creating lvm snapshot before backup
# and removing it after. This should ensure consistency of data in some special
# cases
#
# LVM snapshot(s) size (lvcreate --size option).
#
#linux_lvm_snapshotsize	100M

# Name to be used when creating the LVM logical volume snapshot(s).
#
#linux_lvm_snapshotname	rsnapshot

# Path to the LVM Volume Groups.
#
#linux_lvm_vgpath	/dev

# Mount point to use to temporarily mount the snapshot(s).
#
#linux_lvm_mountpath	/path/to/mount/lvm/snapshot/during/backup

###############################
### BACKUP POINTS / SCRIPTS ###
###############################

# LOCALHOST
backup	/home/		localhost/
backup	/etc/		localhost/
backup	/usr/local/	localhost/
#backup	/var/log/rsnapshot		localhost/
#backup	/etc/passwd	localhost/
#backup	/home/foo/My Documents/		localhost/
#backup	/foo/bar/	localhost/	one_fs=1, rsync_short_args=-urltvpog
#backup_script	/usr/local/bin/backup_pgsql.sh	localhost/postgres/
# You must set linux_lvm_* parameters below before using lvm snapshots
#backup	lvm://vg0/xen-home/	lvm-vg0/xen-home/

# EXAMPLE.COM
#backup_exec	/bin/date "+ backup of example.com started at %c"
#backup	root@example.com:/home/	example.com/	+rsync_long_args=--bwlimit=16,exclude=core
#backup	root@example.com:/etc/	example.com/	exclude=mtab,exclude=core
#backup_exec	ssh root@example.com "mysqldump -A > /var/db/dump/mysql.sql"
#backup	root@example.com:/var/db/dump/	example.com/
#backup_exec	/bin/date "+ backup of example.com ended at %c"

# CVS.SOURCEFORGE.NET
#backup_script	/usr/local/bin/backup_rsnapshot_cvsroot.sh	rsnapshot.cvs.sourceforge.net/

# RSYNC.SAMBA.ORG
#backup	rsync://rsync.samba.org/rsyncftp/	rsync.samba.org/rsyncftp/

';
	}
}
