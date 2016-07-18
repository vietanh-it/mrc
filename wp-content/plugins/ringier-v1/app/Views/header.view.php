<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="vi" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="vi" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="vi" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>
    <title><?php wp_title('-', true, 'right'); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <!--[if lt IE 9]>
    <script src="<?php echo VIEW_URL ?>/js/html5.js"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="vi" />
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <link href='https://fonts.googleapis.com/css?family=Playfair+Display' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <?php wp_head(); ?>
    <script>
        /*(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
         })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

         ga('create', 'UA-62562895-1', 'auto');
         ga('send', 'pageview');*/
    </script>


</head>
<body <?php body_class(); ?>>

<!--[if lt IE 10]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->


<header>
    <div class="top-bar">
        <div class="container container-big">
            <div class="row">
                <div class="col-xs-12 col-sm-7">
                    <ul class="top-info">
                        <li><a href="#"> <img src="<?php echo VIEW_URL.'/images/icon-add-friends.png' ?>"> Refer a Friend</a></li>
                        <li><a href="#"> Media Centre </a></li>
                        <li><a href="#"> Q&A </a></li>
                        <li><a href="#"> Terms and Conditions </a></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-5">
                    <ul class="top-info text-right">
                        <li><a href="#"> <i class="fa fa-calendar" aria-hidden="true"></i> Your booking</a></li>
                        <li class="sign-up"> <img src="<?php echo VIEW_URL.'/images/icon-user.png' ?>">
                            &nbsp; <a href="#"> Sign in </a> | <a href="#"> Sign up</a> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar">
        <div class="container container-big">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <a href="<?php echo WP_SITEURL ?>"><img src="<?php echo VIEW_URL ?>/images/logo.png" width="100%"></a>
                </div>
                <div class="col-sm-7 col-xs-12">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="<?php echo WP_SITEURL ?>" title="" > Home </a></li>
                            <li><a href="#" title="">WHY US   </a></li>
                            <li><a href="#" title="">SHIPs </a></li>
                            <li><a href="<?php echo WP_SITEURL.'/journey/' ?>" title="">JOURNEy </a></li>
                            <li><a href="#" title="">OFFERS </a></li>
                            <li><a href="#" title="">RESOURCEs </a></li>
                            <li><a href="#" title="">news </a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-2">
                    <div class="action-contact">
                        <a href="#" title="">
                            <img src="<?php echo VIEW_URL .'/images/icon-headphone.png' ?>">                                           <div class="right-icon"><span class="top">1-800-304-9616</span><br><span class="bot">or contact our agent</span> </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>