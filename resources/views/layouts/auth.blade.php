<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') — {{ __('site.brand') }}</title>
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#100D09">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ __('site.brand') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: time() }}">
<style>
  body{
    display:flex; align-items:center; justify-content:center; min-height:100vh; padding:30px;
    background:
      radial-gradient(560px circle at 12% 8%, rgba(226,173,82,.12), transparent 60%),
      radial-gradient(620px circle at 88% 92%, rgba(16,13,9,.07), transparent 60%),
      var(--bg);
  }
  .auth-box{width:100%; max-width:440px; background:var(--surface); border:1px solid var(--border); border-radius:20px; padding:38px; box-shadow:0 24px 60px rgba(16,13,9,.12); animation:fadeInUp .5s var(--ease) both;}
  .auth-box.wide{max-width:640px;}
  .auth-logo{display:flex; align-items:center; gap:10px; font-size:20px; font-weight:400; color:var(--primary); justify-content:center; margin-bottom:8px;}
  .auth-title{text-align:center; font-size:15px; color:var(--ink-soft); margin-bottom:28px;}
  .auth-footer{text-align:center; font-size:13px; color:var(--ink-soft); margin-top:22px;}
  .auth-footer a{color:var(--primary); font-weight:600; position:relative;}
  .auth-footer a::after{content:""; position:absolute; right:0; left:0; bottom:-2px; height:1.5px; background:var(--primary); transform:scaleX(0); transition:transform .25s var(--ease);}
  .auth-footer a:hover::after{transform:scaleX(1);}
  @media(max-width:600px){
    body{padding:14px; min-height:100%; align-items:flex-start;}
    .auth-box, .auth-box.wide{max-width:100%; padding:26px 20px; border-radius:16px;}
  }
  @media(max-width:380px){
    .auth-box, .auth-box.wide{padding:20px 16px;}
  }
</style>
@stack('styles')
</head>
<body>
  <div class="auth-box @yield('box-class')">
    <div class="auth-logo"><div class="logo-mark"><img src="{{ asset('images/logo.png') }}" alt="{{ __('site.brand') }}"></div>{{ __('site.brand') }}</div>
    <div class="auth-title">@yield('subtitle')</div>

    @if ($errors->any())
      <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @yield('content')
  </div>

  @stack('scripts')
</body>
</html>
