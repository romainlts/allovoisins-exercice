<?php

/**
 * API User Class
 * Handle user related API requests.
 * 
 * @author	Romain Lacits
 * @license	https://opensource.org/license/mit
 */

use chriskacerguis\RestServer\RestController;

defined('BASEPATH') or exit('No direct script access allowed');

class Fo_user extends RestController
{
	/**
	 * User model
	 *
	 * @var User_model
	 */
	public $user_model;

	/**
	 * Form validation library
	 * 
	 * @var CI_Form_validation
	 */
	public $form_validation;

	// ------------------------------------------------------------------------

	/**
	 * Constructor
	 * 
	 * Initializes the User controller, loads the user model and form validation library,
	 * and sets the request level and limits for the API methods.
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		// 500 requests per hour per user/key
		$this->methods['list_get']['level'] = 0;
		$this->methods['add_post']['level'] = 0;
		// 500 requests per hour per user/key
		$this->methods['list_get']['limit'] = 500;

		$this->load->library('form_validation');
		$this->load->model('user_model');

		$this->form_validation->set_error_delimiters('', '');
	}

	// ------------------------------------------------------------------------

	/**
	 * Register a new user
	 * 
	 * This method handles POST requests to register a new user.
	 * 
	 * @return void
	 * 
	 * @example	POST /	api/v1/user/registration
	 * 
	 * @apiParam POST parameters:
	 * 		@apiParam {String} firstname
	 * 		@apiParam {String} lastname
	 * 		@apiParam {String} email
	 * 		@apiParam {String} phone
	 * 		@apiParam {String} address
	 * 		@apiParam {String} professional_status
	 * 
	 * @apiSuccess {String} Success message. HTTP code 201.
	 * @apiError {String} Error message. HTTP code 400.
	 */
	public function registration_post()
	{
		// Handle POST parameters and validate them through the form_validation library 
		// and the user_registration form_validation configuration available in application/config/form_validation.php
		$this->form_validation->set_data([
			'firstname' => $this->post('firstname'),
			'lastname' => $this->post('lastname'),
			'email' => $this->post('email'),
			'phone' => $this->post('phone'),
			'address' => $this->post('address'),
			'professional_status' => $this->post('professional_status')
		]);

		// Check if the form_validation rules are respected
		if ($this->form_validation->run('user_registration') == FALSE) {
			// If not, return the validation errors
			$this->set_response(validation_errors(), RestController::HTTP_BAD_REQUEST);
		} else {
			// If the validation is successful, create a new user
			$new_user = new User_model();
			$new_user->setFirstname($this->post('firstname'));
			$new_user->setLastname($this->post('lastname'));
			$new_user->setEmail($this->post('email'));
			$new_user->setPhone($this->post('phone'));
			$new_user->setAddress($this->post('address'));
			$new_user->setProfessionalStatus($this->post('professional_status'));
			$new_user->setLastConnexion(date('Y-m-d H:i:s'));
			// Insert the new user
			$result = $this->user_model->insert($new_user);
			// Return the result of the insertion
			if ($result === true) {
				$this->set_response("User added successfully", RestController::HTTP_CREATED);
			} else {
				$this->set_response("Error: " . $result['message'], RestController::HTTP_BAD_REQUEST);
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Update a specific User
	 * 
	 * This method handles PUT requests to update a specific user.
	 * 
	 * @return void
	 * 
	 * @example	PUT /	api/v1/user/update
	 * 
	 * @apiParam GET parameters:
	 * 		@apiParam {Int} id
	 * 
	 * @apiParam POST parameters:
	 * 		@apiParam {String} firstname
	 * 		@apiParam {String} lastname
	 * 		@apiParam {String} email
	 * 		@apiParam {String} phone
	 * 		@apiParam {String} address
	 * 		@apiParam {String} professional_status
	 * 
	 * @apiSuccess {String} Success message. HTTP code 201.
	 * @apiError {String} Error message. HTTP code 400.
	 */
	public function update_put()
	{
		// Check if the user ID is provided
		if ($this->input->get('id') === null) {
			// If not, return an error message
			$this->set_response("User ID is required", RestController::HTTP_BAD_REQUEST);
		} else {

			// Handle GET parameters and validate them through the form_validation library 
			// and the id form_validation configuration available in application/config/form_validation.php
			$this->form_validation->set_data([
				'id' => $this->input->get('id')
			]);

			// Check if the form_validation rules are respected
			if ($this->form_validation->run('id') == FALSE) {
				// If not, return the validation errors
				$this->set_response(validation_errors(), RestController::HTTP_BAD_REQUEST);
			} else {

				// Find the user to update
				$user = $this->user_model->find_one_by_id($this->input->get('id'));
				if (is_array($user)) {
					$this->set_response("Error: " . $user['message'], RestController::HTTP_BAD_REQUEST);
				} else {
					// Handle POST parameters and validate them through the form_validation library 
					// and the user_update form_validation configuration available in application/config/form_validation.php
					$this->form_validation->set_data([
						'firstname' => $this->put('firstname'),
						'lastname' => $this->put('lastname'),
						'email' => $this->put('email'),
						'phone' => $this->put('phone'),
						'address' => $this->put('address'),
						'professional_status' => $this->put('professional_status')
					]);

					// Check if the form_validation rules are respected
					if ($this->form_validation->run('user_update') == FALSE) {
						// If not, return the validation errors
						$this->set_response(validation_errors(), RestController::HTTP_BAD_REQUEST);
					} else {
						// If the validation is successful, update the user
						if ($this->put('firstname'))
							$user->setFirstname($this->put('firstname'));
						if ($this->put('lastname'))
							$user->setLastname($this->put('lastname'));
						if ($this->put('email'))
							$user->setEmail($this->put('email'));
						if ($this->put('phone'))
							$user->setPhone($this->put('phone'));
						if ($this->put('address'))
							$user->setAddress($this->put('address'));
						if ($this->put('professional_status'))
							$user->setProfessionalStatus($this->put('professional_status'));
						$user->setLastConnexion(date('Y-m-d H:i:s'));
						// Update the user
						$result = $this->user_model->update($user);
						// Return the result of the update
						if ($result === true) {
							$this->set_response("User updated successfully", RestController::HTTP_CREATED);
						} else {
							$this->set_response("Error: " . $result['message'], RestController::HTTP_BAD_REQUEST);
						}
					}
				}
			}
		}
	}

	// ------------------------------------------------------------------------
}
