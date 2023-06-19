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

    <!-- Toastr CSS -->
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <!-- Ambitious CSS -->
    <link href="{{ asset('css/ambitious.css') }}" rel="stylesheet">
    <!-- overlayScrollbars -->
    <link href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- flag -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">

    <!-- .. -->
    @yield('one_page_css')

    <!-- js back -->



<!-- jQuery -->
<script src="{{ asset('plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Menu Plugin JavaScript -->


<!--Style Switcher -->



<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    @yield('one_page_js')
</head>

<body class="fix-header">
<div id="wrapper">
    <div id="page-wrapper" style="background-color: white !important;">
        <div class="container-fluid">
            @php
                $version = phpversion();
                $float  = floatval($version);
                $checkphp = "7.3";
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
                if($float >= $checkphpfloat) {
                $phpc="<div class='alert alert-success'><i class='fa fa-check-circle'></i> <b>php : </b>Version Ok</div>";
                } else{
                $install_allow = 0;
                $phpc="<div class='alert alert-danger'><i class='fa fa-times-circle'></i> <b>php : </b>Error. php version less than 7.3</div>";
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
                        <form class="form-material form-horizontal" action="{{ route('install.install') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>App Name *</label>
                                <input type="text" value="ShadowHRM" name="app_name" required class="form-control ambitious-form-loading col-xs-12"  placeholder="App Name *" pattern="[a-zA-Z0-9]+" minlength="4" maxlength="20" style="cursor: not-allowed; background-color : #E9ECEF" readonly>
                                @if ($errors->has('app_name'))
                                    {{ Session::flash('error',$errors->first('app_name')) }}
                                @endif
                                <small class="form-text text-muted">No space, No special character, Minimum 4 &amp; Maximum 20 Character</small>
                            </div>
                            <div class="form-group">
                                <label>App URL *</label>
                                <input type="text" value="{{ env('APP_URL', 'null') }}" name="app_url" required class="form-control ambitious-form-loading col-xs-12"  placeholder="App URL *" pattern="https?://.+" style="cursor: not-allowed; background-color : #E9ECEF" readonly>
                                @if ($errors->has('app_url'))
                                    {{ Session::flash('error',$errors->first('app_url')) }}
                                @endif
                                 <small class="form-text text-muted">Start with <span style="color : black">http://</span> or <span style="color : black">https://</span> Example : <span style="color : black">https://yourdomain.com/hrm</span></small>
                            </div>
                            <div class="form-group">
                                <label>Host Name *</label>
                                <input type="text" value="{{ env('DB_HOST', 'null') }}" name="host_name" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Host Name *" pattern="[a-zA-Z0-9]+" style="cursor: not-allowed; background-color : #E9ECEF" readonly>
                                @if ($errors->has('host_name'))
                                    {{ Session::flash('error',$errors->first('host_name')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Name *</label>
                                <input type="text" value="{{ env('DB_DATABASE', 'null') }}" name="database_name" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Name *" style="cursor: not-allowed; background-color : #E9ECEF" readonly>
                                @if ($errors->has('database_name'))
                                    {{ Session::flash('error',$errors->first('database_name')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Port *</label>
                                <input type="text" value="{{ env('DB_PORT', 'null') }}" name="database_port" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Port *" pattern="[0-9]+" style="cursor: not-allowed; background-color : #E9ECEF" readonly>
                                @if ($errors->has('database_port'))
                                    {{ Session::flash('error',$errors->first('database_port')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Username *</label>
                                <input type="text" value="{{ env('DB_USERNAME', 'null') }}" name="database_username" required class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Username *" style="cursor: not-allowed; background-color : #E9ECEF" readonly>
                                @if ($errors->has('database_username'))
                                    {{ Session::flash('error',$errors->first('database_username')) }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Database Password </label>
                                <input type="text" value="{{ env('DB_PASSWORD', 'null') }}" name="database_password" class="form-control ambitious-form-loading col-xs-12"  placeholder="Database Password " style="cursor: not-allowed; background-color : #E9ECEF" readonly>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" style="margin-top:20px" class="btn btn-warning btn-lg" <?php if($install_allow == 0) echo "disabled"; ?> ><i class="fa fa-check" ></i> Install ShadowHRM Now</button><br/><br/>
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
