<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'principal_name',
        'phone',
        'email',
        'address',
        'city',
        'region',
        'logo_url',
        'established_year',
        'description',
        'is_deleted',
        'is_synced',
    ];
}
