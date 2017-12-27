/*global $ */
$(document).ready(function() {

    "use strict";

    $('.menu a').attr("tabindex",0);
    $('.menu span').attr("tabindex",0);
    $('.menu > ul > li:has( > ul)').addClass('menu-dropdown-icon');
    //Checks if li has sub (ul) and adds class for toggle icon - just an UI

    //$('.menu > ul > li > ul:not(:has(ul))').addClass('normal-sub');
    if ($('.menu > ul > li > ul > ul:has(.single-col)')) {
        $(".single-col").parent().addClass('normal-sub');
    }
    /*Decides the columns width on the dropdown based on the number of columns*/
    $(".top-link").each(function() {
        var columns = $(this).children().find(".column");
        var columnLength = columns.length;
        if (columnLength > 1) {
            columns.css("width", 100 / columnLength + "%");
        }
    });
    /*Adds icon animation*/
    $(".menu-dropdown-icon").click(function() {
        $(this).toggleClass("is-expanded");
    });
    //Checks if drodown menu's li elements have anothere level (ul), if not the dropdown is shown as regular dropdown, not a mega menu (thanks Luka Kladaric)

    $(".menu > ul").before("<a href=\"#\" class=\"menu-mobile\">MENU</a>");

    //Adds menu-mobile class (for mobile toggle menu) before the normal menu
    //Mobile menu is hidden if width is more then 959px, but normal menu is displayed
    //Normal menu is hidden if width is below 959px, and jquery adds mobile menu
    //Done this way so it can be used with wordpress without any trouble

    //Adds delay to the hover
    var hoverTimer;
    var hoverEnter = function() {
        if ($(window).width() > 943) {
            var menuItem = this;
            hoverTimer = setTimeout(function () {
                $(menuItem).children("ul").stop(true, false).fadeIn(150);
            }, 300);
        }
    }
    var hoverExit = function() {
        if ($(window).width() > 943) {
            clearTimeout(hoverTimer);
            $(this).children("ul").stop(true, false).fadeOut(150);
        }
    }

    var focusEnter = function() {
        if ($(window).width() > 943) {
            var menuItem = $(this).parent();
                $(menuItem).children("ul").stop(true, false).fadeIn(150);
        }
    }
    var focusExit = function() {
        if ($(window).width() > 943) {
            var menuItem = $(this).parent();
            menuItem.children("ul").stop(true, false).fadeOut(150);
        }
    }
    $(".menu > ul > li").hover(hoverEnter, hoverExit);

    $(".menu > ul > li >").focusin(focusEnter);
    $(".menu > ul > li >").focusout(focusExit);
    //If width is more than 943px dropdowns are displayed on hover

    $(".menu > ul > li").click(function() {
        if ($(window).width() <= 943) {
            $(this).children("ul").fadeToggle(150);
        }
    });
    //If width is less or equal to 943px dropdowns are displayed on click (thanks Aman Jain from stackoverflow)

    $(".menu-mobile").click(function(e) {
        $(".menu > ul").toggleClass('show-on-mobile');
        e.preventDefault();
    });
    //when clicked on mobile-menu, normal menu is shown as a list, classic rwd menu story (thanks mwl from stackoverflow)

});
