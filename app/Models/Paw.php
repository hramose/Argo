<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paw extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'user_id'
		,'name'
		,'race'
		,'gender'
		,'paw_nature_id'
		,'year_of_brith'
		,'complete_vaccines'
		,'date_last_vaccine'
		,'last_vaccine'
		,'pregnant'
		,'under_medication'
		,'medication'
		,'neutered_or_sterilized'
		,'special_health_condition'
		,'medical_condition'
		,'description'
		,'token'
    ];
}
