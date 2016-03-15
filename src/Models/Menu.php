<?php

namespace Laraveldaily\Quickadmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{
    protected $fillable = [
        'position',
        'menu_type',
        'icon',
        'name',
        'title',
        'parent_id',
    ];

    public $relation_ids = [];

    /**
     * Convert name to ucfirst() and camelCase
     *
     * @param $input
     */
    public function setNameAttribute($input)
    {
        $this->attributes['name'] = ucfirst(Str::camel($input));
    }

    /**
     * Get children links
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany('Laraveldaily\Quickadmin\Models\Menu', 'parent_id', 'id')->orderBy('position');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function availableForRole($role)
    {
        if ($role instanceof Role) {
            $role = $role->id;
        }

        if (! isset($this->relation_ids['roles'])) {
            $this->relation_ids['roles'] = $this->roles()->pluck('id')->flip()->all();
        }

        return isset($this->relation_ids['roles'][$role]);
    }
}
