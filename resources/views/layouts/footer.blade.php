<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        {{ "Version : ".$ApplicationSetting->item_version }}
    </div>
    <!-- Default to the left -->
    {{ "All Rights Reserved ".date("Y") }} &copy; <strong>{{ $ApplicationSetting->item_name }}</strong>
</footer>
