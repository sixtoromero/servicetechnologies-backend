<?php namespace App\Models;

use CodeIgniter\Model;


class OrdersModel extends Model {
    protected $table            = 'orders';
    protected $primarykey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'status', 'date'];    

}