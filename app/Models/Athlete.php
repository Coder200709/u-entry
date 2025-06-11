<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    use HasFactory;

    // Update the fillable property to match your database columns
    protected $fillable = [
        'gender',
        'family_name',
        'given_name',
        'date_of_birth',
        'nation',
        'region',
        'adams_id',
        'picture',
        'id_card_picture',
        'certificate'
    ];
    public function competitions()
{
    return $this->belongsToMany(Competition::class, 'athlete_competition')
                ->withPivot('category', 'entry_total', 'reserve');
}


    // Define relationships if needed
    // public function team() {
    //     return $this->belongsTo(Team::class);
    // }
}