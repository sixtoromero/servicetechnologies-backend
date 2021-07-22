<?php namespace App\Models;

use CodeIgniter\Model;


class UsersModel extends Model {
    protected $table            = 'users';
    protected $primarykey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['names', 'surnames', 'email', 'usertype', 'address', 'cellphone', 'password'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updateField      = 'update_at';
    
    //Para que las validaciones sean obligatorias
    protected $skipValidation = false;

}