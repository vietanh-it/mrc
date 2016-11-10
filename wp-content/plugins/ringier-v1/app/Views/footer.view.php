<?php if(is_user_logged_in()){
    $objBooking = \RVN\Models\Booking::init();
    $objJourney = \RVN\Models\Journey::init();
    $booking_id = 0;
    $check_user_have_booking = $objBooking->getBookingLists(get_current_user_id());
    if(!empty($check_user_have_booking)){
        foreach ($check_user_have_booking  as $v){
            if($v->status =='before-you-go'){
                $journey_info = $objJourney->getInfo($v->journey_id);
                if(strtotime($journey_info->departure) > time() ){
                    $booking_id = $v->id;
                }
            }
        }
    }
} ?>

<div class="join-with">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">

                    <div class="col-xs-12 col-sm-6">
                        <h3 class="tile-main orange text-left">Sign up to get the hottest cruise deals!</h3>

                        <form class="connect-email">
                            <input type="email" class="form-control" name="c_email" placeholder="Your email address">
                            <input type="hidden" name="action" value="ajax_handler_account">
                            <input type="hidden" name="method" value="ConnectEmail">
                            <button type="submit"><i class="fa fa-envelope-o" aria-hidden="true"></i></button>
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-6">

                        <h3 class="tile-main orange text-left">Join us</h3>
                        <ul class="social-icon">
                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-twiter.png?v3' ?>" alt="" width="32" style="width: 32px;"></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-prin.png?v3' ?>" alt="" width="32" style="width: 32px;"></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-facebook.png?v3' ?>" alt="" width="32" style="width: 32px;"></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-youtube.png?v3' ?>" alt="" width="32" style="width: 32px;"></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-insta.png?v3' ?>" alt="" width="32" style="width: 32px;"></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-google.png?v3' ?>" alt="" width="32" style="width: 32px;"></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<footer>
    <div class="container">
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-8 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <h4>Company information</h4>
                        <ul class="hide-on-med-and-down">
                            <li><a href="<?php echo WP_SITEURL . '/about-us'; ?>" title=""> About Us </a></li>
                            <li><a href="<?php echo WP_SITEURL . '/contact-us'; ?>" title=""> Contact Us</a></li>
                            <li><a href="<?php echo WP_SITEURL . '/news/' ?>" title=""> Media Centre</a></li>
                            <li><a href="<?php echo WP_SITEURL . '/faq'; ?>" title=""> FAQ</a></li>
                            <li><a href="<?php echo WP_SITEURL . '/partner/' ?>" title=""> Partners</a></li>
                        </ul>
                    </div>

                    <div class="col-xs-12 col-sm-2 ">
                        <h4>Ships</h4>
                        <ul class="hide-on-med-and-down">
                            <li><a href="<?php echo WP_SITEURL . '/ships/' ?>" title="">Overview</a></li>
                            <li>
                                <a href="<?php echo WP_SITEURL . '/ship/mekong-princess/' ?>" title="Mekong Princess">
                                    Mekong Princess
                                </a>
                            </li>
                            <li><a href="<?php echo WP_SITEURL . '/ship/viet-princess/' ?>" title="Viet Princess">Viet Princess</a>
                            </li>
                            <li><a href="<?php echo WP_SITEURL . '/ship/han-princess/' ?>" title="Han Princess">Han
                                    Princess</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-xs-12 col-sm-2 ">
                        <h4>Journeys</h4>
                        <ul class="hide-on-med-and-down">
                            <li><a href="<?php echo WP_SITEURL . '/journeys/' ?>" title="">Destination</a></li>
                            <?php if(!empty($booking_id)){ ?>
                                <li><a href="<?php echo WP_SITEURL . '/before-you-go/?id='.$booking_id; ?>" title="">Before you go</a></li>
                            <?php } ?>
                            <li>
                                <a href="<?php echo WP_SITEURL . '/account/your-booking/' ?>" title="">
                                    Your booking
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo WP_SITEURL . '/review-us/' ?>" title="">
                                    Review Us
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-xs-12 col-sm-2 ">
                        <h4>Resources</h4>
                        <ul class="hide-on-med-and-down">
                            <li><a href="<?php echo WP_SITEURL . '/resource/human-resources/'; ?>"
                                   title="Human resource">Human resource</a></li>
                            <li><a href="<?php echo WP_SITEURL . '/resource/onboard-services/'; ?>"
                                   title="On-board services">On-board services</a></li>
                            <li><a href="<?php echo WP_SITEURL . '/resource/food-and-beverage/'; ?>"
                                   title="Food and beverage">Food and beverage</a></li>
                        </ul>
                    </div>

                    <div class="col-xs-12 col-sm-3 text-right hide-on-med-and-down">
                        <div class="row">
                            <!--<div class="col-md-6 col-xs-12">-->
                            <!--    <a href="javascript:void(0)">-->
                            <!--        <img src="--><?php //echo VIEW_URL . '/images/verify-web.png' ?><!--" alt="">-->
                            <!--    </a>-->
                            <!--</div>-->
                            <div class="col-md-12 col-xs-12">
                                <a href="javascript:void(0)">
                                    <img src="<?php echo VIEW_URL . '/images/rapidssl.gif' ?>" alt="">
                                </a>
                            </div>
                        </div>
                        <br/><br/>
                        VIET PRINCESS CRUISES LIMITED <br/>
                        Add: No. 11.O, Mieu Noi Residences, Ward 3, Binh Thanh District, HCMC, Vietnam <br/>
                        Tel: +84 8 3514 6033 <br/>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 hide-on-med-and-up">
                <a href="javascript:void(0)">
                    <img src="<?php echo VIEW_URL . '/images/rapidssl.gif' ?>" alt="" style="margin-top: 20px">
                </a>
            </div>
            <div class="col-xs-12 text-left hide-on-med-and-up ul-mb">
                <img src="<?php echo VIEW_URL.'/images/icon-location.png?v=1' ?>" alt="" style="margin-right: 10px; width: 15px;"> VIET PRINCESS CRUISES LIMITED <br/>
                <ul>
                    <li>Add: No. 11.O, Mieu Noi Residences, Ward 3, Binh Thanh District, HCMC, Vietnam </li>
                    <li>Tel: +84 8 3514 6033 <br/></li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="copy-right">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6"
                             style="font-family: 'Source Sans Pro', sans-serif; font-size: 14px;">&copy; <?php echo date('Y'); ?>
                            Viet Princess Cruises
                        </div>
                        <div class="col-xs-12 col-sm-6 text-right" style="text-transform: none;">
                            <a href="<?php echo WP_SITEURL . '/terms-and-conditions'; ?>" title="">
                                Terms and Conditions
                            </a> |
                            <a href="<?php echo WP_SITEURL . '/privacy-policy'; ?>" title="">
                                Privacy Policy
                            </a> |
                            <a href="<?php echo WP_SITEURL . '/sitemap'; ?>" title="">Site Map</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>


<div class="loading-wrapper" style="display: none;">
    <div class="loading-overlay">
        <i class="fa fa-spin fa-refresh"></i>
    </div>
</div>

<a href="javascript:void(0)" class="back-top">
    <i class="fa fa-chevron-up"></i>
</a>


<div id="fb-root"></div>
<?php wp_footer(); ?>
<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
</body>
</html>