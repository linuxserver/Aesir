<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Aesir v1.0
 *
 * Web GUI for unRAID
 *
 * @package     Aesir v1.0
 * @author      Kode
 * @copyright   Copyright (c) 2015 coderior.com
 * @link        http://coderior.com
 * @since       Version 1.0
 */

 // --------------------------------------------------------------------

/**
 * Users_model class
 *
 * Model for user specific functions
 *
 * @package     Aesir v1.0
 * @subpackage  Models
 * @author      Kode
 */

class User_model extends CI_Model {

	public function __construct() {        
	    parent::__construct();

	}
	
    /**
     * get user details by id
     *
     * @access	public
     * @param	string		$user_id id of user
     * @return array returns object if there are results and false if there aren't
     */
	public function get_user_by_id( $user_id ) {
		/*$this->db->select( 'user_id, user_email' );
		$user = $this->db->get_where('users', array('user_id' => $user_id));
		if ($user->num_rows() > 0) {
			// file already exists so no need to move the file
			return $user->row();

		} else {
			// No user found
			return false;
		}*/
		

	}

    /**
     * save users email address
     *
     * @access	public
     * @param	integer		$user_id id of user
     * @param	string		$user_email email address to save
     * @return void
     */
	public function save_email( $user_id, $user_email ) {

		/*$data = array(
			'user_email' => $user_email
		);

		$this->db->where('user_id',$user_id);
   		$q = $this->db->get('users');

		if ( $q->num_rows() > 0 ) {
	      $this->db->where('user_id',$user_id);
	      $this->db->update('users',$data);
		} else {
	      $this->db->set('user_id', $user_id);
	      $this->db->insert('users',$data);
		}*/

	}



}
