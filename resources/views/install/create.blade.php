<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('plugins/images/favicon.png') }}">
    <title>ShadowHRM</title>
    

    <!-- css back -->


<link href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.dataTables.min.css" rel="stylesheet">

<!-- Date picker flatpickr css -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<link href="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">

<link href="{{ asset('plugins/alertifyjs/css/alertify.min.css') }}" rel="stylesheet">
<!-- Summernote CSS -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
<!-- Select 2 CSS -->
<link href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
<!-- fontawesome CSS -->
<link href="{{ asset('plugins/fontawesome_5_11_2/css/all.min.css') }}" rel="stylesheet">
<!-- alertify CSS -->
<link href="{{ asset('plugins/alertifyjs/css/alertify.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/alertifyjs/css/themes/default.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/alertifyjs/css/themes/semantic.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/alertifyjs/css/themes/bootstrap.min.css') }}" rel="stylesheet">
<!-- Bootstrap Core CSS -->
<link href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- Menu CSS -->
<link href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" rel="stylesheet">
<!-- morris CSS -->
<link href="{{ asset('plugins/bower_components/morrisjs/morris.css') }}" rel="stylesheet">
<!-- animation CSS -->
<link href="{{ asset('css/animate.css') }}" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<!-- color CSS -->
<link href="{{ asset('css/colors/default.css') }}" id="theme" rel="stylesheet">
<!-- Ambitious CSS -->
<link href="{{ asset('css/ambitious.css') }}" rel="stylesheet">
<!-- Toastr CSS -->
<link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
<!-- Datatables CSS -->
<link href="{{ asset('plugins/bower_components/datatables.net-bs/css/dataTables.bootstrap.css') }}" rel="stylesheet">
<!-- Date picker plugins css -->
<link href="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<!-- Include stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- .. -->
    @yield('one_page_css')

    <!-- js back -->
    

    <script src="{{ asset('plugins/alertifyjs/alertify.min.js') }}"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- jQuery -->
<script src="{{ asset('plugins/fontawesome_5_11_2/js/all.min.js') }}"></script>

<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Menu Plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<!-- chatjs -->
<script src="{{ asset('plugins/bower_components/chart.js/bundle.js') }}"></script>
<script src="{{ asset('plugins/bower_components/chart.js/utils.js') }}"></script>
<!--slimscroll JavaScript -->
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('js/waves.js') }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ asset('js/custom.min.js') }}"></script>
<!--Style Switcher -->
<script src="{{ asset('plugins/bower_components/styleswitcher/jQuery.style.switcher.js') }}"></script>
<!--Datatables -->
<script src="{{ asset('plugins/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- Select 2 js -->
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<!-- Summernote js -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js" defer></script>
<!-- Date Picker Plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- Bootstrap Multiselect -->
<script src="{{ asset('plugins/bootstrap-multiselect/bootstrap-multiselect.js') }}"></script>
<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<!-- Clock Plugin JavaScript -->
<script src="{{ asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>

    
    @yield('one_page_js')
</head>

<body class="fix-header">
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
    </svg>
