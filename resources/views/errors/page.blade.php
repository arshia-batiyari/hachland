<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title }} — هاچ‌لند</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: time() }}">
<style>
  body{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:#fff;}
  .err{width:min(460px,100%);text-align:center;}
  .err b{display:block;font-size:13px;letter-spacing:.08em;color:#CC9A3D;margin-bottom:10px;}
  .err h1{font-size:28px;margin:0 0 10px;}
  .err p{color:#5c564c;line-height:1.8;margin:0 0 22px;}
</style>
</head>
<body>
  <div class="err">
    <b>{{ $code }}</b>
    <h1>{{ $title }}</h1>
    <p>{{ $text }}</p>
    <a class="btn btn-primary" href="{{ url('/') }}">بازگشت به هاچ‌لند</a>
  </div>
</body>
</html>
