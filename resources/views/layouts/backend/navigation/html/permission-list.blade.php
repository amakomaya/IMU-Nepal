<div style="position: fixed;
bottom: 0;
margin-left: 40px;
margin-botton: 10px;">
<li><b><a href="#">Your permissions:</a></b></li>

<?php
    $permissions = auth()->user()->getAllPermissions()->pluck('name');
    $permissions = json_decode($permissions);
?>

@foreach ($permissions as $item)
<li>
    <a href="#">{{ $item }}</a>
</li>
@endforeach
</div>