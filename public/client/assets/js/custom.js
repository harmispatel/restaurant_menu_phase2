$(document).ready(function() {
    var win = $(window);
    var sideMenu = $(".side_menu_inr .child-cats");
    var activeTab = sideMenu.find('.active');

    var defCat = $("#def_cat").val();


    // Function to scroll to center of active tab within viewport
    function scrollToActiveTab() {
        if (activeTab.length > 0) {
            var scrollOffset = activeTab.offset().left + activeTab.outerWidth() / 2 - win.width() / 2;
            var scrollTo = sideMenu.scrollLeft() + scrollOffset;
            sideMenu.stop().animate({ scrollLeft: scrollTo }, 500);
        }
    }

    var isPageReloaded = true;
    // Scroll to center active tab initially
    scrollToActiveTab();

    // Update active tab and center it on scroll
    win.on("scroll", function () {

        if (isPageReloaded) {
            scrollToSection(defCat, 300);
            isPageReloaded = false; // Update the flag to false after calling scrollToSection
        }
        $(".menu_info_inr").each(function () {
            // if (win.scrollTop() >= $(this).offset().top - 160) {
            //     $("."+$(this).attr("id")).addClass("active").parent().siblings().find("a").removeClass("active");
            //     activeTab = sideMenu.find('.active');
            //     scrollToActiveTab()
            // }
            if (win.scrollTop() >= $(this).offset().top - 310) {
                $("."+$(this).attr("id")).addClass("active").parent().siblings().find("a").removeClass("active");
                activeTab = sideMenu.find('.active');
                scrollToActiveTab()
            }
        });
    });
});


$(document).ready(function(){
    $(".barger_menu_icon").click(e => $(".barger_menu_list").toggleClass("is_open"));
    $('body').click(e => { if(!$(e.target).closest('.barger_menu_icon, .barger_menu_list').length) $(".barger_menu_list").removeClass("is_open"); });
    $('.barger_menu_list').click(e => e.stopPropagation());
    $('.barger_menu_list ul li').click(() => $(".barger_menu_list").removeClass("is_open"));
});


var mySwiper = new Swiper('.flip-category .swiper-container', {
    loop: true,
    speed: 1500,
    // autoplay: {
    //     delay: 3000,
    // },
    effect: 'coverflow',
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',
    mousewheel: true,
    coverflowEffect: {
        rotate: 0,
        stretch: 80,
        depth: 200,
        modifier: 1,
        slideShadows: false,
    },

    // Navigation arrows
    navigation: {
        nextEl: '.flip-category .swiper-button-next',
        prevEl: '.flip-category .swiper-button-prev',
    },


})


