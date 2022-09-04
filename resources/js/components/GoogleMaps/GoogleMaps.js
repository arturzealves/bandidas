window.initMap = function () {
    window.GoogleMaps.initMap();
};
class GoogleMaps {
    constructor() {
        this.options = {
            drawingModes: []
        };

        this.mapProperties = {
            zoom: 7,
            center: { lat: 38.72, lng: -9.139 },
            mapTypeId: 'terrain',
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            rotateControl: false,
            scaleControl: false,
            zoomControl: true,
        };

        this.defaultCircleOptions = {
            fillColor: '#FF0000',
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            fillOpacity: 0.45,
            strokeWeight: 2,
            clickable: true,
            editable: false,
            zIndex: 1,
        };

        this.selectedCircleOptions = {
            fillOpacity: 0.2,
            editable: true,
        };

        this.circles = [];
        this.markers = [];
        this.googleMapsUserCircles = [];
        this.googleMapsPromoterMarkers = [];
        this.map = {};
        this.selectedIndex = null;
        this.selectedCircle = null;
        this.user = null;
        this.drawingManager = null;
    }

    addCircle(circle) {
        this.circles.push(circle);

        if (typeof circle.index == 'undefined') {
            circle.index = this.circles.length - 1;
        }
    }

    addMarker(marker) {
        this.markers.push(marker);

        if (typeof marker.index == 'undefined') {
            marker.index = this.markers.length - 1;
        }
    }

    bindEventListeners(circle) {
        google.maps.event.addListener(circle, 'click', () => this.selectCircleAtIndex(circle.index));
        google.maps.event.addListener(circle, 'contextmenu', () => this.deleteCircle(circle));
        google.maps.event.addListener(circle, 'mouseover', () => this.focus(circle));
        google.maps.event.addListener(circle, 'mouseout', () => this.unfocus(circle));
        google.maps.event.addListener(circle, 'radius_changed', () => this.updateCircle(circle));
        google.maps.event.addListener(circle, 'center_changed', () => this.updateCircle(circle));
    }

    circleComplete(circle) {
        // Exit circle drawing mode after finishing a circle
        this.drawingManager.setDrawingMode(null);

        this.saveCircle(circle);
        this.addCircle(circle);
        this.bindEventListeners(circle);
        this.selectCircleAtIndex(circle.index);

        Livewire.emit('mount', circle.id);

        window.addEventListener('submitted', event => this.setCircleId(circle, event.detail.id));

        //   this.circleOptions.fillColor = "#FFFFFF";
        //   console.log('nice', this, element);
        // });

        // google.maps.event.addListener(circle, 'click', function(element) {
        //   element.id = circle.id;
        //   this.selectCircleAtIndex(this.circles.length - 1);
        // });
    }

    deleteCircle(circle) {
        this.circles[circle.index].setMap(null);

        Livewire.emit('destroy', circle.id);
    }

    deselectCircle() {
        if (this.selectedCircle == null) {
            return null;
        }
        console.log('deselecting circle');
        this.selectedCircle.setOptions(this.defaultCircleOptions);
        this.selectedCircle = null;
        this.selectedIndex = null;
        document.getElementById('sideDrawer').classList.remove('active');
    }

    drawCircle(circle, index) {
        let scope = this;

        let mapCircle = new google.maps.Circle({
            id: circle.id,
            index: index,
            strokeColor: circle.strokeColor ?? '#FF0000',
            strokeOpacity: circle.strokeOpacity ?? 0.8,
            strokeWeight: circle.strokeWeight ?? 2,
            fillColor: circle.fillColor ?? '#FF0000',
            fillOpacity: circle.fillOpacity ?? 0.35,
            map: this.map,
            center: {
                lat: parseFloat(circle.latitude),
                lng: parseFloat(circle.longitude),
            },
            radius: circle.radius,
        });

        console.log(mapCircle, circle);

        this.bindEventListeners(mapCircle);

        return mapCircle;
    }

