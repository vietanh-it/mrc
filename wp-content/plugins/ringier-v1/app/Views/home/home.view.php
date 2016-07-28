<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 7/14/2016
 * Time: 7:54 PM
 */
get_header();

?>
<div class="home-slider" id="home-slider">
    <div  class="owl-carousel">
        <div><img src="<?php echo VIEW_URL.'/images/bn1.jpg' ?>" alt=""></div>
        <div><img src="<?php echo VIEW_URL.'/images/bn2.jpg' ?>" alt=""></div>
        <div><img src="<?php echo VIEW_URL.'/images/bn3.jpg' ?>" alt=""></div>
        <div><img src="<?php echo VIEW_URL.'/images/bn4.jpg' ?>" alt=""></div>
    </div>

    <div class="form-find-journey">
        <form method="get" action="<?php echo WP_SITEURL.'/journeys' ?>">
            <h3>Find your journey</h3>
            <div class="form-group">
                <select name="_destination" class="form-control select-2">
                    <option value="">Choose your destination</option>
                    <?php if($list_destination['data']){
                        foreach ($list_destination['data'] as $v){ ?>
                            <option value="<?php echo $v->post_name ?>" > <?php echo $v->post_title ?></option>
                        <?php }
                    } ?>
                </select>
                <span class="icon-n icon-location"></span>
            </div>
            <div class="form-group">
                <input type="text" name="_month" class="form-control month-year-input" placeholder="Choose sail month">
                <span class="icon-n icon-date"></span>
            </div>
            <div class="form-group">
                <select name="_port" class="form-control select-2">
                    <option value="">Choose port of departure</option>
                    <?php if($list_port['data']){
                        foreach ($list_port['data'] as $v){ ?>
                            <option value="<?php echo $v->post_name ?>" > <?php echo $v->post_title ?></option>
                        <?php }
                    } ?>
                </select>
                <span class="icon-n icon-port"></span>
            </div>

            <div class="form-group">
                <select name="_ship" class="form-control select-2">
                    <option value="">Choose your ship</option>
                    <?php if($list_ship['data']){
                        foreach ($list_ship['data'] as $v){ ?>
                            <option value="<?php echo $v->post_name ?>" > <?php echo $v->post_title ?></option>
                        <?php }
                    } ?>
                </select>
                <span class="icon-n icon-ship"></span>
            </div>

            <div class="text-center">
                <button type="submit"> <i class="fa fa-search" aria-hidden="true"></i> Find now</button>
            </div>
        </form>
    </div>
</div>

