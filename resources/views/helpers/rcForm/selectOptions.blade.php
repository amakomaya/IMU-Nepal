<?php 
	$value = !is_null($value) ? $value : old($name);
?>
 
<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
	<label for="{{ $name }}">{{ $label }} </label>
	<select name="{{ $name }}" class="form-control">
	@if(!is_null($value))
	  	<option value="{{ array_search($value, $options)+1 }}" selected>{{ $value }}</option>
	@else
	  	<option selected disabled>Select {{ $label }}</option>
	@endif
	@foreach($options as $option)
	  	<option value="{{ $loop->iteration }}">{{ $option }}</option>
	@endforeach
	</select>
	@if ($errors->has($name))
    	<small id="help" class="form-text text-danger">{{ $errors->first($name) }}</small>
    @endif
</div>