;
(function($, window, document, i, undefined) {
  "use strict";
  var pluginName = "ezDialogBox",
    defaults = {
      autoOpen: false,
      height: 200,
      width: 350,
      modal: true,
      buttons: {}
    },
    dataKey = "plugin_" + pluginName;;

  function Plugin(element, options) {
    this.element = $(element);
    this.index = i;
    this.id = "ez-dialog-" + this.index;
    this.title = this.element.attr("title");
    this.dialogBox = '<div id="' + this.id + '" class="ez-dialog"></div>'
    this.buttonPane = '<div class="ez-dialog-buttonpane"><div class="ez-dialog-buttonpanein"></div></div>';
    this.titleBar = '<div class="ez-dialog-titlebar"><div class="ez-dialog-title"></div><div class="ez-dialog-titlebar-close"></div></div>';
    this.buttonHTML = '<button class="ez-dialog-button" type="button" role="button"><span class="ez-dialog-button-text"></span></button>';
    this.timerHTML = '<div class="ez-dialog-timer"><div class="ez-dialog-timerin">Time Remaining: <span class="ez-dialog-timer-countdown"></span></div></div>',
      this.settings = $.extend({}, defaults, options);
    this._defaults = defaults;
    this._name = pluginName;
    this.init();
    return this;
  }

  // Avoid Plugin.prototype conflicts
  $.extend(Plugin.prototype, {
    init: function() {
      this.createDialog();
    },
    createDialog: function() {
      var self = this;
      var dialogBox = $(this.dialogBox);
      var titleBar = this.createTitleBar();
      var buttonPane = this.createButtonPane();
      var timer = this.createTimer();
      this.element.show().append(timer);
      this.element.wrap(dialogBox);
      this.element.find(".eztime").text(this.settings.countdown);
      dialogBox = $("#" + this.id).css({
        width: this.settings.width,
        height: this.settings.height,
        display: "none"
      });
      dialogBox.prepend(titleBar);
      dialogBox.append(buttonPane);
      this.dialog = dialogBox;
      if (this.settings.modal) {
        this.dialog.wrap("<div class='ez-dialog-modal'></div>")
      }
      this.bindCloseButton();
    },
    createTitleBar: function() {
      var titleBar = $(this.titleBar);
      titleBar.find(".ez-dialog-title").text(this.title);
      return titleBar;
    },
    createButtonPane: function() {
      var buttons = $(this.buttonPane);
      for (var button in this.settings.buttons) {
        if (this.settings.buttons.hasOwnProperty(button)) {
          var buttonToAppend = this.createButton(button, this.settings.buttons[button]);
          buttons.find(".ez-dialog-buttonpanein").append(buttonToAppend);
        }
      }
      return buttons;
    },
    createButton: function(name, func) {
      var button = $(this.buttonHTML);
      button.find(".ez-dialog-button-text").text(name);
      button.on("click", func.bind(this));
      return button;
    },
    setElementHeight: function() {
      var elHeight = this.settings.height - this.dialog.find(".ez-dialog-titlebar").outerHeight(true) - this.dialog.find(".ez-dialog-buttonpane").outerHeight(true);
      this.element.css("height", elHeight);
    },
    close: function() {
      this.dialog.hide();
      this.dialog.closest(".ez-dialog-modal").hide();
      this.stopTimer();
    },
    open: function() {
      this.startTimer();
      this.setTimer();
      this.dialog.closest(".ez-dialog-modal").show();
      this.dialog.show();
    },
    bindCloseButton: function() {
      this.dialog.find(".ez-dialog-titlebar-close").on("click", this.close.bind(this));
      this.dialog.find(".ez-dialog-titlebar-close").on("click", this.closeCallback.bind(this));
    },
    closeCallback: function() {
      if (typeof this.settings.closeButtonCalllback == "function") {
        this.settings.closeButtonCalllback();
      }
    },
    createTimer: function() {
      if (!this.settings.countdown) return;
      var timer = $(this.timerHTML);
      return timer;
    },
    startTimer: function() {
      var self = this;
      self.countdown = self.settings.countdown * 60;
      self.interval = setInterval(function() {
        self.countdown--;
        self.setTimer();
        if (self.countdown < 0) {
          clearInterval(self.interval);
          if (typeof self.settings.countdownCallback == "function") {
            self.settings.countdownCallback();
          }
          self.close();
        }
      }, 1000);
    },
    setTimer: function() {
      var self = this;
      var hours = Math.floor(this.countdown / 3600);
      var minutes = Math.floor((this.countdown % 3600) / 60);
      var seconds = Math.floor((this.countdown % 60));
      if (hours < 10) hours = "0" + hours;
      if (minutes < 10) minutes = "0" + minutes;
      if (seconds < 10) seconds = "0" + seconds;
      var timeString = hours + ":" + minutes + ":" + seconds;
      if (hours == 0) {
        timeString = minutes + ":" + seconds;
      }
      this.dialog.find(".ez-dialog-timer-countdown").text(timeString);
    },
    stopTimer: function() {
      clearInterval(this.interval);
    }
  });
  $.fn[pluginName] = function(options) {
    var plugin = this.data(dataKey);
    // has plugin instantiated ?
    if (plugin instanceof Plugin) {
      // if have options arguments, call plugin.init() again
      if (typeof options !== 'undefined') {
        plugin.init(options);
      }
    } else {
      i++;
      plugin = new Plugin(this, options);
      this.data(dataKey, plugin);
    }
    return plugin;
  };

})(jQuery, window, document, 0);
