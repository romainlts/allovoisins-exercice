<?php

/**
 * CLI User Controller
 * Command line interface controller for User
 * 
 *
 * @author	Romain Lacits
 * @license	https://opensource.org/license/mit
 */

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

	/**
	 * User model
	 *
	 * @var User_model
	 */
	public $user_model;

	// ------------------------------------------------------------------------

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Restrict access to CLI only
		if (!$this->input->is_cli_request()) {
			show_error('This can only be accessed via the command line.', 403);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Remove outdated users with no connexion since 36 months
	 * @example php index.php cli/user outdated_users
	 * 
	 * @return void
	 */
	public function outdated_users(): void
	{
		echo "cli/user : Remove outdated users with no connexion since 36 months. \n";

		// Load the user model
		$this->load->model('user_model');

		// Get the date 36 months ago
		$date_outdated = new DateTime('now', new DateTimeZone('Europe/Paris'));
		$date_outdated->modify('-36 months');
		$date = $date_outdated->format('Y-m-d H:i:s');

		// Count outdated users
		$nb_outdated_users = $this->user_model->count_outdated($date);

		// Display the number of outdated users
		echo "$nb_outdated_users outdated users found. \n";

		// Delete outdated users
		if ($nb_outdated_users > 0) {
			echo "Proceed to deletion... \n";
			$this->user_model->delete_outdated($date);
			echo "Deletion completed. \n";
		} else {
			echo "No outdated users to delete. \n";
		}
	}

	// ------------------------------------------------------------------------
}
