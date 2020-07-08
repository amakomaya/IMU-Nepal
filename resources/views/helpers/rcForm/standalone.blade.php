<?php 
  $value = !is_null($value) ? $value : old($name);
?>
<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="input-group">
      <span class="input-group-btn">
        <a id="{{ $name }}" data-input="{{ Illuminate\Support\Str::camel($label) }}" data-preview="{{ $name }}" class="btn btn-primary">
          <i class="fa fa-picture-o"></i> Choose
        </a>
      </span>
      <input id="{{ Illuminate\Support\Str::camel($label) }}" class="form-control" type="text" value="{{ $value }}" name="{{ $name }}">
    </div>
    @if ($errors->has($name))
      <small id="help" class="form-text text-danger">{{ $errors->first($name) }}</small>
    @endif
    <img id="{{ $name }}" style="margin-top:15px;max-height:100px;">
</div>

<script>
var route_prefix = "{{ url(config('lfm.url_prefix', config('lfm.prefix'))) }}";
</script>

<script>
{!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/lfm.js')) !!}
</script>
<script>
$('#{{ $name }}').filemanager('file', {prefix: route_prefix});
</script>
