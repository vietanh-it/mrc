<?php
$list_guest = !empty($list_guest) ? $list_guest : array();
$booking_code = !empty($booking_code) ? $booking_code : '';
$total_guest = !empty($total_guest) ? $total_guest : 1;

get_header();

function htmlDetailBeforeYouGo($cart_id,$k = 0,$guest = ''){
    $Location = \RVN\Models\Location::init();
    $country_list = $Location->getCountryList();
    $objBooking = \RVN\Models\Booking::init();
    $list_service_addon = $objBooking->getServiceAddonByBookingId($cart_id);
    $list_room = $objBooking->getRoomByBookingId($cart_id);
    ?>

    <div class="ctn-traveller panel panel-default">
        <div class="panel-heading" role="tab" id="heading_<?php echo $k + 1 ?>">
            <div class="panel-title be-travel">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $k + 1 ?>" aria-expanded="true" aria-controls="collapseOne">
                    Traveller <?php echo $k + 1 ?>
                </a>
            </div>
        </div>
        <div id="collapse_<?php echo $k + 1 ?>" class="panel-collapse collapse <?php echo ($k==0) ? "in" : "" ?> ctn-show-hide-traveller" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body row">
                <div class="col-xs-12 col-sm-4">
                    <div class="form-account">
                        <p class="title ">Traveller personal information</p>
                        <div class="form-group">
                            <label for="first_name">First name</label>
                            <input type="text" name="first_name[]"
                                   value="<?php echo !empty($guest->first_name) ? $guest->first_name : '' ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last name</label>
                            <input type="text" name="last_name[]"
                                   value="<?php echo !empty($guest->last_name) ? $guest->last_name : '' ?>"
                                   class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="middle_name">Middle name</label>
                                    <input type="text" name="middle_name[]"
                                           value="<?php echo !empty($guest->middle_name) ? $guest->middle_name : '' ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group" style="position: relative">
                                    <label for="nickname">Nickname</label>
                                    <input type="text" name="nickname[]"
                                           value="<?php echo !empty($guest->nickname) ? ($guest->nickname) : '' ?>"
                                           class="form-control " >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select name="gender[]" class="form-control">
                                <option
                                    value="male" <?php echo (!empty($guest->gender) && $guest->gender == 'male') ? 'selected' : '' ?>>
                                    Male
                                </option>
                                <option
                                    value="female" <?php echo (!empty($guest->gender) && $guest->gender == 'female') ? 'selected' : '' ?>>
                                    Female
                                </option>
                            </select>
                        </div>

                        <div class="form-group" style="position: relative">
                            <label for="birthday">Date of birth</label>
                            <input type="text" name="birthday[]"
                                   value="<?php echo (!empty($guest->birthday) && $guest->birthday != '0000-00-00') ? date('d M Y',
                                       strtotime($guest->birthday)) : '' ?>"
                                   class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <select name="country[]"  class="form-control">
                                <option value="">--- Select your country ---</option>
                                <?php if (!empty($country_list)) {
                                    foreach ($country_list as $c) {
                                        ?>
                                        <option
                                            value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($guest->country) && $guest->country == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
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
                            <select name="nationality[]"  class="form-control">
                                <option value="">--- Select your nationality ---</option>
                                <?php if (!empty($country_list)) {
                                    foreach ($country_list as $c) {
                                        ?>
                                        <option
                                            value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($guest->nationality) && $guest->nationality == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="passport_no">Passport No.</label>
                            <input type="text" name="passport_no[]"
                                   value="<?php echo !empty($guest->passport_no) ? $guest->passport_no : '' ?>"
                                   class="form-control">
                        </div>


                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group" style="position: relative">
                                    <label for="passport_issue_date">Issued date</label>
                                    <input type="text" name="passport_issue_date[]"
                                           value="<?php echo !empty($guest->passport_issue_date) && $guest->passport_issue_date != '0000-00-00' ? date('d M Y',
                                               strtotime($guest->passport_issue_date)) : '' ?>"
                                           class="form-control datepicker" readonly>
                                    <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group" style="position: relative">
                                    <label for="passport_expiration_date">Expiration Date</label>
                                    <input type="text" name="passport_expiration_date[]"
                                           value="<?php echo !empty($guest->passport_expiration_date) && $guest->passport_expiration_date != '0000-00-00' ? date('d M Y',
                                               strtotime($guest->passport_expiration_date)) : '' ?>"
                                           class="form-control datepicker" readonly>
                                    <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country_of_birth">Country of birth</label>
                            <select name="country_of_birth[]"  class="form-control">
                                <option value="">--- Select your country of birth ---</option>
                                <?php if (!empty($country_list)) {
                                    foreach ($country_list as $c) {
                                        ?>
                                        <option
                                            value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($guest->country_of_birth) && $guest->country_of_birth == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="issued_in">Issued in</label>
                            <select name="issued_in[]"  class="form-control">
                                <option value="">--- Select your issued in ---</option>
                                <?php if (!empty($country_list)) {
                                    foreach ($country_list as $c) {
                                        ?>
                                        <option
                                            value="<?php echo $c->alpha_2 ?>" <?php echo (!empty($guest->country_of_birth) && $guest->issued_in == $c->alpha_2) ? 'selected' : '' ?>><?php echo $c->name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="is_visa">Visa</label>
                                    <select name="is_visa[]"  class="form-control">
                                        <option
                                            value="no" <?php echo (!empty($guest->is_visa) && $guest->is_visa == 'no') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                        <option
                                            value="yes" <?php echo (!empty($guest->is_visa) && $guest->is_visa == 'yes') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option
                                            value="not_yes" <?php echo (!empty($guest->is_visa) && $guest->is_visa == 'not_yes') ? 'selected' : '' ?>>
                                            Not yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="travel_insurance">Travel insurance</label>
                                    <select name="travel_insurance[]" class="form-control">
                                        <option
                                            value="no" <?php echo (!empty($guest->travel_insurance) && $guest->travel_insurance == 'no') ? 'selected' : '' ?>>
                                            No
                                        </option>
                                        <option
                                            value="yes" <?php echo (!empty($guest->travel_insurance) && $guest->travel_insurance == 'yes') ? 'selected' : '' ?>>
                                            Yes
                                        </option>
                                        <option
                                            value="not_yes" <?php echo (!empty($guest->travel_insurance) && $guest->travel_insurance == 'not_yes') ? 'selected' : '' ?>>
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
                            <input type="text" name="occasion_note[]"  value="<?php echo !empty($guest->occasion_note) ? $guest->occasion_note : '' ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="diet_note">Food/Diet note</label>
                            <input type="text" name="diet_note[]" value="<?php echo !empty($guest->diet_note) ? $guest->diet_note : '' ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="medical_note">Medical note</label>
                            <input type="text" name="medical_note[]"  value="<?php echo !empty($guest->medical_note) ? $guest->medical_note : '' ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="speacial_assistant_note">Special assistance</label>
                            <input type="text" name="speacial_assistant_note[]"
                                   class="form-control" value="<?php echo !empty($guest->speacial_assistant_note) ? $guest->speacial_assistant_note : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="room_no">Room No.</label>
                            <select name="room_no[]" class="form-control room_no_be">
                                <option value=""> --- Select room no ---</option>
                                <?php if(!empty($list_room)){
                                    foreach ($list_room as $v){ ?>
                                        <option value="<?php echo $v->room_id ?>" data-type="<?php echo $v->type ?>" data-id="<?php echo $k+1 ?>" <?php echo ( !empty($guest->room_no) && $guest->room_no == $v->room_id ) ? 'selected' : '' ?>> <?php echo $v->room_name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="bedding_note">Bedding</label>
                            <select name="bedding_note[]" class="form-control bedding_note_be_<?php echo $k+1 ?>">
                                <option value=""> --- Select bedding ---</option>
                                <option value="twins"  <?php echo (!empty($guest->bedding_note) && $guest->bedding_note == 'twins') ? 'selected' : '' ?>> Twins</option>
                                <option value="queen" <?php echo (!empty($guest->bedding_note) && $guest->bedding_note == 'queen') ? 'selected' : '' ?>>Queen</option>
                                <option value="fixed_king" <?php echo (!empty($guest->bedding_note) && $guest->bedding_note == 'fixed_king') ? 'selected' : '' ?>>Fixed King</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 col-sm-4">
                    <div class="form-account">
                        <p class="title ">Arrival information</p>
                        <div class="form-group" style="position: relative">
                            <label for="embarkation_date">Embarkation date</label>
                            <input type="text" name="embarkation_date[]"
                                   value="<?php echo !empty($guest->embarkation_date) && $guest->embarkation_date != '0000-00-00' ? date('d M Y',
                                       strtotime($guest->embarkation_date)) : '' ?>"
                                   class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>
                        <div class="form-group">
                            <label for="embarkation_city">Embarkation city</label>
                            <input type="text" name="embarkation_city[]"  class="form-control" value="<?php echo !empty($guest->embarkation_city) ? $guest->embarkation_city : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_inbound_flight_no">Last inbound flight no #</label>
                            <input type="text" name="last_inbound_flight_no[]"
                                   class="form-control" value="<?php echo !empty($guest->last_inbound_flight_no) ? $guest->last_inbound_flight_no : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_inbound_originating_airport">Last inbound originating airport</label>
                            <input type="text" name="last_inbound_originating_airport[]"
                                   class="form-control" value="<?php echo !empty($guest->last_inbound_originating_airport) ? $guest->last_inbound_originating_airport : '' ?>">
                        </div>
                        <div class="form-group" style="position: relative">
                            <label for="last_inbound_date">Last inbound date</label>
                            <input type="text" name="last_inbound_date[]"
                                   value="<?php echo !empty($guest->last_inbound_date) && $guest->last_inbound_date != '0000-00-00' ? date('d M Y',
                                       strtotime($guest->last_inbound_date)) : '' ?>"
                                   class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>

                        <div class="form-group">
                            <label for="last_inbound_arrival_time">Last inbound arrival time</label>
                            <input type="text"  class="timepicker form-control" value="<?php echo !empty($guest->last_inbound_arrival_time) ? $guest->last_inbound_arrival_time : '' ?>" name="last_inbound_arrival_time[]">
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-4">
                    <div class="form-account">
                        <p class="title ">Departure information</p>
                        <div class="form-group" style="position: relative">
                            <label for="debarkation_date">Debarkation date</label>
                            <input type="text" name="debarkation_date[]"
                                   value="<?php echo !empty($guest->debarkation_date) && $guest->debarkation_date != '0000-00-00' ? date('d M Y',
                                       strtotime($guest->debarkation_date)) : '' ?>"
                                   class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>
                        <div class="form-group">
                            <label for="debarkation_city">Debarkation city</label>
                            <input type="text" name="debarkation_city[]"  class="form-control" value="<?php echo !empty($guest->debarkation_city) ? $guest->debarkation_city : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="first_outbound_flight_no">First outbound flight no #</label>
                            <input type="text" name="first_outbound_flight_no[]"
                                   class="form-control" value="<?php echo !empty($guest->first_outbound_flight_no) ? $guest->first_outbound_flight_no : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="first_outbound_destination_airport">First outbound destination
                                airport</label>
                            <input type="text" name="first_outbound_destination_airport[]"
                                   class="form-control" value="<?php echo !empty($guest->first_outbound_destination_airport) ? $guest->first_outbound_destination_airport : '' ?>">
                        </div>
                        <div class="form-group" style="position: relative">
                            <label for="first_outbound_date">First outbound date</label>
                            <input type="text" name="first_outbound_date[]"
                                   value="<?php echo !empty($guest->first_outbound_date) && $guest->first_outbound_date != '0000-00-00' ? date('d M Y',
                                       strtotime($guest->first_outbound_date)) : '' ?>"
                                   class="form-control datepicker" readonly>
                            <img src="<?php echo VIEW_URL ?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                        </div>

                        <div class="form-group">
                            <label for="first_outbound_departure_time[]">First outbound departure time</label>
                            <input type="text"  class="timepicker form-control" value="<?php echo !empty($guest->first_outbound_departure_time) ? $guest->first_outbound_departure_time : '' ?>" name="first_outbound_departure_time">
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-4">
                    <div class="form-account">
                        <p class="title ">Service addons</p>
                        <div class="form-group">
                            <label for="service_addon">Transfers only</label>
                            <select name="service_addon[]"  class="form-control">
                                <option value=""> --- Select service addons ---</option>
                                <?php if(!empty($list_service_addon)){
                                    foreach ($list_service_addon as $v){
                                        ?>
                                        <option value="<?php echo $v->object_id ?>" <?php echo ( !empty($guest->addon_id) &&  $guest->addon_id == $v->object_id) ? 'selected' : '' ?>><?php echo $v->addon_name ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        <a href="<?php echo WP_SITEURL.'/buy-more-service/?id='.$_GET['id'] ?>">Buy more service</a>
                        <!--
                    <div class="form-group">
                        <label for="transfers_only">Transfers only</label>
                        <select name="transfers_only"  class="form-control">
                            <option value=""> --- Select ---</option>
                            <option value="yes" <?php /*echo (!empty($guest->transfers_only) && $guest->transfers_only == 'yes') ? 'selected' : '' */?>> Yes</option>
                            <option value="no" <?php /*echo (!empty($guest->transfers_only) && $guest->transfers_only == 'no') ? 'selected' : '' */?>> No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pretour_extension">Pre-tour extension</label>
                        <select name="pretour_extension"  class="form-control">
                            <option value=""> --- Select ---</option>
                            <option value="yes" <?php /*echo (!empty($guest->pretour_extension) && $guest->pretour_extension == 'yes') ? 'selected' : '' */?>> Yes</option>
                            <option value="no" <?php /*echo (!empty($guest->pretour_extension) && $guest->pretour_extension == 'no') ? 'selected' : '' */?>> No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="posttour_extension">Post-tour extension</label>
                        <select name="posttour_extension"  class="form-control">
                            <option value=""> --- Select ---</option>
                            <option value="yes" <?php /*echo (!empty($guest->posttour_extension) && $guest->posttour_extension == 'yes') ? 'selected' : '' */?>> Yes</option>
                            <option value="no" <?php /*echo (!empty($guest->posttour_extension) && $guest->posttour_extension == 'no') ? 'selected' : '' */?>> No</option>
                        </select>
                    </div>

                    <div class="form-group" style="padding-bottom: 12px">
                        <label for="extra_hotel_nights_date">Extra Hotel Nights Date</label><br>
                        <a href="javascript:void(0)" class="be-bnt-action"><img
                                src="<?php /*echo VIEW_URL . '/images/tru.png' */?>"
                                style="padding-right: 10px"></a>
                        <input type="text" readonly value="<?php /*echo !empty($guest->extra_hotel_nights_date) ? $guest->extra_hotel_nights_date : '1' */?>" class="be-input-number" style="background: none">
                        <a href="javascript:void(0)" class="be-bnt-action"><img
                                src="<?php /*echo VIEW_URL . '/images/cong.png' */?>"
                                style="padding-left: 10px"></a> <span style="padding-left: 10px">Night(s)</span>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group" style="position: relative">
                                <label for="service_addon_from">From</label>
                                <input type="text" name="service_addon_from"
                                       value="<?php /*echo !empty($guest->service_addon_from) ? date('d M Y',
                                           strtotime($guest->service_addon_from)) : '' */?>"
                                        class="form-control datepicker" readonly>
                                <img src="<?php /*echo VIEW_URL */?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group" style="position: relative">
                                <label for="service_addon_to">To</label>
                                <input type="text" name="service_addon_to"
                                       value="<?php /*echo !empty($guest->service_addon_to) ? date('d M Y',
                                           strtotime($guest->service_addon_to)) : '' */?>"
                                        class="form-control datepicker" readonly>
                                <img src="<?php /*echo VIEW_URL */?>/images/icon-date-2.png" style="position: absolute;
    bottom: 10px;
    right: 10px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="service_addon_1">Service addon 1</label>
                        <select name="service_addon_1"  class="form-control">
                            <option value=""> --- Select ---</option>
                            <option value="yes"> Yes</option>
                            <option value="no"> No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service_addon_2">Service addon 2</label>
                        <select name="service_addon_2"  class="form-control">
                            <option value=""> --- Select ---</option>
                            <option value="yes"> Yes</option>
                            <option value="no"> No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service_addon_3">Service addon 3</label>
                        <select name="service_addon_3"  class="form-control">
                            <option value=""> --- Select ---</option>
                            <option value="yes"> Yes</option>
                            <option value="no"> No</option>
                        </select>
                    </div>-->
                    </div>
                </div>
            </div>
            <input type="hidden" value="<?php echo !empty($guest->id) ? $guest->id : 0 ?>" name="guest_id[]">
        </div>
    </div>
    <?php
}
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
            <?php if(!empty($_GET['id'])){ ?>
                <div class="book-no">
                    Booking No #<?php echo $booking_code ?>
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
                <form method="post">
                    <div class="ctn-detail-byg panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <?php
                        // $ctrBooking = \RVN\Controllers\BookingController::init();
                        if(!empty($list_guest)){
                            foreach ($list_guest as $k => $guest) {
                                htmlDetailBeforeYouGo($_GET['id'],$k, $guest);
                            }
                        }else{
                            for ($n = 0;$n < $total_guest; $n++){
                                htmlDetailBeforeYouGo($_GET['id'],$n);
                            }
                        }
                        ?>
                    </div>

                    <!--<div>
                        <a href="javascript:void(0)" class="add_new_traveller" data-number="1" data-cart_id ="<?php /*echo $_GET['id'] */?>">Add new traveller</a>
                    </div>-->
                    <div class="text-center" style="margin: 30px 0 50px;float: left;width:100%;">

                        <button type="submit" class="bnt-primary">SAVE</button>
                    </div>
                </form>
            <?php } ?>

        </div>
    </div>
</div>


<?php get_footer() ?>
