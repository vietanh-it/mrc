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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="vi"/>
    <link rel="icon" href="/favicon.ico">
    <meta name="theme-color" content="#e4a611">
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
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="https://apis.google.com/js/client:platform.js?onload=start" async defer></script>
    <script>
        function start() {
            gapi.load('auth2', function () {
                auth2 = gapi.auth2.init({
                    client_id: '<?php echo GOOGLE_CLIENT_ID; ?>'
                });
            });
        }
    </script>

</head>
<body <?php body_class(); ?>>

<!--[if lt IE 10]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.
</p>
<![endif]-->

<?php $page_name = get_query_var('pagename');
$post_type = '';
if (is_single()) {
    global $post;
    $post_type = $post->post_type;
}
?>

<header>
    <div class="top-bar">
        <div class="container container-big">
            <div class="row">
                <div class="col-xs-6 col-sm-6">
                    <ul class="top-info">
                        <li><a href="<?php echo WP_SITEURL.'/refer-friends/'?>" class="refer-friend">
                                <img src="<?php echo VIEW_URL . '/images/icon-add-friends.png' ?>"> Refer friends</a>
                            <?php /*if (is_user_logged_in()) { */?><!--
                                <form id="form-refer-friend" style="display: none" class="form-facybox">
                                    <div class="form-group">
                                        <label for="email_friend">Friend email:</label>
                                        <input type="email" value="" name="email_friend" id="email_friend"
                                               placeholder="" class="form-control">
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="hidden" name="action" value="ajax_handler_account">
                                        <input type="hidden" name="method" value="ReferFriend">
                                        <button type="submit" class="btn">Send refer</button>
                                    </div>
                                </form>
                            <?php /*} else { */?>
                                <form id="form-refer-friend" style="display: none">
                                    Please
                                    <a href="<?php /*echo wp_login_url(get_permalink()); */?>" title="sign in">Sign in</a>
                                    or
                                    <a href="<?php /*echo wp_registration_url(); */?>">Sign up</a> to refer friend.
                                </form>
                            --><?php /*} */?>
                        </li>
                        <li class="hide-on-med-and-down"><a href="<?php echo WP_SITEURL . '/news/' ?>"> Media centre </a></li>
                        <li class="hide-on-med-and-down"><a href="<?php echo WP_SITEURL . '/faq/' ?>"> FAQ </a></li>
                        <li class="hide-on-med-and-down"><a href="<?php echo WP_SITEURL; ?>/terms-of-use"> Terms of use</a></li>
                    </ul>
                </div>
                <div class="col-xs-6 col-sm-6">
                    <ul class="top-info text-right">
                        <li><a href="<?php echo WP_SITEURL . '/account/your-booking'; ?>">
                                <img src="<?php echo VIEW_URL . '/images/icon-date-2.png' ?>"
                                     style="margin-top: -3px;margin-right: 5px" alt=""> Your booking</a>
                        </li>
                        <?php if (!is_user_logged_in()) { ?>
                            <li class="sign-up hide-on-med-and-down">
                                <img src="<?php echo VIEW_URL . '/images/icon-user.png' ?>" style="padding-right: 7px;">
                                <a href="<?php echo wp_login_url(get_permalink()); ?>">
                                    Sign in
                                </a>
                                |
                                <a href="<?php echo wp_registration_url(); ?>">
                                    Sign up
                                </a>
                            </li>
                        <?php } else {
                            $objUser = \RVN\Models\Users::init();
                            $user_info = $objUser->getUserInfo(get_current_user_id());
                            ?>
                            <li class="sign-up hide-on-med-and-down">
                                <img src="<?php echo VIEW_URL . '/images/icon-user.png' ?>">
                                <a href="<?php echo WP_SITEURL . '/account/profile/' ?>">
                                    <?php echo $user_info->display_name ?>
                                </a>
                                |
                                <a href="<?php echo wp_logout_url() ?>">
                                    Log out
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar">
        <div class="container container-big">
            <div class="row">
                <div class="col-sm-3 col-xs-12 hide-on-med-and-down">
                    <a href="<?php echo WP_SITEURL ?>"><img src="<?php echo VIEW_URL ?>/images/logo.png"
                                                            width="100%"></a>
                </div>
                <div class="col-sm-7 col-xs-2">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="<?php echo is_home() ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL ?>" title=""> Home </a></li>
                            <li class="<?php echo (!empty($page_name) && $page_name == 'why-us') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/why-us/' ?>" title="">WHY US </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'ships') or $post_type == 'ship') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/ships/' ?> " title="">SHIPs </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'journeys') or $post_type == 'journey' or $post_type == 'journey_type') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/journeys/' ?>" title="">JOURNEy </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'offers') or $post_type == 'offer') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/offers/' ?>" title="">OFFERS </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'resources') or $post_type == 'resource') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/resources/' ?>" title="">RESOURCEs </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'news') or $post_type == 'new') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/news/' ?>" title="">news </a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-8 hide-on-med-and-up">
                    <a href="<?php echo WP_SITEURL ?>"><img src="<?php echo VIEW_URL ?>/images/logo.png" width="100%" style="margin-top: 10px"></a>
                </div>
                <div class="col-xs-2 hide-on-med-and-up">
                    <a href="#" class="user-mobile" data-toggle="modal" data-target="#modelUser">
                        <img src="<?php echo VIEW_URL.'/images/icon-user-2.png' ?>" alt="">
                    </a>
                    <div class="modal fade" id="modelUser" tabindex="-1" role="dialog" aria-labelledby="modelUserLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <?php if (!is_user_logged_in()) { ?>
                                    <a href="<?php echo wp_login_url(get_permalink()); ?>">
                                        Sign in
                                    </a>
                                    <a href="<?php echo wp_registration_url(); ?>">
                                        Sign up
                                    </a>
                                </div>
                                <?php } else {
                                    $objUser = \RVN\Models\Users::init();
                                    $user_info = $objUser->getUserInfo(get_current_user_id());
                                    ?>
                                    <a href="<?php echo WP_SITEURL . '/account/profile/' ?>">
                                        Hello <?php echo $user_info->display_name ?>
                                    </a>
                                    <a href="<?php echo WP_SITEURL . '/account/profile/' ?>">
                                        View profile
                                    </a>
                                    <a href="<?php echo wp_logout_url() ?>">
                                        Log out
                                    </a>
                                <?php } ?>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 hide-on-med-and-up">
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                        <ul class="nav navbar-nav">
                            <li class="<?php echo is_home() ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL ?>" title=""> Home </a></li>
                            <li class="<?php echo (!empty($page_name) && $page_name == 'why-us') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/why-us/' ?>" title="">WHY US </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'ships') or $post_type == 'ship') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/ships/' ?> " title="">SHIPs </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'journeys') or $post_type == 'journey' or $post_type == 'journey_type') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/journeys/' ?>" title="">JOURNEy </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'offers') or $post_type == 'offer') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/offers/' ?>" title="">OFFERS </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'resources') or $post_type == 'resource') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/resources/' ?>" title="">RESOURCEs </a></li>

                            <li class="<?php echo ((!empty($page_name) && $page_name == 'news') or $post_type == 'new') ? 'active' : '' ?>">
                                <a href="<?php echo WP_SITEURL . '/news/' ?>" title="">news </a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2 hide-on-med-and-down">
                    <div class="action-contact">
                        <a href="#" title="">
                            <img src="<?php echo VIEW_URL . '/images/icon-headphone.png' ?>">
                            <div class="right-icon">
                                <span class="top">1-800-304-9616</span><br><span class="bot">or contact our agent</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<?php if (function_exists('yoast_breadcrumb') && !is_home()) {
    yoast_breadcrumb('<div class="container container-big"><div class="row"><div class="col-xs-12 col-sm-12"><div class="site-breadcrumb">',
        '</div></div></div></div>');
} ?>