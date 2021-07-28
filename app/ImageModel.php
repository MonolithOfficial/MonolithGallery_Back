<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageModel extends Model
{
    protected $table = 'images';
    protected $fillable = ['album_id', 'description', 'image', 'userId'];
}
