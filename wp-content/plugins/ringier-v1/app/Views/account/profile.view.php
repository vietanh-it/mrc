<?php
$user_info = !empty($user_info) ? $user_info : array();
$country_list = !empty($country_list) ? $country_list : array();
$return = !empty($return) ? $return : array();

$user = wp_get_current_user();

get_header();
?>
<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">ACCOUNT SETTING
            <br> <img src="<?php echo VIEW_URL . '/images/line.png?v=1' ?>" style="width: 110px">
        </h1>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <div class="message">
                <?php if(!empty($return['status'])){
                    if($return['status'] == 'error'){

                        if(!empty($return['message']) ) {

                            if (is_array($return['message'])) {
                                foreach ($return['message'] as $er) { ?>
                                    <p class="text-danger bg-danger"><?php echo $er ?></p>
                                <?php }
                            } else {
                                echo '<p class="text-danger bg-danger">' . $return['message'] . '</p>';
                            }

                        }

                    } else { ?>
                      <p class="text-success bg-success">
                          <?php echo is_array($return['message']) ? $return['message'][0] : $return['message']; ?>
                      </p>
                    <?php }
                } ?>
            </div>

            <form id="profile-form" class="form-account" method="post">
                <p class="title">Your personal information</p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" value="<?php echo !empty($user->user_firstname) ? $user->user_firstname : '' ?>" id="first_name" class="form-control" >
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group" style="position: relative">
                            <label for="birthday">Birthday</label>
                            <input type="text" name="birthday" value="<?php echo !empty($user_info->birthday) ? date('d M Y', strtotime($user_info->birthday) ) : '' ?>" id="birthday" class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" value="<?php echo !empty($user->user_lastname) ? $user->user_lastname : '' ?>" id="last_name" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" value="<?php echo !empty($user_info->address) ? $user_info->address : '' ?>" id="address" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="male" <?php echo (!empty($user_info->gender) && $user_info->gender =='male')  ? 'selected': '' ?>>Male</option>
                                <option value="female" <?php echo (!empty($user_info->gender) && $user_info->gender =='female')  ? 'selected': '' ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select name="country" id="country" class="form-control">
                                <option value="">--- Select your country ---</option>
                                <?php if(!empty($country_list)){
                                    foreach ($country_list as $c){
                                        ?>
                                        <option value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($user_info->country) && $user_info->country == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit">Update</button>
                </div>
            </form>


            <form id="passport-form" class="form-account" method="post">
                <p class="title">Your passport information</p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="passport_id">Passport ID</label>
                            <input type="text" name="passport_id" value="<?php echo !empty($user_info->passport_id) ? $user_info->passport_id : '' ?>" id="passport_id" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group" style="position: relative">
                            <label for="valid_until">Valid until</label>
                            <input type="text" name="valid_until" value="<?php echo !empty($user_info->valid_until) ? date('d M Y', strtotime($user_info->valid_until)) : '' ?>" id="valid_until" class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group"  style="position: relative">
                            <label for="date_of_issue">Date of issue</label>
                            <input type="text" name="date_of_issue" value="<?php echo !empty($user_info->date_of_issue) ? date('d M Y', strtotime($user_info->date_of_issue)) : '' ?>" id="date_of_issue" class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <select name="nationality" id="nationality" class="form-control">
                                <option value="">--- Select your nationality ---</option>
                                <?php if(!empty($country_list)){
                                    foreach ($country_list as $c){
                                        ?>
                                        <option value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($user_info->nationality) && $user_info->nationality == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit">Update</button>
                </div>
            </form>


            <form id="account-form" class="form-account" method="post" style="margin-bottom: 70px;">
                <p class="title">Your account information</p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group" style="position: relative">
                            <label for="a_email">Your email address</label>
                            <input type="text" name="a_email" value="<?php echo !empty($user_info->user_email) ? $user_info->user_email : '' ?>" id="a_email" class="form-control" autocomplete="off" >
                            <label for="a_email" style="display: block; cursor: pointer;"><img src="<?php echo VIEW_URL ?>/images/icon-a-edit.jpg" style="position: absolute;
    bottom: 0px;
    right: 0px;"></label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group" style="position: relative">
                            <label for="a_password">Your password
                            </label>
                            <input type="password" name="a_password" id="a_password" class="form-control" autocomplete="off" >
                            <label for="a_password" style="display: block; cursor: pointer;"><img src="<?php echo VIEW_URL ?>/images/icon-a-edit.jpg" style="position: absolute;
    bottom: 0px;
    right: 0px;"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php get_footer() ?>
