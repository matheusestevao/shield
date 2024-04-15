<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TypeAddress extends Model
{
    use HasFactory;

	public $incrementing = false;

	protected $fillable = [
		'name'
	];

    /**
	 *  Setup model event hooks
	 */
	public static function boot()
	{
	    parent::boot();

	    self::creating(function ($model) {
	        $model->id = Str::uuid()->toString();
	    });
	}

	public function companyTypeAddress(): HasMany
	{
		return $this->hasMany(CompanyTypeAddress::class, 'type_address_id', 'id');
	}
}
