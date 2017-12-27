(function() {
    if (document.addEventListener) {
        document.addEventListener("DOMContentLoaded", runLoader);
    }
    else if(document.attachEvent) {
        document.attachEvent("onreadystatechange", runLoader);
    }
    else{
        console.log("Couldn't attatch event");
    }

    function runLoader() {
        var loaders = document.querySelectorAll(".iframewrapper");
        var ezTaskLoader = function(element) {
            var isOlderBrowser = document.getElementsByClassName('t-ie9')[0] || document.getElementsByClassName('no-cssanimations')[0] || document.getElementsByClassName('no-csstransitions')[0] || false;
            var iframe = element.getElementsByTagName("iframe")[0];
            var loaderwrapper = element.getElementsByClassName("ezloaderwrapper")[0];
            var height, type;
            if(iframe){
                if (iframe.id.indexOf("Database") > -1) {
                    type = "Default";
                }
                if (iframe.id.indexOf("rss") > -1) {
                    type = "RSS";
                }
                if (iframe.id.indexOf("contentframe") > -1) {
                    type = "ProjectManager";
                }
                if (iframe.id.indexOf("frmCal") > -1) {
                    type = "Calendar";
                }
            }
            else{
                loaderwrapper.style.display = "none";
            }
            
            return {
                init: function() {
                    var self = this;
                    if (!self.browserCheck()) {
                        switch(type){
                            case "Default":
                                self.DefaultLoader();
                                break;
                            case "RSS":
                                self.DefaultLoader();
                                break;
                            case "ProjectManager":
                                self.ProjectManagerLoader();
                                break;
                            case "Calendar":
                                self.CalendarLoader();
                                break;
                        }
                        if(iframe){
                            iframe.addEventListener("load", self.removeLoaders);
                        }
                    } else {
                        if(iframe){
                            iframe.addEventListener("load", self.removeLoaderOldBrowser);
                        }
                    }
                },
                DefaultLoader: function() {
                },
                ProjectManagerLoader: function() {
                    var topOffset = element.getBoundingClientRect().top - 40;
                    var windowHeight = window.innerHeight;
                    height = windowHeight - topOffset - 150;
                    loaderwrapper.style.height = height + "px";
                },
                CalendarLoader: function() {
                    var topOffset = element.getBoundingClientRect().top;
                    var windowHeight = window.innerHeight;
                    height = windowHeight - topOffset-100;
                    element.style.height = height + "px";
                },
                removeLoaders: function() {
                    element.classList.remove("init-load");
                    element.classList.add("finished--load");
                    element.classList.remove("delay");
                    loaderwrapper.classList.add("remove--loader");
                },
                browserCheck: function() {
                    return isOlderBrowser;
                },
                removeLoaderOldBrowser: function() {
                    iframe.style.opacity = 1;
                    loaderwrapper.style.display = "none";
                }
            }
        };
        if(loaders.length){
            for (var i = 0; i < loaders.length; i++) {
                var createLoader = new ezTaskLoader(loaders[i]);
                createLoader.init();
            }
        }
    }
})();
