<?php
/**
 * Created by PhpStorm.
 * User: vietanh
 * Date: 14-Aug-16
 * Time: 10:47 AM
 * Block: Item Offer
 */

if (!empty($item)) { ?>

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
                                <?php if ($item->type == 'addon') {
                                    $addon_model = \RVN\Models\Addon::init();
                                    $addon_options = $addon_model->getAddonOptions($item->ID); ?>

                                    <!--Addon-->
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

                                        <?php if (!empty($addon_options)) {
                                            foreach ($addon_options as $k => $v) { ?>
                                                <tr>
                                                    <td><?php echo $v->option_name; ?></td>
                                                    <td>US$<?php echo number_format($v->price); ?></td>
                                                    <td>
                                                        <a class="action-quantity" href="javascript:void(0)">-</a>
                                                        <span style="padding: 0 10px; float: left;"
                                                              class="option-person">0</span>
                                                        <a href="javascript:void(0)" class="action-quantity">+</a>
                                                    </td>
                                                    <td>US$0</td>
                                                </tr>
                                            <?php }
                                        } ?>

                                        <tr>
                                            <td colspan="3" style="text-align: right;padding-right: 10%">
                                                <b>Total</b>
                                            </td>
                                            <td><b>US$0.0</b></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                <?php } else { ?>

                                    <!--Tour-->
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
                                            <td>US$<?php echo number_format($item->twin_share_price); ?></td>
                                            <td>
                                                <a class="action-quantity" href="javascript:void(0)">-</a>
                                                <span style="float: left; padding: 0 10px;">0</span>
                                                <a href="javascript:void(0)" class="action-quantity">+</a>
                                            </td>
                                            <td>US$0</td>
                                        </tr>
                                        <tr>
                                            <td>One Person</td>
                                            <td>US$<?php echo number_format($item->single_price); ?></td>
                                            <td>
                                                <a class="action-quantity" href="javascript:void(0)">-</a>
                                                <span style="float: left; padding: 0 10px;">0</span>
                                                <a href="javascript:void(0)" class="action-quantity">+</a>
                                            </td>
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
                                <a class="add-addon" data-addon-id="<?php echo $item->ID; ?>" href="javascript:void(0)">
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