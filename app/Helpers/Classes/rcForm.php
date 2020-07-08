<?php 
namespace App\Helpers\Classes;


class rcForm
{
	public static function open($method, $route, $attributes = [])
	{
		return view('helpers.rcForm.open', compact('method', 'route', 'attributes'))->render();
	}

	public static function close($method)
	{
		return view('helpers.rcForm.close', compact('method'))->render();
	}

	public static function text($label, $name, $value = null, $attributes = [])
	{
		return view('helpers.rcForm.text', compact('label', 'name', 'value', 'attributes'))->render();
	}

	public static function dateTime($label, $name, $value = null, $attributes = [])
	{
		return view('helpers.rcForm.dateTime', compact('label', 'name', 'value', 'attributes'))->render();
	}

	public static function ckeditor($label, $name, $value = null, $attributes = [])
	{
		return view('helpers.rcForm.ckeditor', compact('label','name', 'value', 'attributes'))->render();
	}

	public static function standalone($label, $name, $value = null, $attributes = [])
	{
		return view('helpers.rcForm.standalone', compact('label', 'name', 'value', 'attributes'))->render();
	}

	public static function selectNumberRange($label, $name, $min, $max, $value = null, $attributes = [])
	{
		return view('helpers.rcForm.selectNumberRange', compact('label', 'name', 'min', 'max', 'value', 'attributes'))->render();
	}

	public static function selectOptions($label, $name, $options = [], $value = null, $attributes = [])
	{
		return view('helpers.rcForm.selectOptions', compact('label', 'name', 'options', 'value', 'attributes'))->render();
	}

	public static function status($label, $name, $value = null)
	{
		return view('helpers.rcForm.status', compact('value', 'name'))->render();
	}
}