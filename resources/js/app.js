import './bootstrap';
import Alpine from 'alpinejs';
import GoogleMaps from './components/GoogleMaps/GoogleMaps';

window.GoogleMaps = new GoogleMaps();
window.initMap = function () {
    window.GoogleMaps.initMap();
};

window.Alpine = Alpine;

Alpine.start();
