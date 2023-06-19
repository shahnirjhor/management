<script>
//Colorpicker
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
    $('.my-colorpicker').colorpicker().on('changeColor.colorpicker', function(event){
        $('.color-id').css("color", event.color.toHex()); 
    });

});

</script>
