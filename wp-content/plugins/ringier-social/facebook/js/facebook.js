/**
 * Created with JetBrains PhpStorm.
 * User: Quoc Vu
 * Date: 9/17/13
 * Time: 1:59 PM
 * To change this template use File | Settings | File Templates.
 */
var $ = jQuery.noConflict();

$(document).ready(function() {
    detectBlockFB("https://www.facebook.com",function(found){
        if(found) {
            // v2
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : ringier_social.app_fb_id,
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

        }
        else {
            console.log("FB blocked");
        }
    })
});


function detectBlockFB(url,callback) {
    // try to load favicon
    var timer = setTimeout(function(){
        // timeout after 5 seconds
        callback(false);
    },3000)

    var img = document.createElement("img");
    img.onload = function() {
        clearTimeout(timer);
        callback(true);
    }

    img.onerror = function() {
        clearTimeout(timer);
        callback(false);
    }

    img.src = url+"/favicon.ico";

}

var obj_overlay;
var obj_loading;

function login_fb(){
    //obj_overlay.unbind("click");
    obj_overlay = $("<div></div>");
    var height = $('body').height();
    obj_overlay.css({
        "position" : "fixed",
        "top" : 0,
        "left": 0,
        "z-index" : 1100,
        "background-color" : "rgb(119, 119, 119)",
        "opacity" : "0.7",
        "cursor" : "pointer",
        "width" : "100%",
        "height" : "100%"
    });
    $('body').append(obj_overlay);

    obj_loading = $("<div><img src='/wp-content/plugins/ringier-social/facebook/images/fancybox_loading.gif' width='22px' height='22px'/></div>");
    obj_loading.css({
        "position" : "fixed",
        "top" : "50%",
        "left" : "50%",
        "margin-top" : "-22px",
        "margin-left" : "-22px",
        "cursor" : "pointer",
        "overflow" : "hidden",
        "z-index" : 1104,
        "background-image" : "url('/wp-content/plugins/ringier-social/facebook/images/fancybox_sprite.png')",
        "background-position" : "0 -108px",
        "padding" : "10px 11px"
    });
    $('body').append(obj_loading);

    $('html').css({'cursor':'wait'});

    FB.login(function(response) {
        var access_token;
        if (response.authResponse) {
            var url = '/me?fields=name,email,link,first_name,gender,last_name,locale,timezone,updated_time,verified,birthday';
            FB.api(url, function(response) {
                access_token =   FB.getAuthResponse()['accessToken'];
                response.access_token = access_token;
            //    console.log(response);
                insertUserFB(response);
            });
        }else{
            obj_overlay.remove();
            obj_loading.remove();
            $('html').css({'cursor':'default'});
        }
    }, {scope: 'email'});
//    obj_overlay.remove();
//    obj_loading.remove();
}

function insertUserFB(data){
    data.action = "ringier_facebook_login";
    $.ajax({
        url : ringier_social.admin_ajax_url,
        data : data,
        type : "post",
        dataType : 'json',
        success : function(response){
            console.log(response);
            if( response.error === 0 ){
                if( response.link == undefined ){
                    location.reload();
                }else{
                    window.location = response.link;
                }

            }else{
                obj_overlay.remove();
                obj_loading.remove();
                alert("Login failed");
            }
        }
    });
}