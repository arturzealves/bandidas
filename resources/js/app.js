import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import GoogleMaps from './components/GoogleMaps/GoogleMaps';
window.GoogleMaps = new GoogleMaps();
window.initMap = function () {
    window.GoogleMaps.initMap();
};

import './websockets/listeners';

import getCookie from './cookies/getCookie';
window.getCookie = getCookie;
