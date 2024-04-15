<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CompanyTypeAddress extends Model
{
    use HasFactory;

	public $incrementing = false;
    public $timestamps = false;

	protected $fillable = [
		'type_address_id',
		'company_address_id'
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

	public function companyAddress(): BelongsTo
	{
		return $this->belongsTo(CompanyAddress::class, 'company_address_id', 'id');
	}

	public function type(): BelongsTo
	{
		return $this->belongsTo(TypeAddress::class, 'type_address_id', 'id');
	}
}