    drawCircles(circles) {
        this.googleMapsUserCircles = circles;
        // console.log('drawnCircles', circles)

        for (const index in circles) {
            this.addCircle(this.drawCircle(circles[index], index));
        }

        return this;
    }

    drawMarker(marker, index, icon) {
        let mapMarker = new google.maps.Marker({
            id: marker.id,
            index: index,
            map: this.map,
            position: {
                lat: parseFloat(marker.latitude),
                lng: parseFloat(marker.longitude),
            },
            icon: icon
        });

        // this.bindEventListeners(mapMarker);

        return mapMarker;
    }

    drawMarkers(markers, color) {
        this.googleMapsPromoterMarkers = markers;

        let svgMarker = {
            path: "M10.453 14.016l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM12 2.016q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
            fillColor: color,
            fillOpacity: 0.6,
            strokeWeight: 0,
            rotation: 0,
            scale: 2,
            anchor: new google.maps.Point(15, 30),
        };

        for (const index in markers) {
            this.addMarker(this.drawMarker(markers[index], index, svgMarker));
        }

        return this;
    }

    // Center on location
    centerOnUserLocation(latitude, longitude) {
        // const pos = {
        //     lat: position.coords.latitude,
        //     lng: position.coords.longitude,
        // };
        const pos = {
            lat: latitude,
            lng: longitude,
        };

        this.map.setCenter(pos);
        // this.map.setZoom(14);

        let cursorSizeLatitudeOffset = -0.0008;
        new google.maps.Marker({
            position: new google.maps.LatLng(
                latitude + cursorSizeLatitudeOffset,
                longitude
            ),
            icon: {
                url: this.user.profile_photo_url,
                scaledSize: new google.maps.Size(40, 40),
            },
            map: this.map,
        });

        this.saveUserLocation(pos.lat, pos.lng);
    }

    circleMounted(circleId) {
        document.getElementById('sideDrawer').classList.add('active');
        this.selectedCircle.id = circleId;
    }

    clickOnMap() {
        console.log('click on map');
        if (this.selectedCircle != null || this.selectedIndex != null) {
            this.deselectCircle();
        }
    }

