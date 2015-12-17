<?php
namespace Laraveldaily\Quickadmin\Builders;

use Illuminate\Support\Str;
use Laraveldaily\Quickadmin\Cache\QuickCache;
use Laraveldaily\Quickadmin\Fields\FieldsDescriber;

class MigrationBuilder
{
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
     * Build our migration file
     */
    public function build()
    {
        $cache          = new QuickCache();
        $cached         = $cache->get('fieldsinfo');
        $this->template = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'migration';
        $this->name     = $cached['name'];
        $this->fields   = $cached['fields'];
        $this->soft     = $cached['soft_delete'];
        $this->names();
        $template = (string) $this->loadTemplate();
        $template = $this->buildParts($template);
        $this->publish($template);
    }

    /**
     *  Load migration template
     */
    private function loadTemplate()
    {
        return file_get_contents($this->template);
    }

    /**
     * Build migration template parts
     *
     * @param $template
     *
     * @return mixed
     */
    private function buildParts($template)
    {
        $camelName = Str::camel($this->name);
        $tableName = strtolower($camelName);
        $template  = str_replace([
            '$TABLENAME$',
            '$CLASS$',
            '$FIELDS$'
        ], [
            $tableName,
            $this->className,
            $this->buildFields()
        ], $template);

        return $template;
    }

    /**
     * Build migration fields
     * @return string
     */
    private function buildFields()
    {
        $migrationTypes = FieldsDescriber::migration();
        $used           = [];
        $fields         = '$table->increments("id");' . "\r\n";
        foreach ($this->fields as $field) {
            // Check if there is no duplication for radio and checkbox
            if (!in_array($field->title, $used)) {
                // Generate our migration line
                $migrationLine = str_replace([
                    '$FIELDNAME$',
                    '$STATE$',
                    '$RELATIONSHIP$',
                ], [
                    $field->title,
                    $field->default == 'true' ? 1 : 0,
                    $field->relationship_name
                ], $migrationTypes[$field->type]);
                $fields .= '            '; // Add formatting space to the migration
                if ($field->type == 'enum') {
                    $values      = '';
                    $field->enum = explode(',', $field->enum);
                    foreach ($field->enum as $val) {
                        // Remove first whitespace
                        if (strpos(substr($val, 0, 1), ' ') !== false) {
                            $len = strlen($val);
                            $val = substr($val, 1, $len);
                        }
                        $values .= '"' . $val . '"';
                        if ($val != last($field->enum)) {
                            $values .= ', ';
                        }
                    }
                    $migrationLine = str_replace('$VALUES$', $values, $migrationLine);
                }
                $fields .= '$table->' . $migrationLine;
                if (in_array($field->validation, FieldsDescriber::nullables())) {
                    $fields .= '->nullable()';
                }
                $fields .= ";\r\n";
                if ($field->type == 'relationship') {
                    $used[$field->relationship_name] = $field->relationship_name;
                } else {
                    $used[$field->title] = $field->title;
                }
            }
        }
        $fields .= '            '; // Add formatting space to the migration
        $fields .= '$table->timestamps();';
        if ($this->soft == 1) {
            $fields .= "\r\n";
            $fields .= '            '; // Add formatting space to the migration
            $fields .= '$table->softDeletes();';
        }

        return $fields;
    }

    /**
     *  Generate file and class names for the migration
     */
    private function names()
    {
        $fileName = date("Y_m_d_His") . '_create_';
        $fileName .= str_replace(' ', '_', $this->name);
        $fileName       = strtolower($fileName) . '_table.php';
        $this->fileName = $fileName;

        $className       = 'Create' . ' ' . $this->name . ' Table';
        $className       = Str::camel($className);
        $this->className = ucfirst($className);
    }

    /**
     *  Publish file into it's place
     */
    private function publish($template)
    {
        file_put_contents(database_path('migrations/' . $this->fileName), $template);
    }

}