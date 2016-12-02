<?php
namespace Laraveldaily\Quickadmin\Builders;

use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Cache\QuickCache;

class RequestBuilder
{
    // Request namespace
    private $namespace = 'App\Http\Requests';
    // Template
    private $template;
    // Names
    private $name;
    private $className;
    private $fileName;
    private $request;
    // Fields
    private $fields;

    /**
     * Build our request file
     */
    public function build()
    {
        $cache          = new QuickCache();
        $cached         = $cache->get('fieldsinfo');
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'request';
        $this->name     = $cached['name'];
        $this->fields   = $cached['fields'];
        $this->soft     = $cached['soft_delete'];
        $this->names();
        $template = [
            $this->loadTemplate(),
            $this->loadTemplate()
        ];
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    /**
     *  Load request template
     */
    private function loadTemplate()
    {
        return file_get_contents($this->template);
    }

    /**
     * Build request template parts
     *
     * @param $template
     *
     * @return mixed
     */
    private function buildParts($template)
    {
        $template[0] = str_replace([
            '$NAMESPACE$',
            '$CLASS$',
            '$RULES$'
        ], [
            $this->namespace,
            'Create' . $this->className,
            $this->buildRules(0),
        ], $template[0]);

        $template[1] = str_replace([
            '$NAMESPACE$',
            '$CLASS$',
            '$RULES$'
        ], [
            $this->namespace,
            'Update' . $this->className,
            $this->buildRules(1),
        ], $template[1]);

        return $template;
    }

    /**
     * Build request rules
     * @return string
     */
    private function buildRules($type)
    {
        $used  = [];
        $rules = '';
        foreach ($this->fields as $field) {
            // Check if there is no duplication for radio and checkbox
            if (! in_array($field->title, $used)) {
                if ($field->type != 'file' && $field->type != 'relationship' && $field->type != 'money') {
                    if ($type == 0 || $field->type != 'password') {
                        switch ($field->validation) {
                            case 'required':
                                $rules .= "'$field->title' => '$field->validation', \r\n            ";
                                break;
                            case 'required|unique':
                                $camelName = Str::camel($this->name);
                                // Insert table names
                                $tableName = strtolower($camelName);
                                $rules .= "'$field->title' => '$field->validation:$tableName,$field->title,'." . '$this->' . $this->request . ", \r\n            ";
                                break;
                        }
                    }
                } elseif ($field->type == 'relationship') {
                    switch ($field->validation) {
                        case 'required':
                            $rules .= "'" . $field->relationship_name . "_id' => '$field->validation', \r\n            ";
                            break;
                        case 'required|unique':
                            $camelName = Str::camel($this->name);
                            // Insert table names
                            $tableName = strtolower($camelName);
                            $rules .= "'" . $field->relationship_name . "_id' => '$field->validation:$tableName,$field->relationship_name,'." . '$this->' . $this->request . ", \r\n            ";
                            break;
                    }
                } elseif ($field->type == 'file' || $field->type == 'photo') {
                    if ($type == 0) {
                        switch ($field->validation) {
                            case 'required':
                                $rules .= "'$field->title' => 'max:$field->size|$field->validation', \r\n            ";
                                break;
                            case 'required|unique':
                                $camelName = Str::camel($this->name);
                                // Insert table names
                                $tableName = strtolower($camelName);
                                $rules .= "'$field->title' => 'max:$field->size|$field->validation:$tableName,$field->title,'." . '$this->' . $this->request . ", \r\n            ";
                                break;
                            default:
                                // We got a file field which has a bit different validation
                                $rules .= "'$field->title' => 'max:$field->size', \r\n            ";
                                break;
                        }
                    } else {
                        switch ($field->validation) {
                            case 'required':
                                $rules .= "'$field->title' => 'max:$field->size', \r\n            ";
                                break;
                            case 'required|unique':
                                $camelName = Str::camel($this->name);
                                // Insert table names
                                $tableName = strtolower($camelName);
                                $rules .= "'$field->title' => 'max:$field->size|$field->validation:$tableName,$field->title,'." . '$this->' . $this->request . ", \r\n            ";
                                break;
                            default:
                                // We got a file field which has a bit different validation
                                $rules .= "'$field->title' => 'max:$field->size', \r\n            ";
                                break;
                        }
                    }

                } elseif ($field->type == 'money') {
                    switch ($field->validation) {
                        case 'required':
                            $rules .= "'$field->title' => 'numeric|$field->validation', \r\n            ";
                            break;
                        case 'required|unique':
                            $camelName = Str::camel($this->name);
                            // Insert table names
                            $tableName = strtolower($camelName);
                            $rules .= "'$field->title' => 'numeric|$field->validation:$tableName,$field->title,'." . '$this->' . $this->request . ", \r\n            ";
                            break;
                        default:
                            // We got a file field which has a bit different validation
                            $rules .= "'$field->title' => 'numeric', \r\n            ";
                            break;
                    }
                }
                $used[$field->title] = $field->title;
            }
        }

        return $rules;
    }

    /**
     *  Generate file and class names for the request
     */
    private function names()
    {
        $camel           = ucfirst(Str::camel($this->name));
        $this->className = $camel . 'Request';
        $this->request   = strtolower($camel);

        $fileName       = $this->className . '.php';
        $this->fileName = $fileName;
    }

    /**
     *  Publish file into it's place
     */
    private function publish($template)
    {
        if (! file_exists(app_path('Http' . DIRECTORY_SEPARATOR . 'Requests'))) {
            mkdir(app_path('Http' . DIRECTORY_SEPARATOR . 'Requests'), 0777, true);
        }
        file_put_contents(app_path('Http' . DIRECTORY_SEPARATOR . 'Requests' . DIRECTORY_SEPARATOR . 'Create' . $this->fileName),
            $template[0]);
        file_put_contents(app_path('Http' . DIRECTORY_SEPARATOR . 'Requests' . DIRECTORY_SEPARATOR . 'Update' . $this->fileName),
            $template[1]);
    }

}