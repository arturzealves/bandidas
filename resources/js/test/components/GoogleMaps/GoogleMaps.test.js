import createGoogleMapsMock from '../../mocks/GoogleMaps/createGoogleMapsMock';
import createLivewireMock from '../../mocks/createLivewireMock.js';
import GoogleMaps from '../../../components/GoogleMaps/GoogleMaps.js';

let googleMaps = new GoogleMaps();

describe('GoogleMaps.js structure', () => {
    it('GoogleMaps should be an object', () => {
        expect(typeof googleMaps).toBe('object');
    });
    it('should have all its properties initialized', () => {
        expect(typeof googleMaps.options).toBe('object');
        expect(Array.isArray(googleMaps.options.drawingModes)).toBe(
            true
        );
        expect(typeof googleMaps.mapProperties).toBe('object');
        expect(typeof googleMaps.mapProperties.zoom).toBe('number');
        expect(typeof googleMaps.mapProperties.center).toBe('object');
        expect(typeof googleMaps.mapProperties.mapTypeId).toBe('string');
        expect(typeof googleMaps.mapProperties.mapTypeControl).toBe(
            'boolean'
        );
        expect(typeof googleMaps.mapProperties.fullscreenControl).toBe(
            'boolean'
        );
        expect(typeof googleMaps.mapProperties.rotateControl).toBe(
            'boolean'
        );
        expect(typeof googleMaps.mapProperties.scaleControl).toBe(
            'boolean'
        );
        expect(typeof googleMaps.mapProperties.zoomControl).toBe(
            'boolean'
        );
        expect(typeof googleMaps.defaultCircleOptions).toBe('object');
        expect(typeof googleMaps.defaultCircleOptions.fillColor).toBe(
            'string'
        );
        expect(typeof googleMaps.defaultCircleOptions.strokeColor).toBe(
            'string'
        );
        expect(
            typeof googleMaps.defaultCircleOptions.strokeOpacity
        ).toBe('number');
        expect(typeof googleMaps.defaultCircleOptions.fillOpacity).toBe(
            'number'
        );
        expect(typeof googleMaps.defaultCircleOptions.strokeWeight).toBe(
            'number'
        );
        expect(typeof googleMaps.defaultCircleOptions.clickable).toBe(
            'boolean'
        );
        expect(typeof googleMaps.defaultCircleOptions.editable).toBe(
            'boolean'
        );
        expect(typeof googleMaps.defaultCircleOptions.zIndex).toBe(
            'number'
        );
        expect(typeof googleMaps.selectedCircleOptions).toBe('object');
        expect(typeof googleMaps.selectedCircleOptions.fillOpacity).toBe(
            'number'
        );
        expect(typeof googleMaps.selectedCircleOptions.editable).toBe(
            'boolean'
        );
        expect(Array.isArray(googleMaps.circles)).toBe(true);
        expect(Array.isArray(googleMaps.markers)).toBe(true);
        expect(Array.isArray(googleMaps.googleMapsUserCircles)).toBe(
            true
        );
        expect(Array.isArray(googleMaps.googleMapsPromoterMarkers)).toBe(
            true
        );
        expect(typeof googleMaps.map).toBe('object');
        expect(googleMaps.selectedIndex === null).toBe(true);
        expect(googleMaps.selectedCircle === null).toBe(true);
        expect(googleMaps.user === null).toBe(true);
        expect(googleMaps.drawingManager === null).toBe(true);
    });
    it('should have all its functions defined', () => {
        expect(typeof googleMaps.addCircle).toBe('function');
        expect(typeof googleMaps.addMarker).toBe('function');
        expect(typeof googleMaps.bindEventListeners).toBe('function');
        expect(typeof googleMaps.deleteCircle).toBe('function');
        expect(typeof googleMaps.deselectCircle).toBe('function');
        expect(typeof googleMaps.drawCircle).toBe('function');
        expect(typeof googleMaps.drawCircles).toBe('function');
        expect(typeof googleMaps.drawMarker).toBe('function');
        expect(typeof googleMaps.drawMarkers).toBe('function');
        expect(typeof googleMaps.centerOnUserLocation).toBe('function');
        expect(typeof googleMaps.createDrawingManager).toBe('function');
        expect(typeof googleMaps.initMap).toBe('function');
        expect(typeof googleMaps.focus).toBe('function');
        expect(typeof googleMaps.updateCircle).toBe('function');
        expect(typeof googleMaps.saveCircle).toBe('function');
        expect(typeof googleMaps.saveMarker).toBe('function');
        expect(typeof googleMaps.saveUserLocation).toBe('function');
        expect(typeof googleMaps.setDrawingModes).toBe('function');
        expect(typeof googleMaps.setUser).toBe('function');
        expect(typeof googleMaps.setUserLocation).toBe('function');
        expect(typeof googleMaps.selectCircleAtIndex).toBe('function');
        expect(typeof googleMaps.unfocus).toBe('function');
    });
});

