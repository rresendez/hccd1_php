var ezTaskJS;
$(document).on("ready", function() {
    ezTaskJS = (function(window, document, undefined) {
        'use strict';
        var settings = {
            version: "1.0.4",
            docHeight: function() {
                var height = $(document).height();
                var windowHeight = window.innerHeight;
                var correctheight;
                if (height <= windowHeight) {
                    setTimeout(function() {
                        correctheight = $(document).height()
                    }, 0);
                    return correctheight
                } else {
                    return height;
                }
            }
        };
        var _docHeight = settings.docHeight();

        return {
            throttle: function(func, limit) {
                var wait = false;
                limit = limit || 200;
                return function() {
                    if (!wait) {
                        func.call();
                        wait = true;
                        setTimeout(function() {
                            wait = false;
                        }, limit);
                    }
                }
            },
            debounce: function(func, wait, immediate) {
                var timeout;
                var args;
                return function() {
                    var context = this,
                        args = arguments;
                    var later = function() {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    }
                    var callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(func, wait || 200);
                    if (callNow) func.apply(context, args);
                }
            },
            getVersion: function() {
                return settings.version;
            },
            getVersionNotes: function() {
                var fileURL = "/framework_changelog/Version%20" + this.getVersion() + ".txt";
                $.ajax({
                    url: fileURL,
                    type: 'get',
                    dataType: "text",
                    success: function(data) {
                        console.log(data);
                    }
                });
            },
            printContent: function(el) {
                var printcontent = document.getElementById(el).innerHTML;
                document.body.innerHTML = printcontent;
                window.print();
                location.reload();
            },
            mobileToggle: function(element, title, mediaQuery) {
                var title = title || "More";
                var mediaQuery = mediaQuery || 700;
                var elementToWrap = document.getElementById(element) ? document.getElementById(element) : document.getElementsByClassName(element)[0];
                var mobileWrapper, ezMobileToggle;
                if (window.outerWidth < mediaQuery) {
                    elementToWrap.insertAdjacentHTML("beforebegin", "<div class='mobilewrappercontainer clearfix floatleft'><span class='ezMobileToggle'>" + title + "</span><div id ='wrapper-" + element + "' class='mobilewrapper clearfix floatleft'></div></div>");
                    mobileWrapper = document.getElementById("wrapper-" + element);
                    $(mobileWrapper).addClass("collapsed");
                    mobileWrapper.appendChild(elementToWrap);
                    ezMobileToggle = mobileWrapper.previousSibling;
                    $(ezMobileToggle).on("click", function() {
                        $(mobileWrapper).toggleClass("expanded");
                        $(mobileWrapper).toggleClass("collapsed");
                        if ($(mobileWrapper).hasClass("expanded")) {
                            mobileWrapper.style.maxHeight = elementToWrap.offsetHeight + 20 + "px";
                        }
                    });
                }
            },
            isElementInView: function(element, direction, callbackInView, callbackNotInView) {
                var _element = document.getElementById(element) ? document.getElementById(element) : document.getElementsByClassName(element)[0];
                direction = direction || "bottom";
                var boundingRect;
                if (_element != undefined) {
                    boundingRect = _element.getBoundingClientRect();
                }
                var scrollTop = $(window).scrollTop();
                var boundingTop = (_element != undefined) ? (boundingRect.top + scrollTop) : (_docHeight - element);
                if (!boundingTop) {
                    _docHeight = settings.docHeight();
                    boundingTop = (_element != undefined) ? (boundingRect.top + scrollTop) : (_docHeight - element);
                } else {
                    boundingTop = (_element != undefined) ? (boundingRect.top + scrollTop) : (_docHeight - element);
                }
                var scrollBottom = scrollTop + window.innerHeight;
                var conditionIsTrue = direction == "bottom" ? scrollBottom > boundingTop : scrollTop < boundingTop;
                if (conditionIsTrue) {
                    if (typeof callbackInView === "function") {
                        callbackInView();
                    }
                } else {
                    if (typeof callbackNotInView === "function") {
                        callbackNotInView();
                    }
                }
            },
            stickyNav: function(element, when, callbackOnStick, callbackNotStick) {
                var context = this;
                $(window).on("load", function() {
                    var _element = document.getElementById(element) ? document.getElementById(element) : document.getElementsByClassName(element)[0];
                    var whenToStick = when ? when : $(_element).offset().top;
                    var windowTop = $(window).scrollTop();
                    var elementHeight = $(_element).outerHeight(true);
                    var placeholderElement = $("<div id='" + element + "-placeholder' class='ezplaceholder'></div>").css({
                        "height": elementHeight + "px"
                    });
                    var placeholderElementExisits = $(element + "-placeholder");
                    $(placeholderElement).insertBefore(_element);
                    var makeNavSticky = function() {
                        windowTop = $(window).scrollTop();
                        if (windowTop > whenToStick) {
                            $(_element).addClass("sticknav");
                            $(placeholderElementExisits).show();
                            if (typeof callbackOnStick === "function") {
                                callbackOnStick();
                            }
                        } else {
                            $(_element).removeClass("sticknav");
                            $(placeholderElementExisits).hide();
                            if (typeof callbackNotStick === "function") {
                                callbackNotStick();
                            }
                        }
                    };
                    makeNavSticky();
                    $(window).on("scroll", makeNavSticky)
                });
            },
            initGoogleSearch: function() {
                var cx = '012200099423418171131:yfwofjbvg0c'; //Get custom code
                var gcse = document.createElement('script');
                gcse.type = 'text/javascript';
                gcse.async = true;
                gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(gcse, s);
                if (window.location.pathname != "/design/Editor.aspx") {
                    var gsceReset = document.getElementsByClassName('gsc-control-cse')[0] || document.querySelector(".gsc-control-cse");
                    var ezsearchbox = document.getElementById("cse-search-input-box-id");
                    var searchparent = document.getElementById("searchbox");
                    if (searchparent) {
                        ezsearchbox.onfocus = function() {
                            $(searchparent).addClass("focused");
                        }
                        ezsearchbox.onblur = function() {
                            $(searchparent).removeClass("focused");
                        }
                        $(window).on("load",function(){
                            var gscBrandingImg = $("img.gsc-branding-img");
                            var gscSearchButton = $("input.gsc-search-button");
                            if (gscBrandingImg.length > 0) gscBrandingImg.attr("alt", "Google Branding Image");
                            if (gscSearchButton.length > 0) gscSearchButton.attr("alt", "Google Search Button");
                        });
                    }
                }
            },
            scrollToTop: function(element, time) {
                var _element = document.getElementById(element);
                time = time ? time : 1000;
                var interval;
                $(_element).on("click", function() {
                    var scrollPosition = $(window).scrollTop();
                    var scrollStep = scrollPosition / (time / 15);
                    clearInterval(interval);
                    if (scrollPosition > 0) {
                        interval = setInterval(function() {
                            if (!(scrollPosition <= 0)) {
                                scrollPosition -= scrollStep;
                                $(window).scrollTop(scrollPosition);
                            } else {
                                clearInterval(interval);
                            }
                        }, 15);
                    }
                });
            },
            copyrightDate: function() {
                var now = new Date();
                var year = now.getFullYear();
                var copyright = $("#ezcopyright");
                if (copyright)
                    $("#ezcopyright").html(year);
            },
            datetime: function(type) {
                var now = new Date();
                var date = now.getDate();
                var year = now.getFullYear();
                var today = function() {
                    var dayArray = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                    return dayArray[now.getDay()];
                }
                var month = function() {
                    var monthArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    return monthArray[now.getMonth()];
                }
                var time = function() {
                    var period = "";
                    var hours = function() {
                        var hour = now.getHours();
                        if (hour === 0) {
                            hour = 12;
                        }
                        if (hour > 12) {
                            hour = hour - 12;
                            period = "PM";
                        } else {
                            period = "AM";
                        }
                        return hour;
                    }
                    var minutes = function() {
                        var minute = now.getMinutes();
                        if (minute.toString().length < 2) {
                            minute = "0" + minute.toString();
                        } else {
                            minute = minute;
                        }
                        return minute;
                    }

                    return hours().toString() + ":" + minutes().toString() + " " + period;
                }
                if (type == "dateonly") {
                    return today().toString() + ", " + month().toString() + " " + date + " " + year;
                }
                if (type == "timeonly") {
                    return time().toString();
                }
                if (type == "dateandtime") {
                    return today().toString() + ", " + month().toString() + " " + date + " " + year + " " + time().toString();
                }
            }
        }
    }(window, document));
    ezTaskJS.copyrightDate();
    ezTaskJS.initGoogleSearch();
    $(window).on("load",function () {
        var google_translate_element_text = $("#google_translate_element > div");
        var text = google_translate_element_text.text();
        $(google_translate_element_text[0].childNodes[1]).remove();
        $(google_translate_element_text[0].childNodes[1]).remove();
    });
});
