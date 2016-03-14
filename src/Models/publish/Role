<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Models\Menu;

class Role extends Model
{
    protected $fillable = ['title'];

    public $relation_ids = [];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function canAccessMenu($menu)
    {
        if ($menu instanceof Menu) {
            $menu = $menu->id;
        }

        if (! isset($this->relation_ids['menus'])) {
            $this->relation_ids['menus'] = $this->menus()->pluck('id')->flip()->all();
        }

        return isset($this->relation_ids['menus'][$menu]);
    }
}

