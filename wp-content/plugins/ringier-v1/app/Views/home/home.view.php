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
        <div><img src="<?php echo THEME_URL.'/images/bn1.jpg' ?>" alt=""></div>
        <div><img src="<?php echo THEME_URL.'/images/bn2.jpg' ?>" alt=""></div>
        <div><img src="<?php echo THEME_URL.'/images/bn3.jpg' ?>" alt=""></div>
        <div><img src="<?php echo THEME_URL.'/images/bn4.jpg' ?>" alt=""></div>
    </div>

    <div class="form-find-journey">
        <form>
            <h3>Find your journey</h3>
            <div class="form-group">
                <select name="destination" class="form-control">
                    <option value="">Choose your destination</option>
                </select>
                <span class="icon-n icon-location"></span>
            </div>
            <div class="form-group">
                <input type="text" name="month" class="form-control" placeholder="Choose sail month">
                <span class="icon-n icon-date"></span>
            </div>
            <div class="form-group">
                <select name="port" class="form-control">
                    <option value="">Choose port of departure</option>
                </select>
                <span class="icon-n icon-port"></span>
            </div>

            <div class="form-group">
                <select name="ship" class="form-control">
                    <option value="">Choose your ship</option>
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
                <br> <img src="<?php echo THEME_URL.'/images/line.png' ?>">
            </h2>

            <div class="col-xs-12 col-sm-4">
                <div class="box-journey">
                    <div class="image">
                        <a href="#" title="">
                            <img src="<?php echo THEME_URL.'/images/laos.png' ?>" alt="" class="lazy">
                        </a>
                    </div>
                    <div class="desc">
                        <a href="#" class="title" title="">Laos</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                        <a href="#" class="explore" title="">Explore</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="offer-home">
    <div class="container ">
        <div class="row">
            <h2 class="col-xs-12 col-sm-12 tile-main">Latest offer
                <br> <img src="<?php echo THEME_URL.'/images/line.png' ?>">
            </h2>

            <div class="col-xs-12 col-sm-4">
                <div class="box-journey box-white">
                    <div class="image">
                        <a href="#" title="">
                            <img src="<?php echo THEME_URL.'/images/laos.png' ?>" alt="" class="lazy">
                        </a>
                    </div>
                    <div class="desc">
                        <a href="#" class="title" title="">Laos</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                        <a href="#" class="explore" title="">Explore</a>
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
                <br> <img src="<?php echo THEME_URL.'/images/line-white.png' ?>">
            </h2>
            <div class="col-xs-12 col-sm-4">
                <div class="box-why">
                    <img src="<?php echo THEME_URL .'/images/why-1.png'?>" alt="">
                    <div class="desc">
                        <p class="title">The differences</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="box-why">
                    <img src="<?php echo THEME_URL .'/images/why-2.png'?>" alt="">
                    <div class="desc">
                        <p class="title">Our care</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum eget mollis. Duis pulvinar nibh ornare.</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4">
                <div class="box-why">
                    <img src="<?php echo THEME_URL .'/images/why-3.png'?>" alt="">
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
                <br> <img src="<?php echo THEME_URL.'/images/line.png' ?>">
            </h2>

            <div class="col-xs-12 col-sm-6">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo THEME_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo THEME_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo THEME_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo THEME_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="box-room">
                    <a href="#" title=""><img src="<?php echo THEME_URL.'/images/room.jpg' ?>" alt="" class="lazy"></a>
                    <a href="#" title="" class="title">Spa Room</a>
                </div>
            </div>
        </div>
    </div>
</div>



<?php get_footer() ?>
