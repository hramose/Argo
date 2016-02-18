<?php namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name'
		,'display_name'
		,'description'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
		'created_at'
		,'updated_at'
    ];
}
