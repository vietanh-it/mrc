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
    
    <div class="container">
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
    
</div>


<?php get_footer() ?>
