<tr>
    <td>
        <select name="f_type[]" class="form-control type" required="required">
            @foreach($fieldTypes as $key => $option)
                <option value="{{ $key }}"
                        @if($key == old('f_type.'.$index)) selected @endif>{{ $option }}</option>
            @endforeach
        </select>
    <td>
        <input type="text" name="f_title[]" value="{{ old('f_title.'.$index) }}" class="form-control"
               required="required" placeholder="Key">
        <input type="text" name="f_value[]" value="{{ old('f_value.'.$index) }}" class="form-control value"
               placeholder="Value" style="display: none;">
    </td>
    <td><input type="text" name="f_label[]" value="{{ old('f_label.'.$index) }}" class="form-control"
               required="required" placeholder="Label"></td>
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
