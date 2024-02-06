<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Auth;
class PunchingForm extends Model
{
    use HasFactory;

    public $table = 'punching_forms';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'employee_id',
        'creator',
        'session',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function scopeCreatedByLoggedInUser($query)
    {
        if(Auth::user()->isAdminorAudit()){
            return $query;
        }

        return  $query->where('creator',Auth::user()->username);
                    

                      //Note:if we have similar usernames in submitted by. people with similar usernames can see each other's submitted items

    }

}