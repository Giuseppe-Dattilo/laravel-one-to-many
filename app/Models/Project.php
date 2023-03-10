<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description','image','prog_url', 'is_published','type_id'];

    // relazione con i types
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

}
