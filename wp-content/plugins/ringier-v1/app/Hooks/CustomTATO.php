<?php
namespace RVN\Hooks;

use RVN\Models\Location;
use RVN\Models\TaTo;

/**
 * Created by PhpStorm.
 *
 * Date: 2/9/2015
 * Time: 9:44 AM
 */
class CustomTATO
{
    private static $instance;


    public static function init()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CustomTATO();
        }

        return self::$instance;
    }


    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'addTaTo']);
        add_action('save_post', [$this, 'save']);
    }


    public function addTaTo()
    {
        add_meta_box('tato_info', 'TA/TO Infomation', [$this, 'show'], 'tato', 'normal', 'high');
    }


    public function show()
    {
        global $post;
        $m_tato = TaTo::init();
        $m_location = Location::init();

        $tato = $m_tato->getTaToByID($post->ID);
        $countries = $m_location->getCountryList();
        ?>

        <style>
            .line-bottom {
                border-bottom: 1px solid #dddddd;
                padding-bottom: 10px;
            }

            .form-group {
                display: inline-block;
                max-width: 100%;
                width: 100%;
            }

            .form-group label {
                display: block;
                padding-bottom: 5px;
            }

            .form-group select, .form-group input {
                width: 100%;
            }

            h4 {
                font-size: 1.2em;
            }

            .container-fluid {
                padding: 0;
            }

            .row {
                margin-bottom: 10px;
            }

            .text-center {
                text-align: center;
            }

            .btn-wrapper {

            }
        </style>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h3>TA/TO</h3>
                    <div>Please check duplication carefully before creating a new one</div>
                </div>
            </div>

            <!----- Company Info ----->
            <div class="row">
                <div class="col-md-12">
                    <h4 class="line-bottom">
                        Company Info
                    </h4>
                </div>

                <div class="col-md-6 col-xs-12">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="text" name="company_name" value="<?php echo $tato->company_name; ?>">
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telephone</label>
                                <input type="text" name="telephone" value="<?php echo $tato->telephone; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fax</label>
                                <input type="text" name="fax" value="<?php echo $tato->fax; ?>">
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Deposit Rate (%)</label>
                                <input type="number" name="deposit_rate" value="<?php echo $tato->deposit_rate; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Commission rate (%)</label>
                                <input type="number" name="commission_rate"
                                       value="<?php echo $tato->commission_rate; ?>">
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-6 col-xs-12">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alias</label>
                                <input type="text" name="alias" value="<?php echo $tato->alias; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country</label>
                                <select name="country" class="select2">
                                    <option value="">--- Select country ---</option>
                                    <?php if (!empty($countries)) {
                                        foreach ($countries as $k => $v) {
                                            $selected = ($tato->country == $v->alpha_2) ? 'selected' : '';
                                            echo '<option value="' . $v->alpha_2 . '" ' . $selected . '>' . $v->name . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="company_email" value="<?php echo $tato->company_email; ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Website</label>
                                <input type="text" name="website" value="<?php echo $tato->website; ?>">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Invoice Information</label>
                            <textarea name="invoice_information"
                                      style="width: 100%;"><?php echo $tato->invoice_information; ?></textarea>
                        </div>
                    </div>

                </div>

            </div>


            <!----- Contact Person ----->
            <div class="row">
                <div class="col-md-12">
                    <h4 class="line-bottom">
                        Contact Person
                    </h4>
                </div>


                <!----- First row ----->
                <div class="col-md-12 col-xs-12">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" value="<?php echo $tato->first_name; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" value="<?php echo $tato->last_name; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" value="<?php echo $tato->middle_name; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nick Name</label>
                                <input type="text" name="nick_name" value="<?php echo $tato->nick_name; ?>">
                            </div>
                        </div>
                    </div>

                </div>


                <!----- Second row ----->
                <div class="col-md-12 col-xs-12">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" value="<?php echo $tato->title; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Company telephone</label>
                                <input type="text" name="company_telephone"
                                       value="<?php echo $tato->company_telephone; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mobile Phone</label>
                                <input type="text" name="mobile_phone" value="<?php echo $tato->mobile_phone; ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" value="<?php echo $tato->email; ?>">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="btn-wrapper">
                        <?php

                        if (!empty($_GET['action'])) {
                            if ($_GET['action'] == 'edit') {
                                submit_button('Save TA/TO');
                            }
                            else {
                                submit_button('Create TA/TO');
                            }
                        }
                        else {
                            submit_button('Create TA/TO');
                        }
                        submit_button('Save draft', 'secondary');

                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }


    public function save()
    {
        global $post, $wpdb;
        if (!empty($post) && $post->post_type == 'tato') {
            $data = [
                'object_id'           => $post->ID,
                'company_name'        => $_POST['company_name'],
                'alias'               => $_POST['alias'],
                'country'             => $_POST['country'],
                'telephone'           => $_POST['telephone'],
                'fax'                 => $_POST['fax'],
                'deposit_rate'        => $_POST['deposit_rate'],
                'commission_rate'     => $_POST['commission_rate'],
                'company_email'       => $_POST['company_email'],
                'website'             => $_POST['website'],
                'invoice_information' => $_POST['invoice_information'],
                'first_name'          => $_POST['first_name'],
                'last_name'           => $_POST['last_name'],
                'middle_name'         => $_POST['middle_name'],
                'nick_name'           => $_POST['nick_name'],
                'title'               => $_POST['title'],
                'company_telephone'   => $_POST['company_telephone'],
                'mobile_phone'        => $_POST['mobile_phone'],
                'email'               => $_POST['email']
            ];

            $query = "SELECT * FROM " . TBL_TATO . " WHERE object_id = {$post->ID}";
            $tato = $wpdb->get_row($query);

            if (empty($tato)) {
                $data['created_at'] = current_time('mysql');
                $wpdb->insert(TBL_TATO, $data);
            }
            else {
                $wpdb->update(TBL_TATO, $data, ['object_id' => $post->ID]);
            }

            $submit_type = $_POST['submit'];
            if ($submit_type == 'Save draft') {
                // wp_update_post(['post_status' => 'draft']);
                $wpdb->update($wpdb->posts, ['post_status' => 'draft'], ['ID' => $post->ID]);
            }
            else {
                $wpdb->update($wpdb->posts, ['post_status' => 'publish'], ['ID' => $post->ID]);
                // wp_publish_post($post);
            }
        }
    }

}
