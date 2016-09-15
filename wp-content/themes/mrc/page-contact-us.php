<?php
get_header();
while (have_posts()) : the_post(); ?>
    <div class="container">
        <div class="row" style="margin-bottom: 30px">
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
                        <p style="color: #898989;font-style: italic;text-align: center;font-size: 15px;margin-bottom: 30px">Leave us a message below or contact us via our contact information</p>
                        <form class="contact-us">
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control" id="contact_full_name" name="contact_full_name" placeholder="Your name">
                            </div>
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <!--<input type="text" class="form-control" id="contact_country" name="contact_country" placeholder="Your name">-->
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
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <p style="color: #898989;font-style: italic;text-align: center;font-size: 15px;margin-bottom: 30px">Contact us via</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile;
get_footer();