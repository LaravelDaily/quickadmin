<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * @var Role
     */
    protected $roles;

    public function __construct(Role $roles)
    {
        $this->roles = $roles;
    }

    /**
     * Show a list of roles
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $roles = $this->roles->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show a page of user creation
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Insert new role into the system
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->roles->create($request->only('title'));

        return redirect()->route('roles.index')->withMessage(trans('quickadmin::admin.roles-controller-successfully_created'));
    }

    /**
     * Show a role edit page
     *
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $role = $this->roles->findOrFail($id);

        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update our role information
     *
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->roles->findOrFail($id)->update($request->only('title'));

        return redirect()->route('roles.index')->withMessage(trans('quickadmin::admin.roles-controller-successfully_updated'));
    }

    /**
     * Destroy specific role
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->roles->findOrFail($id)->delete();

        return redirect()->route('roles.index')->withMessage(trans('quickadmin::admin.roles-controller-successfully_deleted'));
    }
}

