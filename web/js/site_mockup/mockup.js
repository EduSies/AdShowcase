window.addEventListener('load', function() {

    window.scrollBannerWhenLoaderHidden = function() {
        var checkLoaderInterval = setInterval(function() {
            var loader = window.parent.document.getElementById('iframe-loader');
            var isHidden = !loader || (loader.style && loader.style.display === 'none') || (loader.classList && loader.classList.contains('d-none'));

            if (isHidden) {
                clearInterval(checkLoaderInterval);

                var targetAdUnitId = '.adshowcase-player-preview';
                var banner = document.querySelector(targetAdUnitId);

                if (banner) {
                    var bannerRect = banner.getBoundingClientRect();
                    var currentScroll = window.scrollY || window.pageYOffset;
                    var bannerTopAbsolute = bannerRect.top + currentScroll;
                    var offsetTop = 100;

                    window.scrollTo({
                        top: bannerTopAbsolute - offsetTop,
                        behavior: 'smooth'
                    });
                }
            }
        }, 100);
    };

    // Ejecutamos la función
    window.scrollBannerWhenLoaderHidden();
});