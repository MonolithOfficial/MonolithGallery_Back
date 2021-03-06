<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $table = 'albums';
    protected $fillable = ['name', 'description', 'cover_image', 'userId'];

    public function Images(){
        return $this->hasMany(ImageModel::class);
    }
}
