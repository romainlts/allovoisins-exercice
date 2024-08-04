<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

		// // If an error occurred, then fall back to the previous method
		// if ($salt === FALSE) {
		// 	$salt = hash('sha256', time() . mt_rand());
		// }

		// $new_key = substr($salt, 0, config_item('rest_key_length'));
		// echo $new_key;
		$this->load->view('welcome_message');
	}
}
