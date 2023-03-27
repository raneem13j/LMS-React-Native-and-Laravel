<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelSection extends Model
{
    use HasFactory;
    public function Level(){
        return $this->belongsToMany(Level::class, 'level_sections', 'level_id', 'section_id');
    }

    public function Section(){
        return $this->belongsToMany(Section::class, 'level_sections', 'section_id', 'section_id');
    }
}
