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
    private $files;
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
            0 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'view_index',
            1 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'view_edit',
            2 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'view_create',
        ];
        $this->name     = $cached['name'];
        $this->fields   = $cached['fields'];
        $this->files    = $cached['files'];
        $this->names();
        $template = (array)$this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    public function buildCustom($name)
    {
        $this->name     = $name;
        $this->template = [
            0 => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'customView_index',
            1 => '',
            2 => ''
        ];
        $this->names();
        $template = (array)$this->loadTemplate();
        $this->publishCustom($template);

    }

    /**
     *  Load views templates
     */
    private function loadTemplate()
    {
        return [
            0 => $this->template[0] != '' ? file_get_contents($this->template[0]) : '',
            1 => $this->template[1] != '' ? file_get_contents($this->template[1]) : '',
            2 => $this->template[2] != '' ? file_get_contents($this->template[2]) : '',
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
            '$FILES$'
        ], [
            $this->route,
            $this->resource,
            $this->formFieldsEdit,
            $this->model,
            $this->files != 0 ? "'files' => true, " : ''
        ], $template[1]);

        // Create template
        $template[2] = str_replace([
            '$ROUTE$',
            '$RESOURCE$',
            '$FORMFIELDS$',
            '$FILES$'
        ], [
            $this->route,
            $this->resource,
            $this->formFieldsCreate,
            $this->files != 0 ? "'files' => true, " : ''
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
            // Check if there is no duplication for radio and checkbox.
            // Password fields are excluded from the table too.
            if (! in_array($field->title, $used)
                && $field->type != 'password'
                && $field->type != 'textarea'
                && $field->show == 1
            ) {
                $headings .= "<th>$field->label</th>\r\n";
                // Format our table column by field type
                if ($field->type == 'relationship') {
                    $columns .= '<td>{{ isset($row->' . $field->relationship_name . '->' . $field->relationship_field . ') ? $row->' . $field->relationship_name . '->' . $field->relationship_field . " : '' }}</td>\r\n";
                    $used[$field->relationship_field] = $field->relationship_field;
                } elseif ($field->type == 'photo') {
                    $columns .= '<td>@if($row->' . $field->title . ' != \'\')<img src="{{ asset(\'uploads/thumb\') . \'/\'.  $row->' . $field->title . " }}\">@endif</td>\r\n";
                    $used[$field->title] = $field->title;
                } else {
                    $columns .= '<td>{{ $row->' . $field->title . " }}</td>\r\n";
                    $used[$field->title] = $field->title;
                }
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
            $title = addslashes($field->label);
            $label = $field->title;
            if (in_array($field->validation,
                    $this->starred) && $field->type != 'password' && $field->type != 'file' && $field->type != 'photo'
            ) {
                $title .= '*';
            }
            if ($field->type == 'relationship') {
                $label = $field->relationship_name . '_id';
            }
            if ($field->type == 'checkbox') {
                $field->default = '$' . $this->model . '->' . $label . ' == 1';
            }
            $temp = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR . $field->type);
            $temp = str_replace([
                'old(\'$LABEL$\')',
                '$LABEL$',
                '$TITLE$',
                '$VALUE$',
                '$STATE$',
                '$SELECT$',
                '$TEXTEDITOR$',
                '$HELPER$',
                '$WIDTH$',
                '$HEIGHT$',
            ], [
                'old(\'$LABEL$\',$' . $this->resource . '->' . $label . ')',
                $label,
                $title,
                $field->type != 'radio' ?
                    $field->value != '' ? ', "' . $field->value . '"' : ''
                    : "'$field->value'",
                $field->default,
                '$' . $field->relationship_name,
                $field->texteditor == 1 ? ' ckeditor' : '',
                $this->helper($field->helper),
                $field->dimension_w,
                $field->dimension_h,
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
            $title = addslashes($field->label);
            $key   = $field->title;
            if (in_array($field->validation, $this->starred)) {
                $title .= '*';
            }
            if ($field->type == 'relationship') {
                $key = $field->relationship_name . '_id';
            }
            $temp = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR . $field->type);
            $temp = str_replace([
                '$LABEL$',
                '$TITLE$',
                '$VALUE$',
                '$STATE$',
                '$SELECT$',
                '$TEXTEDITOR$',
                '$HELPER$',
                '$WIDTH$',
                '$HEIGHT$',
            ], [
                $key,
                $title,
                $field->type != 'radio' ?
                    $field->value != '' ? ', ' . $field->value : ''
                    : "'$field->value'",
                $field->default,
                '$' . $field->relationship_name,
                $field->texteditor == 1 ? ' ckeditor' : '',
                $this->helper($field->helper),
                $field->dimension_w,
                $field->dimension_h,
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
     * Create helper blocks for form fields
     *
     * @param $value
     *
     * @return string
     */
    private function helper($value)
    {
        if ($value != '') {
            return '<p class="help-block">' . $value . '</p>';
        } else {
            return '';
        }
    }

    /**
     *  Publish files into their places
     */
    private function publish($template)
    {
        if (! file_exists(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path))) {
            mkdir(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path));
            chmod(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin'), 0777);
        }
        file_put_contents(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'index.blade.php'),
            $template[0]);
        file_put_contents(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'edit.blade.php'),
            $template[1]);
        file_put_contents(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'create.blade.php'),
            $template[2]);
    }

    private function publishCustom($template)
    {
        if (! file_exists(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path))) {
            mkdir(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path));
            chmod(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin'), 0777);
        }
        file_put_contents(base_path('resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . 'index.blade.php'),
            $template[0]);
    }

}