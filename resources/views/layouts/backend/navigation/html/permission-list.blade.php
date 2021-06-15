<div style="position: fixed;
bottom: 0;
margin-left: 15px;
padding:10px;
background-color: white;
width:200px;
border: 2px solid black;
z-index:1;">
    <li><b id="permain" style="cursor: pointer">Your permissions <i class="fa fa-chevron-up" aria-hidden="true"></i></b></li>

    <?php
        $permissions = auth()->user()->getAllPermissions()->pluck('name')->sort();
        $permissions = json_decode($permissions);
    ?>

    <ul id="perlist" style="padding-top: 10px;">
        @foreach ($permissions as $item)
        <li>
            <?php $item = str_replace('-', ' ', $item); ?>
            {{ ucfirst($item) }}
        </li>
        @endforeach
    </ul>
</div>

<script>
    $("#perlist").hide();
    jQuery("#permain").click(function(){
        jQuery("#perlist").toggle();
        $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down')
    });
</script>