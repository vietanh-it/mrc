// Declare for Google Maps
var geocoder
var marker;
var map;
function r_map_initialize() {
    if(jQuery(".store-lat").val() == "" || jQuery(".store-lat").val() == ""){
        var lat = 10.7623478;
        var lng = 106.70824170000003;
    }else{
        var lat = parseFloat(jQuery(".store-lat").val());
        var lng = parseFloat(jQuery(".store-lng").val());
    }
    var myLatlng = new google.maps.LatLng(lat, lng);

    geocoder = new google.maps.Geocoder();

    var mapOptions = {
        zoom: 14,
        center: myLatlng
    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable: true
    });

    google.maps.event.addListener(map, 'click', function (e) {
        r_map_setLocation(map, marker, e.latLng, false);
    });

    google.maps.event.addListener(marker, 'dragend', function (e) {
        r_map_setLocation(map, marker, e.latLng, false);
    });

}

google.maps.event.addDomListener(window, 'load', r_map_initialize);

function r_map_setLocation(map, marker, latLng, map_center) {
    if (map_center==true) map.setCenter(latLng);
    marker.setPosition(latLng);
    jQuery(".store-lat").val(latLng.lat());
    jQuery(".store-lng").val(latLng.lng());
};

function r_map_goToAddress() {
    var str_address = "";
    var str_city = "";
    var str_district = "";
    var str_ward = "";

    str_address = jQuery('.location_address').val();
    if(jQuery('.location_city').val() != 0){
        str_city = ', ' + jQuery('.location_city option:selected').text();
    }
    if(jQuery('.location_district').val() != 0){
        str_district = ', ' + jQuery('.location_district option:selected').text();
    }
    if(jQuery('.location_ward').val() != 0){
        str_ward = ', ' + jQuery('.location_ward option:selected').text();
    }

    str_exc = str_address + str_ward + str_district + str_city;

    geocoder.geocode( { 'address': str_exc}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            r_map_setLocation(map, marker, results[0].geometry.location, true);
        } else {
            alert("Hệ thống không nhận dạng được địa chỉ này! \nVui lòng nhập lại địa chỉ theo định dạng [số, tên đường, phường, quận/huyện, thành phố]");
        }
    });
};

jQuery(document).ready(function ($) {
    //r_map_initialize();
    $(".get_latlng").click(function () {
        r_map_goToAddress();
    });

    $("#acf-field-location_province").change(function() {
        // Check input( $( this ).val() ) for validity here
        var province_id = ($( this ).val());

        $.ajax({
            type : "post",
            url : ajaxurl,
            dataType : 'json',
            data : {
                action : 'get_district_by_province_id',
                province_id : province_id
            },
            success: function (response, statusText, xhr) {
                var a = response.data;
                $('#acf-field-location_district').html('<option value="">--- chọn ---</option>');
                $('#acf-field-location_ward').html('<option value="">--- chọn ---</option>');
                if (response.status) {
                    $.each(a, function(key, value) {
                        $('#acf-field-location_district')
                            .append($("<option></option>")
                                .attr("value",value.key)
                                .text(value.value));
                    });
                }
            }
        });
    });

    $("#acf-field-location_district").change(function() {
        // Check input( $( this ).val() ) for validity here
        var district_id = ($( this ).val());

        $.ajax({
            type : "post",
            url : ajaxurl,
            dataType : 'json',
            data : {
                action : 'get_ward_by_district_id',
                district_id : district_id
            },
            success: function (response, statusText, xhr) {
                var a = response.data;
                $('#acf-field-location_ward').html('<option value="">--- chọn ---</option>');
                if (response.status) {
                    $.each(a, function(key, value) {
                        $('#acf-field-location_ward')
                            .append($("<option></option>")
                                .attr("value",value.key)
                                .text(value.value));
                    });
                }
            }
        });
    });
});
