<?php 
	$value = !is_null($value) ? $value : old($name);
?>

<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <textarea class="form-control" id="{{ $name }}" name="{{ $name }}">{{ $value }}</textarea>
    @if ($errors->has($name))
    	<small id="emailHelp" class="form-text text-danger">{{ $errors->first($name) }}</small>
    @endif
 </div>


<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    var options = {
	    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
	    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
	    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
	    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
 	};
	CKEDITOR.replace( '{{ $name }}', options);

</script>
