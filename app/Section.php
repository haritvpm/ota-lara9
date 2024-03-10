<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    public $table = 'sections';

    protected $dates = [
        'created_at',
        'updated_at',
      
    ];

    protected $fillable = [
        'name',
        'officer_id',
        'created_at',
        'updated_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }
}
