<?php

/**
 * User Model
 * 
 * This model is used to interact with the "user" table in the database.
 * 
 * @author	Romain Lacits
 * @license	https://opensource.org/license/mit
 * @link https://codeigniter.com/userguide3/general/models.html
 */
class User_model extends CI_Model
{
    /**
     * List of professional status
     *
     * @var	array
     */
    public $professional_status_list =    [
        'particulier',
        'auto-entrepreneur',
        'indépendant',
        'entreprise',
        'association à but non lucratif'
    ];

    /**
     * Firstname of the current user
     *
     * @var	string
     */
    public $firstname;

    /**
     * Lastname of the current user
     * 
     * @var string
     * max_length[50]
     * required
     */
    public $lastname;

    /**
     * Email of the current user
     * 
     * @var string
     * max_length[128]
     * required
     * valid_email
     * is_unique[user.email]
     */
    public $email;

    /**
     * Phone of the current user
     * 
     * @var int
     */
    public $phone;

    /**
     * Address of the current user
     * 
     * @var string
     */
    public $address;

    /**
     * Professional status of the current user
     * 
     * @var string
     */
    public $professional_status;

    /**
     * Last connexion of the current user
     * 
     * @var datetime
     */
    public $last_connexion;

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Load the database library
        $this->load->database();
    }

    // ------------------------------------------------------------------------

    /**
     * Get the list of professional status
     *
     * @return array
     */
    public function getProfessionalStatusList()
    {
        return $this->professional_status_list;
    }

    /**
     * Get the firstname of the current user
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the firstname of the current user
     *
     * @param string $firstname
     * @return void
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * Get the lastname of the current user
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the lastname of the current user
     *
     * @param string $lastname
     * @return void
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * Get the email of the current user
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the email of the current user
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get the phone of the current user
     *
     * @return int
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the phone of the current user
     *
     * @param int $phone
     * @return void
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get the address of the current user
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the address of the current user
     *
     * @param string $address
     * @return void
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get the professional status of the current user
     *
     * @return string
     */
    public function getProfessionalStatus()
    {
        return $this->professional_status;
    }

    /**
     * Set the professional status of the current user
     *
     * @param string $professional_status
     * @return void
     */
    public function setProfessionalStatus($professional_status)
    {
        $this->professional_status = $professional_status;
    }

    /**
     * Get the last connexion of the current user
     *
     * @return datetime
     */
    public function getLastConnexion()
    {
        return $this->last_connexion;
    }

    /**
     * Set the last connexion of the current user
     *
     * @param datetime $last_connexion
     * @return void
     */
    public function setLastConnexion($last_connexion)
    {
        $this->last_connexion = $last_connexion;
    }

    // ------------------------------------------------------------------------

    /**
     * Get total number of users with filters
     * 
     * @param string $firstname - The firstname of the user
     * @param string $lastname - The lastname of the user
     *
     * @return int
     */
    public function count_with_filters(string $firstname, string $lastname): int
    {
        // Count the number of users
        $this->db->from('user');

        // Add the like clause
        $this->db->like(['firstname' => $firstname, 'lastname' => $lastname]);

        // Return the number of users
        return $this->db->count_all_results();
    }

    // ------------------------------------------------------------------------

    /**
     * Count the number of outdated users with no connexion since $date
     * 
     * @param string $date - The date
     *
     * @return int - Return the number of outdated users
     */
    public function count_outdated(string $date): int
    {
        // Count the number of users
        $this->db->from('user');

        // Where last connexion is older than $date
        $this->db->where(['last_connexion <' => $date]);

        // Return the number of users
        return $this->db->count_all_results();
    }

    // ------------------------------------------------------------------------

    /**
     * Get the list of users
     *
     * @param string $sort - The column to sort by
     * @param string $order - The order to sort by
     * @param int $limit - The number of users to show per page
     * @param int $offset - The offset to start from
     * @param string $firstname - The firstname of the user
     * @param string $lastname - The lastname of the user
     * 
     * @return array - Return an array of users
     * @return bool - Return false if query failed
     */
    public function list(string $sort = 'id', string $order = 'ASC', int $limit = 10, int $offset = 20, string $firstname, string $lastname): array|bool
    {
        // Add the order by clause
        $this->db->order_by($sort, $order);

        // Add the like clause
        $this->db->like(['firstname' => $firstname, 'lastname' => $lastname]);

        // Get the list of users
        $query = $this->db->get('user', $limit, $offset);

        return ($query) ? $query->result() : false;
    }

    // ------------------------------------------------------------------------

    /**
     * Find a unique user by id
     *
     * @param int $id - The id of the user to find
     * 
     * @return User_model - Return the user if found
     * @return null - Return null if the user is not found
     */
    public function find_one_by_id(int $id): User_model|null
    {
        return $this->db->get_where('user', ['id' => $id])->custom_row_object(0, 'User_model');
    }

    // ------------------------------------------------------------------------

    /**
     * Insert a new user in the database
     *
     * @param User_model $user - One User_model to insert
     * @param ArrayObject $user - An ArrayObject of User_model to insert
     * 
     * @return bool - Return true if the user has been inserted else return false
     */
    public function insert(User_model|ArrayObject $data): bool
    {
        // Insert only one user
        if ($data instanceof User_model) {
            return $this->db->Insert('user', $data);
        }

        // Insert many users as a transaction block
        $error = false;
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        // Insert each user
        foreach ($data as $user) {

            // If an error occurred on the current user, break the loop
            if (!$this->db->insert('user', $user)) {
                $error = true;
                break;
            }
        }

        // If an error occurred, rollback the transaction
        if ($error === true) {
            $this->db->trans_rollback();
            return false;
        }

        // Commit the transaction
        return $this->db->trans_complete();
    }

    // ------------------------------------------------------------------------

    /**
     * Update a user in the database
     * 
     * @param User_model $user - The user to update
     * 
     * @return bool - Return true if the user has been updated, else return false
     */
    public function update(User_model $user): bool
    {
        return $this->db->replace('user', $user);
    }

    // ------------------------------------------------------------------------

    /**
     * Delete user(s) in the database
     *
     * @param array $where - The where clause
     * 
     * @return bool - Return true if user(s) has been deleted, else return false
     */
    public function delete(array $where = []): bool
    {
        return $this->db->delete('user', $where);
    }

    // ------------------------------------------------------------------------

    /**
     * Delete a user by id
     *
     * @param int $id - The id of the user to delete
     * 
     * @return bool - Return true if user has been deleted, else return false
     */
    public function delete_by_id(int $id): bool
    {
        return $this->db->delete('user', ['id' => $id]);
    }

    // ------------------------------------------------------------------------

    /**
     * Delete outdated users with no connexion since $date
     *
     * @param string $date - The date
     * 
     * @return bool - Return true if user has been deleted, else return false
     */
    public function delete_outdated(string $date): bool
    {
        return $this->db->delete('user', ['last_connexion <' => $date]);
    }

    // ------------------------------------------------------------------------
}
