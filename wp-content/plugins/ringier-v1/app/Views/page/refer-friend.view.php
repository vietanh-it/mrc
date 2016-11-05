<?php
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(WP_SITEURL.'/refer-friends/'));
    exit;
}
$user = wp_get_current_user();
get_header();
while (have_posts()) : the_post();
    ?>
    <div class="container">
        <div class="row" style="margin-bottom: 60px">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h1 class="tile-main" style="margin-bottom: 10px"><?php the_title() ?>
                            <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <p class="font-playfair" style="color: #898989;font-style: italic;text-align: center;font-size: 14px;margin-bottom: 30px">The journey would be fun if you have friends to join it</p>
                        <form class="contact-us" id="form-refer-friend">
                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control" id="rf_full_name" name="rf_full_name" placeholder="Your name" value="<?php echo !empty($user->display_name) ? $user->display_name : '' ?>" readonly>
                            </div>

                            <div class="form-group">
                                <!--<label for="last_name" class="text"></label>-->
                                <input type="text" class="form-control"  name="rf_email" placeholder="Your email address" value="<?php echo !empty($user->user_email) ? $user->user_email : '' ?>" readonly>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-control" id="rf_subject" name="rf_subject" placeholder="Subject: [Your name] invites you to join a cruise" value="<?php echo !empty($user->display_name) ? $user->display_name .' invites you to join a cruise' : '' ?>" readonly>
                            </div>
                            <div class="form-group">
                                <textarea rows="5" class="form-control" name="email_friend" placeholder=" Input your friends's email, one per line
 Email 1
 Email 2"></textarea>
                            </div>

                            <div class="form-group">
                                <textarea rows="5" class="form-control" name="rf_message" placeholder="Your message"></textarea>
                            </div>

                            <div class="text-center">
                                <input class="bnt-primary" type="submit" value="send message" style="font-weight: normal">
                                <input type="hidden" name="action" value="ajax_handler_account">
                                <input type="hidden" name="method" value="ReferFriend">
                            </div>
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-6 contact-right">
                        <p class="font-playfair" style="color:black;text-align: center;font-size: 17px;margin-bottom: 27px;text-transform: uppercase;font-weight: bold">Inviting List</p>
                        <p class="font-playfair" style="color: #898989;font-style: italic;text-align: center;font-size: 14px;margin-bottom: 27px">You invited <b class="number_invited" style="color: black;font-family: 'Times New Roman';font-weight: bold;font-size: 17px"><?php echo !empty($list_refer) ? count($list_refer) : 0 ?></b> following people</p>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if (!empty($list_refer)) {
                                foreach ($list_refer as $k => $v) {
                                    ?>
                                    <tr >
                                        <td>
                                            <?php echo $v->email_refer ?>
                                        </td>
                                        <td>
                                            <?php if($v->status == 'publish'){
                                                echo '<span style="color: green"><i class="fa fa-check-square-o" aria-hidden="true" style="font-size: 20px"></i></span>';
                                            }else{
                                                echo '<span style="color: orange">'.$v->status.'</span>';
                                            } ?>
                                        </td>
                                    </tr>

                                <?php }
                            }
                            else { ?>
                                <tr>
                                    <td colspan="2"> No invitations</td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile;
get_footer();