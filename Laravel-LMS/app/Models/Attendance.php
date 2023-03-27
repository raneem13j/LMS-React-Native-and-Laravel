<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'studentId',
        'levelSection_id',
        'status',
        'date',
    ];



    public function Student(){
        return $this->belongsTo(UserLMS::class);
    }

    public function LevelSection()
    {
        return $this->belongsTo(LevelSection::class, 'levelSection_id');
    }
}
