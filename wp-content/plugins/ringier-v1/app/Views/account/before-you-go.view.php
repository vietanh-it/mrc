<?php
$user_info = !empty($user_info) ? $user_info : [];
$country_list = !empty($country_list) ? $country_list : [];
get_header();
?>
<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">Before you go
            <br> <img src="<?php echo VIEW_URL . '/images/line.png' ?>">
        </h1>

        <div class="col-xs-12 col-sm-12 ">
            <div class="message">
                <?php if (!empty($return['status'])) {
                    if ($return['status'] == 'error') {

                        if (!empty($return['message'])) {

                            if (is_array($return['message'])) {
                                foreach ($return['message'] as $er) { ?>
                                    <p class="text-danger bg-danger"><?php echo $er ?></p>
                                <?php }
                            }
                            else {
                                echo '<p class="text-danger bg-danger">' . $return['message'] . '</p>';
                            }

                        }

                    }
                    else { ?>
                        <p class="text-success bg-success">
                            <?php echo is_array($return['message']) ? $return['message'][0] : $return['message']; ?>
                        </p>
                    <?php }
                } ?>
            </div>

            <div class="book-no">
                Booking No # 2015024790
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                    <div class="be-text">
                        Please fill all the traveller's information as follows to make your journey ready.
                        If you don't have enough information this time, let come back as soon you get it.
                    </div>
                </div>
            </div>

            <ul class="be-dow">
                <li><a href="#"><i class="fa fa-download" aria-hidden="true"></i> Down load the form</a></li>
                <li><a href="#"><i class="fa fa-question-circle-o" aria-hidden="true"></i> Need support?</a></li>
            </ul>

            <div class="be-travel">
                Traveller 1
            </div>

            <form method="post">
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-account">
                            <p class="title ">Traveller personal information</p>
                            <div class="form-group">
                                <label for="first_name">First name</label>
                                <input type="text" name="first_name"
                                       value="<?php echo !empty($user->user_firstname) ? $user->user_firstname : '' ?>"
                                       id="first_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last name</label>
                                <input type="text" name="last_name"
                                       value="<?php echo !empty($user->user_firstname) ? $user->user_firstname : '' ?>"
                                       id="last_name" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="middle_name">Middle name</label>
                                        <input type="text" name="middle_name"
                                               value="<?php echo !empty($user->user_firstname) ? $user->user_firstname : '' ?>"
                                               id="middle_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group" style="position: relative">
                                        <label for="nickname">Nickname</label>
                                        <input type="text" name="nickname"
                                               value="<?php echo !empty($user_info->nickname) ? ($user_info->nickname) : '' ?>"
                                               id="nickname" class="form-control " readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option
                                        value="male" <?php echo (!empty($user_info->gender) && $user_info->gender == 'male') ? 'selected' : '' ?>>
                                        Male
                                    </option>
                                    <option
                                        value="female" <?php echo (!empty($user_info->gender) && $user_info->gender == 'female') ? 'selected' : '' ?>>
                                        Female
                                    </option>
                                </select>
                            </div>

                            <div class="form-group" style="position: relative">
                                <label for="birthday">Date of birth</label>
                                <input type="text" name="birthday"
                                       value="<?php echo !empty($user_info->birthday) ? date('d M Y',
                                           strtotime($user_info->birthday)) : '' ?>" id="birthday"
                                       class="form-control datepicker" readonly>
                                <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                            </div>

                            <div class="form-group">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">--- Select your country ---</option>
                                    <?php if (!empty($country_list)) {
                                        foreach ($country_list as $c) {
                                            ?>
                                            <option
                                                value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($user_info->country) && $user_info->country == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-account">
                            <p class="title ">Travel documents</p>
                            <div class="form-group">
                                <label for="nationality">Nationality</label>
                                <select name="nationality" id="nationality" class="form-control">
                                    <option value="">--- Select your nationality ---</option>
                                    <?php if (!empty($country_list)) {
                                        foreach ($country_list as $c) {
                                            ?>
                                            <option
                                                value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($user_info->nationality) && $user_info->nationality == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="passport_id">Passport No.</label>
                                <input type="text" name="passport_id"
                                       value="<?php echo !empty($user_info->passport_id) ? $user_info->passport_id : '' ?>"
                                       id="passport_id" class="form-control">
                            </div>


                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group" style="position: relative">
                                        <label for="date_of_issue">Issued date</label>
                                        <input type="text" name="date_of_issue"
                                               value="<?php echo !empty($user_info->date_of_issue) ? date('d M Y',
                                                   strtotime($user_info->date_of_issue)) : '' ?>" id="date_of_issue"
                                               class="form-control datepicker" readonly>
                                        <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group" style="position: relative">
                                        <label for="expiration_date">Expiration Date</label>
                                        <input type="text" name="expiration_date"
                                               value="<?php echo !empty($user_info->expiration_date) ? date('d M Y',
                                                   strtotime($user_info->expiration_date)) : '' ?>" id="expiration_date"
                                               class="form-control datepicker" readonly>
                                        <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="country_of_birth">Country of birth</label>
                                <select name="country_of_birth" id="country_of_birth" class="form-control">
                                    <option value="">--- Select your country of birth ---</option>
                                    <?php if (!empty($country_list)) {
                                        foreach ($country_list as $c) {
                                            ?>
                                            <option
                                                value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($user_info->country_of_birth) && $user_info->country_of_birth == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="issued_in">Issued in</label>
                                <select name="issued_in" id="issued_in" class="form-control">
                                    <option value="">--- Select your issued in ---</option>
                                    <?php if (!empty($country_list)) {
                                        foreach ($country_list as $c) {
                                            ?>
                                            <option
                                                value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($user_info->country_of_birth) && $user_info->issued_in == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="is_visa">Visa</label>
                                        <select name="is_visa" id="is_visa" class="form-control">
                                            <option
                                                value="no" <?php echo (!empty($user_info->is_visa) && $user_info->is_visa == 'no') ? 'selected' : '' ?>>
                                                No
                                            </option>
                                            <option
                                                value="yes" <?php echo (!empty($user_info->is_visa) && $user_info->is_visa == 'yes') ? 'selected' : '' ?>>
                                                Yes
                                            </option>
                                            <option
                                                value="not_yes" <?php echo (!empty($user_info->is_visa) && $user_info->is_visa == 'not_yes') ? 'selected' : '' ?>>
                                                Not yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="travel_insurance">Travel insurance</label>
                                        <select name="travel_insurance" id="travel_insurance" class="form-control">
                                            <option
                                                value="no" <?php echo (!empty($user_info->travel_insurance) && $user_info->travel_insurance == 'no') ? 'selected' : '' ?>>
                                                No
                                            </option>
                                            <option
                                                value="yes" <?php echo (!empty($user_info->travel_insurance) && $user_info->travel_insurance == 'yes') ? 'selected' : '' ?>>
                                                Yes
                                            </option>
                                            <option
                                                value="not_yes" <?php echo (!empty($user_info->travel_insurance) && $user_info->travel_insurance == 'not_yes') ? 'selected' : '' ?>>
                                                Not yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                        <div class="form-account">
                            <p class="title ">Notes</p>
                            <div class="form-group">
                                <label for="occasion_note">Occasion note</label>
                                <input type="text" name="occasion_note" id="occasion_note" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="food_note">Food/Diet note</label>
                                <input type="text" name="food_note" id="food_note" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="medical_note">Medical note</label>
                                <input type="text" name="medical_note" id="medical_note" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="special_assistance">Special assistance</label>
                                <input type="text" name="special_assistance" id="special_assistance"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="room_no">Room No.</label>
                                <select name="room_no" id="room_no" class="form-control">
                                    <option value=""> --- Select room no ---</option>
                                    <option value="1"> Type 1 Room 1</option>
                                    <option value="2">Type 2 Room 2</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="bedding">Bedding</label>
                                <select name="bedding" id="bedding" class="form-control">
                                    <option value=""> --- Select bedding ---</option>
                                    <option value="twins"> Twins</option>
                                    <option value="queen">Queen</option>
                                    <option value="fixed_king">Fixed King</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                        <div class="form-account">
                            <p class="title ">Arrival information</p>
                            <div class="form-group" style="position: relative">
                                <label for="embarkation_date">Embarkation date</label>
                                <input type="text" name="embarkation_date"
                                       value="<?php echo !empty($user_info->embarkation_date) ? date('d M Y',
                                           strtotime($user_info->embarkation_date)) : '' ?>" id="embarkation_date"
                                       class="form-control datepicker" readonly>
                                <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                            </div>
                            <div class="form-group">
                                <label for="embarkation_city">Embarkation city</label>
                                <input type="text" name="embarkation_city" id="embarkation_city" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="last_inbound_flight_no">Last inbound flight no #</label>
                                <input type="text" name="last_inbound_flight_no" id="last_inbound_flight_no"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="last_inbound_originating_airport">Last inbound originating airport</label>
                                <input type="text" name="last_inbound_originating_airport"
                                       id="last_inbound_originating_airport" class="form-control">
                            </div>
                            <div class="form-group" style="position: relative">
                                <label for="last_inbound_date">Last inbound date</label>
                                <input type="text" name="last_inbound_date"
                                       value="<?php echo !empty($user_info->last_inbound_date) ? date('d M Y',
                                           strtotime($user_info->last_inbound_date)) : '' ?>" id="last_inbound_date"
                                       class="form-control datepicker" readonly>
                                <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                            </div>

                            <div class="form-group">
                                <label for="last_inbound_arrival_time">Last inbound arrival time</label>
                                <select name="last_inbound_arrival_time" id="last_inbound_arrival_time"
                                        class="form-control">
                                    <option value=""> --- Select time ---</option>
                                    <option value=""> time</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-4">
                        <div class="form-account">
                            <p class="title ">Departure information</p>
                            <div class="form-group" style="position: relative">
                                <label for="debarkation_date">Debarkation date</label>
                                <input type="text" name="debarkation_date"
                                       value="<?php echo !empty($user_info->debarkation_date) ? date('d M Y',
                                           strtotime($user_info->debarkation_date)) : '' ?>" id="debarkation_date"
                                       class="form-control datepicker" readonly>
                                <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                            </div>
                            <div class="form-group">
                                <label for="debarkation_city">Debarkation city</label>
                                <input type="text" name="debarkation_city" id="debarkation_city" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="first_outbound_flight_no">First outbound flight no #</label>
                                <input type="text" name="first_outbound_flight_no" id="first_outbound_flight_no"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="first_outbound_destination_airport">First outbound destination
                                    airport</label>
                                <input type="text" name="first_outbound_destination_airport"
                                       id="first_outbound_destination_airport" class="form-control">
                            </div>
                            <div class="form-group" style="position: relative">
                                <label for="first_outbound_date">First outbound date</label>
                                <input type="text" name="first_outbound_date"
                                       value="<?php echo !empty($user_info->first_outbound_date) ? date('d M Y',
                                           strtotime($user_info->first_outbound_date)) : '' ?>" id="first_outbound_date"
                                       class="form-control datepicker" readonly>
                                <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                            </div>

                            <div class="form-group">
                                <label for="first_outbound_departure_time">First outbound departure time</label>
                                <select name="first_outbound_departure_time" id="first_outbound_departure_time"
                                        class="form-control">
                                    <option value=""> --- Select time ---</option>
                                    <option value=""> time</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-4">
                        <div class="form-account">
                            <p class="title ">Service addons</p>
                            <div class="form-group">
                                <label for="transfers_only">Transfers only</label>
                                <select name="transfers_only" id="transfers_only" class="form-control">
                                    <option value=""> --- Select ---</option>
                                    <option value="yes"> Yes</option>
                                    <option value="no"> No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pretour_extension">Pre-tour extension</label>
                                <select name="pretour_extension" id="pretour_extension" class="form-control">
                                    <option value=""> --- Select ---</option>
                                    <option value="yes"> Yes</option>
                                    <option value="no"> No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="posttour_extension">Post-tour extension</label>
                                <select name="posttour_extension" id="posttour_extension" class="form-control">
                                    <option value=""> --- Select ---</option>
                                    <option value="yes"> Yes</option>
                                    <option value="no"> No</option>
                                </select>
                            </div>

                            <div class="form-group" style="padding-bottom: 12px">
                                <label for="extra_hotel_nights_date">Extra Hotel Nights Date</label><br>
                                <a href="javascript:void(0)" class="be-bnt-action"><img
                                        src="<?php echo VIEW_URL . '/images/tru.png' ?>"
                                        style="padding-right: 10px"></a>
                                <input type="text" value="1" class="be-input-number">
                                <a href="javascript:void(0)" class="be-bnt-action"><img
                                        src="<?php echo VIEW_URL . '/images/cong.png' ?>"
                                        style="padding-left: 10px"></a> <span style="padding-left: 10px">Night(s)</span>

                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group" style="position: relative">
                                        <label for="service_addon_from">From</label>
                                        <input type="text" name="service_addon_from"
                                               value="<?php echo !empty($user_info->service_addon_from) ? date('d M Y',
                                                   strtotime($user_info->service_addon_from)) : '' ?>"
                                               id="service_addon_from" class="form-control datepicker" readonly>
                                        <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group" style="position: relative">
                                        <label for="service_addon_to">To</label>
                                        <input type="text" name="service_addon_to"
                                               value="<?php echo !empty($user_info->service_addon_to) ? date('d M Y',
                                                   strtotime($user_info->service_addon_to)) : '' ?>"
                                               id="service_addon_to" class="form-control datepicker" readonly>
                                        <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="service_addon_1">Service addon 1</label>
                                <select name="service_addon_1" id="service_addon_1" class="form-control">
                                    <option value=""> --- Select ---</option>
                                    <option value="yes"> Yes</option>
                                    <option value="no"> No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="service_addon_2">Service addon 2</label>
                                <select name="service_addon_2" id="service_addon_2" class="form-control">
                                    <option value=""> --- Select ---</option>
                                    <option value="yes"> Yes</option>
                                    <option value="no"> No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="service_addon_3">Service addon 3</label>
                                <select name="service_addon_3" id="service_addon_3" class="form-control">
                                    <option value=""> --- Select ---</option>
                                    <option value="yes"> Yes</option>
                                    <option value="no"> No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="text-center" style="margin: 30px 0 50px">
                    <button type="submit" class="bnt-primary">SAVE</button>
                </div>
            </form>

        </div>
    </div>
</div>


<?php get_footer() ?>
