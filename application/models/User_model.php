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
     * Get total number of users
     * 
     * 
     * @param array $where - The where clause
     * @param array $like - The like clause
     *
     * @return int
     */
    public function count(array $where = [], array $like = []): int
    {
        // Count the number of users
        $this->db->from('user');

        // Add the where clause
        if (!empty($where)) {
            $this->db->where($where);
        }

        // Add the like clause
        if (!empty($like)) {
            $this->db->like($like);
        }

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
     * @param array $where - The where clause
     * @param array $like - The like clause
     * 
     * @return array - Return an array of users
     * @return array - Return the error [MySQL Error code, The error message]
     */
    public function list(string $sort = 'id', string $order = 'ASC', int $limit = 10, int $offset = 20, array $where = [], array $like = []): array
    {
        // Check if the sort and order parameters are valid
        $sort = (in_array($sort, ['id', 'firstname', 'lastname', 'email', 'phone', 'address', 'professional_status', 'last_connexion'])) ? $sort : 'id';
        $order = (in_array($order, ['ASC', 'DESC'])) ? $order : 'ASC';

        // Get the list of users
        $this->db->order_by($sort, $order);

        // Add the where clause
        if (!empty($where)) {
            $this->db->where($where);
        }

        // Add the like clause
        if (!empty($like)) {
            $this->db->like($like);
        }

        $query = $this->db->get('user', $limit, $offset);

        // Return the list of users or an error
        return ($query) ? $query->result() : $this->db->error();
    }

    // ------------------------------------------------------------------------

    /**
     * Find a unique user by id
     *
     * @param int $id - The id of the user to find
     * 
     * @return User_model - Return the user if found
     * @return array - Return the error [MySQL Error code, The error message]
     */
    public function find_one_by_id(int $id): User_model|array
    {
        $user = $this->db->get_where('user', ['id' => $id])->custom_row_object(0, 'User_model');
        return ($user !== null) ? $user : ['message' => 'User not found'];
    }

    // ------------------------------------------------------------------------

    /**
     * Insert a new user in the database
     *
     * @param User_model $user - One User_model to insert
     * @param ArrayObject $user - An ArrayObject of User_model to insert
     * 
     * @return bool - Return true if the user has been inserted
     * @return array - Return the error [MySQL Error code, The error message]
     */
    public function insert(User_model|ArrayObject $data): bool|array
    {
        if ($data instanceof User_model) {
            // Insert one user
            return ($this->db->Insert('user', $data)) ?: $this->db->error();
        } else {
            $error = false;
            // Insert many users as a transaction block
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);

            foreach ($data as $user) {
                if (!$this->db->insert('user', $user)) {
                    $error = $this->db->error();
                    break;
                }
            }

            if ($error !== false) {
                $this->db->trans_rollback();
                return $error;
            } else {
                return ($this->db->trans_complete()) ?: $this->db->error();
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Update a user in the database
     * 
     * @param User_model $user - The user to update
     * 
     * @return bool - Return true if the user has been updated
     * @return array - Return the error [MySQL Error code, The error message]
     */
    public function update(User_model $user): bool|array
    {
        return ($this->db->replace('user', $user)) ?: $this->db->error();
    }

    // ------------------------------------------------------------------------

    /**
     * Delete user(s) in the database
     *
     * @param array $where - The where clause
     * 
     * @return bool - Return true if user(s) has been deleted
     * @return array - Return the error [MySQL Error code, The error message]
     */
    public function delete(array $where = []): bool|array
    {
        return ($this->db->delete('user', $where)) ?: $this->db->error();
    }

    // ------------------------------------------------------------------------
}
