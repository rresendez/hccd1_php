(function() {
    var ezAlert = function() {
        var settings = {
            "currentLocation": window.location.pathname,
            "externalHost": false, //set url of host if the alert is coming from a different website
            "pageURL": "http://" + window.location.hostname + "/page/emergency.alert", //location of the page that has the alert text
            "whereToInsert": "topnavwrapper", //Where the html will be inserted, you'll want it to be before the first element for the website
            "alertHTML": $('<div id ="emergency-alert"></div>'),
            "hasAlert": $("body").hasClass("show-alert"),
            "timeShown": 2000, //the amount of time the alert is show before disappearing
            "hasTimer": false, //enables a time limit for the alert
            "allPages": true //shows the alert on all templates that have the javascript file
        };
        var hostURL = settings.externalHost ? "http://" + settings.externalHost + "/page/emergency.alert" : "http://" + window.location.hostname + "/page/emergency.alert";
        return {
            init: function() {
                var self = this;
                if (settings.currentLocation != "/design/Editor.aspx") {
                    self.getHTML();
                }
            },
            getHTML: function() {
                var self = this;
                //Checks which pages to insert alert on based on the body class
                if (settings.allPages === false) {
                    $(settings.alertHTML).insertBefore("body.show-alert #" + settings.whereToInsert);
                } else {
                    $(settings.alertHTML).insertBefore("body #" + settings.whereToInsert);
                }
                //Loads the content
                $(settings.alertHTML).load(settings.pageURL + " #emergencywrapper", function(responseTxt, statusTxt, jqXHR) {
                    var me = this;
                    if (statusTxt == "success") {
                        if ($(me).find(".eztext_area")[0].innerText == "") {
                            $(me).remove();
                        } else {
                            $(me).find(".widgetitem").addClass("innercontainer");
                            self.animateHTML(settings.alertHTML);
                            self.bindEvents();
                            if (settings.hasTimer) {
                                self.setTimer();
                            }
                        }
                    }
                });
            },
            animateHTML: function(element) {
                element = $(element);
                var theheight = $("#emergencywrapper").height().toString();
                $(element).css("max-height", 0 + "px");
                setTimeout(function() {
                    $(element).css({
                        "max-height": theheight + "px",
                        "opacity": 1
                    })
                }, 0);
            },
            removeAlert: function() {
                element = $("#emergency-alert");
                $(element).css({
                    "max-height": 0,
                    "opacity": 0
                });
                setTimeout(function() {
                    $(element).remove();
                }, 2000);
            },
            setTimer: function() {
                var self = this;
                setTimeout(function() {
                    if (settings.alertHTML) {
                        $(settings.alertHTML).remove();
                    }
                }, settings.timeShown);
            },
            bindEvents: function() {
                var self = this;
                $("#emergencyin .ion-ios-close-empty").on("click", self.removeAlert);
            }
        }
    }
    var startAlert = new ezAlert();
    startAlert.init();
})();
