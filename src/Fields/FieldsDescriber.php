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
            'text'     => 'Text field',
            'email'    => 'Email field',
            'textarea' => 'Long text field',
            'radio'    => 'Radio',
            'checkbox' => 'Checkbox',
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
}