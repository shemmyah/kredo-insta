<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //[admin side] displays categories
    public function categoryPost() {
        return $this->hasMany(CategoryPost::class);
    }
}
