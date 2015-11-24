<?php
namespace Laraveldaily\Quickadmin\Fields;

class FieldsDescriber
{
    /**
     * Default QuickAdmin field types
     * @return array
     */
    public static function types()
    {
        return [
            'text'         => 'Text field',
            'email'        => 'Email field',
            'textarea'     => 'Long text field',
            'radio'        => 'Radio',
            'checkbox'     => 'Checkbox',
            'date'         => 'Date picker',
            'relationship' => 'Relationship',
            'file'         => 'File field',
            'password'     => 'Password field (hashed)',
        ];
    }

    /**
     * Default QuickAdmin field validation types
     * @return array
     */
    public static function validation()
    {
        return [
            'optional'        => 'Optional',
            'required'        => 'Required',
            'required|unique' => 'Required unique'
        ];
    }

    /**
     * Default QuickAdmin field types for migration
     * @return array
     */
    public static function migration()
    {
        return [
            'text'         => 'string("$FIELDNAME$")',
            'email'        => 'string("$FIELDNAME$")',
            'textarea'     => 'text("$FIELDNAME$")',
            'radio'        => 'string("$FIELDNAME$")',
            'checkbox'     => 'tinyInteger("$FIELDNAME$")->default($STATE$)',
            'date'         => 'string("$FIELDNAME$")',
            'relationship' => 'integer("$RELATIONSHIP$_id")->references("id")->on("$RELATIONSHIP$")',
            'file'         => 'string("$FIELDNAME$")',
            'password'     => 'string("$FIELDNAME$")',
        ];
    }

    /**
     * Default QuickAdmin state for checkbox
     * @return array
     */
    public static function default_cbox()
    {
        return [
            'false' => 'Default unchecked',
            'true'  => 'Default checked'
        ];
    }
}