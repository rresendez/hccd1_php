(function($, window, document, i, undefined) {
    'use strict';
    var pluginName = "responsiveSlides",
        dataKey = "plugin_" + pluginName;
    var debounce = function(func, wait, immediate) {
        var timeout;
        var args;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args)
            }
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(func, wait || 200);
            if (callNow) func.apply(context, args)
        }
    };
    var calculateAspectRatioFit = function(srcWidth, srcHeight, maxWidth, maxHeight) {
        var ratio = Math.max(maxWidth / srcWidth, maxHeight / srcHeight);
        return {
            width: srcWidth * ratio,
            height: srcHeight * ratio
        }
    }
    var stripWhiteSpace = function(string) {
        return string.replace(/\s/g, '')
    };
    var Plugin = function(element, options) {
        this.element = element;
        this.slides = element.children();
        this.options = {
            "timeout": 5000,
            "speed": 500,
            "nav": !0,
            "navigation": !1,
            "thumbnails": !1,
            "pause": !0,
            "random": !1,
            "namespace": "callbacks",
            "animation": "fadeOut",
            "thumbnailSize": 60,
            "maxHeight": 0,
            "imageSize": "",
            "carouselType": null,
            "carouselWidth": 0
        };
        this.init(options)
    };
    Plugin.prototype = {
        init: function(options) {
            var options = $.extend(this.options, options),
                model = new this.Model(this.element, this.slides, options),
                view = new this.View(model, this.slides),
                controller = new this.Controller(model, view);
            return this
        }
    };
    Plugin.prototype.Model = function(element, slides, options) {
        this.element = element;
        this.slides = slides;
        this.options = options;
        this.length = slides.length;
        this.width = element.width();
        this.parentWidth = element.parent().width();
        this.totalWidth = this.width * this.length;
        this.index = 0;
        this.images = this.slides.find("img");
        this.imagesLoaded = 0;
        this.visible = {
            "display": "block",
            "float": "left",
            "position": "relative",
            "opacity": 1,
        };
        this.hidden = {
            "display": "block",
            "float": "left",
            "position": "absolute",
            "opacity": 0
        };
        this.fadeTime = parseFloat(this.options.speed), this.waitTime = parseFloat(this.options.timeout), this.animationType = "doCSSAnimation", this.thumbMargin = 0;
        this.namespaceIdx = this.options.namespace + i;
        this.visibleClass = this.namespaceIdx + "_on";
        this.visibleClassString = this.namespaceIdx + "_on " + this.options.namespace + "_visible";
        this.hiddenClassString = this.options.namespace + "_hidden";
        this.activeClass = this.options.namespace + "_here";
        this.slideClassPrefix = this.options.namespace + i + "_s";
        this.navClass = this.options.namespace + "_nav";
        this.navClassIdx = this.namespaceIdx + "_nav";
        this.loader = element.parent().prev(".loaderwrapper");
        this.navigation = $("<ul class='" + this.options.namespace + "_tabs " + this.namespaceIdx + "_tabs' />");
        this.prevBtn = $("<span class='ion-ios-arrow-left " + this.navClassIdx + " " + this.navClass + " prev'></span>");
        this.nextBtn = $("<span class='ion-ios-arrow-right " + this.navClassIdx + " " + this.navClass + " next'></span>");
        this.thumbnailContainer = $("<ul class='thumbnailcontainer'></ul>");
        this.thumbnailWrapper = $("<ul class='" + "thumbnailswrapper " + this.options.namespace + i + "_thumbnails'></ul>").css("height", this.options.thumbnailSize).appendTo(this.thumbnailContainer);
        this.thumbnailNavLeft = $("<li class='thumbnailsroller thumbnailleft ion-chevron-left'></li>").appendTo(this.thumbnailContainer);
        this.thumbnailNavRight = $("<li class='thumbnailsroller thumbnailright ion-chevron-right'></li>").appendTo(this.thumbnailContainer);
        this.offsetLeft = element.offset().left;
        this.offsetRight = element.offset().left + this.width;
        this.slidesToShow = 0;
        this.scrollAmount = 0;
        this.hasCaption = element.parent().children().find(".ezcaption").length;
        this.maxHeight = options.maxHeight > 0 ? options.maxHeight : parseFloat(element.css("maxHeight"));
        this.thumbnailSize = parseFloat(this.options.thumbnailSize);
        this.currentTabPosition = 0;
        this.thumbsTotalWidth = parseFloat((this.thumbnailSize * this.length) + this.length);
        this.thumbsPerSlide = parseFloat(Math.floor(this.element.width() / (this.thumbnailSize)));
        this.built = !1, this.isReady = !1, this.animating = !1, this.touchable = !1, this.animating = !1, this.transitionSupport = !1;
        this.thumbClicked = !1, this.thumbnailAnimating = !1, this.isCarousel = !1;
        this.tabs, this.timer, this.lastScrollAmount, this.startInterval, this.buildInterval, this.thumbnailTimer = null, this.carouselData = {};
        return this
    };
    Plugin.prototype.Model.prototype = {
        indexData: function(idx) {
            var data = {
                right: idx + 1 < this.length ? idx + 1 : 0,
                left: idx === 0 ? this.length - 1 : idx - 1
            }
            return data
        },
        next: function() {
            return this.index + 1 < this.length ? this.index + 1 : 0
        },
        prev: function() {
            return this.index === 0 ? this.length - 1 : this.index - 1
        },
        getDirection: function(idx) {
            if (this.index == this.length - 1 && idx == 0) {
                return "right"
            } else if (this.index == 0 && idx == this.length - 1) {
                return "left"
            } else if (this.index < idx) {
                return "right"
            } else if (this.index > idx) {
                return "left"
            }
        },
        loadImages: function() {
            ++this.imagesLoaded
        },
        determineSlidesToShow: function() {
            var src = this.slides.eq(0).find("img")[0].src;
            var newImg = new Image();
            newImg.src = src;
            this.carouselData.originalWidth = newImg.width;
            this.carouselData.originalHeight = newImg.height;
            this.carouselData.originalRatio = newImg.height / newImg.width;
            this.carouselData.slidesToShow = Math.ceil(this.width / newImg.width);
            this.carouselData.slideWidth = parseFloat(this.element.parent().width() / this.carouselData.slidesToShow).toFixed(3);
            if (this.parentWidth <= newImg.width && this.options.carouselWidth >= this.parentWidth) {
                this.element.addClass("ezcarousel-noeffect");
                this.options.carouselType = "normal";
                this.carouselData.slidesToShow = 1
            }
            if (this.options.carouselWidth) {
                if (this.width <= this.options.carouselWidth) {
                    this.options.carouselWidth = this.width
                }
                this.carouselData.slideWidth = this.options.carouselWidth;
                this.carouselData.slidesToShow = Math.ceil(this.width / this.options.carouselWidth)
            }
            if (this.options.carouselType == "center") {
                if (this.options.carouselWidth) {
                    var ratio = newImg.height / newImg.width;
                    this.carouselData.centerWidth = this.options.carouselWidth * .8;
                    this.carouselData.centerHeight = (this.options.carouselWidth) * ratio;
                    this.carouselData.slideWidth = this.carouselData.centerWidth
                } else {
                    this.carouselData.centerHeight = newImg.height * .8;
                    this.carouselData.centerWidth = newImg.width * .8;
                    this.carouselData.slideWidth = this.carouselData.centerWidth
                }
                this.carouselData.slidesToShow = Math.ceil(this.width / this.carouselData.centerWidth);
                if (this.carouselData.slidesToShow % 2 == 0) {
                    this.carouselData.slidesToShow = this.carouselData.slidesToShow + 1
                }
            }
            return {
                width: newImg.width,
                height: newImg.height
            }
        },
        setCarouselData: function() {
            this.carouselData.elementWidth = this.carouselData.numberOfSlides * this.carouselData.slideWidth;
            this.carouselData.startPoint = -(Math.ceil(this.carouselData.slidesToShow / 2) * this.carouselData.slideWidth).toFixed(3);
            this.carouselData.endPoint = -(this.carouselData.elementWidth - (this.carouselData.slideWidth * (this.carouselData.slidesToShow + (Math.ceil(this.carouselData.slidesToShow / 2))))).toFixed(3);
            this.carouselData.currentPoint = this.carouselData.startPoint;
            this.carouselData.endSlide = this.length - 1;
            this.carouselData.startSlide = 0;
            if (this.options.carouselWidth > 0 || this.options.carouselType == "center") {
                var elWidth = this.element.width();
                var widthToUse = this.options.carouselType == "center" ? this.carouselData.centerWidth : this.carouselData.slideWidth;
                var offset = (elWidth - widthToUse) / 2
                this.carouselData.startPoint = -(widthToUse * this.carouselData.slidesToShow) + offset;
                this.carouselData.currentPoint = this.carouselData.startPoint;
                this.carouselData.endPoint = this.carouselData.startPoint - (this.carouselData.slideWidth * (this.length - 1))
            }
            this.carouselData.carouselSlides = this.element.find("li")
        },
        supportsTouch: function() {
            this.touchable = 'ontouchstart' in window || navigator.maxTouchPoints || window.DocumentTouch && document instanceof DocumentTouch || 'ontouchstart' in document.documentElement ? !0 : !1;
            return this
        },
        checkForCSSTransitions: function() {
            var self = this;
            var docBody = document.body || document.documentElement;
            var styles = docBody.style;
            var prop = "transition";
            if (typeof styles[prop] === "string") {
                self.transitionSupport = !0;
                return !0
            }
            vendor = ["Moz", "Webkit", "Khtml", "O", "ms"];
            prop = prop.charAt(0).toUpperCase() + prop.substr(1);
            var i;
            for (i = 0; i < vendor.length; i++) {
                if (typeof styles[vendor[i] + prop] === "string") {
                    self.transitionSupport = !0;
                    return !0
                }
            }
            self.animationType = "doJSAnimation";
            self.transitionSupport = !1;
            return !1
        },
        setCSSTransitions: function() {
            var m = this;
            var animation = m.options.animation;
            if (m.transitionSupport) {
                if (animation == "slideTop") {
                    m.visible = $.extend({}, {
                        "transform": "translateY(0%)",
                        "zIndex": 2
                    }, m.visible);
                    m.hidden = $.extend(m.hidden, {
                        "zIndex": 1,
                        "transform": "translateY(-100%)",
                    }, m.hidden)
                } else if (animation == "slideLeft") {
                    m.visible = $.extend({}, {
                        "transform": "translateX(0%)",
                        "zIndex": 2
                    }, m.visible);
                    m.hidden = $.extend(m.hidden, {
                        "zIndex": 1,
                        "transform": "translateX(-100%)",
                    }, m.hidden)
                } else {
                    m.visible = m.visible;
                    m.hidden = m.hidden
                }
            } else {
                if (animation == "slideLeft") {
                    m.visibleLeft = 0;
                    m.hiddenLeft = -m.width
                } else if (animation == "slideTop") {
                    m.visibleTop = 0;
                    m.hiddenTop = -m.maxHeight ? -m.maxHeight : -m.slides.height()
                } else {
                    m.visibleOpacity = 1;
                    m.hiddenOpacity = 0
                }
            }
        },
    }
    Plugin.prototype.View = function(model, slides) {
        this.model = model;
        this.slides = slides;
        return this
    };
    Plugin.prototype.View.prototype = {
        arrangeSlides: function() {
            var m = this.model;
            var cd = m.carouselData;
            if (m.options.animation == "swipe" || m.options.carouselType !== null) {
                m.visible = $.extend({}, {
                    "overflow": "hidden",
                    "transition": "none"
                }, m.visible);
                m.determineSlidesToShow();
                $(this.slides).css(m.visible);
                this.addSlideData();
                this.buildInfiniteMode(-cd.slidesToShow, m.length);
                cd.numberOfSlides = m.length + (cd.slidesToShow * 2);
                m.setCarouselData();
                m.carouselData.clonedSlides = m.element.find("li.rs-clone");
                cd.clonedSlides.css("height", cd.centerHeight);
                m.slides.css("height", cd.centerHeight);
                this.carouselStructure();
                if (m.options.carouselType == "center") this.carouselCenter();
                m.isCarousel = !0
            } else {
                $(this.slides).css(m.hidden);
                this.slides.eq(0).css(m.visible);
                this.addAnimationClasses(0)
            }
            return this
        },
        buildInfiniteMode: function(leftIndex, rightIndex) {
            var self = this;
            var m = this.model;
            var cd = m.carouselData;
            var leftClones = [];
            var rightClones = [];
            for (var i = 0; i < m.slides.length; i++) {
                if (i < cd.slidesToShow) {
                    rightClones.push($(self.slides[i]).clone().attr("id", "").removeClass(m.visibleClass).addClass("rs-clone").attr("data-ez-index", rightIndex));
                    rightIndex++
                }
                if (i > m.length - 1 - cd.slidesToShow) {
                    leftClones.push($(m.slides[i]).clone().attr("id", "").removeClass(m.visibleClass).addClass("rs-clone").attr("data-ez-index", leftIndex));
                    leftIndex++
                }
            }
            m.element.prepend(leftClones);
            m.element.append(rightClones)
        },
        carouselCenter: function() {
            var m = this.model;
            var cd = m.carouselData;
            m.element.css("height", m.options.maxHeight);
            cd.clonedSlides.css("top", (m.options.maxHeight - cd.centerHeight) / 2);
            m.slides.css("top", (m.options.maxHeight - cd.centerHeight) / 2)
        },
        carouselStructure: function() {
            var m = this.model;
            var cd = m.carouselData;
            var autoWidth = m.options.carouselWidth > 0 ? "ezcarousel-centerwidth" : "";
            m.element.addClass("ezcarousel ezcarousel-" + m.options.carouselType + " " + "ezcarousel-centerwidth");
            m.element.css({
                "width": cd.elementWidth,
                "transition": "none"
            });
            cd.carouselSlides.css("width", cd.slideWidth);
            this.stretchAndFill(cd.slideWidth);
            if (m.transitionSupport) {
                m.element.css("transform", "translate(" + cd.startPoint + "px)")
            } else {
                m.element.css("left", cd.startPoint + "px")
            }
        },
        addNamespace: function() {
            var self = this,
                m = this.model,
                o = this.model.options;
            m.element.attr("id", m.namespaceIdx);
            m.element.addClass(o.namespace, m.namespaceIdx);
            this.slides.each(function(i) {
                $(this).attr("id", m.namespaceIdx + "_s" + i)
            });
            this.slides.eq(0).addClass(m.visibleClassString);
            return this
        },
        addSlideData: function() {
            this.slides.each(function(i) {
                $(this).attr("data-ez-index", i);
                $(this).attr("data-ez-original", i)
            });
            return this
        },
        randomize: function() {
            var m = this.model;
            if (m.options.random === !0) {
                this.slides.sort(function() {
                    return (Math.round(Math.random()) - 0.5)
                });
                m.element.empty().append(this.slides)
            }
            return this
        },
        setImageSize: function() {
            var m = this.model;
            if (m.options.imageSize == "actual") {
                m.element.css("lineHeight", m.maxHeight + "px").addClass("actualsize");
                m.slides.addClass("actualsizeli").find("img").addClass("actualsizeimg")
            } else if ((m.options.imageSize == "stretchfill" && m.maxHeight > 0)) {
                this.stretchAndFill()
            }
            return this
        },
        addTransitions: function(element, transitionProperty, time) {
            var m = this.model;
            var time = time || m.fadeTime;
            if (m.transitionSupport) {
                $(element).css({
                    "-webkit-transition": transitionProperty + " " + time + "ms ease-in-out",
                    "-moz-transition": transitionProperty + " " + time + "ms ease-in-out",
                    "-o-transition": transitionProperty + " " + time + "ms ease-in-out",
                    "transition": transitionProperty + " " + time + "ms ease-in-out"
                })
            }
            return this
        },
        stretchAndFill: function(width) {
            var self = this;
            var m = this.model;
            var maxHeight = m.maxHeight || m.options.maxHeight || parseFloat(m.element.css("maxHeight"));
            if (m.options.carouselType == "center") {
                maxHeight = m.carouselData.centerHeight
            }
            $("#" + m.element.attr("id") + " li").find("img").each(function(i) {
                $(this).css("minHeight", maxHeight);
                var parent = $(this).closest("li");
                parent.css({
                    "overflow": "hidden",
                    "opacity": 1
                });
                $(this).addClass("stretchfill");
                var horizontalDiff = width ? $(this).width() - width : $(this).width() - parent.width();
                var verticalDiff = $(this).height() - parent.height();
                if (horizontalDiff <= 0 || verticalDiff <= 0) {
                    var imgClass = ($(this).width() / $(this).height() > 1) ? 'wide' : 'tall';
                    $(this).removeClass("stretchfill");
                    $(this).addClass(imgClass);
                    var imgHeight = $(this)[0].clientHeight;
                    var imgWidth = $(this)[0].clientWidth;
                    var parentWidth = width ? Math.ceil(width) : parent.width();
                    var aspect = calculateAspectRatioFit(imgWidth, imgHeight, parentWidth, maxHeight);
                    var roundedHeight = Math.round(aspect.height);
                    var roundedWidth = Math.round(aspect.width);
                    $(this).css("height", roundedHeight + "px");
                    $(this).css("width", roundedWidth + "px");
                    verticalDiff = aspect.height - maxHeight;
                    horizontalDiff = aspect.width - parentWidth
                } else {
                    if (m.options.carouselType == "center") {
                        $(this).css({
                            "max-width": "100%",
                        });
                        verticalDiff = 0;
                        horizontalDiff = 0
                    }
                }
                $(this).css("top", (-verticalDiff / 2));
                $(this).css("left", (-horizontalDiff / 2))
            });
            return this
        },
        buildNavigation: function() {
            var m = this.model;
            m.element.after(m.prevBtn);
            m.element.after(m.nextBtn);
            if (m.options.thumbnails) {
                m.prevBtn.css("top", "calc(50% - 60px)");
                m.nextBtn.css("top", "calc(50% - 60px)")
            }
            return this
        },
        buildPager: function() {
            var self = this;
            var m = this.model;
            var markup = [];
            var distIncr = 0;
            m.slides.each(function(i) {
                var n = i + 1;
                if (m.options.navigation) {
                    markup += "<li class='" + m.slideClassPrefix + (i + 1) + "'>" + "<a href='#' class='" + m.slideClassPrefix + n + "'>" + "<span class='pager-dot'></span>" + "</a>" + "</li>"
                }
                if (m.options.thumbnails) {
                    var image = $(m.slides[i]).find("img");
                    var ratio = calculateAspectRatioFit($(image).width(), $(image).height(), m.thumbnailSize, m.thumbnailSize);
                    markup += "<li class='callbacks_thumbnails' style='left:" + distIncr + "px; width:" + m.thumbnailSize + "px;height:" + m.thumbnailSize + "px'>" + "<a href='#' class='" + m.slideClassPrefix + n + "'>" + "<img style='height:" + Math.round(parseFloat(ratio.height)) + "px;width:" + Math.round(parseFloat(ratio.width)) + "px' src='" + $(this).find("img")[0].src + "' alt='" + $(this).find("img").attr("alt") + " - Thumbnail" + "'>" + "</a>" + "</li>"
                }
                distIncr += (m.options.thumbnailSize + m.thumbMargin)
            });
            if (m.options.thumbnails) {
                m.thumbnailWrapper.append(markup);
                m.element.after(m.thumbnailContainer);
                m.tabs = m.thumbnailWrapper.find('a');
                this.thumbnailContainerScroll();
                if (m.slides.length * m.thumbnailSize < m.thumbsPerSlide * m.thumbnailSize) {
                    m.thumbnailNavLeft.css("display", "none");
                    m.thumbnailNavRight.css("display", "none")
                }
            } else if (m.options.navigation) {
                if (m.hasCaption) {
                    m.navigation.addClass("hascaption")
                }
                m.navigation.append(markup);
                m.element.after(m.navigation);
                m.tabs = m.navigation.find('a')
            }
            return this
        },
        formatCaptions: function() {
            var self = this;
            var m = this.model;
            var captionWrapper, captionTitleLength, captionDescriptionLength;
            m.slides.each(function(i) {
                captionWrapper = $(this).find(".ezcaptionwrapper");
                captionTitleLength = stripWhiteSpace(captionWrapper.find(".ezcaptiontitle").text()).length;
                captionDescriptionLength = stripWhiteSpace(captionWrapper.find(".ezcaption").text()).length;
                if (captionTitleLength == 0 && captionDescriptionLength == 0) captionWrapper.hide()
            })
        },
        setUpJsAnimations: function() {
            var m = this.model;
            if (!m.transitionSupport) {
                if (m.options.animation != "carousel") {
                    if (m.options.animation == "slideLeft") m.slides.css("left", m.hiddenLeft).eq(0).css("left", m.visibleLeft);
                    else if (m.options.animation == "slideTop") m.slides.css("top", m.hiddenTop).eq(0).css("top", m.visibleTop);
                    else m.slides.css("opacity", m.hiddenOpacity).eq(0).css("opacity", m.visibleOpacity)
                }
            }
            return this
        },
        jsAnimations: function(index, idx) {
            var m = this.model;
            var animations = {};
            var slideAnimation = function(prop, amountIn, amountOut) {
                var outAnim = {
                    opacity: 0
                };
                var inAnim = {
                    opacity: 1
                };
                outAnim[prop] = amountOut;
                inAnim[prop] = amountIn;
                m.slides.eq(index).animate(outAnim, m.fadeTime);
                m.slides.eq(idx).animate(inAnim, m.fadeTime);
                m.slides.each(function(i) {
                    if (i != index && i != idx) {
                        $(this).css(prop, amountOut)
                    }
                })
            };
            animations.slideLeft = function() {
                slideAnimation("left", 0, m.hiddenLeft)
            }
            animations.slideTop = function() {
                slideAnimation("top", 0, m.hiddenTop)
            }
            animations.fadeOut = function() {
                slideAnimation("opacity", 1, m.hiddenOpacity)
            }
            return animations
        },
        removeLoader: function() {
            var self = this;
            var m = this.model;
            m.loader.remove();
            m.element.closest('.delay .callbacks_container').css("opacity", 1);
            m.element.closest('.ezrotatorwrapper').removeClass('delay')
        },
        slideTo: function(idx) {
            var self = this;
            var m = this.model;
            var indexData = m.indexData(idx);
            var leftSlide = indexData.left;
            var rightSlide = indexData.right;
            if (m.options.thumbnails) {
                this.thumbScrollTracker(idx)
            }
            if (!m.isCarousel) {
                this[m.animationType](idx, leftSlide, rightSlide)
            }
            m.index = idx;
            indexData = m.indexData(m.index);
            m.currentLeftSlide = m.slides.eq(leftSlide);
            m.currentRightSlide = m.slides.eq(rightSlide);
            m.thumbClicked = !1
        },
        addAnimationClasses: function(idx) {
            var m = this.model;
            var indexData = m.indexData(idx);
            m.slides.removeClass(m.visibleClassString).eq(idx).addClass(m.visibleClassString);
            m.slides.removeClass(m.options.namespace + "_next").eq(indexData.right).addClass(m.options.namespace + "_next");
            m.slides.removeClass(m.options.namespace + "_prev").eq(indexData.left).addClass(m.options.namespace + "_prev")
        },
        addClassToCopiedSlides: function(idx) {
            var m = this.model;
            var cd = m.carouselData;
            cd.clonedSlides.each(function() {
                if (parseInt($(this).attr("data-ez-original")) == idx) {
                    $(this).addClass(m.visibleClassString)
                } else {
                    $(this).removeClass(m.visibleClassString)
                }
            })
        },
        doCSSAnimation: function(idx, left, right) {
            var m = this.model;
            this.addAnimationClasses(idx);
            m.slides.css(m.hidden).eq(idx).css(m.visible)
        },
        doJSAnimation: function(idx, left, right) {
            var m = this.model;
            var jsAnimations = this.jsAnimations(m.index, idx);
            this.addAnimationClasses(idx);
            jsAnimations[m.options.animation]()
        },
        scrollCarousel: function(idx, direction) {
            var m = this.model;
            var cd = m.carouselData;
            cd.currentPoint = parseFloat(cd.currentPoint);
            if (m.animating == !1) {
                this.addAnimationClasses(idx);
                this.addClassToCopiedSlides(idx);
                m.animating = !0;
                if (direction == "+") {
                    cd.currentPoint -= parseFloat(cd.slideWidth);
                    if (idx === cd.startSlide) {
                        m.carouselEnd = !0
                    }
                    this.animateCarousel(idx, cd.currentPoint);
                    this.carouselTimer(idx, cd.endSlide, cd.startSlide)
                } else {
                    cd.currentPoint += parseFloat(cd.slideWidth);
                    if (idx == cd.endSlide) {
                        m.carouselBegin = !0
                    }
                    this.animateCarousel(idx, cd.currentPoint);
                    this.carouselTimer(idx, cd.endSlide, cd.startSlide)
                }
                m.index = idx;
                var indexData = m.indexData(m.index)
            } else {
                return !1
            }
        },
        animateCarousel: function(idx, amount) {
            var m = this.model;
            var transitionProperty = "translate3d(" + amount + "px,0,0)";
            if (!m.transitionSupport) {
                transitionProperty = amount + "px";
                m.element.animate({
                    "left": transitionProperty
                }, m.fadeTime)
            } else {
                m.element.css({
                    "transition": "all " + m.fadeTime + "ms ease-in-out",
                    "transform": transitionProperty
                })
            }
        },
        carouselReset: function(amount, resetVar) {
            var m = this.model;
            var cd = m.carouselData;
            m[resetVar] = !1;
            var transitionProperty = "translate3d(" + amount + "px,0,0)";
            if (!m.transitionSupport) {
                transitionProperty = amount + "px";
                m.element.css({
                    "left": transitionProperty
                })
            } else {
                m.element.css({
                    "transition": "none",
                    "transform": transitionProperty
                })
            }
            cd.currentPoint = amount
        },
        carouselTimer: function(idx, startIndex, endIndex) {
            var self = this;
            var m = this.model;
            var cd = m.carouselData;
            var animatingTimer = setTimeout(function() {
                if (m.carouselEnd) {
                    self.carouselReset(cd.startPoint, "carouselEnd")
                } else if (m.carouselBegin) {
                    self.carouselReset(cd.endPoint, "carouselBegin")
                }
                m.animating = !1;
                clearTimeout(animatingTimer)
            }, m.fadeTime + 100)
        },
        selectTab: function(idx) {
            var m = this.model;
            m.tabs.closest("li").removeClass(m.activeClass).eq(idx).addClass(m.activeClass);
            return this
        },
        thumbScrollTracker: function(idx) {
            var m = this.model;
            if (m.length >= m.thumbsPerSlide) {
                m.currentTabPosition = $(m.tabs[idx]).offset().left + $(m.tabs[idx]).width();
                if (m.currentTabPosition >= m.offsetRight && m.currentTabPosition <= m.offsetRight + m.thumbnailSize) {
                    this.scrollThumbnails(500, "+")
                } else if (m.index > idx && idx != 0 && m.index != m.length - 1) {
                    if (m.currentTabPosition <= m.offsetLeft && m.currentTabPosition >= m.offsetLeft - m.thumbnailSize) {
                        this.scrollThumbnails(500, "-")
                    }
                } else if (m.index == m.length - 1 && idx == 0 && !m.thumbClicked) {
                    this.scrollThumbnails(500, "+")
                } else if (m.index == 0 && idx == m.length - 1 && !m.thumbClicked) {
                    this.scrollThumbnails(500, "-")
                }
            }
        },
        thumbnailContainerScroll: function() {
            var m = this.model;
            var currentLeft = m.thumbnailWrapper.position().left;
            m.scrollAmount = parseFloat($(m.tabs[Math.round(m.thumbsPerSlide / 2)]).parent().css("left"));
            m.lastScrollAmount = m.thumbsTotalWidth + m.length - m.slides.width();
            var atEnd = 0;
            this.scrollThumbnails = function(speed, direction) {
                if (direction == "+") {
                    currentLeft -= m.scrollAmount;
                    if (Math.abs(currentLeft) > m.lastScrollAmount) {
                        currentLeft = currentLeft + m.scrollAmount;
                        var difference = currentLeft + m.lastScrollAmount;
                        currentLeft -= difference
                    }
                    if (Math.abs(currentLeft) == m.lastScrollAmount) {
                        if (atEnd == 1) {
                            currentLeft = 0;
                            atEnd = 0
                        } else atEnd += 1
                    }
                    if (m.transitionSupport) {
                        m.thumbnailWrapper.css({
                            transform: "translateX(" + currentLeft + "px)",
                            transition: "all " + speed + "ms ease"
                        })
                    } else {
                        m.thumbnailWrapper.animate({
                            "left": currentLeft + "px"
                        }, speed)
                    }
                }
                if (direction == "-") {
                    if (currentLeft == 0) {
                        currentLeft -= m.lastScrollAmount;
                        atEnd = 1
                    } else currentLeft += m.scrollAmount;
                    if (currentLeft > 0) currentLeft = 0;
                    if (m.transitionSupport) {
                        m.thumbnailWrapper.css({
                            transform: "translateX(" + currentLeft + "px)",
                            transition: "all " + speed + "ms ease"
                        })
                    } else {
                        m.thumbnailWrapper.animate({
                            "left": currentLeft + "px"
                        }, speed)
                    }
                }
            }
        },
        enableSwipe: function() {
            var self = this;
            var m = this.model;
            if (m.touchable) {
                var x = 0,
                    minXMove = 30,
                    xMove = 0,
                    xDiff = 0,
                    isMoving = !1;
                this.handleTouchStart = function(evt) {
                    isMoving = !0;
                    x = evt.originalEvent.touches[0].clientX
                };
                this.handleTouchMove = function(evt) {
                    if (isMoving) {
                        xMove = evt.originalEvent.touches[0].clientX;
                        xDiff = x - xMove
                    }
                };
                this.handleTouchEnd = function(evt, leftFunction, rightFunction) {
                    isMoving = !1;
                    if (Math.abs(xDiff) >= minXMove) {
                        if (xDiff > 0) {
                            leftFunction()
                        } else {
                            rightFunction()
                        }
                    }
                    xMove = null;
                    xDiff = null
                }
            }
        }
    }
    Plugin.prototype.Controller = function(model, view) {
        var self = this;
        this.model = model;
        this.view = view;
        model.element.css("maxHeight", model.maxHeight);
        model.checkForCSSTransitions();
        model.images.each(function() {
            var image = $(this)[0];
            var interval = setInterval(function() {
                if (image.complete) {
                    clearInterval(interval);
                    self.model.imagesLoaded++
                }
            }, 300)
        });
        this.waitForImagesToLoad();
        return this
    };
    Plugin.prototype.Controller.prototype = {
        waitForImagesToLoad: function() {
            var self = this;
            var m = this.model;
            var v = this.view;
            if ($(window).width() <= 700) {}
            m.startInterval = setInterval(function() {
                if (m.imagesLoaded == m.length) {
                    clearInterval(m.startInterval);
                    v.setImageSize();
                    v.formatCaptions();
                    m.setCSSTransitions();
                    m.supportsTouch();
                    v.randomize().addNamespace().arrangeSlides().buildNavigation().buildPager();
                    v.setUpJsAnimations();
                    m.built = !0;
                    self.waitToFinishBuild()
                }
            }, 200)
        },
        waitToFinishBuild: function() {
            var self = this;
            var m = this.model;
            m.buildInterval = setInterval(function() {
                if (self.model.built) {
                    clearInterval(m.buildInterval);
                    self.view.addTransitions(self.model.slides, "all").removeLoader();
                    if (self.model.options.carouselType || self.model.options.animation == "swipe") {
                        self.view.addTransitions(self.model.element, "all")
                    };
                    self.view.enableSwipe();
                    self.bindUIActions();
                    self.model.isReady = !0
                }
            }, 200)
        },
        bindUIActions: function() {
            var self = this;
            var m = this.model;
            var v = this.view;
            if (m.options.thumbnails || m.options.navigation) {
                this.model.tabs.on("click", function(e) {
                    e.preventDefault();
                    self.navigationClick(this, e)
                }).eq(0).closest("li").addClass(m.activeClass)
            }
            if (m.options.auto) {
                this.startCycle()
            }
            $(m.prevBtn).on("click", function(e) {
                e.preventDefault();
                self._previous()
            });
            $(m.nextBtn).on("click", function(e) {
                e.preventDefault(e);
                self._next()
            });
            m.thumbnailNavLeft.on("click", function(e) {
                e.preventDefault();
                v.scrollThumbnails(500, "-")
            });
            m.thumbnailNavRight.on("click", function(e) {
                e.preventDefault();
                v.scrollThumbnails(500, "+")
            });
            $(m.slides).on("touchstart", function(e) {
                v.handleTouchStart(e)
            });
            $(m.slides).on("touchmove", function(e) {
                v.handleTouchMove(e)
            });
            $(m.slides).on("touchend", function(e) {
                v.handleTouchEnd(e, function() {
                    return self._next()
                }, function() {
                    return self._previous()
                })
            });
            $(m.thumbnailWrapper).on("touchstart", function(e) {
                v.handleTouchStart(e)
            });
            $(m.thumbnailWrapper).on("touchmove", function(e) {
                v.handleTouchMove(e)
            });
            $(m.thumbnailWrapper).on("touchend", function(e) {
                v.handleTouchEnd(e, function() {
                    return v.scrollThumbnails(500, "+")
                }, function() {
                    return v.scrollThumbnails(500, "-")
                })
            });
            this.initPauseOnHover();
            return this
        },
        _previous: function() {
            var self = this;
            var m = this.model;
            var v = this.view;
            var idx = m.prev();
            if (m.options.navigation || m.options.thumbnails) {
                v.selectTab(idx)
            }
            if (m.isCarousel) {
                v.scrollCarousel(idx, "-")
            } else {
                v.slideTo(idx)
            }
            this.restartCycle();
            m.thumbClicked = !1
        },
        _next: function() {
            var self = this;
            var m = this.model;
            var v = this.view;
            var idx = m.next();
            if (m.options.navigation || m.options.thumbnails) {
                v.selectTab(idx)
            }
            if (m.isCarousel) {
                v.scrollCarousel(idx, "+")
            } else {
                v.slideTo(idx)
            }
            this.restartCycle();
            m.thumbClicked = !1
        },
        navigationClick: function(element, event) {
            event.preventDefault();
            var self = this;
            var m = this.model;
            var v = this.view;
            var currentTabIdx = $(m.tabs).index(element);
            var idx = m.indexData(currentTabIdx).next;
            if (m.index === currentTabIdx || $("." + m.visibleClass).queue('fx').length) {
                return
            }
            m.thumbClicked = !0;
            v.selectTab(currentTabIdx);
            v.slideTo(currentTabIdx);
            this.restartCycle()
        },
        initPauseOnHover: function() {
            var m = this.model;
            var self = this;
            if (m.options.pause) {
                m.element.hover(function() {
                    clearInterval(m.timer)
                }, function() {
                    self.restartCycle()
                })
            }
        },
        startCycle: function() {
            var self = this;
            var m = this.model;
            var v = this.view;
            m.timer = setInterval(function() {
                m.slides.stop(!0, !0);
                var idx = m.next();
                if (m.options.navigation || m.options.thumbnails) {
                    v.selectTab(idx)
                }
                if (m.isCarousel) {
                    v.scrollCarousel(idx, "+")
                } else {
                    v.slideTo(idx)
                }
            }, m.waitTime)
        },
        restartCycle: function() {
            var self = this;
            var m = this.model;
            if (m.options.auto || m.options.thumbnails || m.options.navigation) {
                clearInterval(m.timer);
                this.startCycle()
            }
        },
    }
    $.fn[pluginName] = function(options) {
        var plugin = this.data(dataKey);
        if (plugin instanceof Plugin) {
            if (typeof options !== 'undefined') {
                plugin.init(options)
            }
        } else {
            i++;
            plugin = new Plugin(this, options);
            this.data(dataKey, plugin)
        }
        return plugin
    }
}(jQuery, window, document, 0));
(function($, window, document, i, undefined) {
    "use strict";
    var pluginName = "ezSlideshow",
        defaults = {
            speed: 500,
            namespace: "callbacks"
        },
        animationType = "doCSSAnimation",
        transitionSupport = !0,
        calculateAspectRatioFit = function(srcWidth, srcHeight, maxWidth, maxHeight) {
            var ratio = Math.max(maxWidth / srcWidth, maxHeight / srcHeight);
            return {
                width: srcWidth * ratio,
                height: srcHeight * ratio
            }
        },
        stripWhiteSpace = function(string) {
            return string.replace(/\s/g, '')
        },
        debounce = function(func, wait, immediate) {
            var timeout;
            var args;
            return function() {
                var context = this,
                    args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args)
                }
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(func, wait || 200);
                if (callNow) func.apply(context, args)
            }
        };
    (function() {
        var docBody = document.body || document.documentElement;
        var styles = docBody.style;
        var prop = "transition";
        if (typeof styles[prop] === "string") {
            transitionSupport = !0;
            return !0
        }
        vendor = ["Moz", "Webkit", "Khtml", "O", "ms"];
        prop = prop.charAt(0).toUpperCase() + prop.substr(1);
        var i;
        for (i = 0; i < vendor.length; i++) {
            if (typeof styles[vendor[i] + prop] === "string") {
                transitionSupport = !0;
                return !0
            }
        }
        animationType = "doJSAnimation";
        transitionSupport = !1;
        return !1
    })();

    function Plugin(element, options) {
        this.element = element;
        this.$element = $(element);
        this.wrapper = this.$element.parent().parent();
        this.loader = this.wrapper.find(".loaderwrapper");
        this.options = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.namespaceIdx = this.options.namespace + i;
        this.slideClassPrefix = this.options.namespace + i + "_s";
        this.animationType = "doCSSAnimation";
        this.transitionSupport = transitionSupport;
        this.slides = this.$element.find("li");
        this.links = this.$element.find("a");
        this.length = this.slides.length;
        this.width = this.$element.width();
        this.busy = !1;
        this.index = 0;
        this.imagesLoaded = 0;
        this.fadeTime = this.options.speed;
        this.i = i;
        this.interval = null;
        this.thumbnailSize = 60;
        this.thumbMargin = 0;
        this.currentTabPosition = 0;
        this.offsetLeft = this.$element.offset().left;
        this.offsetRight = this.$element.offset().left + this.width;
        this.thumbsTotalWidth = parseFloat((this.thumbnailSize * this.length) + this.length);
        this.thumbsPerSlide = parseFloat(Math.floor(this.width / (this.thumbnailSize)));
        this.thumbnailWrapperTranslate = 0;
        this.thumbClicked = !1;
        this.scrollAmount = 0;
        this.hasCaption = this.$element.parent().children().find(".ezcaptionwrapper").length;
        this.init()
    }
    $.extend(Plugin.prototype, {
        init: function() {
            var self = this;
            this.prepareForLoad();
            this.randomize();
            this.buildNavigation();
            this.buildPager();
            this.hideEmptyCaptions();
            this.pauseOnHover();
            if (this.options.maxHeight == 0) this.$element.css("maxHeight", "none");
            for (var i = 0; i < this.links.length; i++) {
                var link = $(this.links[i]).addClass("imageWrapper");
                var url = link.attr("href");
                var target = link.attr("target") || "";
                link.replaceWith("<span class='imageWrapper' data-href='" + url + "' data-target='" + target + "'></span>")
            }
            if (this.options.thumbnails) {
                this.loader.css("display", "block");
                this.loadAllImages((function() {
                    self.loader.css("display", "none");
                    self.wrapper.find(".loaderwrapper").remove();
                    self.$element.css("opacity", 1);
                    self[self.animationType](self.slides.eq(0))
                    self.buildThumbnails();
                    self.addTransition(self.slides, "all");
                    self.startCycle();
                    self.wrapper.removeClass("delay")
                }))
            } else {
                this.wrapper.removeClass("delay");
                this.lazyLoadImage(this.slides[0], function(slide) {
                    self.afterImageLazyLoaded(slide);
                    if (self.options.imageSize != "stretchfill") {
                        self.$element.css({
                            height: "auto"
                        })
                    }
                    setTimeout(function() {
                        self.addTransition(self.slides, "all")
                    }, 200)
                });
                this.startCycle()
            }
        },
        prepareForLoad: function() {
            this.$element.css({
                maxHeight: this.options.maxHeight,
                height: this.options.maxHeight
            }).addClass(this.options.namespace);
            //this.slides.find("a").attr("aria-label", "hidden")
        },
        lazyLoadImage: function(slide, callback) {
            var self = this;
            this.busy = !0;
            this.loader.css("display", "block");
            var self = this;
            var src = $(slide).attr("data-lazy");
            var alt = $(slide).attr("data-alt");
            $(slide).removeAttr("data-lazy").removeAttr("data-alt");
            var imageWrapper = $(slide).find(".imageWrapper");
            if (imageWrapper.attr("data-href") != "javascript:void(0)") {
                imageWrapper.replaceWith("<a href='" + imageWrapper.attr("data-href") + "' target='" + imageWrapper.attr("data-target") + "' class='imageWrapper'></a>");
            }
            var image = new Image();
            image.onload = function() {
                $(slide).find(".imageWrapper").append(image);
                if (callback && typeof callback === "function") callback.call(self, slide);
                self.busy = !1;
                self.loader.css("display", "none")
            }
            image.onerror = function() {
                if (callback && typeof callback === "function") callback.call(self, slide);
                self.busy = !1;
                self.loader.css("display", "none")
            }
            image.src = src;
            if (alt) image.alt = alt;
            else $(image).removeAttr("alt");
        },
        loadAllImages: function(callback) {
            var self = this;
            this.loader = $('<div class="loaderwrapper ezloaderwrapper"><div class="ezloaderbkg"><div class="ezCSSloader"></div></div></div>');
            this.imagesLoadedInterval = setInterval(function() {
                if (self.length == self.imagesLoaded) {
                    if (callback && typeof callback === "function") callback();
                    clearInterval(self.imagesLoadedInterval)
                }
            }, 300);
            this.$element.css("opacity", 0);
            this.slides.each(function(i) {
                var slide = $(this);
                var src = slide.attr("data-lazy");
                var alt = slide.attr("data-alt");
                $(slide).removeAttr("data-lazy").removeAttr("data-alt");
                var imageWrapper = $(slide).find(".imageWrapper");
                if (imageWrapper.attr("data-href") != "javascript:void(0)") {
                    imageWrapper.replaceWith("<a href='" + imageWrapper.attr("data-href") + "' target='" + imageWrapper.attr("data-target") + "' class='imageWrapper'></a>");
                }
                var image = new Image();
                image.onload = function() {
                    slide.find(".imageWrapper").append(image);
                    if (self.options.imageSize == "actual") self.makeImageActualSize(slide);
                    if (self.options.imageSize == "stretchfill") self.stretchAndFillImage(slide);
                    self.imagesLoaded++
                }
                image.src = src;
                if (alt) image.alt = alt;
                else $(image).removeAttr("alt");
            })
        },
        afterImageLazyLoaded: function(slide) {
            if (this.options.imageSize == "actual") this.makeImageActualSize(slide);
            if (this.options.imageSize == "stretchfill") this.stretchAndFillImage(slide);
            this[this.animationType](slide)
        },
        slideTo: function(slide, index) {
            if (this.options.thumbnails) {
                this.thumbScrollTracker(index)
            }
            this.index = parseInt(index);
            if (slide.attr("data-lazy")) {
                this.lazyLoadImage(slide, this.afterImageLazyLoaded)
            } else {
                this[this.animationType](slide)
            }
            this.thumbClicked = !1
        },
        updateNavigation: function() {
            if (this.options.navigation) {
                this.goToPager(this.index, !0)
            }
            if (this.options.thumbnails) {
                this.goToThumbnail(this.index, !0)
            }
        },
        doCSSAnimation: function(slide) {
            $(this.slides).removeClass("active ezsVisible").addClass("ezsHidden ez" + this.options.animation);
            $(slide).addClass("active ezsVisible ez" + this.options.animation).removeClass("ezsHidden")
        },
        doJSAnimation: function(slide) {
            console.log(slide)
        },
        addTransition: function(element, transitionProperty, time, easing) {
            var time = time || this.fadeTime;
            var easing = easing || "ease-in-out";
            if (this.transitionSupport) {
                $(element).css({
                    "-webkit-transition": transitionProperty + " " + time + "ms " + easing,
                    "-moz-transition": transitionProperty + " " + time + "ms " + easing,
                    "-o-transition": transitionProperty + " " + time + "ms " + easing,
                    "transition": transitionProperty + " " + time + "ms " + easing
                })
            }
            return this
        },
        buildNavigation: function() {
            this.nextBtn = $('<span class="ion-ios-arrow-right callbacks_nav next"></span>');
            this.prevBtn = $('<span class="ion-ios-arrow-left callbacks_nav prev"></span>');
            this.$element.append(this.nextBtn).append(this.prevBtn);
            this.nextBtn.on("click", this.goToNextSlide.bind(this));
            this.prevBtn.on("click", this.goToPrevSlide.bind(this))
        },
        buildPager: function() {
            if (this.options.navigation) {
                var self = this;
                var markup = [];
                this.navigation = $("<ul class='" + this.options.namespace + "_tabs " + this.namespaceIdx + "_tabs' />");
                this.slides.each(function(i) {
                    var n = i + 1;
                    markup += "<li class='" + self.slideClassPrefix + (i + 1) + "'>" + "<span class='" + self.slideClassPrefix + n + "' aria-label='hidden' data-index='" + (n - 1) + "'>" + "<span class='pager-dot'></span>" + "</span>" + "</li>"
                });
                this.navigation.append(markup);
                this.navigation.find("li").eq(0).find("span > span").addClass("pager-active");
                this.$element.after(this.navigation);
                this.bindPagerClick();
                if (this.hasCaption) {
                    this.navigation.addClass("hascaption")
                }
            }
        },
        buildThumbnails: function() {
            if (this.options.thumbnails) {
                var distIncr = 0;
                var markup = [];
                var self = this;
                this.thumbnailContainer = $("<ul class='thumbnailcontainer'></ul>");
                this.thumbnailWrapper = $("<ul class='" + "thumbnailswrapper " + this.options.namespace + i + "_thumbnails'></ul>").css("height", this.thumbnailSize).appendTo(this.thumbnailContainer);
                this.thumbnailNavLeft = $("<li class='thumbnailsroller thumbnailleft ion-chevron-left'></li>").appendTo(this.thumbnailContainer);
                this.thumbnailNavRight = $("<li class='thumbnailsroller thumbnailright ion-chevron-right'></li>").appendTo(this.thumbnailContainer);
                this.slides.each(function(i) {
                    var n = i + 1;
                    var image = $(this).find("img");
                    var ratio = calculateAspectRatioFit($(image).width(), $(image).height(), self.thumbnailSize, self.thumbnailSize);
                    markup += "<li class='callbacks_thumbnails' style='left:" + distIncr + "px; width:" + self.thumbnailSize + "px;height:" + self.thumbnailSize + "px'>" + "<span class='" + self.slideClassPrefix + n + "' aria-label='hidden' data-index='" + i + "'>" + "<img style='height:" + Math.round(parseFloat(ratio.height)) + "px;width:" + Math.round(parseFloat(ratio.width)) + "px' src='" + image[0].src + "' alt='" + image.attr("alt") + " - Thumbnail" + "'>" + "</span>" + "</li>";
                    distIncr += (self.thumbnailSize + self.thumbMargin)
                });
                this.thumbnailWrapper.append(markup);
                this.tabs = this.thumbnailWrapper.find('span');
                this.tabs.eq(0).closest("li").addClass("callbacks_here");
                this.$element.after(this.thumbnailContainer);
                if (this.length * this.thumbnailSize < this.thumbsPerSlide * this.thumbnailSize) {
                    this.thumbnailNavLeft.css("display", "none");
                    this.thumbnailNavRight.css("display", "none")
                }
                this.thumbnailContainerScroll();
                this.bindThumbnailClick();
                this.thumbnailNavLeft.on("click", function(e) {
                    e.preventDefault();
                    self.scrollThumbnails(500, "-")
                });
                this.thumbnailNavRight.on("click", function(e) {
                    e.preventDefault();
                    self.scrollThumbnails(500, "+")
                })
            }
        },
        bindPagerClick: function() {
            var self = this;
            this.navigation.find("li > span").on("click", function(e) {
                e.preventDefault();
                var index = $(this).attr("data-index");
                self.goToPager(index)
            })
        },
        bindThumbnailClick: function() {
            var self = this;
            this.thumbnailWrapper.find("span").on("click", function(e) {
                e.preventDefault();
                var index = $(this).attr("data-index");
                self.thumbClicked = !0;
                self.goToThumbnail(index)
            })
        },
        goToPager: function(index, manual) {
            var pager = this.navigation.find("li").eq(index);
            var slide = this.slides.eq(index);
            this.navigation.find("span").removeClass("pager-active");
            pager.find("span").addClass("pager-active");
            if (!manual) this.slideTo(slide, index);
            this.restartCycle()
        },
        goToThumbnail: function(index, manual) {
            var thumbnail = this.thumbnailWrapper.find("li").eq(index);
            var slide = this.slides.eq(index);
            this.thumbnailWrapper.find("li").removeClass("callbacks_here");
            thumbnail.closest("li").addClass("callbacks_here");
            if (!manual) this.slideTo(slide, index);
            this.restartCycle()
        },
        goToNextSlide: function() {
            var index = (this.index + 1) < this.length ? this.index + 1 : 0;
            var slide = this.slides.eq(index);
            this.thumbClicked = !1;
            this.slideTo(slide, index);
            this.updateNavigation();
            this.restartCycle()
        },
        goToPrevSlide: function() {
            var index = (this.index === 0) ? this.length - 1 : this.index - 1;
            var slide = this.slides.eq(index);
            this.thumbClicked = !1;
            this.slideTo(slide, index);
            this.updateNavigation();
            this.restartCycle()
        },
        makeImageActualSize: function(slide) {
            this.$element.css("lineHeight", this.options.maxHeight + "px").addClass("actualsize");
            $(slide).addClass("actualsizeli").find("img").addClass("actualsizeimg")
        },
        stretchAndFillImage: function(slide) {
            var maxHeight = parseFloat(this.options.maxHeight) || parseFloat(this.$element.css("maxHeight"));
            var $slide = $(slide);
            var slideWidth = $slide.outerWidth(!0);
            var slideHeight = $slide.outerHeight(!0);
            var image = $slide.find("img");
            if (!image.length) return;
            image.addClass("stretchfill");
            var imageWidth = $(image).width();
            var imageHeight = $(image).height();
            var horizontalOffset = imageWidth - slideWidth;
            var verticalOffset = imageHeight - slideHeight;
            if (horizontalOffset <= 0 || verticalOffset <= 0) {
                var imgClass = (imageWidth / imageHeight > 1) ? 'wide' : 'tall';
                image.removeClass("stretchfill").addClass(imgClass);
                var imgHeight = image[0].clientHeight;
                var imgWidth = image[0].clientWidth;
                var aspect = calculateAspectRatioFit(imgWidth, imgHeight, slideWidth, maxHeight);
                var roundedHeight = Math.round(aspect.height);
                var roundedWidth = Math.round(aspect.width);
                $(image).css("height", roundedHeight + "px");
                $(image).css("width", roundedWidth + "px");
                verticalOffset = aspect.height - maxHeight;
                horizontalOffset = aspect.width - slideWidth
            }
            $(image).css("top", (-verticalOffset / 2));
            $(image).css("left", (-horizontalOffset / 2))
        },
        hideEmptyCaptions: function() {
            var captionWrapper, captionTitleLength, captionDescriptionLength;
            this.slides.each(function(i) {
                captionWrapper = $(this).find(".ezcaptionwrapper");
                captionTitleLength = stripWhiteSpace(captionWrapper.find(".ezcaptiontitle").text()).length;
                captionDescriptionLength = stripWhiteSpace(captionWrapper.find(".ezcaption").text()).length;
                if (captionTitleLength == 0 && captionDescriptionLength == 0) captionWrapper.hide()
            })
        },
        randomize: function() {
            if (this.options.random) {
                this.slides.sort(function() {
                    return (Math.round(Math.random()) - 0.5)
                });
                this.$element.empty().append(this.slides)
            }
            return this
        },
        startCycle: function() {
            var self = this;
            if (this.options.auto) {
                this.interval = setInterval(function() {
                    var index = (self.index + 1) < self.length ? self.index + 1 : 0;
                    var slide = self.slides.eq(index);
                    self.slideTo(slide, index);
                    self.updateNavigation()
                }, this.options.timeout)
            }
        },
        restartCycle: function() {
            clearInterval(this.interval);
            this.startCycle()
        },
        thumbScrollTracker: function(idx) {
            if (this.length >= this.thumbsPerSlide) {
                this.currentTabPosition = $(this.tabs[idx]).offset().left + $(this.tabs[idx]).width();
                if (this.currentTabPosition >= this.offsetRight && this.currentTabPosition <= this.offsetRight + this.thumbnailSize) {
                    this.scrollThumbnails(500, "+")
                } else if (this.index > idx && idx != 0 && this.index != this.length - 1) {
                    if (this.currentTabPosition <= this.offsetLeft && this.currentTabPosition >= this.offsetLeft - this.thumbnailSize) {
                        this.scrollThumbnails(500, "-")
                    }
                } else if (this.index == this.length - 1 && idx == 0 && !this.thumbClicked) {
                    this.scrollThumbnails(500, "+")
                } else if (this.index == 0 && idx == this.length - 1 && !this.thumbClicked) {
                    this.scrollThumbnails(500, "-")
                }
            }
        },
        thumbnailContainerScroll: function() {
            var currentLeft = this.thumbnailWrapper.position().left;
            this.scrollAmount = parseFloat($(this.tabs[Math.round(this.thumbsPerSlide / 2)]).parent().css("left"));
            this.lastScrollAmount = this.thumbsTotalWidth + this.length - this.slides.width();
            var atEnd = 0;
            this.scrollThumbnails = function(speed, direction) {
                if (direction == "+") {
                    currentLeft -= this.scrollAmount;
                    if (Math.abs(currentLeft) > this.lastScrollAmount) {
                        currentLeft = currentLeft + this.scrollAmount;
                        var difference = currentLeft + this.lastScrollAmount;
                        currentLeft -= difference
                    }
                    if (Math.abs(currentLeft) == this.lastScrollAmount) {
                        if (atEnd == 1) {
                            currentLeft = 0;
                            atEnd = 0
                        } else atEnd += 1
                    }
                    if (this.transitionSupport) {
                        this.thumbnailWrapper.css({
                            transform: "translateX(" + currentLeft + "px)",
                            transition: "all " + speed + "ms ease"
                        })
                    } else {
                        this.thumbnailWrapper.animate({
                            "left": currentLeft + "px"
                        }, speed)
                    }
                }
                if (direction == "-") {
                    if (currentLeft == 0) {
                        currentLeft -= this.lastScrollAmount;
                        atEnd = 1
                    } else currentLeft += this.scrollAmount;
                    if (currentLeft > 0) currentLeft = 0;
                    if (this.transitionSupport) {
                        this.thumbnailWrapper.css({
                            transform: "translateX(" + currentLeft + "px)",
                            transition: "all " + speed + "ms ease"
                        })
                    } else {
                        this.thumbnailWrapper.animate({
                            "left": currentLeft + "px"
                        }, speed)
                    }
                }
            }
        },
        pauseOnHover: function() {
            var self = this;
            this.$element.hover(function() {
                clearTimeout(self.interval)
            }, function() {
                self.restartCycle()
            })
        }
    });
    $.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, "plugin_" + pluginName)) {
                i++;
                $.data(this, "plugin_" + pluginName, new Plugin(this, options))
            }
        })
    }
})(jQuery, window, document, 0)
