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
        <p><b>Name:</b> {{$data['first_name']}} {{$data['last_name']}}</p>
        <p><b>Phone number:</b> {{$data['phone_number']}}</p>
        <p><b>E-mail:</b> {{$data['email']}}</p>
        <hr>
        <b>Message</b>
        <p>
        {{$data['message']}}
        </p>
        <!-- END BODY -->

    <div class="footer" style="margin-top: 100px;background: #e8e8e8;padding: 20px;">
        <p>Please do not reply to this email. Emails sent to this address will not be answered.
        </p>
        <p>Copyright © 2018-2019{{ config('app.name') }}  </p>
    </div>
</div>
</body>
</html>
