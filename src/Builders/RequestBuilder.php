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
        $this->template = __DIR__ . '/../Templates/request';
        $this->name     = $cached['name'];
        $this->fields   = $cached['fields'];
        $this->soft     = $cached['soft_delete'];
        $this->names();
        $template = (string) $this->loadTemplate();
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
        $rules    = $this->buildRules();
        $template = str_replace([
            '$NAMESPACE$',
            '$CLASS$',
            '$RULES$'
        ], [
            $this->namespace,
            $this->className,
            $rules
        ], $template);

        return $template;
    }

    /**
     * Build request rules
     * @return string
     */
    private function buildRules()
    {
        $used      = [];
        $fillables = '';
        foreach ($this->fields as $field) {
            // Check if there is no duplication for radio and checkbox
            if (!in_array($field->title, $used)) {
                switch ($field->validation) {
                    case 'required':
                        $fillables .= "'$field->title' => '$field->validation', \r\n";
                        break;
                    case 'required|unique':
                        $camelName = Str::camel($this->name);
                        // Insert table names
                        $tableName = strtolower($camelName);
                        $fillables .= "'$field->title' => '$field->validation:$tableName,$field->title,'." . '$this->' . $this->request . ", \r\n";
                        break;
                }
                $used[$field->title] = $field->title;
            }
        }

        return $fillables;
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
        file_put_contents(app_path('Http/Requests/' . $this->fileName), $template);
    }

}