"use strict";

var _axios = _interopRequireDefault(require("axios"));

var _AuthSocket = _interopRequireDefault(require("./js/AuthSocket"));

var _HelloWorldComponent = _interopRequireDefault(require("./components/HelloWorldComponent.vue"));

var _MapComponent = _interopRequireDefault(require("./components/MapComponent.vue"));

var _HeroPickerComponent = _interopRequireDefault(require("./components/HeroPickerComponent.vue"));

var _HeroPickerWrapper = _interopRequireDefault(require("./components/HeroPickerWrapper.vue"));

var _StartGameButton = _interopRequireDefault(require("./components/StartGameButton.vue"));

var _FormAjaxWrapper = _interopRequireDefault(require("./components/FormAjaxWrapper.vue"));

var _ButtonLoad = _interopRequireDefault(require("./components/ButtonLoad.vue"));

var _GameTable = _interopRequireDefault(require("./components/GameTable.vue"));

var _PropertyCard = _interopRequireDefault(require("./components/PropertyCard.vue"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

window.Vue = require('vue');
Vue.prototype.$axios = _axios["default"];
if (window.yii) Vue.prototype.$axios.defaults.headers.common["X-CSRF-TOKEN"] = window.yii.getCsrfToken();
Vue.prototype.$socketStorage = new Map();

Vue.prototype.$socketGet = function (game_id, action) {
  if (!this.$socketStorage.has(action)) this.$socketStorage.set(action, new _AuthSocket["default"](game_id, "ws://127.0.0.1:8989/" + action));
  return this.socket = this.$socketStorage.get(action);
};

var app = new Vue({
  el: '#app',
  components: {
    HelloWorldComponent: _HelloWorldComponent["default"],
    MapComponent: _MapComponent["default"],
    HeroPickerComponent: _HeroPickerComponent["default"],
    HeroPickerWrapper: _HeroPickerWrapper["default"],
    StartGameButton: _StartGameButton["default"],
    FormAjaxWrapper: _FormAjaxWrapper["default"],
    ButtonLoad: _ButtonLoad["default"],
    GameTable: _GameTable["default"],
    PropertyCard: _PropertyCard["default"] // FormAjax

  }
});