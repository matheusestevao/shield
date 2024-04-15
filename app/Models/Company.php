<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

	public $incrementing = false;

	protected $fillable = [
		'company_name',
		'trade_name',
		'corporate_registry_number'
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

	public function address(): HasMany
	{
		return $this->hasMany(CompanyAddress::class, 'company_id', 'id');
	}
}
