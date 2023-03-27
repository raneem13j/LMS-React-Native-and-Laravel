<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'description'
        ];

    public function UserLevelSection(){
        return $this->hasMany(UserLevelSection::class);
    }

    public function users()
    {
      return $this->hasMany(UserLMS::class, 'user_level_sections','courses', 'user_l_m_s_id', 'courses_id');
     
    } 
}


