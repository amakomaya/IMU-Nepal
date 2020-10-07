$(document).ready(function() {
    $('[id^="dataTables-example"]').DataTable({
            responsive: true,
            pageLength: 50,
            "dom" : '<"top"f>rt<"button"lp><"clear">'
    });
});

document.querySelector("#print").addEventListener("click", function() {
window.print();
});

function printDiv(printable) {
    var printContents = document.getElementById(printable).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}

// Change the type of input to password or text 
function TogglePassword() { 
    var temp = document.getElementById("password"); 
    if (temp.type === "password") { 
        temp.type = "text"; 
    } 
    else { 
        temp.type = "password"; 
    } 
} 

$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
});