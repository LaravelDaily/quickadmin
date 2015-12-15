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
               required="required" placeholder="Field DB name">

        <!-- File size limit -->
        <label class="size">File size limit (in MB):</label>
        <input type="text" name="f_size[]" value="{{ old('f_size.'.$index, '2') }}" class="form-control size"
               placeholder="File size limit (in MB)" style="display: none;">
        <!-- /File size limit -->

        <!-- File dimensions limit -->
        <label class="dimensions">File maximum width:</label>
        <input type="text" name="f_dimension_w[]" value="{{ old('f_dimension_w.'.$index, '4096') }}"
               class="form-control dimensions"
               placeholder="File maximum width" style="display: none;">
        <label class="dimensions">File maximum height:</label>
        <input type="text" name="f_dimension_h[]" value="{{ old('f_dimension_h.'.$index, '4096') }}"
               class="form-control dimensions"
               placeholder="File maximum height" style="display: none;">
        <!-- /File dimensions limit -->

        <!-- Value for radio button -->
        <input type="text" name="f_value[]" value="{{ old('f_value.'.$index) }}" class="form-control value"
               placeholder="Value" style="display: none;">
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
                    @if($key == old('f_texteditor.'.$index)) selected @endif>Don't use CKEDITOR
            </option>
            <option value="1"
                    @if($key == old('f_texteditor.'.$index)) selected @endif>Use CKEDITOR
            </option>
        </select>
        <!-- /Use ckeditor on textarea field -->

        <!-- Select for relationship -->
        <select name="f_relationship[]" class="form-control relationship" style="display: none;">
            <option value="">Select relationship</option>
            @foreach($menusSelect as $key => $option)
                <option value="{{ $key }}"
                        @if($key == old('f_relationship.'.$index)) selected @endif>{{ $option }}</option>
            @endforeach
        </select>
        <!-- /Select for relationship -->
        <div class="relationship-holder"></div>

        <!-- ENUM values -->
        <label class="enum">ENUM values: (comma separated)</label>
        <input type="text" name="f_enum[]" value="{{ old('f_enum.'.$index) }}" class="form-control enum tags"
               placeholder="ENUM values (comma separated)" style="display: none;">
        <!-- /ENUM values -->
    </td>
    <td>
        <input type="text" name="f_label[]" value="{{ old('f_label.'.$index) }}" class="form-control"
               required="required" placeholder="Field visual title">
        <input type="text" name="f_helper[]" value="{{ old('f_helper.'.$index) }}" class="form-control"
               placeholder="Optional additional comment below the field">
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
