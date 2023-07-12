var popupTimeout;
var time = metavalue[70]['time'];
// console.log(time);
var isopen = false;
jQuery(document).ready(function () {
    setTimeout(() => {
        jQuery(document).mousemove(function (e) {
            var distanceFromTop = e.clientY;
            if (distanceFromTop <= 100 && !isopen) {
                jQuery(".mfp-bg").show();
            }
        });
    }, time);

    jQuery(".close-btn").click(function () {
        jQuery(".mfp-bg").hide();
        isopen = true;
    });
});
jQuery(document).ready(function ($) {
    jQuery("#pages").change(function () {
        var selectedValue = document.getElementById("pages").value;
        if (selectedValue === "specific" || selectedValue === "exclude") {
            jQuery("#specific-page-dropdown").show();
        } else {
            jQuery("#specific-page-dropdown").hide();
        }
    });
});





