<?php
/**
 * Created by PhpStorm.
 * User: Vo sy dao
 * Date: 7/18/2016
 * Time: 2:49 PM
 */
get_header();
?>

<div class="quick-search-journey">
    <form>
        <div class="container container-big">
            <div class="row">
                <h3 class="col-xs-12 col-sm-12">Find your journey</h3>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select name="destination" class="form-control">
                                    <option value="">Choose your destination</option>
                                </select>
                                <span class="icon-n icon-location"></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <input type="text" name="month" class="form-control" placeholder="Choose sail month">
                                <span class="icon-n icon-date"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select name="port" class="form-control">
                                    <option value="">Choose port of departure</option>
                                </select>
                                <span class="icon-n icon-port"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3">
                            <div class="form-group">
                                <select name="ship" class="form-control">
                                    <option value="">Choose your ship</option>
                                </select>
                                <span class="icon-n icon-ship"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-2">
                    <div class="text-center">
                        <button type="submit"> <i class="fa fa-search" aria-hidden="true"></i> Find now</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="container">
    <div class="row">
        <h1 class="col-xs-12 col-sm-12 tile-main">All Journeys
            <br> <img src="<?php echo THEME_URL.'/images/line.png' ?>">
        </h1>


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

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="#" title="">
                        <img src="<?php echo THEME_URL.'/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="#" class="title" title="">Vietnam</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                    <a href="#" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="#" title="">
                        <img src="<?php echo THEME_URL.'/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="#" class="title" title="">Cambodia</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                    <a href="#" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

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

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="#" title="">
                        <img src="<?php echo THEME_URL.'/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="#" class="title" title="">Vietnam</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                    <a href="#" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4">
            <div class="box-journey">
                <div class="image">
                    <a href="#" title="">
                        <img src="<?php echo THEME_URL.'/images/laos.png' ?>" alt="" class="lazy">
                    </a>
                </div>
                <div class="desc">
                    <a href="#" class="title" title="">Cambodia</a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dapibus scelerisque ipsum dapibus...</p>
                    <a href="#" class="explore" title="">Explore</a>
                </div>
            </div>
        </div>

    </div>
</div>


<?php get_footer() ?>
