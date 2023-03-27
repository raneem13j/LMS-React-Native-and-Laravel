<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

class Level extends Model
{
  use EagerLoadPivotTrait;

    protected $fillable = [
        'levelName',
        ];
    // public function sections()
    // {
    //   return $this->belongsToMany(Section::class, 'level_sections')
    //   ->using(LevelSection::class)
    //   ->withPivot('id');
    // }
    public function sections()
{
    return $this->belongsToMany(Section::class, 'level_sections', 'level_id', 'section_id');
}

}
