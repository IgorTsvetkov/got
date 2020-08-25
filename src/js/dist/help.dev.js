"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.systemMessage = systemMessage;

function systemMessage(message) {
  return {
    action: "chat",
    data: {
      from: false,
      message: this.message,
      time: new Date().toLocaleTimeString()
    }
  };
}