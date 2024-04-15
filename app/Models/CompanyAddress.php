<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CompanyAddress extends Model
{
    use HasFactory;

	public $incrementing = false;
    public $timestamps = false;

	protected $fillable = [
		'company_id',
		'zip_code',
        'address',
        'number_address',
        'complement_address',
        'neighborhood',
		'state',
		'city'
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

	public function company(): BelongsTo
	{
		return $this->belongsTo(Company::class, 'company_id', 'id');
	}

	public function type(): HasMany
	{
		return $this->hasMany(CompanyTypeAddress::class, 'company_address_id', 'id');
	}
}
