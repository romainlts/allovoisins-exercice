<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Form Validation
| -------------------------------------------------------------------------
|
*/

$config = [
    'id' => [
        [
            'field' => 'id',
            'label' => 'ID',
            'rules' => 'trim|required|integer',
            'errors' => [
                'required' => 'Vous n\'avez pas fourni d\'identifiant.',
                'integer' => 'L\'identifiant doit être un nombre entier.'
            ]
        ]
    ],
    'user_registration' => [
        [
            'field' => 'firstname',
            'label' => 'Firstname',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Vous n\'avez pas fourni de prénom.',
                'max_length' => 'Le prénom doit avoir au maximum 50 caractères.'
            ]
        ],
        [
            'field' => 'lastname',
            'label' => 'Lastname',
            'rules' => 'trim|required|max_length[50]',
            'errors' => [
                'required' => 'Vous n\'avez pas fourni de nom.',
                'max_length' => 'Le nom doit avoir au maximum 50 caractères.'
            ]
        ],
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|max_length[128]|is_unique[user.email]|valid_email',
            'errors' => [
                'required'      => 'Vous n\'avez pas fourni d\'adresse email.',
                'max_length' => 'L\'adresse email doit avoir au maximum 128 caractères.',
                'is_unique'     => 'L\'adresse email est déjà utilisée.',
                'valid_email'   => 'L\a adresse email n\'est pas valide.'
            ]
        ],
        [
            'field' => 'phone',
            'label' => 'Phone number',
            'rules' => 'trim|required|max_length[10]',
            'errors' => [
                'required' => 'Vous n\'avez pas fourni de numéro de téléphone.',
                'max_length' => 'Le numéro de téléphone doit avoir au maximum 10 caractères.'
            ]
        ],
        [
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'trim|required|max_length[255]',
            'errors' => [
                'required' => 'Vous n\'avez pas fourni d\'adresse.',
                'max_length' => 'L\'adresse doit avoir au maximum 255 caractères.'
            ]
        ],
        [
            'field' => 'professional_status',
            'label' => 'Professional status',
            'rules' => 'trim|required|max_length[50]|in_list[particulier,auto-entrepreneur,indépendant,entreprise,association à but non lucratif]',
            'errors' => [
                'required' => 'Vous n\'avez pas fourni de status professionnel.',
                'max_length' => 'Le status professionnel doit avoir au maximum 50 caractères.',
                'in_list' => 'Le status professionnel doit être particulier, auto-entrepreneur, indépendant, entreprise ou association à but non lucratif.'
            ]
        ]
    ],
    'user_update' => [
        [
            'field' => 'firstname',
            'label' => 'Firstname',
            'rules' => 'trim|max_length[50]',
            'errors' => [
                'max_length' => 'Le prénom doit avoir au maximum 50 caractères.'
            ]
        ],
        [
            'field' => 'lastname',
            'label' => 'Lastname',
            'rules' => 'trim|max_length[50]',
            'errors' => [
                'max_length' => 'Le nom doit avoir au maximum 50 caractères.'
            ]
        ],
        [
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|max_length[128]|is_unique[user.email]|valid_email',
            'errors' => [
                'max_length' => 'L\'adresse email doit avoir au maximum 128 caractères.',
                'is_unique'     => 'L\'adresse email est déjà utilisée.',
                'valid_email'   => 'L\a adresse email n\'est pas valide.'
            ]
        ],
        [
            'field' => 'phone',
            'label' => 'Phone number',
            'rules' => 'trim|max_length[10]',
            'errors' => [
                'max_length' => 'Le numéro de téléphone doit avoir au maximum 10 caractères.'
            ]
        ],
        [
            'field' => 'address',
            'label' => 'Address',
            'rules' => 'trim|max_length[255]',
            'errors' => [
                'max_length' => 'L\'adresse doit avoir au maximum 255 caractères.'
            ]
        ],
        [
            'field' => 'professional_status',
            'label' => 'Professional status',
            'rules' => 'trim|max_length[50]|in_list[particulier,auto-entrepreneur,indépendant,entreprise,association à but non lucratif]',
            'errors' => [
                'max_length' => 'Le status professionnel doit avoir au maximum 50 caractères.',
                'in_list' => 'Le status professionnel doit être particulier, auto-entrepreneur, indépendant, entreprise ou association à but non lucratif.'
            ]
        ]
    ]
];
