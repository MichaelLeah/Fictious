<?php
/**
 * Employee Model
 *
 * A model which acts as a database representation of the
 * Employee table. 
 */

namespace API\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Eloquent
{
    use SoftDeletes;

    protected $table    = 'Employee';
    protected $fillable = ['id', 'name', 'position', 'created_at', 'updated_at'];
    protected $dates    = ['deleted_at'];
}