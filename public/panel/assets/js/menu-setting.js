"use strict";
$(document).ready(function() {
    // =========================================================
    // =========    Menu Customizer [ HTML ] code   ============
    // =========================================================
    setTimeout(function(){
        $('#pills-cust-tabContent').css({'height':'calc(100vh - 340px)','position':'relative'});
        // var px = new PerfectScrollbar('#pills-cust-tabContent', {
        //     wheelSpeed: .5,
        //     swipeEasing: 0,
        //     suppressScrollX: !0,
        //     wheelPropagation: 1,
        //     minScrollbarLength: 40,
        // });
        $('.prelayout-color').css({'height':'calc(100vh - 120px)','position':'relative'});
        // var px = new PerfectScrollbar('.prelayout-color', {
        //     wheelSpeed: .5,
        //     swipeEasing: 0,
        //     suppressScrollX: !0,
        //     wheelPropagation: 1,
        //     minScrollbarLength: 40,
        // });
    },400);
    // =========================================================
    // ==================    Menu Customizer Start   ===========
    // =========================================================
    // open Menu Styler
    $('#styleSelector > .style-toggler').on('click', function() {
        $('#styleSelector').toggleClass('open');
        $('#styleSelector').removeClass('prebuild-open');
    });
    $('.prebuild-link-styler').on('click', function() {
        $('#styleSelector').addClass('open');
        $('#styleSelector').removeClass('prebuild-open');
        $('.prebuild-link-layout').removeClass('active');
        $('.prebuild-link-styler').addClass('active');
    });
    $('#styleSelector > .prebuild-toggler > a, .prebuild-link, .prebuild-link-layout').on('click', function() {
        $('#styleSelector').addClass('open');
        $('#styleSelector').toggleClass('prebuild-open');
        $('.prebuild-link-layout').addClass('active');
        $('.prebuild-link-styler').removeClass('active');

    });
    // layout types
    $('.layout-type > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.layout-type > a').removeClass('active');
        $('.pcoded-navbar').removeClassPrefix('navbar-image-');
        $(this).addClass('active');
        $('head').append('<link rel="stylesheet" class="layout-css" href="">');
        if (temp == "menu-dark") {
            $('.pcoded-navbar').removeClassPrefix('menu-');
            $('.pcoded-navbar').removeClass('navbar-dark');
        }
        if (temp == "menu-light") {
            $('.pcoded-navbar').removeClassPrefix('menu-');
            $('.pcoded-navbar').removeClass('navbar-dark');
            $('.pcoded-navbar').addClass(temp);
        }
        if (temp == "reset") {
            location.reload();
        }
        if (temp == "dark") {
            $('.pcoded-navbar').removeClassPrefix('menu-');
            $('.pcoded-navbar').addClass('navbar-dark');
            $('.layout-css').attr("href", "assets/css/layouts/dark.css");
        } else {
            $('.layout-css').attr("href", "");
        }
    });
    // Header Color
    $('.header-color > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.header-color > a').removeClass('active');
        $(this).addClass('active');
        if (temp == "header-default") {
            $('.pcoded-header').removeClassPrefix('header-');
        } else {
            $('.pcoded-header').removeClassPrefix('header-');
            $('.pcoded-header').addClass(temp);
        }
    });
    // Menu Color
    $('.navbar-color > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.navbar-color > a').removeClass('active');
        $('.pcoded-navbar').removeClassPrefix('menu-');
        $('.pcoded-navbar').addClass('brand-dark');
        $(this).addClass('active');
        if (temp == "navbar-default") {
            $('.pcoded-navbar').removeClassPrefix('navbar-');
            $('.pcoded-navbar').removeClassPrefix('brand-dark');
        } else {
            $('.pcoded-navbar').removeClassPrefix('navbar-');
            $('.pcoded-navbar').addClass(temp);
        }
    });
    // Menu Gradient Color
    $('.navbar-gradient-color > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.navbar-gradient-color > a').removeClass('active');
        $('.pcoded-navbar').removeClassPrefix('menu-');
        $('.pcoded-navbar').addClass('brand-dark');
        $(this).addClass('active');
        if (temp == "navbar-default") {
            $('.pcoded-navbar').removeClassPrefix('navbar-');
            $('.pcoded-navbar').removeClassPrefix('brand-dark');
        } else {
            $('.pcoded-navbar').removeClassPrefix('navbar-');
            $('.pcoded-navbar').addClass(temp);
        }
    });
    // Active Color
    $('.active-color > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.active-color > a').removeClass('active');
        $(this).addClass('active');
        if (temp == "active-default") {
            $('.pcoded-navbar').removeClassPrefix('active-');
        } else {
            $('.pcoded-navbar').removeClassPrefix('active-');
            $('.pcoded-navbar').addClass(temp);
        }
    });
    // rtl layouts
    $('#theme-rtl').change(function() {
        $('head').append('<link rel="stylesheet" class="rtl-css" href="">');
        if ($(this).is(":checked")) {
            $('.rtl-css').attr("href", "assets/css/layouts/rtl.css");
        } else {
            $('.rtl-css').attr("href", "");
        }
    });
    // Menu Fixed
    $('#menu-fixed').change(function() {
        if ($(this).is(":checked")) {
            $('.pcoded-navbar').addClass('menupos-fixed');
            setTimeout(function() {
                // $(".navbar-content").css({'overflow':'visible','height':'calc(100% - 70px)'});
            }, 400);
        } else {
            $('.pcoded-navbar').removeClass('menupos-fixed');
        }
    });
    // Header Fixed
    $('#header-fixed').change(function() {
        if ($(this).is(":checked")) {
            $('.pcoded-header').addClass('headerpos-fixed');
        } else {
            $('.pcoded-header').removeClass('headerpos-fixed');

        }
    });
    // Menu Icon Color
    $('#icon-colored').change(function() {
        if ($(this).is(":checked")) {
            $('.pcoded-navbar').addClass('icon-colored');
        } else {
            $('.pcoded-navbar').removeClass('icon-colored');
        }
    });
    // Box layouts
    $('#box-layouts').change(function() {
        if ($(this).is(":checked")) {
            $('body').addClass('container');
            $('body').addClass('box-layout');
        } else {
            $('body').removeClass('container');
            $('body').removeClass('box-layout');
        }
    });
    // Caption Hide
    $('#caption-hide').change(function() {
        if ($(this).is(":checked")) {
            $('.pcoded-navbar').addClass('caption-hide');
        } else {
            $('.pcoded-navbar').removeClass('caption-hide');
        }
    });
    // Header Hide
    $('#header-hide').change(function() {
        if ($(this).is(":checked")) {
            $('.pcoded-navbar').addClass('header-hide');
        } else {
            $('.pcoded-navbar').removeClass('header-hide');
        }
    });

    // Menu image background
    $('.navbar-images > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.pcoded-navbar').removeClassPrefix('menu-');
        $('.navbar-images > a').removeClass('active');
        $(this).addClass('active');
        $('.pcoded-navbar').removeClassPrefix('navbar-image-');
        $('.pcoded-navbar').addClass(temp);
    });
    // Menu pattern background
    $('.navbar-pattern > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.pcoded-navbar').removeClassPrefix('menu-');
        $('.pcoded-navbar').removeClassPrefix('brand-');
        $('.navbar-pattern > a').removeClass('active');
        $(this).addClass('active');
        $('.pcoded-navbar').removeClassPrefix('navbar-pattern-');
        $('.pcoded-navbar').removeClassPrefix('navbar-image-');
        $('.pcoded-navbar').addClass(temp);
    });
    // title Color
    $('.title-color > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.title-color > a').removeClass('active');
        $(this).addClass('active');
        if (temp == "title-default") {
            $('.pcoded-navbar').removeClassPrefix('title-');
        } else {
            $('.pcoded-navbar').removeClassPrefix('title-');
            $('.pcoded-navbar').addClass(temp);
        }
    });
    // brand Color
    $('.brand-color > a').on('click', function() {
        var temp = $(this).attr('data-value');
        $('.brand-color > a').removeClass('active');
        $(this).addClass('active');
        if (temp == "brand-default") {
            $('.pcoded-navbar').removeClassPrefix('brand-');
        } else {
            $('.pcoded-navbar').removeClassPrefix('brand-');
            $('.pcoded-navbar').addClass(temp);
        }
    });
    $.fn.removeClassPrefix = function(prefix) {
        this.each(function(i, it) {
            var classes = it.className.split(" ").map(function(item) {
                return item.indexOf(prefix) === 0 ? "" : item;
            });
            it.className = classes.join(" ");
        });
        return this;
    };
    // ==================    Menu Customizer End   =============
    // =========================================================
});
// Menu Dropdown icon
function drpicon(temp) {
    if (temp == "style1") {
        $('.pcoded-navbar').removeClassPrefix('drp-icon-');
    } else {
        $('.pcoded-navbar').removeClassPrefix('drp-icon-');
        $('.pcoded-navbar').addClass('drp-icon-' + temp);
    }
}
// Menu subitem icon
function menuitemicon(temp) {
    if (temp == "style1") {
        $('.pcoded-navbar').removeClassPrefix('menu-item-icon-');
    } else {
        $('.pcoded-navbar').removeClassPrefix('menu-item-icon-');
        $('.pcoded-navbar').addClass('menu-item-icon-' + temp);
    }
}
