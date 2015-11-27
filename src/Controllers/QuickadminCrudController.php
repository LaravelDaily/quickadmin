<?php
namespace Laraveldaily\Quickadmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Builders\ControllerBuilder;
use Laraveldaily\Quickadmin\Builders\MigrationBuilder;
use Laraveldaily\Quickadmin\Builders\ModelBuilder;
use Laraveldaily\Quickadmin\Builders\RequestBuilder;
use Laraveldaily\Quickadmin\Builders\ViewsBuilder;
use Laraveldaily\Quickadmin\Cache\QuickCache;
use Laraveldaily\Quickadmin\Fields\FieldsDescriber;
use Laraveldaily\Quickadmin\Models\Crud;

class QuickadminCrudController extends Controller
{
    /**
     * Show new crud creation page
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $fieldTypes        = FieldsDescriber::types();
        $fieldValidation   = FieldsDescriber::validation();
        $defaultValuesCbox = FieldsDescriber::default_cbox();
        $crudsSelect       = Crud::lists('title', 'id');
        // Get columns for relationship
        $models = [];
        foreach (Crud::all() as $crud) {
            // We are having a default User model
            if ($crud->title == 'User' && $crud->is_crud == 0) {
                $tableName = 'users';
            } else {
                $tableName = strtolower($crud->name);
            }
            $models[$crud->id] = Schema::getColumnListing($tableName);
        }

        return view("qa::cruds.create",
            compact('fieldTypes', 'fieldValidation', 'defaultValuesCbox', 'crudsSelect', 'models'));
    }

    /**
     * Insert new crud
     *
     * @param Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function insert(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'  => 'required',
            'title' => 'required',
            'soft'  => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation);
        }
        // Get model names
        $cruds  = Crud::all();
        $models = [];
        foreach ($cruds as $crud) {
            $tableName         = strtolower($crud->name);
            $models[$crud->id] = $tableName;
        }
        // Init QuickCache
        $cache                   = new QuickCache();
        $cached                  = [];
        $cached['relationships'] = 0;
        $cached['files']         = 0;
        $cached['password']      = 0;
        $cached['date']          = 0;
        $fields                  = [];
        foreach ($request->f_type as $index => $field) {
            $fields[$index] = [
                'type'               => $field,
                'title'              => $request->f_title[$index],
                'label'              => $request->f_label[$index],
                'validation'         => $request->f_validation[$index],
                'value'              => $request->f_value[$index],
                'default'            => $request->f_default[$index],
                'relationship_id'    => $request->has('f_relationship.' . $index) ? $request->f_relationship[$index] : '',
                'relationship_name'  => $request->has('f_relationship.' . $index) ? $models[$request->f_relationship[$index]] : '',
                'relationship_field' => $request->has('f_relationship_field.' . $request->f_relationship[$index]) ? $request->f_relationship_field[$request->f_relationship[$index]] : '',
                'texteditor'         => $request->f_texteditor[$index],
                'size'               => $request->f_size[$index] * 1024,
                'show'               => $request->f_show[$index],
            ];
            if ($field == 'relationship') {
                $cached['relationships']++;
            } elseif ($field == 'file') {
                $cached['files']++;
            } elseif ($field == 'password') {
                $cached['password']++;
            } elseif ($field == 'date') {
                $cached['date']++;
            }
        }
        $cached['fields']      = $fields;
        $cached['name']        = $request->name;
        $cached['soft_delete'] = $request->soft;
        $cache->put('fieldsinfo', $cached);
        // Create crud entry
        Crud::create([
            'position'  => 0,
            'icon'      => $request->icon != '' ? $request->icon : 'fa-database',
            'name'      => $request->name,
            'title'     => $request->title,
            'parent_id' => null,
            'roles'     => '1'
        ]);
        // Create migrations
        $migrationBuilder = new MigrationBuilder();
        $migrationBuilder->build();
        // Create model
        $modelBuilder = new ModelBuilder();
        $modelBuilder->build();
        // Create request
        $requestBuilder = new RequestBuilder();
        $requestBuilder->build();
        // Create controller
        $controllerBuilder = new ControllerBuilder();
        $controllerBuilder->build();
        // Create views
        $viewsBuilder = new ViewsBuilder();
        $viewsBuilder->build();

        // Call migrations
        Artisan::call('migrate');

        // Destroy our cache file
        $cache->destroy('fieldsinfo');

        return redirect(config('quickadmin.route'));
    }
}