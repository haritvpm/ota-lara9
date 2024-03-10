<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficerMapping extends Model
{
    use HasFactory;

    public $table = 'officer_mappings';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'section_or_officer_user_id',
        'controlling_officer_user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function section_or_officer_user()
    {
        return $this->belongsTo(User::class, 'section_or_officer_user_id');
    }

    public function controlling_officer_user()
    {
        return $this->belongsTo(User::class, 'controlling_officer_user_id');
    }
}
