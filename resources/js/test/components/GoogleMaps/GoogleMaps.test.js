import createGoogleMapsMock from '../../mocks/GoogleMaps/createGoogleMapsMock';
import createLivewireMock from '../../mocks/createLivewireMock.js';
require('../../../components/GoogleMaps/GoogleMaps.js');

describe('GoogleMaps.js structure', () => {
    it('window.initMap should be a function', () => {
        expect(typeof window.initMap).toBe('function');
    });
    it('window.GoogleMaps should be an object', () => {
        expect(typeof window.GoogleMaps).toBe('object');
    });
    it('should have all its properties initialized', () => {
        expect(typeof window.GoogleMaps.options).toBe('object');
        expect(Array.isArray(window.GoogleMaps.options.drawingModes)).toBe(
            true
        );
        expect(typeof window.GoogleMaps.mapProperties).toBe('object');
        expect(typeof window.GoogleMaps.mapProperties.zoom).toBe('number');
        expect(typeof window.GoogleMaps.mapProperties.center).toBe('object');
        expect(typeof window.GoogleMaps.mapProperties.mapTypeId).toBe('string');
        expect(typeof window.GoogleMaps.mapProperties.mapTypeControl).toBe(
            'boolean'
        );
        expect(typeof window.GoogleMaps.mapProperties.fullscreenControl).toBe(
            'boolean'
        );
        expect(typeof window.GoogleMaps.mapProperties.rotateControl).toBe(
            'boolean'
        );
        expect(typeof window.GoogleMaps.mapProperties.scaleControl).toBe(
            'boolean'
        );
        expect(typeof window.GoogleMaps.mapProperties.zoomControl).toBe(
            'boolean'
        );
        expect(typeof window.GoogleMaps.defaultCircleOptions).toBe('object');
        expect(typeof window.GoogleMaps.defaultCircleOptions.fillColor).toBe(
            'string'
        );
        expect(typeof window.GoogleMaps.defaultCircleOptions.strokeColor).toBe(
            'string'
        );
        expect(
            typeof window.GoogleMaps.defaultCircleOptions.strokeOpacity
        ).toBe('number');
        expect(typeof window.GoogleMaps.defaultCircleOptions.fillOpacity).toBe(
            'number'
        );
        expect(typeof window.GoogleMaps.defaultCircleOptions.strokeWeight).toBe(
            'number'
        );
        expect(typeof window.GoogleMaps.defaultCircleOptions.clickable).toBe(
            'boolean'
        );
        expect(typeof window.GoogleMaps.defaultCircleOptions.editable).toBe(
            'boolean'
        );
        expect(typeof window.GoogleMaps.defaultCircleOptions.zIndex).toBe(
            'number'
        );
        expect(typeof window.GoogleMaps.selectedCircleOptions).toBe('object');
        expect(typeof window.GoogleMaps.selectedCircleOptions.fillOpacity).toBe(
            'number'
        );
        expect(typeof window.GoogleMaps.selectedCircleOptions.editable).toBe(
            'boolean'
        );
        expect(Array.isArray(window.GoogleMaps.circles)).toBe(true);
        expect(Array.isArray(window.GoogleMaps.markers)).toBe(true);
        expect(Array.isArray(window.GoogleMaps.googleMapsUserCircles)).toBe(
            true
        );
        expect(Array.isArray(window.GoogleMaps.googleMapsPromoterMarkers)).toBe(
            true
        );
        expect(typeof window.GoogleMaps.map).toBe('object');
        expect(window.GoogleMaps.selectedIndex === null).toBe(true);
        expect(window.GoogleMaps.selectedCircle === null).toBe(true);
        expect(window.GoogleMaps.user === null).toBe(true);
        expect(window.GoogleMaps.drawingManager === null).toBe(true);
    });
    it('should have all its functions defined', () => {
        expect(typeof window.GoogleMaps.addCircle).toBe('function');
        expect(typeof window.GoogleMaps.addMarker).toBe('function');
        expect(typeof window.GoogleMaps.bindEventListeners).toBe('function');
        expect(typeof window.GoogleMaps.deleteCircle).toBe('function');
        expect(typeof window.GoogleMaps.deselectCircle).toBe('function');
        expect(typeof window.GoogleMaps.drawCircle).toBe('function');
        expect(typeof window.GoogleMaps.drawCircles).toBe('function');
        expect(typeof window.GoogleMaps.drawMarker).toBe('function');
        expect(typeof window.GoogleMaps.drawMarkers).toBe('function');
        expect(typeof window.GoogleMaps.centerOnUserLocation).toBe('function');
        expect(typeof window.GoogleMaps.createDrawingManager).toBe('function');
        expect(typeof window.GoogleMaps.initMap).toBe('function');
        expect(typeof window.GoogleMaps.focus).toBe('function');
        expect(typeof window.GoogleMaps.updateCircle).toBe('function');
        expect(typeof window.GoogleMaps.saveCircle).toBe('function');
        expect(typeof window.GoogleMaps.saveMarker).toBe('function');
        expect(typeof window.GoogleMaps.saveUserLocation).toBe('function');
        expect(typeof window.GoogleMaps.setDrawingModes).toBe('function');
        expect(typeof window.GoogleMaps.setUser).toBe('function');
        expect(typeof window.GoogleMaps.setUserLocation).toBe('function');
        expect(typeof window.GoogleMaps.selectCircleAtIndex).toBe('function');
        expect(typeof window.GoogleMaps.unfocus).toBe('function');
    });
});

