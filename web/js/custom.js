var isIpad = false;
var ua = navigator.userAgent;
var nav_platform = navigator.platform;
var hgt_navbar = $("#nav").outerHeight();
var device = deviceType();
var isPortrait = matchMedia("(orientation: portrait)").matches;

function deviceType() {
    let iPad = (nav_platform === 'MacIntel' && navigator.maxTouchPoints && navigator.maxTouchPoints > 1 && typeof navigator.standalone !== "undefined");

    if (/(Tablet|iPad|PlayBook|Silk|Kindle)|(Android(?!.*Mobi))/.test(ua) || iPad) {

        // console.log('Device: Tablet (3)');
        if (iPad) {
            isIpad = true;
        }

        return 3;

    } else if (/Mobile|Android|iP(hone|od)|IEMobile|Windows Phone|BlackBerry|BB|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {

        // console.log('Device: Mobile (2)');
        return 2;

    }

    // console.log('Device: Desktop (1)');
    return 1;
}
