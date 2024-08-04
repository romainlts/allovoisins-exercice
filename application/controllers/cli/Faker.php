<?php

/**
 * Faker Class
 * Generate fake data for testing with Faker.
 *
 * @author	Romain Lacits
 * @license	https://opensource.org/license/mit
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Faker extends CI_Controller
{
	/**
	 * Faker instance
	 *
	 * @var Faker\Generator
	 */
	protected $faker;

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

		// use the factory to create a Faker\Generator instance
		$this->faker = Faker\Factory::create('fr_FR');
	}

	// ------------------------------------------------------------------------

	/**
	 * Generate fake users for the database table "user"
	 * @example php index.php cli/faker generate_users 10
	 * 
	 * @param Int $nb_users Number of users to generate
	 * @return void
	 * 
	 */
	public function generate_users(Int $nb_users = 10): void
	{
		// Load the user model
		$this->load->model('user_model');

		// Create an empty array of users
		$users = new ArrayObject();

		// Generate $nb_users fake users
		for ($i = 0; $i < $nb_users; $i++) {
			$new_user = new User_model();
			$new_user->setFirstname($this->faker->firstName);
			$new_user->setLastname($this->faker->lastName);
			$new_user->setEmail($this->faker->unique()->email);
			$new_user->setPhone($this->faker->phoneNumber);
			$new_user->setAddress($this->faker->address);
			$new_user->setProfessionalStatus($this->faker->randomElement($this->user_model->professional_status_list));
			$new_user->setLastConnexion($this->faker->dateTimeBetween('-10 years', 'now', 'Europe/Paris')->format('Y-m-d H:i:s'));
			$users->append($new_user);
		}

		// Insert the users in the database
		$result = $this->user_model->insert($users);

		// Display the result of the insertion
		if ($result === true) {
			echo "Faker : Generated $nb_users users.\n";
		} else {
			echo "Faker : Error " . $result['code'] . " : " . $result['message'] . "\n";
		}
	}

	// ------------------------------------------------------------------------
}
