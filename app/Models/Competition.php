<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'location', 'image'];

    public function athletes()
    {
        return $this->belongsToMany(Athlete::class, 'athlete_competition')
                    ->withPivot('category', 'entry_total', 'reserve');
    }

}
