<script>
    "use strict";



    $(document).ready(function() {

        $("#financial_start").flatpickr({
            enableTime: false,
            dateFormat: "d-m"
        });

        var equill = new Quill('#edit_input_address', {
            theme: 'snow'
        });

        var company_address = $("#company_address").val();
        equill.clipboard.dangerouslyPasteHTML(company_address);
        equill.root.blur();
        $('#edit_input_address').on('keyup', function(){
            var edit_input_address = equill.container.firstChild.innerHTML;
            $("#company_address").val(edit_input_address);
        });

        $('.dropify').dropify();
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: {
                    'fileSize': 'The file size is too big  max.',
                    'fileFormat': 'The image format is not allowed only.'
                }
            }
        });
        var drEvent = $('#input-file-events').dropify();
        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });
        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });
        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });
        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>
