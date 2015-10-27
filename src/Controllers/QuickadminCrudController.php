<?php
namespace Laraveldaily\Quickadmin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
        $fieldTypes      = FieldsDescriber::types();
        $fieldValidation = FieldsDescriber::validation();

        return view("qa::cruds.create", compact('fieldTypes', 'fieldValidation'));
    }

    /**
     * Insert new crud
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
        // Init QuickCache
        $cache  = new QuickCache();
        $cached = [];
        $fields = [];
        foreach ($request->f_type as $index => $field) {
            $fields[$index] = [
                'type'       => $field,
                'title'      => $request->f_title[$index],
                'label'      => $request->f_label[$index],
                'validation' => $request->f_validation[$index],
                'value'      => $request->f_value[$index]
            ];
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

        return redirect('qa');
    }
}