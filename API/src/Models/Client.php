<?php
/**
 * Client Model
 *
 * A model which acts as a database representation of the
 * Client table. 
 */

namespace API\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Eloquent
{
    use SoftDeletes;

    protected $table    = 'Client';
    protected $fillable = ['id', 'name', 'address', 'number', 'email'];
    protected $dates    = ['deleted_at'];
}