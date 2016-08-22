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
                                        src="<?php echo VIEW_URL . '/images/icon-twiter.png' ?>" alt=""></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-prin.png' ?>" alt=""></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-facebook.png' ?>" alt=""></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-youtube.png' ?>" alt=""></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-insta.png' ?>" alt=""></a>
                            </li>

                            <li>
                                <a href="#" title="" target="_blank" rel="nofollow"><img
                                        src="<?php echo VIEW_URL . '/images/icon-google.png' ?>" alt=""></a>
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
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <h4>Company information</h4>
                        <ul>
                            <li><a href="<?php echo WP_SITEURL . '/about-us'; ?>" title=""> About Us </a></li>
                            <li><a href="<?php echo WP_SITEURL . '/contact-us'; ?>" title=""> Contact Us</a></li>
                            <li><a href="#" title=""> Media Centre</a></li>
                            <li><a href="<?php echo WP_SITEURL . '/faq'; ?>" title=""> FAQ</a></li>
                            <li><a href="#" title=""> Partner</a></li>
                        </ul>
                    </div>

                    <div class="col-xs-12 col-sm-2">
                        <h4>Ship</h4>
                        <ul>
                            <li><a href="#" title="">Overview</a></li>
                            <li><a href="#" title="">Mekong Princess</a></li>
                            <li><a href="#" title="">Viet Princess</a></li>
                            <li><a href="#" title="">Ship Tour</a></li>
                        </ul>
                    </div>

                    <div class="col-xs-12 col-sm-2">
                        <h4>Journey</h4>
                        <ul>
                            <li><a href="#" title="">Destination</a></li>
                            <li><a href="<?php echo WP_SITEURL . '/before-you-go'; ?>" title="">Before you go</a></li>
                            <li><a href="#" title=""> Manage my journey</a></li>
                            <li><a href="#" title=""> Travel information</a></li>
                            <li><a href="#" title=""> Services</a></li>
                        </ul>
                    </div>


                    <div class="col-xs-12 col-sm-2">
                        <h4>Resource</h4>
                        <ul>
                            <li><a href="#" title="">E-brochure </a></li>
                            <li><a href="#" title=""> Gallery </a></li>
                            <li><a href="#" title=""> Video </a></li>
                        </ul>
                    </div>

                    <div class="col-xs-12 col-sm-3 text-right">
                        <img src="<?php echo VIEW_URL . '/images/verify-web.png' ?>" alt="">
                        <br/><br/>
                        MEKONG RIVER CRUISER LIMITED <br/>
                        Add: No. 11.O, Mieu Noi Residential Area, Ward 3, Binh Thanh District, HCMC, Vietnam <br/>
                        Tel: +84 8 3514 6033 <br/>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="copy-right">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">&copy; <?php echo date('Y'); ?> Mekong River Cruise.</div>
                        <div class="col-xs-12 col-sm-6 text-right">
                            <a href="<?php echo WP_SITEURL . '/terms-of-use'; ?>" title="">
                                Terms of Use
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


<div id="fb-root"></div>
<?php wp_footer(); ?>
</body>
</html>