</div>
<div id="wrapper">
    <div id="page-wrapper" style="background-color: white !important;">
        <div class="container-fluid">
            @php

                $version = phpversion();
                $float  = floatval($version);
                $checkphp = "7.1.3";
                $checkphpfloat  = floatval($checkphp);

                $phpc="Can't Checked";
                $ctype="Can't Checked";
                $json="Can't Checked";
                $mbstring="Can't Checked";
                $openssl="Can't Checked";
                $pdo="Can't Checked";
                $tokenizer="Can't Checked";
                $xml="Can't Checked";
                $set_time_limit="Can't Checked";
                $install_allow = 1;


                if($float > $checkphpfloat) {
                $phpc="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>php : </b>Version Ok</div>";
                } else{
                $install_allow = 0;
                $phpc="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>php : </b>Error. php version less than 7.1.3</div>";
                }

                if (extension_loaded('ctype')) {
                    $ctype="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>ctype : </b>Ok. Enabled.</div>";
                } else {
                    $install_allow = 0;
                    $ctype="<div class='alert alert-danger'><i class='fa fa-check-circle'></i> <b>ctype : </b>Error ctype is not Enabled. Please enable it.</div>";
                }

                if(function_exists("mb_detect_encoding")) {
                    $mbstring="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>mbstring : </b>Ok. Enabled.</div>";
                } else{
                    $install_allow = 0;
                    $mbstring="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>mbstring : </b>Error.Mbstring is not Enabled. Please enable it.</div>";
                }


                if (extension_loaded('openssl')) {
                    $openssl="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>openssl : </b>Ok. Enabled.</div>";
                } else {
                    $install_allow = 0;
                    $openssl="<div class='alert alert-danger'><i class='fa fa-check-circle'></i> <b>openssl : </b>Error openssl is not Enabled. Please enable it.</div>";
                }

                if (class_exists('PDO')) {
                    $pdo="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>PDO : </b>Ok. Enabled.</div>";
                } else {
                    $install_allow = 0;
                    $pdo="<div class='alert alert-danger'><i class='fa fa-check-circle'></i> <b>PDO : </b>Error pdo is not Enabled. Please enable it.</div>";
                }


                if (extension_loaded('tokenizer')) {
                    $tokenizer = "<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>Tokenizer : </b>Ok. Enabled.</div>";
                } else {
                    $install_allow = 0;
                    $tokenizer="<div class='alert alert-danger'><i class='fa fa-check-circle'></i> <b>Tokenizer : </b>Error Tokenizer is not Enabled. Please enable it.</div>";
                }

                if (extension_loaded('xml')) {
                    $xml = "<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>Xml : </b>Ok. Enabled.</div>";
                } else {
                    $install_allow = 0;
                    $xml="<div class='alert alert-danger'><i class='fa fa-check-circle'></i> <b>Xml : </b>Error Xml is not Enabled. Please enable it.</div>";
                }

                if(function_exists('set_time_limit')) {
                $set_time_limit="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>set time limit: </b>OK. Supported</div>";
                } else{
                $install_allow = 0;
                $set_time_limit="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>set time limit : </b>Error. set_time_limit() is not enabled. Please enable set_time_limit() function.</div>";
                }
            @endphp

            <br>
            <style>#recovery_form{text-align:center;}</style>
            <div class="row" style="padding-left:15px;padding-right:15px;">
                <div class="col-sm-12 col-xs-12 col-md-7 col-lg-7 border_gray grid_content padded background_white alert">
                    <h2 class="column-title"><i class="fas fa-cog"></i> Install ShadowHRM Package</h2>
                    <hr>
                    <br>

                    @if(Session::has('mysql_error'))
                        @if(Session::get('mysql_error') != "")
                            <?php echo "<pre style='margin:0 auto;color:red;text-align:center;'><h3 style='color:red;'>"; ?>
                            {{ Session::get('mysql_error') }}
                            @php Session::forget('mysql_error'); @endphp
                            <?php echo "</h3></pre><br/>"; ?>
                        @endif
                    @endif
                    <div class="account-wall" id='recovery_form' style='text-align:left; padding:0 15px;'>
                        <form class="form-material form-horizontal" action="{{ route('install.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>App Name *</label>
                                <input type="text" value="ShadowHRM" name="app_name" required class="form-control ambitious-form-loading col-xs-12"  placeholder="App Name *">
                                @if ($errors->has('app_name'))
                                    {{ Session::flash('error',$errors->first('app_name')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>App URL *</label>
                                <input type="text" value="https://" name="app_url" required class="form-control ambitious-form-loading col-xs-12"  placeholder="App URL *">
                                @if ($errors->has('app_url'))
                                    {{ Session::flash('error',$errors->first('app_url')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Host Name *</label>
                                <input type="text" value="localhost" name="host_name" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Host Name *">
                                @if ($errors->has('host_name'))
                                    {{ Session::flash('error',$errors->first('host_name')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Name *</label>
                                <input type="text" name="database_name" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Name *">
                                @if ($errors->has('database_name'))
                                    {{ Session::flash('error',$errors->first('database_name')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Port *</label>
                                <input type="text" value="3306" name="database_port" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Port *">
                                @if ($errors->has('database_port'))
                                    {{ Session::flash('error',$errors->first('database_port')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Username *</label>
                                <input type="text" name="database_username" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Username *">
                                @if ($errors->has('database_username'))
                                    {{ Session::flash('error',$errors->first('database_username')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Password </label>
                                <input type="password" name="database_password" class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Password ">
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" style="margin-top:20px" class="btn btn-warning btn-lg" <?php if($install_allow == 0) echo "disabled"; ?> ><i class="fa fa-check"></i> Install ShadowHRM Now</button><br/><br/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12 col-md-5 col-lg-5 border_gray grid_content padded background_white alert">
                    <h2 class="column-title"><i class="fas fa-wrench"></i> Server Requirements</h2>
                    <hr>
                    <br>
                    <?php
                    echo $phpc;
                    echo $ctype;
                    echo $mbstring;
                    echo $openssl;
                    echo $pdo;
                    echo $tokenizer;
                    echo $xml;
                    echo $set_time_limit;
                    ?>
                    <?php if($install_allow==1) :?>
                    <div class="alert alert-info text-center"><b>Congratulation ! Your server is fully configured to install this application.</b></div>
                    <?php else : ?>
                    <div class="alert alert-danger text-center"><b>Warning ! Please fullfill the above requirements (red colored) first.</b></div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
