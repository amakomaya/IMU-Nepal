<?php 
	$value = !is_null($value) ? $value : old($name);
?>
<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
	<label for="{{ $name }}">{{ $label }}</label>
	<select name="{{ $name }}" class="form-control">
	  @if(!is_null($value))
	  	<option value="{{ $value }}" selected>{{ $value }}</option>
	  @else
	  	  <option selected disabled>Select {{ $label }}</option>
	  @endif
	  @for($number = $min; $number <= $max; $number ++)
	  	<option value="{{ $number }}">{{ $number }}</option>
	  @endfor
	</select>
	@if ($errors->has($name))
    	<small id="help" class="form-text text-danger">{{ $errors->first($name) }}</small>
    @endif
</div>