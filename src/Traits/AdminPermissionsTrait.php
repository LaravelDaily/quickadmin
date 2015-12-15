<?php namespace Laraveldaily\Quickadmin\Traits;

use Laraveldaily\Quickadmin\Models\Menu;

trait AdminPermissionsTrait
{

    public function permissionCan($request)
    {
        if (is_null($request->route()->getName())) {
            return true;
        }
        list($role, $menu) = $this->parseData($request);
        if (in_array($role, explode(',', $menu->roles))) {
            return true;
        }

        return false;
    }

    /**
     * @param $request
     *
     * @return array
     */
    private function parseData($request)
    {
        $role     = $request->user()->role_id;
        $route    = explode('.', $request->route()->getName());
        $official = [
            'menu',
            'users',
            'actions'
        ];
        if (in_array($route[0], $official)) {
            return [$role, (object) ['roles' => config('quickadmin.defaultRole') . ',']];
        } else {
            $menuName = $route[1];
        }
        $menu = Menu::where('name', ucfirst($menuName))->firstOrFail();

        return [$role, $menu];
    }
}