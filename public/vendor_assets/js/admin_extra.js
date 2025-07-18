window.addEventListener('load', function () {

    $(".loader-overlay").delay(500).fadeOut("slow");
    $("#overlayer").fadeOut(500, function () {
        $('body').removeClass('overlayScroll');
    });

    document.querySelector('body').classList.add("loaded")

    /* feather icon */
    feather.replace();

    /* sidebar collapse  */
    const sidebarToggle = document.querySelector(".sidebar-toggle");

    function sidebarCollapse() {
        $('.overlay-dark-sidebar').toggleClass('show');
        document.querySelector(".sidebar").classList.toggle("sidebar-collapse");
        document.querySelector(".sidebar").classList.toggle("collapsed");
        document.querySelector(".contents").classList.toggle("expanded");
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function (e) {
            e.preventDefault();
            sidebarCollapse();
        });
    }

    /* sidebar nav events */
    $(".sidebar_nav .has-child ul").hide();
    $(".sidebar_nav .has-child.open ul").show();
    $(".sidebar_nav .has-child >a").on("click", function (e) {
        e.preventDefault();
        $(this).parent().next("has-child").slideUp();
        $(this).parent().parent().children(".has-child").children("ul").slideUp();
        $(this).parent().parent().children(".has-child").removeClass("open");
        if ($(this).next().is(":visible")) {
            $(this).parent().removeClass("open");
        } else {
            $(this).parent().addClass("open");
            $(this).next().slideDown();
        }
    });

    /* Header mobile view */
    $(window).on('resize', function () {
        var screenSize = window.innerWidth;
        if ($(this).width() <= 767.98) {
            $(".navbar-right__menu").appendTo(".mobile-author-actions");
            // $(".search-form").appendTo(".mobile-search");
            $(".contents").addClass("expanded");
            $(".sidebar ").removeClass("sidebar-collapse");
            $(".sidebar ").addClass("collapsed");
        } else {
            $(".navbar-right__menu").appendTo(".navbar-right");
        }
    })
        .trigger("resize");

    $(window)
        .bind("resize", function () {
            var screenSize = window.innerWidth;
            if ($(this).width() > 767.98) {
                $(".atbd-mail-sidebar").addClass("show");
            }
        })
        .trigger("resize");

    $(window)
        .bind("resize", function () {
            var screenSize = window.innerWidth;
            if ($(this).width() <= 991) {
                $(".sidebar").removeClass("sidebar-collapse");
                $(".sidebar").addClass("collapsed");
                $(".sidebar-toggle").on("click", function () {
                    $(".overlay-dark-sidebar").toggleClass("show");
                });
                $(".overlay-dark-sidebar").on("click", function () {
                    $(this).removeClass("show");
                    $(".sidebar").removeClass("sidebar-collapse");
                    $(".sidebar").addClass("collapsed");
                });
            }
        })
        .trigger("resize");

    /* Mobile Menu */
    $(window)
        .bind("resize", function () {
            var screenSize = window.innerWidth;
            if ($(this).width() <= 991.98) {
                $(".menu-horizontal").appendTo(".mobile-nav-wrapper");
            }
        })
        .trigger("resize");
});
