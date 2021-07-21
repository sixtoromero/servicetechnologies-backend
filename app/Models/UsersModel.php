<?php namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model {
    protected $table            = 'users';
    protected $primarykey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['names', 'surnames', 'email', 'usertype', 'address', 'cellphone', 'password'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updateField     = 'update_at';

    // //'email'     => 'required|valid_email|is_unique[users.email]',
    // protected $validationRules  = [
    //     'names'     => 'required|alpha_space|min_length[3]|max_length[255]',
    //     'surnames'  => 'required|alpha_space|min_length[3]|max_length[255]',
    //     'usertype'  => 'required|alpha_space|min_length[3]|max_length[255]',
    //     'email'     => 'required|valid_email|max_length[255]',
    //     'usertype'  => 'required|alpha_space|min_length[6]|max_length[6]',
    //     'address'   => 'required|alpha_space|min_length[5]|max_length[255]',
    //     'cellphone' => 'required|alpha_space|max_length[20]',
    //     'password'  => 'required|alpha_space|max_length[255]',
    // ];

    // protected $validationMessages = [
    //     'email'    => [
    //             'valid_email'   => 'Please register a valid email.'
    //         ]
    //     ];

    // //Para que las validaciones sean obligatorias
    // protected $skipValidation = false;

}