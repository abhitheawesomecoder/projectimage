window.App = function () {
  var App = {};
  var controllers = {};
  var config = {};

  App.controller = function (name, ctrl) {
    controllers[name] = ctrl;
  };

  App.set = function (key, value) {
    config[key] = value;
  };

  App.get = function (key, defVal) {
    return config[key] || defVal;
  };

  $(function () {
    for (var key in controllers) {
      if (controllers.hasOwnProperty(key)) {
        var elem = document.getElementById(key);
        if (elem) {
          controllers[key].call(null, elem);
        }
      }
    }
  });

  return App;
}(window.App);