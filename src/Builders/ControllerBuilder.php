<?php
namespace Laraveldaily\Quickadmin\Builders;

use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Cache\QuickCache;

class ControllerBuilder
{
    // Controller namespace
    private $namespace = 'App\Http\Controllers';
    // Template
    private $template;
    // Global names
    private $name;
    private $className;
    private $modelName;
    private $requestName;
    private $fileName;

    /**
     * Build our controller file
     */
    public function build()
    {
        $cache          = new QuickCache();
        $cached         = $cache->get('fieldsinfo');
        $this->template = __DIR__ . '/../Templates/controller';
        $this->name     = $cached['name'];
        $this->fields   = $cached['fields'];
        $this->soft     = $cached['soft_delete'];
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
            '$REQUESTNAME$',
            '$CLASS$',
            '$COLLECTION$',
            '$RESOURCE$',
        ], [
            $this->namespace,
            $this->modelName,
            $this->requestName,
            $this->className,
            strtolower($this->modelName),
            strtolower($this->modelName),
        ], $template);

        return $template;
    }

    /**
     *  Generate names
     */
    private function names()
    {
        $camelName         = ucfirst(Str::camel($this->name));
        $this->className   = $camelName . 'Controller';
        $this->modelName   = $camelName;
        $this->requestName = $camelName . 'Request';

        $fileName       = $this->className . '.php';
        $this->fileName = $fileName;
    }

    /**
     *  Publish file into it's place
     */
    private function publish($template)
    {
        file_put_contents(app_path('Http/Controllers/' . $this->fileName), $template);
    }

}