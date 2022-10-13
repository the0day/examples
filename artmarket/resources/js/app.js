require('./bootstrap');
require('alpinejs');
require('./notification');
require('./form')
require('./order')
require('./chat')
const {forEach} = require("lodash");

window.showModal = function (el) {
    document.getElementById(el).__x.$data.open = true;
}
window.hideModal = function (el) {
    document.getElementById(el).__x.$data.open = false;
}