describe('GoogleMaps.js features', () => {
    let googleMapsMock;
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
        idInput.setAttribute('id', 'circle_uuid');

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
        googleMapsMock = createGoogleMapsMock(['drawing', 'event']);

        global.window.google = {
            maps: googleMapsMock,
        };

        global.Livewire = createLivewireMock();

        buildDOM();
    });

    // it('should have initMap on window', () => {
    //     window.initMap();

    //     expect(googleMapsMock.Map).toHaveBeenCalledTimes(1);
    //     expect(googleMapsMock.Map.mock.instances.length).toBe(1);
    // });

    it('should execute initMap successfully', () => {
        var mapDiv = document.createElement('div');
        mapDiv.setAttribute('id', 'map');
        document.body.innerHTML = mapDiv.outerHTML;

        googleMaps.initMap();

        expect(googleMapsMock.Map).toHaveBeenCalledTimes(1);
        expect(googleMapsMock.Map.mock.instances.length).toBe(1);
        expect(googleMapsMock.Map).toHaveBeenLastCalledWith(mapDiv, googleMaps.mapProperties);
        expect(googleMaps.map).toBeDefined();

        expect(googleMaps.map.controls[googleMapsMock.ControlPosition.TOP_RIGHT][0]).toBeInstanceOf(HTMLButtonElement);

        expect(googleMapsMock.drawing.DrawingManager).toHaveBeenCalledTimes(1);
        expect(googleMapsMock.drawing.DrawingManager.mock.instances.length).toBe(1);
    });

    it('should add a circle', () => {
        googleMaps.initMap();
        expect(googleMaps.circles.length).toBe(0);

        googleMaps.addCircle({index: 2});
        expect(googleMaps.circles.length).toBe(1);

        googleMaps.addCircle({});
        expect(googleMaps.circles.length).toBe(2);
        expect(googleMaps.circles[1].index).toBe(1);
    });

    it('should add a marker', () => {
        googleMaps.initMap();
        expect(googleMaps.markers.length).toBe(0);

        googleMaps.addMarker({index: 2});
        expect(googleMaps.markers.length).toBe(1);

        googleMaps.addMarker({});
        expect(googleMaps.markers.length).toBe(2);
        expect(googleMaps.markers[1].index).toBe(1);
    });

    it('should bind event listeners', () => {
        let circle = {};
        googleMaps.bindEventListeners(circle);

        // expect(googleMaps.event).toBe(
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

        googleMaps.circles = [{
            uuid: 1,
            index: 0,
            setOptions: jest.fn()
        }]

        googleMaps.drawingManager = {
            setDrawingMode: jest.fn()
        };

        expect(googleMaps.circles.length).toBe(1);
        
        googleMaps.circleComplete(circle);

        expect(googleMaps.circles.length).toBe(2);
    });
    
    it('should execute after a circle is mounted', () => {
        let uuid = 2;
        googleMaps.selectedCircle = { uuid: null };

        googleMaps.circleMounted(uuid);
        
        expect(document.getElementById('sideDrawer').classList.contains('active')).toBe(true);
        expect(googleMaps.selectedCircle.uuid).toBe(uuid);
    });

    it('should delete a circle', () => {
        let circle = { 
            index: 0, 
            uuid: 3,
            setMap: jest.fn(),
        };

        googleMaps.circles = [
            { index: 0, uuid: 3, setMap: jest.fn() },
            { index: 1, uuid: 2, setMap: jest.fn() },
        ];

        expect(googleMaps.circles.length).toBe(2);
        googleMaps.deleteCircle(circle);
        // expect(googleMaps.circles.length).toBe(1);
    });

    it('should deselect a circle', () => {
        googleMaps.selectedCircle = null;
        expect(googleMaps.deselectCircle() === null).toBe(true);
        
        googleMaps.selectedCircle = {
            uuid: 1,
            index: 0,
            setOptions: jest.fn()
        };

        sideDrawer.setAttribute('class', 'active');

        document.body.innerHTML = sideDrawer.outerHTML;

        googleMaps.selectedIndex = 1;

        expect(googleMaps.selectedCircle === null).toBe(false);
        expect(googleMaps.selectedIndex === null).toBe(false);
        expect(document.getElementById('sideDrawer').className === 'active').toBe(true);
        
        googleMaps.deselectCircle();
        expect(googleMaps.selectedCircle === null).toBe(true);
        expect(googleMaps.selectedIndex === null).toBe(true);
        expect(document.getElementById('sideDrawer').className === 'active').toBe(false);
    });

    it('should draw a circle', () => {
        let circle = {
            uuid: 123,
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

        let result = googleMaps.drawCircle(circle, index);

        expect(googleMapsMock.Circle).toHaveBeenCalledTimes(1);
        expect(result).toBeInstanceOf(googleMapsMock.Circle);
        expect(result.opts.uuid).toBe(circle.uuid);
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
        let existingCirclesCount = googleMaps.circles.length;

        let circle = {
            uuid: 123,
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

        let result = googleMaps.drawCircles(circles);

        expect(googleMapsMock.Circle).toHaveBeenCalledTimes(1);
        expect(googleMaps.circles.length).toBe(existingCirclesCount + 1);
        expect(googleMaps.googleMapsUserCircles).toBe(circles);
        expect(typeof result).toBe('object');
    });

    it('should draw a marker', () => {
        let marker = {
            uuid: 234,
            latitude: 30,
            longitude: 12,
        };
        let index = 3;
        let icon = 'icon.png';

        let result = googleMaps.drawMarker(marker, index, icon);

        expect(googleMapsMock.Marker).toHaveBeenCalledTimes(1);
        expect(result).toBeInstanceOf(googleMapsMock.Marker);
        expect(result.opts.uuid).toBe(marker.uuid);
        expect(result.opts.index).toBe(index);
        expect(result.opts.position.lat).toBe(marker.latitude);
        expect(result.opts.position.lng).toBe(marker.longitude);
        expect(result.opts.icon).toBe(icon);
    });

    it('should draw markers', () => {
        let existingMarkersCount = googleMaps.markers.length;
        let marker = {
            uuid: 123,
            latitude: 90,
            longitude: 82,
            radius: 1234
        };
        let markers = [marker];
        let color = 'green';

        let result = googleMaps.drawMarkers(markers, color);

        expect(googleMapsMock.Marker).toHaveBeenCalledTimes(1);
        expect(googleMaps.markers.length).toBe(existingMarkersCount + 1);
        expect(googleMaps.googleMapsPromoterMarkers).toBe(markers);
        expect(typeof result).toBe('object');
    });
    
    it('should click on the map', () => {
        googleMaps.selectedCircle = {
            uuid: 1,
            index: 0,
            setOptions: jest.fn()
        };
        
        googleMaps.clickOnMap();

        expect(googleMaps.selectedCircle === null).toBe(true);
        expect(googleMaps.selectedIndex === null).toBe(true);
    });

    it('should center on user location', () => {
        let latitude = 92.123;
        let longitude = 23.342;

        googleMaps.user = {
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

        googleMaps.centerOnUserLocation(latitude, longitude);

        // expect(googleMaps.map.getCenter()).toMatchObject(expectedCenter);
        expect(googleMapsMock.Marker).toHaveBeenCalledTimes(1);
        expect(googleMapsMock.Marker.mock.instances.length).toBe(1);
    });

    it('should create DrawingManager', () => {
        let map = {};
        let modes = {sample: 'mode'};

        googleMaps.drawingManager = null;
        googleMaps.createDrawingManager(map, modes);

        expect(googleMapsMock.drawing.DrawingManager).toHaveBeenCalledTimes(1);
        expect(googleMapsMock.drawing.DrawingManager.mock.instances.length).toBe(1);
        expect(googleMapsMock.drawing.DrawingManager).toHaveBeenLastCalledWith({
            drawingControl: true,
            drawingControlOptions: {
                position: googleMapsMock.ControlPosition.TOP_CENTER,
                drawingModes: modes,
            },
            circleOptions: googleMaps.defaultCircleOptions,
        });
    });


    it('should focus', () => {
        let circle = {
            options: googleMaps.defaultCircleOptions,
            setOptions: jest.fn(),
        };

        googleMaps.focus(circle);
        expect(circle.setOptions).toHaveBeenCalledTimes(0);
        expect(circle.options.editable).toBe(false);
        
        circle.index = 1;
        googleMaps.selectedIndex = 2;
        
        googleMaps.focus(circle);
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

        let idInput = document.getElementById('circle_uuid');
        idInput.value = existingId;

        googleMaps.updateCircle(circle);

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

        googleMaps.saveCircle(circle);

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

        googleMaps.saveMarker(marker);

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

        googleMaps.saveUserLocation(latitude, longitude);

        expect(latitudeInput.value).toBe(String(latitude));
        expect(longitudeInput.value).toBe(String(longitude));
    });
    
    it('should set the circle uuid', () => {
        let uuid = 912;
        let circle = {uuid: 0};

        googleMaps.setCircleUuid(circle, uuid);
        expect(circle.uuid).toBe(uuid);
    });

    it('should set drawing modes', () => {
        googleMaps.drawingManager = null;

        let result = googleMaps.setDrawingModes({});

        expect(googleMapsMock.drawing.DrawingManager).toHaveBeenCalledTimes(1);
        expect(googleMapsMock.drawing.DrawingManager.mock.instances.length).toBe(1);
        expect(typeof result).toBe('object');
    });
    
    it('should get the user location', () => {
        global.navigator.geolocation = {
            getCurrentPosition: jest.fn(),
        };

        googleMaps.getUserLocation();
    });

    it('should set the user', () => {
        let user = { uuid: 1 };
        let result = googleMaps.setUser(user);
        
        expect(typeof result).toBe('object');
        expect(googleMaps.user).toBe(user);
    });

    it('should set the user location', () => {
        let result = googleMaps.setUserLocation(null);
        expect(typeof result).toBe('object');

        let location = { 
            latitude: 92.123,
            longitude: 23.342,
        };

        googleMaps.user = {
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

        result = googleMaps.setUserLocation(location);
        expect(typeof result).toBe('object');
        expect(googleMapsMock.Marker).toHaveBeenCalledTimes(1);
        expect(googleMapsMock.Marker.mock.instances.length).toBe(1);
    });

    it('should select the circle at given index', () => {
        googleMaps.selectedCircle = null;
        googleMaps.circles = [
            {
                uuid: 2, 
                index: 2,
                setOptions: jest.fn()
            }
        ];
        googleMaps.selectedIndex = 2;

        googleMaps.selectCircleAtIndex(0);
        expect(googleMaps.selectedIndex === 0).toBe(true);
        expect(googleMaps.selectedCircle).toMatchObject({uuid: 2, index: 2 });
    });

    it('should unfocus', () => {
        let circle = {
            options: googleMaps.defaultCircleOptions,
            setOptions: jest.fn(),
        };

        googleMaps.unfocus(circle);
        expect(circle.setOptions).toHaveBeenCalledTimes(1);
        expect(circle.options.editable).toBe(false);
        
        circle.index = 1;
        googleMaps.selectedIndex = 1;
        
        googleMaps.unfocus(circle);
        expect(circle.setOptions).toHaveBeenCalledTimes(2);
        expect(circle.options.editable).toBe(false);
    });

});
