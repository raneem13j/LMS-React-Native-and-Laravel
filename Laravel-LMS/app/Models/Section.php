<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

class Section extends Model
{
  use EagerLoadPivotTrait;

    protected $fillable = [
        'sectionName',
        
    ];
    public function levels()
    {
      return $this->belongsToMany(Level::class, 'level_sections', 'section_id','level_id');
     
    }

    public function users()
    {
      return $this->hasMany(UserLMS::class, 'user_level_sections','level_sections', 'user_l_m_s_id', 'section_id');
     
    }   
}