    createDrawingManager(map, modes) {
        if (this.drawingManager !== null) {
            this.drawingManager.setMap(null);
        }

        this.drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: modes,
            },
            circleOptions: this.defaultCircleOptions,
        });

        this.drawingManager.setMap(map);

        google.maps.event.addListener(this.drawingManager, 'circlecomplete', (circle) => this.circleComplete(circle));

        google.maps.event.addListener(this.drawingManager, 'markercomplete', (marker) => this.saveMarker(marker));
    }

    initMap() {
        // Create the map.
        this.map = new google.maps.Map(
            document.getElementById('map'),
            this.mapProperties
        );

        // Init custom map buttons
        const locationButton = document.createElement('button');

        locationButton.classList.add('map-user-location-button');
        locationButton.addEventListener('click', () => this.getUserLocation());

        this.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
            locationButton
        );

        // Drawing mode
        this.createDrawingManager(this.map, this.options.drawingModes);

        let scope = this;
        google.maps.event.addListener(this.map, 'click', () => this.clickOnMap());
    }

    focus(circle) {
        if (circle.index != this.selectedIndex) {
            let options = this.defaultCircleOptions;
            options.editable = true;
            circle.setOptions(options);
        }
    }

    updateCircle(circle) {
        // Update the DOM elements and trigger the model properties update
        document.getElementById('latitude').value = circle.center.lat();
        document.getElementById('latitude').dispatchEvent(new Event('change'));
        document.getElementById('longitude').value = circle.center.lng();
        document.getElementById('longitude').dispatchEvent(new Event('change'));
        document.getElementById('radius').value = circle.radius;
        document.getElementById('radius').dispatchEvent(new Event('change'));

        if (!circle.id && document.getElementById('circle_id').value !== '') {
            circle.id = document.getElementById('circle_id').value;
        }

        document.getElementById('circle_id').value = circle.id;
        document
            .getElementById('circle_id')
            .dispatchEvent(new Event('change'));

        // trigger the form update
        document
            .getElementById('google-maps-user-circle-form-update')
            .click();
    }


    saveCircle(circle) {
        // Update the DOM elements and trigger the model properties update
        document.getElementById('latitude').value = circle.center.lat();
        document.getElementById('latitude').dispatchEvent(new Event('change'));
        document.getElementById('longitude').value = circle.center.lng();
        document.getElementById('longitude').dispatchEvent(new Event('change'));
        document.getElementById('radius').value = circle.radius;
        document.getElementById('radius').dispatchEvent(new Event('change'));

        // trigger the form save
        document
            .getElementById('google-maps-user-circle-form-submit')
            .click();
    }

    saveMarker(marker) {
        // Update the DOM elements and trigger the model properties update
        document.getElementById('latitude').value = marker.position.lat();
        document.getElementById('latitude').dispatchEvent(new Event('change'));
        document.getElementById('longitude').value = marker.position.lng();
        document.getElementById('longitude').dispatchEvent(new Event('change'));

        document
            .getElementById('map-markers-form-submit')
            .click();
    }

    saveUserLocation(latitude, longitude) {
        let form = document.getElementById('userLocationForm');
        
        form.elements["latitude"].value = latitude;
        form.elements["latitude"].dispatchEvent(new Event('change'))
        form.elements["longitude"].value = longitude;
        form.elements["longitude"].dispatchEvent(new Event('change'))

        Livewire.emit('submit');

        console.log('saveUserLocation', form);
    }

    // setOptions(options) {
    //     this.options = {...this.options, ...options};

    //     return this;
    // }

    setCircleId(circle, id) {
        circle.id = id;
    }

    setDrawingModes(modes) {
        this.createDrawingManager(this.map, modes);

        return this;
    }

    setUser(user) {
        this.user = user;

        console.log('setUser', this.user);

        // this.map.setCenter(this.user.center); 
        // this.map.setZoom(10);

        return this;
    }

    getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.centerOnUserLocation(position.coords.latitude, position.coords.longitude);
                }
            );
        }
    }

    setUserLocation(location) {
        console.log('setUserLocation', location);

        if (location == null) {
            return this;
        }
        
        this.centerOnUserLocation(parseFloat(location.latitude), parseFloat(location.longitude));

        // this.map.setCenter({
        //     lat: parseFloat(location.latitude),
        //     lng: parseFloat(location.longitude)
        // });

        return this;
    }

    selectCircleAtIndex(index) {
        let scope = this;
        // console.log('selectCircle: total circles', this.circles.length);

        this.deselectCircle();

        this.selectedCircle = this.circles[index];
        this.selectedIndex = index;

        this.selectedCircle.setOptions(this.selectedCircleOptions);

        // var overlay = new google.maps.OverlayView()
        // overlay.draw = function () {}
        // overlay.setMap(this.map)

        // var proj = overlay.getProjection();
        // var pos = marker.getPosition();
        // var p = proj.fromLatLngToContainerPixel(pos);

        // console.log('teste', overlay, proj);

        // https://developers.google.com/maps/documentation/javascript/reference/overlay-view#MapPanes.markerLayer

        // https://developers.google.com/maps/documentation/javascript/reference/polygon?authuser=1#Circle

        // Fill inputs
        Livewire.emit('mount', this.selectedCircle.id);

        window.addEventListener('mounted', event => this.circleMounted(event.detail.id));
    }

    unfocus(circle) {
        let options = this.defaultCircleOptions;
        options.editable = false;
        if (this.selectedIndex == circle.index) {
            circle.setOptions(this.selectedCircleOptions);
        } else {
            circle.setOptions(this.defaultCircleOptions);
        }
    }
}

window.GoogleMaps = new GoogleMaps();
