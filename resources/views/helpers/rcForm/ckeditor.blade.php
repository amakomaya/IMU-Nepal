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
	    filebrowserImageBrowseUrl: '/filemanager?type=Images',
	    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
	    filebrowserBrowseUrl: '/filemanager?type=Files',
	    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
 	};
	CKEDITOR.replace( '{{ $name }}', options);

</script>
