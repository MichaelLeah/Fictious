<?php
/**
 * Conversation Model
 *
 * A model which acts as a database representation of the
 * Conversation table. 
 */

namespace API\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Eloquent
{
    use SoftDeletes;

    protected $table    = 'Conversation';
    protected $fillable = ['id', 'details', 'employee_id', 'contact_id', 'client_id', 'created_at', 'updated_at'];
    protected $dates    = ['deleted_at'];
}