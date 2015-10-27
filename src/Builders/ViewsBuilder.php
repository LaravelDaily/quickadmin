<?php
namespace Laraveldaily\Quickadmin\Builders;

use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Cache\QuickCache;

class ViewsBuilder
{
    // Templates
    private $template; // Array: [0]->index, [1]->edit, [2]->create
    // Variables
    private $fields;
    private $route;
    private $resource;
    private $headings;
    private $columns;
    private $formFieldsEdit;
    private $model;
    private $path;
    private $formFieldsCreate;
    // @todo Move into FieldsDescriber for usage in fields extension
    private $starred = [
        'required',
        'required|unique'
    ];


    /**
     * Build our views files
     */
    public function build()
    {
        $cache          = new QuickCache();
        $cached         = $cache->get('fieldsinfo');
        $this->template = [
            0 => __DIR__ . '/../Templates/view_index',
            1 => __DIR__ . '/../Templates/view_edit',
            2 => __DIR__ . '/../Templates/view_create',
        ];
        $this->name     = $cached['name'];
        $this->fields   = $cached['fields'];
        $this->soft     = $cached['soft_delete'];
        $this->names();
        $template = (array) $this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    /**
     *  Load views templates
     */
    private function loadTemplate()
    {
        return [
            0 => file_get_contents($this->template[0]),
            1 => file_get_contents($this->template[1]),
            2 => file_get_contents($this->template[2]),
        ];
    }

    /**
     * Build views templates parts
     *
     * @param $template
     *
     * @return mixed
     */
    private function buildParts($template)
    {
        $this->buildTable();
        $this->buildCreateForm();
        $this->buildEditForm();
        // Index template
        $template[0] = str_replace([
            '$ROUTE$',
            '$RESOURCE$',
            '$HEADINGS$',
            '$FIELDS$',
        ], [
            $this->route,
            $this->resource,
            $this->headings,
            $this->columns
        ], $template[0]);
        // Edit template
        $template[1] = str_replace([
            '$ROUTE$',
            '$RESOURCE$',
            '$FORMFIELDS$',
            '$MODEL$',
        ], [
            $this->route,
            $this->resource,
            $this->formFieldsEdit,
            $this->model

        ], $template[1]);
        // Create template
        $template[2] = str_replace([
            '$ROUTE$',
            '$RESOURCE$',
            '$FORMFIELDS$',
        ], [
            $this->route,
            $this->resource,
            $this->formFieldsCreate
        ], $template[2]);

        return $template;
    }

    /**
     *  Build index table
     */
    private function buildTable()
    {
        $used     = [];
        $headings = '';
        $columns  = '';
        foreach ($this->fields as $field) {
            // Check if there is no duplication for radio and checkbox
            if (!in_array($field->title, $used)) {
                $headings .= "<th>$field->label</th>\r\n";
                $columns .= '<td>{{ $row->' . $field->title . " }}</td>\r\n";
                $used[$field->title] = $field->title;
            }
        }
        $this->headings = $headings;
        $this->columns  = $columns;
    }

    /**
     *  Build edit.blade.php form
     */
    private function buildEditForm()
    {
        $form = '';
        foreach ($this->fields as $field) {
            $label = $field->label;
            if(in_array($field->validation,$this->starred)) {
                $label .= '*';
            }
            $temp = file_get_contents(__DIR__ . '/../Templates/fields/' . $field->type);
            $temp = str_replace([
                'old(\'$LABEL$\')',
                '$LABEL$',
                '$TITLE$',
                '$VALUE$'
            ], [
                'old(\'$LABEL$\',$' . $this->resource . '->' . $field->title . ')',
                $field->title,
                $label,
                $field->value != '' ? ', "' . $field->value . '"' : ''
            ], $temp);
            $form .= $temp;
        }
        $this->formFieldsEdit = $form;
    }

    /**
     *  Build create.blade.php form
     */
    private function buildCreateForm()
    {
        $form = '';
        foreach ($this->fields as $field) {
            $label = $field->label;
            if(in_array($field->validation,$this->starred)) {
                $label .= '*';
            }
            $temp = file_get_contents(__DIR__ . '/../Templates/fields/' . $field->type);
            $temp = str_replace([
                '$LABEL$',
                '$TITLE$',
                '$VALUE$'
            ], [
                $field->title,
                $label,
                $field->value != '' ? ', ' . $field->value : ''
            ], $temp);
            $form .= $temp;
        }
        $this->formFieldsCreate = $form;
    }

    /**
     *  Generate names for the views
     */
    private function names()
    {
        $camelCase      = ucfirst(Str::camel($this->name));
        $this->route    = strtolower($camelCase);
        $this->path     = strtolower($camelCase);
        $this->resource = strtolower($camelCase);
        $this->model    = strtolower($camelCase);
    }

    /**
     *  Publish files into their places
     */
    private function publish($template)
    {
        if (!file_exists(base_path('resources/views/admin/' . $this->path))) {
            mkdir(base_path('resources/views/admin/' . $this->path));
        }
        file_put_contents(base_path('resources/views/admin/' . $this->path . '/index.blade.php'), $template[0]);
        file_put_contents(base_path('resources/views/admin/' . $this->path . '/edit.blade.php'), $template[1]);
        file_put_contents(base_path('resources/views/admin/' . $this->path . '/create.blade.php'), $template[2]);
    }

}