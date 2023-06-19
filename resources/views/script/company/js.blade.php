<script>
    "use strict";
    $(document).ready(function() {
        $(".select2").select2();
    });

    $(document).ready(function() {
        $('.dropify').dropify();
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
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

    $(document).ready( function () {
        $('#laravel_datatable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ url('companies-list') }}",
            type:'POST', 'headers': { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'c_logo', name: 'c_logo' },
                { data: 'c_name', name: 'c_name' },
                { data: 'email', name: 'email' },
                { data: 'website', name: 'website' },
                { data: 'contact_number', name: 'contact_number' },
                { data: 'c_address', name: 'c_address' },
                    @canany(['company-delete', 'company-edit'])
                { data: 'action', name: 'action' },
                @endcan
            ]
        });
    });

    $(document.body).on('click','#submit_country',function(){
        var itemName = "{{ $ApplicationSetting->item_name  }}";
        $('#submit_country').addClass('disabled');
        var user_id = $("#user_id").val();
        var company_name = $("#company_name").val();
        var company_type = $("#company_type").val();
        var trading_name = $("#trading_name").val();
        var registration_number = $("#registration_number").val();
        var weekly_holiday = $("#weekly_holiday").val();
        var etin = $("#etin").val();
        var email = $("#email").val();
        var website = $("#website").val();
        var address = $("#address").val();
        var contact_number = $("#contact_number").val();
        var city = $("#city").val();
        var state = $("#state").val();
        var zip_code = $("#zip_code").val();
        var country = $("#country").val();
        var logo = $("#logo").val();

        if(weekly_holiday==undefined) {
            alertify.alert(itemName, 'Create a Weekly Holiday By First');
            return;
        }
        if(weekly_holiday=="") {
            alertify.alert(itemName, 'Select Weekly Holiday By First');
            return;
        }

        if(user_id=="") {
            alertify.alert(itemName, 'User Id Required');
            return;
        }

        if(company_name=="") {
            alertify.alert(itemName, 'Please Type Your Company Name');
            return;
        }

        if(company_type=="") {
            alertify.alert(itemName, 'Please Type Your Company Type');
            return;
        }

        if(address=="") {
            alertify.alert(itemName, 'Please Type Your Address');
            return;
        }

        if(city=="") {
            alertify.alert(itemName, 'Please Type Your City');
            return;
        }

        if(state=="") {
            alertify.alert(itemName, 'Please Type Your State');
            return;
        }

        if(zip_code=="") {
            alertify.alert(itemName, 'Please Type Your Zip Code');
            return;
        }

        if(country=="") {
            alertify.alert(itemName, 'Please Type Your country');
            return;
        }

        if(logo=="") {
            alertify.alert(itemName, 'Please Type Your Logo');
            return;
        }

        var queryString = new FormData($("#auto_company_form")[0]);
        $.ajax({
            url: '{{ url('company/store') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type:'POST',
            data:queryString,
            dataType : 'JSON',
            cache: false,
            contentType: false,
            processData: false,
            success:function(response){
                if(response.status==0){
                    alertify.alert(itemName, 'Oops something wrong.And try again.', function(){
                        $('#submit_country').removeClass('disabled');
                        location.reload();
                    });
                }
                else {
                    alertify.alert(itemName, 'Succussfully Insert Campany Information :)', function(){
                        $('#submit_country').removeClass('disabled');
                        location.reload();
                    });
                }
            }
        });
    });

    $(document.body).on('click','.edit_company',function(){
        var itemName = "{{ $ApplicationSetting->item_name  }}";
        var company_table_id = $(this).attr('table_id');
        $.ajax({
            url:'{{ url('company/getEditCompanyData') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            method:"POST",
            data:{company_table_id:company_table_id},
            dataType:"JSON",
            success:function(data)
            {
                var edit_weekly_holiday = data.edit_weekly_holiday;
                var weekly_array = edit_weekly_holiday.split(",");
                $("#edit_table_id").val(data.table_id);
                $("#edit_user_id").val(data.edit_user_id);
                $("#edit_company_name").val(data.edit_company_name);
                $("#edit_company_type option[value='"+data.edit_company_type+"']").attr("selected","selected");
                $("#edit_trading_name").val(data.edit_trading_name);
                $('#edit_weekly_holiday').val(weekly_array);
                $('#edit_weekly_holiday').trigger('change');
                $("#edit_registration_number").val(data.edit_registration_number);
                $("#edit_etin").val(data.edit_etin);
                $("#edit_email").val(data.edit_email);
                $("#edit_website").val(data.edit_website);
                $("#edit_address").val(data.edit_address);
                $("#edit_city").val(data.edit_city);
                $("#edit_state").val(data.edit_state);
                $("#edit_contact_number").val(data.edit_contact_number);
                $("#edit_zip_code").val(data.edit_zip_code);
                $("#edit_country option[value='"+data.edit_country+"']").attr("selected","selected");
                $('#edit_company_modal').modal('show');
            }
        });
    });

    $(document.body).on('click','#edit_submit_company',function() {
        var itemName = "{{ $ApplicationSetting->item_name  }}";
        $("#edit_submit_company").prop("edit_submit_company", true);
        var table_id = $("#edit_table_id").val();
        var user_id = $("#edit_user_id").val();
        var edit_company_name = $("#edit_company_name").val();
        var edit_company_type = $("#edit_company_type").val();
        var edit_trading_name = $("#edit_trading_name").val();
        var edit_weekly_holiday = $("#edit_weekly_holiday").val();
        var edit_registration_number = $("#edit_registration_number").val();
        var edit_etin = $("#edit_etin").val();
        var edit_email = $("#edit_email").val();
        var edit_website = $("#edit_website").val();
        var edit_address = $("#edit_address").val();
        var edit_contact_number = $("#edit_contact_number").val();
        var edit_city = $("#edit_city").val();
        var edit_state = $("#edit_state").val();
        var edit_zip_code = $("#edit_zip_code").val();
        var edit_country = $("#edit_country").val();

        if (table_id == "") {
            alertify.alert(itemName, 'Id is Required ');
            return;
        }
        if (user_id == "") {
            alertify.alert(itemName, 'User Id Is Required');
            return;
        }

        if(edit_company_name=="") {
            alertify.alert(itemName, 'Please Type Your Company Name');
            return;
        }

        if(edit_company_type=="") {
            alertify.alert(itemName, 'Please Type Your Company Type');
            return;
        }

        if(edit_weekly_holiday==undefined) {
            alertify.alert(itemName, 'Select Weekly Holiday By First');
            return;
        }
        if(edit_weekly_holiday=="") {
            alertify.alert(itemName, 'Select Weekly Holiday By First');
            return;
        }

        if(edit_address=="") {
            alertify.alert(itemName, 'Please Type Your Address');
            return;
        }

        if(edit_city=="") {
            alertify.alert(itemName, 'Please Type Your City');
            return;
        }

        if(edit_state=="") {
            alertify.alert(itemName, 'Please Type Your State');
            return;
        }

        if(edit_zip_code=="") {
            alertify.alert(itemName, 'Please Type Your Zip Code');
            return;
        }

        if(edit_country=="") {
            alertify.alert(itemName, 'Please Type Your country');
            return;
        }

        var demo = "{{ $ApplicationSetting->is_demo }}";
        if (demo == 1) {
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            alertify.alert(itemName, 'This Feature Is Disabled In Demo Version');
        } else {
            var queryString = new FormData($("#edit_company_form")[0]);
            $.ajax({
                url: '{{ url('company/updateEditCompanyAction') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: 'POST',
                data: queryString,
                dataType : 'JSON',
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status == 0) {
                        alertify.alert(itemName, 'Oops something wrong.And try again.', function () {
                            location.reload();
                        });
                    } else {
                        alertify.alert(itemName, 'Succussfully Edit Company :)', function () {
                            location.reload();
                        });
                    }
                }
            });
        }
    });

    $(document.body).on('click','.delete_company',function(){
        var demo = "{{ $ApplicationSetting->is_demo }}";
        if(demo == 1){
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            alertify.alert(itemName, 'This Feature Is Disabled In Demo Version');
        } else {
            var itemName = "{{ $ApplicationSetting->item_name  }}";
            var company_table_id = $(this).attr('table_id');
            alertify.confirm(itemName, 'Do you want to delete this company from database???',
                function(clickYes){
                    alertify.success('Ok');
                    if(clickYes) {
                        $.ajax
                        ({
                            type:'POST',
                            url:'{{ url('company/deleteCompanyAction') }}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            },
                            data:{company_table_id:company_table_id},
                            dataType:'JSON',
                            success:function(response) {
                                if(response.success) {
                                    location.reload();
                                }
                            }
                        });
                    }
                },
                function(){
                    alertify.error('Cancel');
                }
            );
        }
    });
</script>
