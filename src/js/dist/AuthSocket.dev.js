"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _get(target, property, receiver) { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get; } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(receiver); } return desc.value; }; } return _get(target, property, receiver || target); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function set(target, property, value, receiver) { if (typeof Reflect !== "undefined" && Reflect.set) { set = Reflect.set; } else { set = function set(target, property, value, receiver) { var base = _superPropBase(target, property); var desc; if (base) { desc = Object.getOwnPropertyDescriptor(base, property); if (desc.set) { desc.set.call(receiver, value); return true; } else if (!desc.writable) { return false; } } desc = Object.getOwnPropertyDescriptor(receiver, property); if (desc) { if (!desc.writable) { return false; } desc.value = value; Object.defineProperty(receiver, property, desc); } else { _defineProperty(receiver, property, value); } return true; }; } return set(target, property, value, receiver); }

function _set(target, property, value, receiver, isStrict) { var s = set(target, property, value, receiver || target); if (!s && isStrict) { throw new Error('failed to set property'); } return value; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }

function _wrapNativeSuper(Class) { var _cache = typeof Map === "function" ? new Map() : undefined; _wrapNativeSuper = function _wrapNativeSuper(Class) { if (Class === null || !_isNativeFunction(Class)) return Class; if (typeof Class !== "function") { throw new TypeError("Super expression must either be null or a function"); } if (typeof _cache !== "undefined") { if (_cache.has(Class)) return _cache.get(Class); _cache.set(Class, Wrapper); } function Wrapper() { return _construct(Class, arguments, _getPrototypeOf(this).constructor); } Wrapper.prototype = Object.create(Class.prototype, { constructor: { value: Wrapper, enumerable: false, writable: true, configurable: true } }); return _setPrototypeOf(Wrapper, Class); }; return _wrapNativeSuper(Class); }

function isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _construct(Parent, args, Class) { if (isNativeReflectConstruct()) { _construct = Reflect.construct; } else { _construct = function _construct(Parent, args, Class) { var a = [null]; a.push.apply(a, args); var Constructor = Function.bind.apply(Parent, a); var instance = new Constructor(); if (Class) _setPrototypeOf(instance, Class.prototype); return instance; }; } return _construct.apply(null, arguments); }

function _isNativeFunction(fn) { return Function.toString.call(fn).indexOf("[native code]") !== -1; }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

var AuthSocket =
/*#__PURE__*/
function (_WebSocket) {
  _inherits(AuthSocket, _WebSocket);

  function AuthSocket(game_id) {
    var _getPrototypeOf2;

    var _this;

    _classCallCheck(this, AuthSocket);

    for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
      args[_key - 1] = arguments[_key];
    }

    _this = _possibleConstructorReturn(this, (_getPrototypeOf2 = _getPrototypeOf(AuthSocket)).call.apply(_getPrototypeOf2, [this].concat(args))); //не уверен что это должно быть здесь, но шо поделать  _(-_-)-/

    _this.uid = game_id;
    _this._data = {};
    _this._authInfo = null;
    _this.onmessageAuth = null;
    _this._onmessageCallbacks = [];
    _this.onbeforeSend = null;
    if (_this.uid) _this.onopen = function () {
      this.send({
        uid: this.uid
      });
    };

    _set(_getPrototypeOf(AuthSocket.prototype), "onmessage", function (e) {
      var parsedData = JSON.parse(e.data);

      if (_typeof(parsedData) === "object") {
        // if (parsedData.hasOwnProperty("status code")) {
        //     //401 не удалось авторизоваться
        //     if(parsedData["status code"] === 401) {
        //       this.authInfo = this.fetchAuthInfoAsync();
        //         //пробуем снова отправить ту же информацию, но уже со свежими пользовательскими данными
        //       this.send(data);
        //     }
        // }
        // this.onmessageAuth(e, parsedData);
        _this._onmessageCallbacks.forEach(function (callback) {
          callback(e, parsedData);
        });
      }
    }, _assertThisInitialized(_this), true);

    return _this;
  }

  _createClass(AuthSocket, [{
    key: "addMessageCallback",
    value: function addMessageCallback(callback) {
      this._onmessageCallbacks.push(callback);
    }
  }, {
    key: "getauthInfo",
    value: function getauthInfo() {
      return regeneratorRuntime.async(function getauthInfo$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              if (this._authInfo) {
                _context.next = 4;
                break;
              }

              _context.next = 3;
              return regeneratorRuntime.awrap(this.fetchAuthInfoAsync());

            case 3:
              this._authInfo = _context.sent;

            case 4:
              return _context.abrupt("return", this._authInfo);

            case 5:
            case "end":
              return _context.stop();
          }
        }
      }, null, this);
    }
  }, {
    key: "isNotGuest",
    value: function isNotGuest() {
      var authInfo;
      return regeneratorRuntime.async(function isNotGuest$(_context2) {
        while (1) {
          switch (_context2.prev = _context2.next) {
            case 0:
              _context2.next = 2;
              return regeneratorRuntime.awrap(this.getauthInfo());

            case 2:
              authInfo = _context2.sent;

              if (!(authInfo.id && authInfo.authKey)) {
                _context2.next = 5;
                break;
              }

              return _context2.abrupt("return", true);

            case 5:
              return _context2.abrupt("return");

            case 6:
            case "end":
              return _context2.stop();
          }
        }
      }, null, this);
    }
  }, {
    key: "send",
    value: function send() {
      var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
      var system_message = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

      if (_typeof(data) === "object") {
        this._data = data;

        if (system_message) {
          this._data.systemMessage = {
            from: false,
            message: system_message,
            time: new Date().toLocaleTimeString()
          };
        } // this._data.authInfo = await this.getauthInfo();


        if (this.uid) this._data.uid = this.uid; // if (await this.isNotGuest()) {

        if (this.onbeforeSend && typeof this.onbeforeSend !== "function") throw new Error("onbeforeSend is not a function");
        if (this.onbeforeSend && typeof this.onbeforeSend === "function") this.onbeforeSend({
          data: this._data
        });
        var stringify = JSON.stringify(this._data);

        _get(_getPrototypeOf(AuthSocket.prototype), "send", this).call(this, stringify); // }
        // else window.location.href = "/site/login";

      } else throw new Error("type of sended data is " + _typeof(data) + " need Object");
    }
  }, {
    key: "fetchAuthInfoAsync",
    value: function fetchAuthInfoAsync() {
      var response;
      return regeneratorRuntime.async(function fetchAuthInfoAsync$(_context3) {
        while (1) {
          switch (_context3.prev = _context3.next) {
            case 0:
              _context3.prev = 0;
              _context3.next = 3;
              return regeneratorRuntime.awrap(fetch("/socket/auth", {
                method: 'GET',
                credentials: 'include'
              }));

            case 3:
              response = _context3.sent;
              console.warn("getUsetInfo");
              _context3.next = 7;
              return regeneratorRuntime.awrap(response.json());

            case 7:
              return _context3.abrupt("return", _context3.sent);

            case 10:
              _context3.prev = 10;
              _context3.t0 = _context3["catch"](0);
              throw new Error(_context3.t0);

            case 13:
            case "end":
              return _context3.stop();
          }
        }
      }, null, null, [[0, 10]]);
    }
  }, {
    key: "authInfo",
    set: function set(value) {
      this._authInfo = value;
    }
  }]);

  return AuthSocket;
}(_wrapNativeSuper(WebSocket));

exports["default"] = AuthSocket;