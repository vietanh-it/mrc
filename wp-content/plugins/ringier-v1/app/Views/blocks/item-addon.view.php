<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 10:47 AM
 * Block: Item Offer
 */ ?>


<?php if (!empty($item)) {
    var_dump($item); ?>
    <!--Single Item-->
    <div class="col-xs-12 col-sm-12">
        <div class="booking-addon">

            <div class="title">
                <?php
                switch (strtolower($item->type)) {
                    case 'pre-tour':
                        echo 'PRE-CRUISE EXTENTIONS';
                        break;
                    case 'post-tour':
                        echo 'POST-CRUISE EXTENTIONS';
                        break;
                    case 'addon':
                        echo 'CRUISE ADDON';
                        break;
                    default:
                        echo 'CRUISE ADDON';
                        break;
                } ?>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-3">
                    <div class="images">
                        <img src="<?php echo $item->images->small; ?>" alt="<?php echo $item->post_title; ?>">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-9">
                    <div class="desc">
                        <p><b><?php echo $item->post_title; ?></b></p>
                        <div class="row">
                            <div class="col-xs-12 col-sm-9">
                                <?php if ($item->type == 'addon') { ?>

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Option</th>
                                            <th>Price per person</th>
                                            <th>Person</th>
                                            <th>Sub Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Sharing</td>
                                            <td>US$325.00</td>
                                            <td><a class="action-quantity" href="javascript:void(0)">-</a> <span
                                                    style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span>
                                                <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                            <td>US$650.00</td>
                                        </tr>
                                        <tr>
                                            <td>One Person</td>
                                            <td>US$640.00</td>
                                            <td><a class="action-quantity" href="javascript:void(0)">-</a> <span
                                                    style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span>
                                                <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                            <td>US$0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right;padding-right: 10%">
                                                <b>Total</b>
                                            </td>
                                            <td><b>US$0.0</b></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                <?php } else { ?>

                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Option</th>
                                            <th>Price per person</th>
                                            <th>Person</th>
                                            <th>Sub Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Sharing</td>
                                            <td>US$325.00</td>
                                            <td><a class="action-quantity" href="javascript:void(0)">-</a> <span
                                                    style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span>
                                                <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                            <td>US$650.00</td>
                                        </tr>
                                        <tr>
                                            <td>One Person</td>
                                            <td>US$640.00</td>
                                            <td><a class="action-quantity" href="javascript:void(0)">-</a> <span
                                                    style="float: left">&nbsp;&nbsp;&nbsp; 2 &nbsp;&nbsp;&nbsp;</span>
                                                <a href="javascript:void(0)" class="action-quantity">+</a></td>
                                            <td>US$0</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: right;padding-right: 10%">
                                                <b>Total</b>
                                            </td>
                                            <td><b>US$0.0</b></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                <?php } ?>

                            </div>
                            <div class="col-xs-12 col-sm-3">
                                Add this service?
                                <a class="add-addon" href="javascript:void(0)">
                                    Yes, please
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- end item -->
<?php } ?>