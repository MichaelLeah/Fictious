<?php
/**
 * Contact Model
 *
 * A model which acts as a database representation of the
 * Contact table.
 */

namespace API\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Eloquent
{
    use SoftDeletes;

    protected $table    = 'Contact';
    protected $fillable = ['id', 'name', 'job_role', 'number', 'email', 'client_id'];
    protected $dates    = ['deleted_at'];
}