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

    $(".list-galary").owlCarousel({
        autoPlay: 5000,
        items : 1,
        itemsDesktop : [1199,1],
        itemsDesktopSmall : [760,1],
        navigation: true,
        pagination: false
    });

    $(".owl-carousel .owl-next").html('<i class="fa fa-angle-right" aria-hidden="true"></i>');
    $(".owl-carousel .owl-prev").html('<i class="fa fa-angle-left" aria-hidden="true"></i>');

    /*$( ".datepicker" ).datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });*/
    $('.month-year-input').attr('readonly',true);
    $('.month-year-input').MonthPicker({ Button: false });


    $("select.select-2").select2({
        formatResult : function( object, container, query, escapeMarkup ){
            var markup=[];
            window.Select2.util.markMatch(object.text, query.term, markup, escapeMarkup);
            if( $(object.element).data('group') == 1 ){
                $(container).css({'font-weight' : 'bold'});
            }else{
                $(container).css({'padding-left' : 20});
            }
            return markup.join("");
        }
    });
    $('select.select-2').on("select2-open", function(e) {
        e.preventDefault();
        $(".select2-results").mCustomScrollbar();
    });



    window.fbAsyncInit = function() {
        FB.init({
            appId      : MyAjax.app_fb_id,
            xfbml      : true,
            version    : 'v2.7'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));


    $('.btn-show-journey').click(function () {
        var  obj = $(this);
        var journey_type_id = obj.attr('data-journey_type');

        $.ajax({
            type: "post",
            url: ajaxurl,
            dataType: 'json',
            data: ({
                action: "ajax_handler_journey",
                method: "GetJourneyByJourneyType",
                journey_type_id: journey_type_id
            }),
            beforeSend: function () {
            },
            success: function (data) {
                if(data.data){
                    var result = data.data;
                    console.log(result);
                }
            }
        })
    });
});

