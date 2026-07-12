<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [

        'group_id',

        'name',

        'route',

    ];

    public function group()
    {
        return $this->belongsTo(MenuGroup::class,'group_id');
    }

    public function roleMenus()
    {
        return $this->hasMany(RoleMenu::class);
    }
}