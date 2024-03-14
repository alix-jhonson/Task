<?php

namespace App\Models;

// app/Models/Person.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['name', 'birth_date', 'family_id'];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function descendants()
    {
        return $this->hasMany(Person::class, 'parent_id')->with('descendants');
    }

    public function getFamilyTree()
    {
        return $this->descendants()->with('descendants')->get();
    }
}

