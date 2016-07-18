/**
 * Created by vo sy dao on 10/03/2015.
 */
var $ = jQuery.noConflict();
jQuery(document).ready(function ($) {

    $(".owl-carousel").owlCarousel({
        autoPlay: 5000,
        items : 1,
        itemsDesktop : [1199,1],
        itemsDesktopSmall : [760,1],
        navigation: true,
        pagination: true
    });

    $(".owl-carousel-2").owlCarousel({
        autoPlay: 5000,
        items : 3,
        itemsDesktop : [1199,1],
        itemsDesktopSmall : [760,1],
        navigation: true,
        pagination: false
    });

    $(".owl-carousel .owl-next").html('<i class="fa fa-angle-right" aria-hidden="true"></i>');
    $(".owl-carousel .owl-prev").html('<i class="fa fa-angle-left" aria-hidden="true"></i>');


});
