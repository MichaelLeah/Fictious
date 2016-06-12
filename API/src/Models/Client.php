<?php
/**
 * Client Model
 *
 * A model which acts as a database representation of the
 * Client table. 
 */

namespace API\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Client extends Eloquent
{
    protected $table    = 'Client';
    protected $fillable = ['id', 'name', 'address', 'number', 'email'];
}