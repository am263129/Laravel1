<html>
<style>
    .content {
        background:#fff;
        width:50%;
        margin: 50px auto;
        padding:50px;
    }
    .logo {
        width:25%;
        margin:0 auto;
        text-align: center;
    }
    .logo span {
        margin-top:50px;
    }
    .logo p  {
        display: inline-block;
        position: relative;
        color: #404040;
        font-size: 25px;
        font-family: sans-serif;
    }
    .footer {
        margin-top:100px;
        background:#e8e8e8;
        padding:20px;
    }
</style>
<body>
<div class="content" style="background: #fff;width: 50%;margin: 50px auto;padding: 50px;">
    <div class="header">
        <div class="logo" style="width: 25%;margin: 0 auto;text-align: center;">
            <p style="display: inline-block;position: relative;color: #404040;font-size: 25px;font-family: sans-serif;">{{ config('app.name') }} </p>
        </div>
    </div>
    <hr>
    <!-- END Header -->

    <div class="body">
        <p>Hi {{$data->name}}</p>
        <p>Someone is trying to get into your account</p>
        <hr>
        <p>Locaiton: {{$data->country}} / {{$data->city}}</p>
        <p>IP: {{$data->ip}}</p>
        <p>Date: {{$data->created_at}}</p>
        <hr>
        <p>If you are please confirm the device location</p>
        <a href="{{route('confirm_device', ['token' => $data->confirm_hash])}}">Click here</a></p>
    </div>
    <!-- END BODY -->

    <div class="footer" style="margin-top: 100px;background: #e8e8e8;padding: 20px;">
        <p>Please do not reply to this email. Emails sent to this address will not be answered.
        </p>
        <p>Copyright © 2018-2019{{ config('app.name') }}  </p>
    </div>
</div>
</body>
</html>
