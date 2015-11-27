<?php
namespace Laraveldaily\Quickadmin\Builders;

use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Cache\QuickCache;
use Laraveldaily\Quickadmin\Models\Crud;

class ControllerBuilder
{
    // Controller namespace
    private $namespace = 'App\Http\Controllers\Admin';
    // Template
    private $template;
    // Global names
    private $name;
    private $className;
    private $modelName;
    private $createRequestName;
    private $updateRequestName;
    private $fileName;
    private $fields;
    private $relationships;
    private $files;

    /**
     * Build our controller file
     */
    public function build()
    {
        $cache               = new QuickCache();
        $cached              = $cache->get('fieldsinfo');
        $this->template      = __DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR .'Templates'. DIRECTORY_SEPARATOR .'controller';
        $this->name          = $cached['name'];
        $this->fields        = $cached['fields'];
        $this->relationships = $cached['relationships'];
        $this->files         = $cached['files'];
        $this->names();
        $template = (string) $this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    /**
     *  Load controller template
     */
    private function loadTemplate()
    {
        return file_get_contents($this->template);
    }

    /**
     * Build controller template parts
     *
     * @param $template
     *
     * @return mixed
     */
    private function buildParts($template)
    {
        $template = str_replace([
            '$NAMESPACE$',
            '$MODEL$',
            '$CREATEREQUESTNAME$',
            '$UPDATEREQUESTNAME$',
            '$CLASS$',
            '$COLLECTION$',
            '$RESOURCE$',
            '$INDEXGET$',
            '$RELATIONSHIPS$',
            '$RELATIONSHIP_COMPACT$',
            '$RELATIONSHIP_COMPACT_EDIT$',
            '$RELATIONSHIP_NAMESPACES$',
            '$FILETRAIT$',
            '$FILESAVING$'
        ], [
            $this->namespace,
            $this->modelName,
            $this->createRequestName,
            $this->updateRequestName,
            $this->className,
            strtolower($this->modelName),
            strtolower($this->modelName),
            $this->indexBuilder(),
            $this->relationshipsBuilder(),
            $this->compactBuilder(),
            $this->compactEditBuilder(),
            $this->relationshipsNamespaces(),
            $this->files > 0 ? 'use App\Http\Controllers\Traits\FileUploadTrait;' : '',
            $this->files > 0 ? '$this->saveFiles($request);' : ''
        ], $template);

        return $template;
    }

    /**
     * Build our index template
     * @return mixed|string
     */
    public function indexBuilder()
    {
        if ($this->relationships == 0) {
            return '$' . strtolower($this->modelName) . ' = ' . $this->modelName . '::all();';
        } else {
            $relationship = '$' . strtolower($this->modelName) . ' = ' . $this->modelName . '::$WITH$get();';
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    $relationship = str_replace([
                        '$WITH$'
                    ], [
                        'with("' . $field->relationship_name . '")->$WITH$'
                    ], $relationship);
                }
            }
            $relationship = str_replace('$WITH$', '', $relationship);

            return $relationship;
        }
    }

    public function relationshipsNamespaces()
    {
        if ($this->relationships == 0) {
            return '';
        } else {
            $cruds         = Crud::all()->keyBy('id');
            $relationships = '';
            $first         = true;
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    $crud = $cruds[$field->relationship_id];
                    $relationships .= 'use App\\' . ucfirst(Str::camel($crud->name)) . ";\r\n";
                }
            }

            return $relationships;
        }
    }

    /**
     * Build relationships for forms
     * @return string
     */
    public function relationshipsBuilder()
    {
        if ($this->relationships == 0) {
            return '';
        } else {
            $cruds         = Crud::all()->keyBy('id');
            $relationships = '';
            $first         = true;
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    // Formatting fix if the relationship is not first
                    if (!$first) {
                        $relationships .= '        ';
                    }
                    $crud = $cruds[$field->relationship_id];
                    $relationships .= '$'
                        . $field->relationship_name
                        . ' = '
                        . ucfirst(Str::camel($crud->name))
                        . '::lists("'
                        . $field->relationship_field
                        . '", "id");'
                        . "\r\n";
                }
            }

            return $relationships;
        }
    }

    /**
     * Build compact for create form
     * @return mixed|string
     */
    public function compactBuilder()
    {
        if ($this->relationships == 0) {
            return '';
        } else {
            $compact = ', compact($RELATIONS$)';
            $first   = true;
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    $toReplace = '';
                    if ($first != true) {
                        $toReplace .= ', ';
                    } else {
                        $first = false;
                    }
                    $toReplace .= '"' . $field->relationship_name . '"$RELATIONS$';
                    $compact = str_replace('$RELATIONS$', $toReplace, $compact);
                }
            }
            $compact = str_replace('$RELATIONS$', '', $compact);

            return $compact;
        }
    }

    /**
     * Build compact for edit form
     * @return string
     */
    public function compactEditBuilder()
    {
        if ($this->relationships == 0) {
            return '';
        } else {
            $compact = '';
            foreach ($this->fields as $field) {
                if ($field->type == 'relationship') {
                    $compact .= ', "' . $field->relationship_name . '"';
                }
            }

            return $compact;
        }
    }

    /**
     *  Generate names
     */
    private function names()
    {
        $camelName               = ucfirst(Str::camel($this->name));
        $this->className         = $camelName . 'Controller';
        $this->modelName         = $camelName;
        $this->createRequestName = 'Create' . $camelName . 'Request';
        $this->updateRequestName = 'Update' . $camelName . 'Request';

        $fileName       = $this->className . '.php';
        $this->fileName = $fileName;
    }

    /**
     *  Publish file into it's place
     */
    private function publish($template)
    {
        if (!file_exists(app_path('Http'. DIRECTORY_SEPARATOR .'Controllers'. DIRECTORY_SEPARATOR .'Admin'))) {
            mkdir(app_path('Http'. DIRECTORY_SEPARATOR .'Controllers'. DIRECTORY_SEPARATOR .'Admin'));
            chmod(app_path('Http'. DIRECTORY_SEPARATOR .'Controllers'. DIRECTORY_SEPARATOR .'Admin'), 0777);
        }
        file_put_contents(app_path('Http'. DIRECTORY_SEPARATOR .'Controllers'. DIRECTORY_SEPARATOR .'Admin'. DIRECTORY_SEPARATOR . $this->fileName), $template);
    }

}