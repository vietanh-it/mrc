<?php
get_header();
while (have_posts()) : the_post();
    $Location = \RVN\Models\Location::init();
    $country_list = $Location->getCountryList();

    ?>
    <div class="container">
        <div class="row" style="margin-bottom: 60px">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h1 class="tile-main" style="margin-bottom: 10px"><?php the_title() ?>
                            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <p class="font-playfair" style="color: #898989;font-style: italic;text-align: center;font-size: 14px;margin-bottom: 30px">Leave us a message below or contact us via our contact information</p>
                        <form class="contact-us">
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control" id="contact_full_name" name="contact_full_name" placeholder="Your name">
                            </div>
                            <div class="form-group">
                                <select name="contact_country" id="contact_country" class="form-control" style="font-weight: bold;color: black">
                                    <option value="">Where are you from</option>
                                    <?php if(!empty($country_list)){
                                        foreach ($country_list as $c){
                                            ?>
                                            <option value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($user_info->nationality) && $user_info->nationality == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control"  name="contact_email" placeholder="E-mail">
                            </div>
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" placeholder="Phone number">
                            </div>

                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control" id="contact_subject" name="contact_subject" placeholder="Message subject">
                            </div>
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <textarea rows="5" class="form-control" name="contact_message" placeholder="Leave your message  "></textarea>
                            </div>
                            <div class="text-center">
                                <input class="bnt-primary" type="submit" value="send massage" style="font-weight: normal">

                                <input type="hidden" name="action" value="ajax_handler_account">
                                <input type="hidden" name="method" value="SendContact">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-6 contact-right">
                        <p class="font-playfair" style="color: #898989;font-style: italic;text-align: center;font-size: 14px;margin-bottom: 27px">Contact us via</p>
                        <ul class="socail-icon">
                            <li>
                                <a href="#" target="_blank" rel="nofollow"><img src="<?php echo VIEW_URL .'/images/icon-twiter-3.png' ?>"></a>
                            </li>
                            <li>
                                <a href="#" target="_blank" rel="nofollow"><img src="<?php echo VIEW_URL .'/images/icon-prin-3.png' ?>"></a>
                            </li>
                            <li>
                                <a href="#" target="_blank" rel="nofollow"><img src="<?php echo VIEW_URL .'/images/icon-facebook-3.png' ?>"></a>
                            </li>
                            <li>
                                <a href="#" target="_blank" rel="nofollow"><img src="<?php echo VIEW_URL .'/images/icon-youtube-3.png' ?>"></a>
                            </li>
                            <li>
                                <a href="#" target="_blank" rel="nofollow"><img src="<?php echo VIEW_URL .'/images/icon-insta-3.png' ?>"></a>
                            </li>
                            <li>
                                <a href="#" target="_blank" rel="nofollow"><img src="<?php echo VIEW_URL .'/images/icon-google-3.png' ?>"></a>
                            </li>
                        </ul>

                        <div class="info-ct" style="margin-top: 20px">
                            <img src="<?php echo VIEW_URL .'/images/icon-location-2.png '?>" alt="" style="padding-right: 12px; font-weight: bold;"> <b>MEKONG RIVER CRUISER LIMITED</b>
                            <ul style="padding-left: 45px; color: #898989;">
                                <li>Add: No. 11.O, Mieu Noi Residences, Ward 3, Binh Thanh District, HCMC, Vietnam</li>
                                <li>Tel: +84 8 3514 6033</li>
                            </ul>
                        </div>
                        <div class="map" style="margin-top: 28px">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3919.1933797263223!2d106.69246756681802!3d10.79649625170139!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1svi!2s!4v1473994758611" width="100%" height="245px" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile;
get_footer();