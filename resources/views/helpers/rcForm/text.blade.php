<?php 
	$value = !is_null($value) ? $value : old($name);
?>
<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="text" class="form-control" name="{{ $name }}" value="{{ $value }}" id="{{ $name }}" aria-describedby="help" placeholder="Enter {{ $label }}"

    @foreach($attributes as $key => $value)

    	{{ $key }} = "{{ $value }}"

    @endforeach
    >
    @if ($errors->has($name))
    	<small id="help" class="form-text text-danger">{{ $errors->first($name) }}</small>
    @endif
 </div>