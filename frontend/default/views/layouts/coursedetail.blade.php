

<!DOCTYPE html>
<html  class="no-js" lang="en-US">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#db4938" />
    <link rel="SHORTCUT ICON" href="{{ base_url('uploads/images/'.frontendData::get_backend('photo')) }}">
    <title> {{ frontendData::get_backend('sname') }}  </title>

    @include('views/partials/headerAssetsFrontend')
</head>
 
<body class="head" data-bs-spy="scroll" data-bs-method="offset" data-bs-target="#list-example" data-bs-offset="500" class="scrollspy-example" tabindex="0">
    <header id="home" class="site-header">  
        @include('views/partials/navbar')
    </header>

    @yield('content')
    @include('views/partials/footer')
    @include('views/partials/footerAssets')
</body>
</html>


