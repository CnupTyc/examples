let isTouch = () => {
    let isIpad    = (navigator.platform.indexOf("iPad")    !== -1) || (navigator.userAgent.match(/iPad/i)     !== null);
    let isIphone  = (navigator.platform.indexOf("iPhone")  !== -1) || (navigator.userAgent.indexOf("iPhone")  !== -1);
    let isIpod    = (navigator.platform.indexOf("iPod")    !== -1) || (navigator.userAgent.indexOf("iPod")    !== -1);
    let isAndroid = (navigator.platform.indexOf("Android") !== -1) || (navigator.userAgent.indexOf("Android") !== -1);
    return isIpad || isIphone || isIpod || isAndroid;
};

export {isTouch};