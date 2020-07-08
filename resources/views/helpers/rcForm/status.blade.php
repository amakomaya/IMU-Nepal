<?php 
    $value = !is_null($value) ? $value : old($name);
?>
<style type="text/css">
    	.material-switch > input[type="checkbox"] {
        display: none;   
    }

    .material-switch > label {
        cursor: pointer;
        height: 0px;
        position: relative; 
        width: 40px;  
    }

    .material-switch > label::before {
        background: rgb(0, 0, 0);
        box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        content: '';
        height: 16px;
        margin-top: -8px;
        position:absolute;
        opacity: 0.3;
        transition: all 0.4s ease-in-out;
        width: 40px;
    }
    .material-switch > label::after {
        background: rgb(255, 255, 255);
        border-radius: 16px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        content: '';
        height: 24px;
        left: -4px;
        margin-top: -8px;
        position: absolute;
        top: -4px;
        transition: all 0.3s ease-in-out;
        width: 24px;
    }
    .material-switch > input[type="checkbox"]:checked + label::before {
        background: inherit;
        opacity: 0.5;
    }
    .material-switch > input[type="checkbox"]:checked + label::after {
        background: inherit;
        left: 20px;
    }

    #status-text{
    padding-left:55px;
    }
</style>

<div class="form-group">
    <div class="row">
    <label class="col-sm-2">Status</label>
    <div class="col-sm-10">
        <div class="material-switch pull-left">
            <input id='testNameHidden' type='hidden' value="0" name='{{ $name }}'>
            <input id="someSwitchOptionPrimary" name="{{ $name }}" type="checkbox" @if($value !== 0) checked @endif value="1">
            <label for="someSwitchOptionPrimary" class="label-primary"></label>

        </div>
        <div id="status-text"></div>
    </div>
    </div>
</div>

<script type="text/javascript">
    document.getElementById("status-text").innerHTML = "Publish this post.";
    document.getElementById("someSwitchOptionPrimary").onchange = function(){
        if(document.getElementById("someSwitchOptionPrimary").checked){
            document.getElementById('testNameHidden').disabled = true;
           document.getElementById("status-text").innerHTML = "Publish this.";

    }else{
           document.getElementById("status-text").innerHTML = "Save in draft.";
    }
    }
</script>