<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Oops! Something went wrong</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>.store-click,.store-links,.store-ops,.store-tips{font-family:monospace}body{padding:0;margin:0;background:rgb(255 255 255)}.page-go-url{height:100vh;display:flex;align-items:center;justify-content:center}.go-info-wrap{width:100%;text-align:center;padding:0 10px 100px;box-sizing:border-box;color:#000}.store-ops{font-weight:700;font-size:80px;margin-bottom:10px}.store-links{font-size:30px;margin-bottom:28px}.store-click{font-size:20px;margin-bottom:32px}.store-tips{font-size:16px;padding-top:30px}.store-tips span{text-decoration:underline;cursor:pointer}@media only screen and (max-width:600px){.go-info-wrap{margin:-300px auto 0;padding:15px}.store-ops{font-size:40px}.store-links{font-size:20px}.store-click{font-size:16px}}.custom-button{font-size:18px;font-weight:700;border-radius:100px;-webkit-box-shadow:0 15px 25px 0 rgb(124 96 213 / 30%);box-shadow:0 15px 25px 0 rgb(124 96 213 / 30%);padding:14px 40px;border:none;text-transform:uppercase;background:#000;color:#fff}</style>
</head>

<body>
<div class="page-go-url">
    <div class="go-info-wrap">
        <div class="store-ops">Oops!</div>
        <div class="store-links">The tracking link may have expired.</div>
        @if(isset($advertiser->name))
            <div class="store-click">But you can still visit the store.<br><br><br>
                <a href="{{ $advertiser->url }}" target="_blank" class="custom-button">Visit {{ $advertiser->name }}</a>
            </div>
        @endif
        <div class="store-tips">
            For any inquiry, please contact <span class="send-btn"><a href="mailto:info@theaffilo.com">info@theaffilo.com</a></span>
        </div>
    </div>
</div>
</body>

</html>
