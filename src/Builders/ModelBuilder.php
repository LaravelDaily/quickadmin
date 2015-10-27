<?php
namespace Laraveldaily\Quickadmin\Builders;

use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Cache\QuickCache;

class ModelBuilder
{
    // Model namespace
    private $namespace = 'App';
    // Template
    private $template;
    // Names
    private $name;
    private $className;
    private $fileName;
    // Fields
    private $fields;
    // Soft delete?
    private $soft;

    /**
     * Build our model file
     */
    public function build()
    {
        $cache          = new QuickCache();
        $cached         = $cache->get('fieldsinfo');
        $this->template = __DIR__ . '/../Templates/model';
        $this->name     = $cached['name'];
        $this->fields   = $cached['fields'];
        $this->soft     = $cached['soft_delete'];
        $this->names();
        $template = (string) $this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    /**
     *  Load model template
     */
    private function loadTemplate()
    {
        return file_get_contents($this->template);
    }

    /**
     * Build model template parts
     *
     * @param $template
     *
     * @return mixed
     */
    private function buildParts($template)
    {
        $camelName = Str::camel($this->name);
        // Insert table names
        $tableName = strtolower($camelName);
        $fillables = $this->buildFillables();
        if ($this->soft == 1) {
            $soft_call = 'use Illuminate\Database\Eloquent\SoftDeletes;';
            $soft_use  = 'use SoftDeletes;';
            $soft_date = '/**
                            * The attributes that should be mutated to dates.
                            *
                            * @var array
                            */
                          protected $dates = [\'deleted_at\'];';
        } else {
            $soft_call = '';
            $soft_use  = '';
            $soft_date = '';
        }
        $template = str_replace([
            '$NAMESPACE$',
            '$SOFT_DELETE_CALL$',
            '$SOFT_DELETE_USE$',
            '$SOFT_DELETE_DATES$',
            '$TABLENAME$',
            '$CLASS$',
            '$FILLABLE$'
        ], [
            $this->namespace,
            $soft_call,
            $soft_use,
            $soft_date,
            $tableName,
            $this->className,
            $fillables
        ], $template);

        return $template;
    }

    /**
     * Build model fillables
     * @return string
     */
    private function buildFillables()
    {
        $used      = [];
        $fillables = '';
        foreach ($this->fields as $field) {
            // Check if there is no duplication for radio and checkbox
            if (!in_array($field->title, $used)) {
                $fillables .= "'" . $field->title . "',\r\n";
                $used[$field->title] = $field->title;
            }
        }

        return $fillables;
    }

    /**
     *  Generate file and class names for the model
     */
    private function names()
    {
        $this->className = ucfirst(Str::camel($this->name));

        $fileName       = $this->className . '.php';
        $this->fileName = $fileName;
    }

    /**
     *  Publish file into it's place
     */
    private function publish($template)
    {
        file_put_contents(app_path($this->fileName), $template);
    }

}