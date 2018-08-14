<title>Sensiplicity Systems Logger</title>
<base href="/">
<br>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link type="text/css" rel="stylesheet" media="all" href="/css/node.css?1" />
<link type="text/css" rel="stylesheet" media="all" href="/css/defaults.css?1" />
<link type="text/css" rel="stylesheet" media="all" href="/css/system.css?1" />
<link type="text/css" rel="stylesheet" media="all" href="/css/system-menus.css?1" />
<link type="text/css" rel="stylesheet" media="all" href="/css/thermometer.css?1" />
<link type="text/css" rel="stylesheet" media="all" href="/css/user.css?1" />
<link type="text/css" rel="stylesheet" media="all" href="/css/style2.css?1" />
<link type="text/css" rel="stylesheet" media="all" href="/css/style.css?1" />
<script type="text/javascript" src="/css/script.js?1"></script>
<script type="text/javascript"> </script>
</head>
 <body >
    <div class="PageBackgroundGlare">
      <div class="PageBackgroundGlareImage"></div>
    </div>

    <div class="Main">
      <div class="Sheet">
        <div class="Sheet-tl"></div>
        <div class="Sheet-tr"></div>
        <div class="Sheet-bl"></div>
        <div class="Sheet-br"></div>
        <div class="Sheet-tc"></div>
        <div class="Sheet-bc"></div>
        <div class="Sheet-cl"></div>
        <div class="Sheet-cr"></div>
        <div class="Sheet-cc"></div>

        <div class="Sheet-body">

        <div class="Header">
          <div class="logo">
              <div id="logo">
		<a href="/" /><img src="images/Sensiplicity_logo_LeftOnly.png" width="760" height="120" border="0" usemap="#map" /></a>
              </div>
          </div>
        </div>
<br>
<br>
        <div class="nav">
          <div class="l"></div>
            <div class="r"></div>
              <ul class="artmenu"><li class="leaf first"><a href="/plots.php" title=""><span class="l"></span><span class="r"></span><span class="t">Sensor Plots</span></a></li>
<li class="leaf"><a href="/rawdata.php" ><span class="l"></span><span class="r"></span><span class="t">Status</span></a></li>
<li class="collapsed"><a href="/download.php" title="Data Download"><span class="l"></span><span class="r"></span><span class="t">Download Data</span></a></li>
<li class="collapsed"><a href="/about.php" title="Learn More"><span class="l"></span><span class="r"></span><span class="t">About System</span></a></li>
<?php
$value = isset($login_session) ? $login_session : '';
  if($value == "") {
     echo '<li class="leaf last"><a href="/login.php" title="Login"><span class="l"></span><span class="r"></span><span class="t">Login</span></a></li>';
  }
  else { 
     echo '<li class="leaf last"><a href="/admin.php" title="Admin Page"><span class="l"></span><span class="r"></span><span class="t">Admin Page</span></a></li>';
  }
?>
</ul>
	</div>
        <div class="cleared"></div>
        <div class="contentLayout">
  <div id="main">
  <div id="node-1" class="node clear-block">

  <div class="meta"></div>

  <div class="content">

