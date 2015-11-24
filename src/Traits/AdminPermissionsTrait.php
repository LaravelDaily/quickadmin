<?php namespace Laraveldaily\Quickadmin\Traits;

use Laraveldaily\Quickadmin\Models\Crud;

trait AdminPermissionsTrait
{

    public function permissionCan($request)
    {
        if (is_null($request->route()->getName())) {
            return true;
        }

        list($role, $crud) = $this->parseData($request);

        if (in_array($role, explode(',', $crud->roles))) {
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
        $crudName = explode('.', $request->route()->getName())[1];
        $crud     = Crud::where('name', ucfirst($crudName))->firstOrFail();

        return [$role, $crud];
    }
}