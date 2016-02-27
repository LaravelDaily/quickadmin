<tr>
    <td>
        <input type="hidden" name="f_show[]" value="1" class="show_hid">
        <input type="checkbox" value="1" checked class="show2">
    </td>
    <td>
        <select name="f_type[]" class="form-control type" required="required">
            @foreach($fieldTypes as $key => $option)
                <option value="{{ $key }}"
                        @if($key == old('f_type.'.$index)) selected @endif>{{ $option }}</option>
            @endforeach
        </select>
    <td>
        <input type="text" name="f_title[]" value="{{ old('f_title.'.$index) }}" class="form-control title"
               required="required" placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-field_db_name') }}">

        <!-- File size limit -->
        <label class="size">{{ trans('quickadmin::templates.templates-menu_field_line-size_limit') }}</label>
        <input type="text" name="f_size[]" value="{{ old('f_size.'.$index, '2') }}" class="form-control size"
               placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-size_limit_placeholder') }}" style="display: none;">
        <!-- /File size limit -->

        <!-- File dimensions limit -->
        <label class="dimensions">{{ trans('quickadmin::templates.templates-menu_field_line-maximum_width') }}</label>
        <input type="text" name="f_dimension_w[]" value="{{ old('f_dimension_w.'.$index, '4096') }}"
               class="form-control dimensions"
               placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-maximum_width_placeholder') }}" style="display: none;">
        <label class="dimensions">{{ trans('quickadmin::templates.templates-menu_field_line-maximum_height') }}</label>
        <input type="text" name="f_dimension_h[]" value="{{ old('f_dimension_h.'.$index, '4096') }}"
               class="form-control dimensions"
               placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-maximum_height_placeholder') }}" style="display: none;">
        <!-- /File dimensions limit -->

        <!-- Value for radio button -->
        <input type="text" name="f_value[]" value="{{ old('f_value.'.$index) }}" class="form-control value"
               placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-value') }}" style="display: none;">
        <!-- /Value for radio button -->

        <!-- Default value of a checkbox -->
        <select name="f_default[]" class="form-control default_c" style="display: none;">
            @foreach($defaultValuesCbox as $key => $option)
                <option value="{{ $key }}"
                        @if($key == old('f_default.'.$index)) selected @endif>{{ $option }}</option>
            @endforeach
        </select>
        <!-- /Default value of a checkbox -->

        <!-- Use ckeditor on textarea field -->
        <select name="f_texteditor[]" class="form-control texteditor" style="display: none;">
            <option value="0"
                    @if($key == old('f_texteditor.'.$index)) selected @endif>{{ trans('quickadmin::templates.templates-menu_field_line-dont_use_ckeditor') }}
            </option>
            <option value="1"
                    @if($key == old('f_texteditor.'.$index)) selected @endif>{{ trans('quickadmin::templates.templates-menu_field_line-use_ckeditor') }}
            </option>
        </select>
        <!-- /Use ckeditor on textarea field -->

        <!-- Select for relationship -->
        <select name="f_relationship[]" class="form-control relationship" style="display: none;">
            <option value="">{{ trans('quickadmin::templates.templates-menu_field_line-select_relationship') }}</option>
            @foreach($menusSelect as $key => $option)
                <option value="{{ $key }}"
                        @if($key == old('f_relationship.'.$index)) selected @endif>{{ $option }}</option>
            @endforeach
        </select>
        <!-- /Select for relationship -->
        <div class="relationship-holder"></div>

        <!-- ENUM values -->
        <label class="enum">{{ trans('quickadmin::templates.templates-menu_field_line-enum_values') }}</label>
        <input type="text" name="f_enum[]" value="{{ old('f_enum.'.$index) }}" class="form-control enum tags"
               placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-enum_values_placeholder') }}" style="display: none;">
        <!-- /ENUM values -->
    </td>
    <td>
        <input type="text" name="f_label[]" value="{{ old('f_label.'.$index) }}" class="form-control"
               required="required" placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-field_visual_title_placeholder') }}">
        <input type="text" name="f_helper[]" value="{{ old('f_helper.'.$index) }}" class="form-control"
               placeholder="{{ trans('quickadmin::templates.templates-menu_field_line-comment_below_placeholder') }}">
    </td>
    <td>
        <select name="f_validation[]" class="form-control" required="required">
            @foreach($fieldValidation as $key => $option)
                <option value="{{ $key }}"
                        @if($key == old('f_validation.'.$index)) selected @endif>{{ $option }}</option>
            @endforeach
        </select>
    </td>
    <td><a href="#" class="rem btn btn-danger"><i class="fa fa-minus"></i></a></td>
</tr>
