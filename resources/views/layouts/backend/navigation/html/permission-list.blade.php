<div style="position: fixed;
bottom: 0;
margin-left: 15px;
padding:10px;
background-color: white;
width:200px;
border: 2px solid black">
<li><b>Your permissions:</b></li>

<?php
    $permissions = auth()->user()->getAllPermissions()->pluck('name')->sort();
    $permissions = json_decode($permissions);
    ?>

<ul>
@foreach ($permissions as $item)
<li>
    <?php
        $item = str_replace('-', ' ', $item);
    ?>
    {{ ucfirst($item) }}
</li>
@endforeach
</ul>
</div>