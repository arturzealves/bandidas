// import userLocationIcon from '@/../images/icons/map_user_location.png'
// import {OverlayViewService} from './OverlayViewService'

window.initMap = function () {
    window.GoogleMaps.initMap();
};
export default class GoogleMaps {
    constructor() {
        this.mapProperties = {
            zoom: 7,
            // center: { lat: 38.72, lng: -9.139 },
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
        this.googleMapsUserCircles = [];
        this.map = {};
        this.self = this;
        this.selectedIndex = null;
        this.selectedCircle = null;
        this.user = null;
    }

    addCircle(circle) {
        this.circles.push(circle);

        if (typeof circle.index == 'undefined') {
            circle.index = this.circles.length - 1;
        }
        // console.log('addCircle', circle, ' total ', this.circles.length)

        // <button wire:click="destroy({{ $post->id }})"
    }

    bindEventListeners(circle) {
        let scope = this;

        google.maps.event.addListener(circle, 'click', function () {
            // console.log('click ', circle);
            scope.selectCircleAtIndex(circle.index);
        });

        google.maps.event.addListener(circle, 'contextmenu', function () {
            // console.log('contextmenu ', circle);
            scope.deleteCircle(circle);
        });

        google.maps.event.addListener(circle, 'mouseover', function () {
            // console.log('mouseover ', circle);
            scope.focus(circle);
        });

        google.maps.event.addListener(circle, 'mouseout', function () {
            // console.log('mouseout ', circle);
            scope.unfocus(circle);
        });

        google.maps.event.addListener(circle, 'radius_changed', function () {
            // console.log('radius_changed ', circle);
            scope.saveCircle(circle);
        });

        google.maps.event.addListener(circle, 'center_changed', function () {
            // console.log('center_changed ', circle);
            scope.saveCircle(circle);
        });
    }

    deleteCircle(circle) {
        this.circles[circle.index].setMap(null);

        Livewire.emit('destroy', circle.id);
    }

    deselectCircle() {
        if (this.selectedCircle == null) {
            return;
        }
        console.log('deselecting circle');
        this.selectedCircle.setOptions(this.defaultCircleOptions);
        this.selectedCircle = null;
        this.selectedIndex = null;
        // document.getElementById('sideDrawer').classList.remove('active');
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

        // // Temporary delete button
        // const deleteButton = document.createElement('button')

        // deleteButton.classList.add('map-user-location-button')
        // deleteButton.addEventListener('click', () => {
        //     scope.deleteCircle(mapCircle)
        // })

        // this.map.controls[google.maps.ControlPosition.TOP_CENTER].push(
        //     deleteButton
        // )

        // // const bounds = new google.maps.LatLngBounds(
        // //     new google.maps.LatLng(35.907052, -10.347496),
        // //     new google.maps.LatLng(37.282994, -2.327476)
        // // )

        // let image =
        //     'https://cdn-icons.flaticon.com/png/512/2961/premium/2961937.png?token=exp=1660331458~hmac=d17bb56ce196deb9a83c34781060aac6'

        // let overlayViewService = new OverlayViewService();
        // let overlayView = overlayViewService.createMapOverlayView(mapCircle, image);

        // overlayView.setMap(scope.map)
        // // overlay.setMap(scope.map)
        // // console.log('overlay', overlayView)

        // const toggleButton = document.createElement('button')

        // toggleButton.textContent = 'Toggle'
        // toggleButton.classList.add('map-user-location-button')

        // const toggleDOMButton = document.createElement('button')

        // toggleDOMButton.textContent = 'Toggle DOM Attachment'
        // toggleDOMButton.classList.add('map-user-location-button')
        // toggleButton.addEventListener('click', () => {
        //     overlay.toggle()
        // })
        // toggleDOMButton.addEventListener('click', () => {
        //     overlay.toggleDOM(scope.map)
        // })
        // scope.map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(
        //     toggleDOMButton
        // )
        // scope.map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(
        //     toggleButton
        // )

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

    initMap() {
        // Create the map.
        this.map = new google.maps.Map(
            document.getElementById('map'),
            this.mapProperties
        );

        // Init custom map buttons
        const locationButton = document.createElement('button');

        locationButton.classList.add('map-user-location-button');
        locationButton.addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.centerOnUserLocation(position.coords.latitude, position.coords.longitude);
                    }
                );
            }
        });

        this.map.controls[google.maps.ControlPosition.TOP_RIGHT].push(
            locationButton
        );

        // Drawing mode
        const drawingManager = new google.maps.drawing.DrawingManager({
            // drawingMode: google.maps.drawing.OverlayType.CIRCLE,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.CIRCLE],
            },
            circleOptions: this.defaultCircleOptions,
        });

        drawingManager.setMap(this.map);

        let scope = this;
        google.maps.event.addListener(this.map, 'click', function () {
            console.log('click on map');
            if (scope.selectedCircle != null || scope.selectedIndex != null) {
                scope.deselectCircle();
            }
        });

        google.maps.event.addListener(
            drawingManager,
            'circlecomplete',
            function (circle) {
                // Exit circle drawing mode after finishing a circle
                drawingManager.setDrawingMode(null);

                scope.saveCircle(circle);
                scope.addCircle(circle);
                scope.bindEventListeners(circle);
                scope.selectCircleAtIndex(circle.index);

                // Livewire.emit('mount', circle.id);

                //   this.circleOptions.fillColor = "#FFFFFF";
                //   console.log('nice', this, element);
                // });

                // google.maps.event.addListener(circle, 'click', function(element) {
                //   element.id = circle.id;
                //   scope.selectCircleAtIndex(scope.circles.length - 1);
                // });
            }
        );
    }

    focus(circle) {
        if (circle.index != this.selectedIndex) {
            let options = this.defaultCircleOptions;
            options.editable = true;
            circle.setOptions(options);
        }
    }

    saveCircle(circle) {
        // Update the DOM elements and trigger the model properties update
        document.getElementById('latitude').value = circle.center.lat();
        document.getElementById('latitude').dispatchEvent(new Event('change'));
        document.getElementById('longitude').value = circle.center.lng();
        document.getElementById('longitude').dispatchEvent(new Event('change'));
        document.getElementById('radius').value = circle.radius;
        document.getElementById('radius').dispatchEvent(new Event('change'));

        if (circle.id) {
            document.getElementById('circle_id').value = circle.id;
            document
                .getElementById('circle_id')
                .dispatchEvent(new Event('change'));

            // trigger the form update
            document
                .getElementById('google-maps-user-circle-form-update')
                .click();
        } else {
            // trigger the form save
            document
                .getElementById('google-maps-user-circle-form-submit')
                .click();
        }
    }

    saveUserLocation(latitude, longitude) {
        let form = document.getElementById('userLocationForm');
        
        form.elements["latitude"].value = latitude;
        form.elements["latitude"].dispatchEvent(new Event('change'))
        form.elements["longitude"].value = longitude;
        form.elements["longitude"].dispatchEvent(new Event('change'))

        Livewire.emit('submit');

        console.log(form);
    }

    setUser(user) {
        this.user = user;

        console.log(this.user);

        // this.map.setCenter(this.user.center); 
        // this.map.setZoom(10);

        return this;
    }

    setUserLocation(location) {
        console.log(location);

        this.centerOnUserLocation(parseFloat(location.latitude), parseFloat(location.longitude));
        // this.map.setCenter({
        //     lat: parseFloat(location.latitude),
        //     lng: parseFloat(location.longitude)
        // });

        return this;
    }

    selectCircleAtIndex(index) {
        // console.log('selectCircle: total circles', this.circles.length);

        this.deselectCircle();

        this.selectedCircle = this.circles[index];
        this.selectedIndex = index;

        console.log('clicked on circle', this.selectedCircle, index);
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
        // Livewire.emit('mount', this.selectedCircle.id);

        // document.getElementById('sideDrawer').classList.add('active');

        // alternative to previous call
        // var selectedUserCircle = this.googleMapsUserCircles[index]
        // console.log('selected circle', selectedUserCircle);
        // document.getElementById('name').value = selectedUserCircle.name
        // document.getElementById('name').dispatchEvent(new Event('change'))
        // document.getElementById('latitude').value = selectedUserCircle.latitude
        // document.getElementById('latitude').dispatchEvent(new Event('change'))
        // document.getElementById('longitude').value = selectedUserCircle.longitude
        // document.getElementById('longitude').dispatchEvent(new Event('change'))
        // document.getElementById('radius').value = selectedUserCircle.radius
        // document.getElementById('radius').dispatchEvent(new Event('change'))
        // document.getElementById('circle_id').value = selectedUserCircle.id
        // document.getElementById('circle_id').dispatchEvent(new Event('change'))
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
