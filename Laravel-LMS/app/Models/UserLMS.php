<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

class UserLMS extends Model
{
    use HasApiTokens, HasFactory, Notifiable, EagerLoadPivotTrait;


    protected $table = 'user_l_m_s';


    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'role',
        'phoneNumber',
    ];



    public function Attendance(){
        return $this->hasOne(Attendance::class, 'studentId', 'id');
    }

    public function levelSections()
    {
        return $this->hasMany(UserLevelSection::class,'levelSection_id');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'user_level_section', 'user_id', 'level_section_id');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'user_level_section', 'user_id', 'level_section_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_level_section', 'user_id', 'course_id');
    }

}