describe('GoogleMaps.js features', () => {
    let googleMaps;
    let sideDrawer;
    let idInput;
    let latitudeInput;
    let longitudeInput;
    let radiusInput;
    let submitButton;
    let updateButton;

    const clearDOM = () => {
        document.body.innerHTML = '';
    };

    const buildDOM = () => {
        clearDOM();

        sideDrawer = document.createElement('div');
        sideDrawer.setAttribute('id', 'sideDrawer');

        idInput = document.createElement('input');
        idInput.setAttribute('id', 'circle_id');

        latitudeInput = document.createElement('input');
        latitudeInput.setAttribute('id', 'latitude');
        latitudeInput.setAttribute('name', 'latitude');

        longitudeInput = document.createElement('input');
        longitudeInput.setAttribute('id', 'longitude');
        longitudeInput.setAttribute('name', 'longitude');

        radiusInput = document.createElement('input');
        radiusInput.setAttribute('id', 'radius');

        submitButton = document.createElement('button');
        submitButton.setAttribute('id', 'google-maps-user-circle-form-submit');
        
        updateButton = document.createElement('button');
        updateButton.setAttribute('id', 'google-maps-user-circle-form-update');

        sideDrawer.append(idInput, latitudeInput, longitudeInput, radiusInput, submitButton, updateButton);
        document.body.append(sideDrawer);
    };

    beforeEach(() => {
        googleMaps = createGoogleMapsMock(['drawing', 'event']);

        global.window.google = {
            maps: googleMaps,
        };

        global.Livewire = createLivewireMock();

        buildDOM();
    });

    it('should have initMap on window', () => {
        window.initMap();

        expect(googleMaps.Map).toHaveBeenCalledTimes(1);
        expect(googleMaps.Map.mock.instances.length).toBe(1);
    });

    it('should execute initMap successfully', () => {
        var mapDiv = document.createElement('div');
        mapDiv.setAttribute('id', 'map');
        document.body.innerHTML = mapDiv.outerHTML;

        window.GoogleMaps.initMap();

        expect(googleMaps.Map).toHaveBeenCalledTimes(1);
        expect(googleMaps.Map.mock.instances.length).toBe(1);
        expect(googleMaps.Map).toHaveBeenLastCalledWith(mapDiv, window.GoogleMaps.mapProperties);
        expect(window.GoogleMaps.map).toBeDefined();

        expect(window.GoogleMaps.map.controls[googleMaps.ControlPosition.TOP_RIGHT][0]).toBeInstanceOf(HTMLButtonElement);

        expect(googleMaps.drawing.DrawingManager).toHaveBeenCalledTimes(1);
        expect(googleMaps.drawing.DrawingManager.mock.instances.length).toBe(1);
    });

    it('should add a circle', () => {
        window.GoogleMaps.initMap();
        expect(window.GoogleMaps.circles.length).toBe(0);

        window.GoogleMaps.addCircle({index: 2});
        expect(window.GoogleMaps.circles.length).toBe(1);

        window.GoogleMaps.addCircle({});
        expect(window.GoogleMaps.circles.length).toBe(2);
        expect(window.GoogleMaps.circles[1].index).toBe(1);
    });

    it('should add a marker', () => {
        window.GoogleMaps.initMap();
        expect(window.GoogleMaps.markers.length).toBe(0);

        window.GoogleMaps.addMarker({index: 2});
        expect(window.GoogleMaps.markers.length).toBe(1);

        window.GoogleMaps.addMarker({});
        expect(window.GoogleMaps.markers.length).toBe(2);
        expect(window.GoogleMaps.markers[1].index).toBe(1);
    });

    it('should bind event listeners', () => {
        let circle = {};
        window.GoogleMaps.bindEventListeners(circle);

        // console.log(window.GoogleMaps.event);
        // expect(window.GoogleMaps.event).toBe(
    });
    
    it('should execute after a new circle is created', () => {
        let latitude = 12.34;
        let longitude = 24.41;
        let circle = {
            index: 0,
            center: {
                lat: () => latitude,
                lng: () => longitude,
            },
            radius: 452,
        };

        window.GoogleMaps.circles = [{
            id: 1,
            index: 0,
            setOptions: jest.fn()
        }]

        window.GoogleMaps.drawingManager = {
            setDrawingMode: jest.fn()
        };

        expect(window.GoogleMaps.circles.length).toBe(1);
        
        window.GoogleMaps.circleComplete(circle);

        expect(window.GoogleMaps.circles.length).toBe(2);
    });

    it('should delete a circle', () => {
        let circle = { 
            index: 0, 
            id: 3,
            setMap: jest.fn(),
        };

        window.GoogleMaps.circles = [
            { index: 0, id: 3, setMap: jest.fn() },
            { index: 1, id: 2, setMap: jest.fn() },
        ];

        expect(window.GoogleMaps.circles.length).toBe(2);
        window.GoogleMaps.deleteCircle(circle);
        // expect(window.GoogleMaps.circles.length).toBe(1);
    });

    it('should deselect a circle', () => {
        window.GoogleMaps.selectedCircle = null;
        expect(window.GoogleMaps.deselectCircle() === null).toBe(true);
        
        window.GoogleMaps.selectedCircle = {
            id: 1,
            index: 0,
            setOptions: jest.fn()
        };

        sideDrawer.setAttribute('class', 'active');

        document.body.innerHTML = sideDrawer.outerHTML;

        window.GoogleMaps.selectedIndex = 1;

        expect(window.GoogleMaps.selectedCircle === null).toBe(false);
        expect(window.GoogleMaps.selectedIndex === null).toBe(false);
        expect(document.getElementById('sideDrawer').className === 'active').toBe(true);
        
        window.GoogleMaps.deselectCircle();
        expect(window.GoogleMaps.selectedCircle === null).toBe(true);
        expect(window.GoogleMaps.selectedIndex === null).toBe(true);
        expect(document.getElementById('sideDrawer').className === 'active').toBe(false);
    });

    it('should draw a circle', () => {
        let circle = {
            id: 123,
            strokeColor: '#123456',
            strokeOpacity: 0.12,
            strokeWeight: 1,
            fillColor: '#234567',
            fillOpacity: 0.98,
            latitude: 90,
            longitude: 82,
            radius: 1234
        };
        let index = 0;

        let result = window.GoogleMaps.drawCircle(circle, index);

        expect(googleMaps.Circle).toHaveBeenCalledTimes(1);
        expect(result).toBeInstanceOf(googleMaps.Circle);
        expect(result.opts.id).toBe(circle.id);
        expect(result.opts.index).toBe(index);
        expect(result.opts.strokeColor).toBe(circle.strokeColor);
        expect(result.opts.strokeOpacity).toBe(circle.strokeOpacity);
        expect(result.opts.strokeWeight).toBe(circle.strokeWeight);
        expect(result.opts.fillColor).toBe(circle.fillColor);
        expect(result.opts.fillOpacity).toBe(circle.fillOpacity);
        expect(result.opts.center.lat).toBe(circle.latitude);
        expect(result.opts.center.lng).toBe(circle.longitude);
        expect(result.opts.radius).toBe(circle.radius);
    });
    
    it('should draw circles', () => {
        let existingCirclesCount = window.GoogleMaps.circles.length;

        let circle = {
            id: 123,
            strokeColor: '#123456',
            strokeOpacity: 0.12,
            strokeWeight: 1,
            fillColor: '#234567',
            fillOpacity: 0.98,
            latitude: 90,
            longitude: 82,
            radius: 1234
        };

        let circles = [circle];

        let result = window.GoogleMaps.drawCircles(circles);

        expect(googleMaps.Circle).toHaveBeenCalledTimes(1);
        expect(window.GoogleMaps.circles.length).toBe(existingCirclesCount + 1);
        expect(window.GoogleMaps.googleMapsUserCircles).toBe(circles);
        expect(typeof result).toBe('object');
    });

    it('should draw a marker', () => {
        let marker = {
            id: 234,
            latitude: 30,
            longitude: 12,
        };
        let index = 3;
        let icon = 'icon.png';

        let result = window.GoogleMaps.drawMarker(marker, index, icon);

        expect(googleMaps.Marker).toHaveBeenCalledTimes(1);
        expect(result).toBeInstanceOf(googleMaps.Marker);
        expect(result.opts.id).toBe(marker.id);
        expect(result.opts.index).toBe(index);
        expect(result.opts.position.lat).toBe(marker.latitude);
        expect(result.opts.position.lng).toBe(marker.longitude);
        expect(result.opts.icon).toBe(icon);
    });

    it('should draw markers', () => {
        let existingMarkersCount = window.GoogleMaps.markers.length;
        let marker = {
            id: 123,
            latitude: 90,
            longitude: 82,
            radius: 1234
        };
        let markers = [marker];
        let color = 'green';

        let result = window.GoogleMaps.drawMarkers(markers, color);

        expect(googleMaps.Marker).toHaveBeenCalledTimes(1);
        expect(window.GoogleMaps.markers.length).toBe(existingMarkersCount + 1);
        expect(window.GoogleMaps.googleMapsPromoterMarkers).toBe(markers);
        expect(typeof result).toBe('object');
    });
    
    it('should click on the map', () => {
        window.GoogleMaps.selectedCircle = {
            id: 1,
            index: 0,
            setOptions: jest.fn()
        };
        
        window.GoogleMaps.clickOnMap();

        expect(window.GoogleMaps.selectedCircle === null).toBe(true);
        expect(window.GoogleMaps.selectedIndex === null).toBe(true);
    });

    it('should center on user location', () => {
        let latitude = 92.123;
        let longitude = 23.342;

        window.GoogleMaps.user = {
            profile_photo_url: 'http://photo.png',
        }

        let form = document.createElement('form');
        form.setAttribute('id', 'userLocationForm');

        let latitudeInput = document.createElement('input');
        latitudeInput.setAttribute('name', 'latitude');
        let longitudeInput = document.createElement('input');
        longitudeInput.setAttribute('name', 'longitude');

        form.appendChild(latitudeInput);
        form.appendChild(longitudeInput);

        document.body.innerHTML = form.outerHTML;

        let expectedCenter = { lat: latitude, lng: longitude };

        window.GoogleMaps.centerOnUserLocation(latitude, longitude);

        // expect(window.GoogleMaps.map.getCenter()).toMatchObject(expectedCenter);
        expect(googleMaps.Marker).toHaveBeenCalledTimes(1);
        expect(googleMaps.Marker.mock.instances.length).toBe(1);
    });

    it('should create DrawingManager', () => {
        let map = {};
        let modes = {sample: 'mode'};

        window.GoogleMaps.drawingManager = null;
        window.GoogleMaps.createDrawingManager(map, modes);

        expect(googleMaps.drawing.DrawingManager).toHaveBeenCalledTimes(1);
        expect(googleMaps.drawing.DrawingManager.mock.instances.length).toBe(1);
        expect(googleMaps.drawing.DrawingManager).toHaveBeenLastCalledWith({
            drawingControl: true,
            drawingControlOptions: {
                position: googleMaps.ControlPosition.TOP_CENTER,
                drawingModes: modes,
            },
            circleOptions: window.GoogleMaps.defaultCircleOptions,
        });
    });


    it('should focus', () => {
        let circle = {
            options: window.GoogleMaps.defaultCircleOptions,
            setOptions: jest.fn(),
        };

        window.GoogleMaps.focus(circle);
        expect(circle.setOptions).toHaveBeenCalledTimes(0);
        expect(circle.options.editable).toBe(false);
        
        circle.index = 1;
        window.GoogleMaps.selectedIndex = 2;
        
        window.GoogleMaps.focus(circle);
        expect(circle.setOptions).toHaveBeenCalledTimes(1);
        expect(circle.options.editable).toBe(true);
    });

    it('should update a circle', () => {
        let existingId = 1431;
        let latitude = 12.34;
        let longitude = 24.41;
        let circle = {
            center: {
                lat: () => latitude,
                lng: () => longitude,
            },
            radius: 452,
        };

        let idInput = document.getElementById('circle_id');
        idInput.value = existingId;

        window.GoogleMaps.updateCircle(circle);

        expect(idInput.value).toBe(String(existingId));
        expect(latitudeInput.value).toBe(String(latitude));
        expect(longitudeInput.value).toBe(String(longitude));
        expect(radiusInput.value).toBe(String(circle.radius));
    });

    it('should save a circle', () => {
        let latitude = 11.34;
        let longitude = 22.41;
        let circle = {
            center: {
                lat: () => latitude,
                lng: () => longitude,
            },
            radius: 412,
        };

        window.GoogleMaps.saveCircle(circle);

        expect(latitudeInput.value).toBe(String(latitude));
        expect(longitudeInput.value).toBe(String(longitude));
        expect(radiusInput.value).toBe(String(circle.radius));
    });

    it('should save a marker', () => {
        let latitude = 11.34;
        let longitude = 22.41;
        let marker = {
            position: {
                lat: () => latitude,
                lng: () => longitude,
            },
        };

        clearDOM();

        let latitudeInput = document.createElement('input');
        latitudeInput.setAttribute('id', 'latitude');

        let longitudeInput = document.createElement('input');
        longitudeInput.setAttribute('id', 'longitude');

        let submitButton = document.createElement('button');
        submitButton.setAttribute('id', 'map-markers-form-submit');

        document.body.append(latitudeInput, longitudeInput, submitButton);

        window.GoogleMaps.saveMarker(marker);

        expect(latitudeInput.value).toBe(String(latitude));
        expect(longitudeInput.value).toBe(String(longitude));
    });

    it('should save the user location', () => {
        let latitude = 21.43;
        let longitude = 92.13;

        let form = document.createElement('form');
        form.setAttribute('id', 'userLocationForm');

        let latitudeInput = document.createElement('input');
        latitudeInput.setAttribute('name', 'latitude');
        let longitudeInput = document.createElement('input');
        longitudeInput.setAttribute('name', 'longitude');

        form.appendChild(latitudeInput);
        form.appendChild(longitudeInput);
        document.body.append(form);

        window.GoogleMaps.saveUserLocation(latitude, longitude);

        expect(latitudeInput.value).toBe(String(latitude));
        expect(longitudeInput.value).toBe(String(longitude));
    });
    
    it('should set the circle id', () => {
        let id = 912;
        let circle = {id: 0};

        window.GoogleMaps.setCircleId(circle, id);
        expect(circle.id).toBe(id);
    });

    it('should set drawing modes', () => {
        window.GoogleMaps.drawingManager = null;

        let result = window.GoogleMaps.setDrawingModes({});

        expect(googleMaps.drawing.DrawingManager).toHaveBeenCalledTimes(1);
        expect(googleMaps.drawing.DrawingManager.mock.instances.length).toBe(1);
        expect(typeof result).toBe('object');
    });
    
    it('should set the user', () => {
        let user = { id: 1 };
        let result = window.GoogleMaps.setUser(user);
        
        expect(typeof result).toBe('object');
        expect(window.GoogleMaps.user).toBe(user);
    });

    it('should set the user location', () => {
        let result = window.GoogleMaps.setUserLocation(null);
        expect(typeof result).toBe('object');

        let location = { 
            latitude: 92.123,
            longitude: 23.342,
        };

        window.GoogleMaps.user = {
            profile_photo_url: 'http://photo.png',
        }

        let form = document.createElement('form');
        form.setAttribute('id', 'userLocationForm');

        let latitudeInput = document.createElement('input');
        latitudeInput.setAttribute('name', 'latitude');
        let longitudeInput = document.createElement('input');
        longitudeInput.setAttribute('name', 'longitude');

        form.appendChild(latitudeInput);
        form.appendChild(longitudeInput);

        document.body.innerHTML = form.outerHTML;

        result = window.GoogleMaps.setUserLocation(location);
        expect(typeof result).toBe('object');
        expect(googleMaps.Marker).toHaveBeenCalledTimes(1);
        expect(googleMaps.Marker.mock.instances.length).toBe(1);
    });

    it('should select the circle at given index', () => {
        window.GoogleMaps.selectedCircle = null;
        window.GoogleMaps.circles = [
            {
                id: 2, 
                index: 2,
                setOptions: jest.fn()
            }
        ];
        window.GoogleMaps.selectedIndex = 2;

        window.GoogleMaps.selectCircleAtIndex(0);
        expect(window.GoogleMaps.selectedIndex === 0).toBe(true);
        expect(window.GoogleMaps.selectedCircle).toMatchObject({id: 2, index: 2 });
    });

    it('should unfocus', () => {
        let circle = {
            options: window.GoogleMaps.defaultCircleOptions,
            setOptions: jest.fn(),
        };

        window.GoogleMaps.unfocus(circle);
        expect(circle.setOptions).toHaveBeenCalledTimes(1);
        expect(circle.options.editable).toBe(false);
        
        circle.index = 1;
        window.GoogleMaps.selectedIndex = 1;
        
        window.GoogleMaps.unfocus(circle);
        expect(circle.setOptions).toHaveBeenCalledTimes(2);
        expect(circle.options.editable).toBe(false);
    });

});