<div class="journey-home">
    <div class="container ">
        <div class="row">
            <h2 class="col-xs-12 col-sm-12 tile-main">Journey
                <br> <img src="<?php echo VIEW_URL.'/images/line.png' ?>">
            </h2>
            <?php if($list_journey_type['data']){
                foreach ($list_journey_type['data'] as $v){
                    ?>
                    <div class="col-xs-12 col-sm-4">
                        <div class="box-journey">
                            <div class="image">
                                <a href="<?php echo $v->permalink ?>" title="<?php echo $v->post_title ?>">
                                    <img src="<?php echo $v->images->featured ?>" alt="<?php echo $v->post_title ?>" class="lazy">
                                </a>
                            </div>
                            <div class="desc">
                                <a href="<?php echo $v->permalink ?>" class="title" title="<?php echo $v->post_title ?>"><?php echo $v->post_title ?></a>
                                <p><?php echo cut_string_by_char(strip_tags($v->post_content),150) ?></p>
                                <a href="<?php echo $v->permalink ?>" class="explore" title="">Explore</a>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>

        </div>
    </div>
    <img src="<?php echo VIEW_URL.'/images/icon-trong-dong.png' ?>" alt="" class="bg-a">
</div>


<div class="offer-home">
    <div class="container ">
        <div class="row">
            <h2 class="col-xs-12 col-sm-12 tile-main">Latest offer
                <br> <img src="<?php echo VIEW_URL.'/images/line.png' ?>">
            </h2>
            <div  class="owl-carousel-2">
                <div class="col-xs-12 col-sm-12">
                    <div class="box-journey box-white">
                        <div class="image">
                            <a href="#" title="">
                                <img src="<?php echo VIEW_URL.'/images/laos.png' ?>" alt="" class="lazy">
                            </a>
                        </div>
                        <div class="desc">
                            <a href="#" class="title" title="">Laos</a>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                            <p><b>Start Date:</b> Aug 10th, 2016 <a href="#" class="read-more" title="read more"><i class="fa fa-angle-right" aria-hidden="true"></i></a> </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12">
                    <div class="box-journey box-white">
                        <div class="image">
                            <a href="#" title="">
                                <img src="<?php echo VIEW_URL.'/images/laos.png' ?>" alt="" class="lazy">
                            </a>
                        </div>
                        <div class="desc">
                            <a href="#" class="title" title="">Laos</a>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                            <p><b>Start Date:</b> Aug 10th, 2016 <a href="#" class="read-more" title="read more"><i class="fa fa-angle-right" aria-hidden="true"></i></a> </p>
                        </div>
                        <div class="price">$299</div>
                        <div class="star"><i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i> <i class="fa fa-star" aria-hidden="true"></i></div>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12">
                    <div class="box-journey box-white">
                        <div class="image">
                            <a href="#" title="">
                                <img src="<?php echo VIEW_URL.'/images/laos.png' ?>" alt="" class="lazy">
                            </a>
                        </div>
                        <div class="desc">
                            <a href="#" class="title" title="">Laos</a>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                            <p><b>Start Date:</b> Aug 10th, 2016 <a href="#" class="read-more" title="read more"><i class="fa fa-angle-right" aria-hidden="true"></i></a> </p>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12">
                    <div class="box-journey box-white">
                        <div class="image">
                            <a href="#" title="">
                                <img src="<?php echo VIEW_URL.'/images/laos.png' ?>" alt="" class="lazy">
                            </a>
                        </div>
                        <div class="desc">
                            <a href="#" class="title" title="">Laos</a>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                            <p><b>Start Date:</b> Aug 10th, 2016 <a href="#" class="read-more" title="read more"><i class="fa fa-angle-right" aria-hidden="true"></i></a> </p>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 col-sm-12">
                    <div class="box-journey box-white">
                        <div class="image">
                            <a href="#" title="">
                                <img src="<?php echo VIEW_URL.'/images/laos.png' ?>" alt="" class="lazy">
                            </a>
                        </div>
                        <div class="desc">
                            <a href="#" class="title" title="">Laos</a>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                            <p><b>Start Date:</b> Aug 10th, 2016 <a href="#" class="read-more" title="read more"><i class="fa fa-angle-right" aria-hidden="true"></i></a> </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="why-us">
    <div class="container ">
        <div class="row">
            <h2 class="col-xs-12 col-sm-12 tile-main white">Why us
                <br> <img src="<?php echo VIEW_URL.'/images/line-white.png' ?>">
            </h2>
            <div class="col-xs-12 col-sm-4">
                <div class="box-why">
                    <img src="<?php echo VIEW_URL .'/images/why-1.png'?>" alt="">
                    <div class="desc">
                        <p class="title">The differences</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="box-why">
                    <img src="<?php echo VIEW_URL .'/images/why-2.png'?>" alt="">
                    <div class="desc">
                        <p class="title">Our care</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="box-why">
                    <img src="<?php echo VIEW_URL .'/images/why-3.png'?>" alt="">
                    <div class="desc">
                        <p class="title">Ship owner and partner</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="room-home">
    <div class="container ">
        <div class="row">
            <h2 class="col-xs-12 col-sm-12 tile-main">Featured Room Item
                <br> <img src="<?php echo VIEW_URL.'/images/line.png' ?>">
            </h2>

            <div class="col-xs-12 col-sm-6">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo VIEW_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo VIEW_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo VIEW_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo VIEW_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo VIEW_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
        </div>
    </div>
</div>



<?php get_footer() ?>
