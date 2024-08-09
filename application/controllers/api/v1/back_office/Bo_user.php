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

class Bo_user extends RestController
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

		$this->load->library('form_validation');
		$this->load->model('user_model');

		$this->form_validation->set_error_delimiters('', '');
	}

	// ------------------------------------------------------------------------

	/**
	 * List users
	 * 
	 * This method handles GET requests to list users.
	 * 
	 * @return void
	 * 
	 * @example	GET /	api/v1/bo/user/list
	 * 
	 * @apiParam GET parameters:
	 * 		@apiParam {Int} page
	 * 		@apiParam {Int} show
	 * 		@apiParam {String} sort
	 * 		@apiParam {String} order
	 * 		
	 * 		@apiParam {String} firstname
	 * 		@apiParam {String} lastname
	 * 
	 * @apiSuccess {String} Success message. HTTP code 201.
	 * @apiError {String} Error message. HTTP code 400.
	 */
	public function list_get()
	{
		// Determine page number
		$page = ($this->input->get('page') !== null && (int) $this->input->get('page') !== 0) ? $this->input->get('page') : 1;

		// Number of users to show per page
		$show = ($this->input->get('show') !== null && (int) $this->input->get('show') !== 0) ? $this->input->get('show') : 10;

		// Determine sorting column
		$sort = ($this->input->get('sort') !== null) ? $this->input->get('sort') : 'id';

		// Determine sorting order
		$order = ($this->input->get('order') !== null) ? $this->input->get('order') : 'ASC';

		// Prepare the where clause
		$where = [];

		// Prepare the like clause
		$like = [];
		if ($this->input->get('firstname') !== null)
			$like['firstname'] = (string)$this->input->get('firstname');
		if ($this->input->get('lastname') !== null)
			$like['lastname'] = (string)$this->input->get('lastname');

		// Get total number of users
		$total = $this->user_model->count($where, $like);

		// Get number of pages
		$total_pages = ceil($total / $show);

		// Check if the page number is valid
		$page = ($page > $total_pages) ? $total_pages : $page;

		// Prepare the data to return
		$data = [
			'current_page' => $page,
			'total_pages' => $total_pages,
			'total_users' => $total
		];

		// Get the list of users
		$data['users'] = $this->user_model->list($sort, $order, $show, ($page - 1) * $show, [], $like);

		// if an error occurred, return an error message and HTTP code 404
		if ($data['users'] === false) {
			$this->set_response("Users not found for these search criteria", RestController::HTTP_NOT_FOUND);
			return;
		}

		// Return the list of users and HTTP code 200
		$this->set_response($data, RestController::HTTP_OK);
	}

	// ------------------------------------------------------------------------

	/**
	 * Update a specific User
	 * 
	 * This method handles PUT requests to update a specific user.
	 * 
	 * @return void
	 * 
	 * @example	PUT /	api/v1/bo/user/update
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
		// Check if the user ID is provided, if not return an error message
		if ($this->input->get('id') === null) {
			$this->set_response("User ID is required", RestController::HTTP_BAD_REQUEST);
			return;
		}

		// Handle GET parameters and validate them through the form_validation library, configuration available in application/config/form_validation.php
		$this->form_validation->set_data([
			'id' => $this->input->get('id')
		]);

		// Check if the form_validation rules are respected, if not return the validation errors
		if ($this->form_validation->run('id') === FALSE) {
			$this->set_response(validation_errors(), RestController::HTTP_BAD_REQUEST);
			return;
		}

		// Find the user to update
		$user = $this->user_model->find_one_by_id($this->input->get('id'));

		// If the user is not found, return an error message with HTTP code 404
		if ($user === null) {
			$this->set_response("User not found", RestController::HTTP_NOT_FOUND);
			return;
		}

		// Handle POST parameters and validate them through the form_validation library, configuration available in application/config/form_validation.php
		$this->form_validation->set_data([
			'firstname' => $this->put('firstname'),
			'lastname' => $this->put('lastname'),
			'email' => $this->put('email'),
			'phone' => $this->put('phone'),
			'address' => $this->put('address'),
			'professional_status' => $this->put('professional_status')
		]);

		// Check if the form_validation rules are respected, if not return the validation errors
		if ($this->form_validation->run('user_update') === FALSE) {
			$this->set_response(validation_errors(), RestController::HTTP_BAD_REQUEST);
			return;
		}

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

		// Update the user
		$result = $this->user_model->update($user);

		// If the update failed, return an error message with HTTP code 500
		if (!$result) {
			$this->set_response("An internal error occurred, please contact administrator", RestController::HTTP_INTERNAL_ERROR);
			return;
		}

		// Return a success response with HTTP code 201
		$this->set_response("User updated successfully", RestController::HTTP_CREATED);
	}

	// ------------------------------------------------------------------------

	/**
	 * Remove a specific user
	 * 
	 * This method handles DELETE requests to remove a specific user.
	 * 
	 * @return void
	 * 
	 * @example	DELETE /	api/v1/bo/user/remove
	 * 
	 * @apiParam GET parameters:
	 * 		@apiParam {Int} id
	 * 
	 * @apiSuccess {String} Success message. HTTP code 201.
	 * @apiError {String} Error message. HTTP code 400.
	 */
	public function remove_delete()
	{
		// Check if the user ID is provided, if not return an error message
		if ($this->input->get('id') === null) {
			$this->set_response("User ID is missing", RestController::HTTP_BAD_REQUEST);
			return;
		}

		// Handle GET parameters and validate them through the form_validation library, configuration available in application/config/form_validation.php
		$this->form_validation->set_data([
			'id' => $this->input->get('id')
		]);

		// Check if the form_validation rules are respected, if not return the validation errors
		if ($this->form_validation->run('id') === FALSE) {
			$this->set_response(validation_errors(), RestController::HTTP_BAD_REQUEST);
			return;
		}

		// Find the user to update
		$user = $this->user_model->find_one_by_id($this->input->get('id'));

		// If the user is not found, return an error message with HTTP code 404
		if ($user === null) {
			$this->set_response("User not found", RestController::HTTP_NOT_FOUND);
			return;
		}

		// Delete the user
		$result = $this->user_model->delete(['id' => $this->input->get('id')]);

		// If the update failed, return an error message with HTTP code 500
		if (!$result) {
			$this->set_response("An internal error occurred, please contact administrator", RestController::HTTP_INTERNAL_ERROR);
			return;
		}

		// Return a success response with HTTP code 201
		$this->set_response("User deleted successfully", RestController::HTTP_CREATED);
	}

	// ------------------------------------------------------------------------
}
