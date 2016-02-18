<?php

namespace App\Models;

use Zizaco\Entrust\Traits\EntrustUserTrait; # Requerido por Entrust
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use EntrustUserTrait; # Requerido por Entrust
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name'
		,'username'
		,'email'
		,'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
		'password'
		,'created_at'
		,'updated_at'
    ];
}
