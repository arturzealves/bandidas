import userLocationIcon from '@/../images/icons/map_user_location.png';

window.initMap = function() {
  // Create the map.
  let map = new google.maps.Map(document.getElementById("map"), {
    zoom: 6,
    center: { lat: 38.72, lng: -9.139 },
    mapTypeId: "terrain",
    mapTypeControl: false,
    streetViewControl: false,
    fullscreenControl: false,
    rotateControl: false,
    scaleControl: false,
    zoomControl: true
  });

  // Init custom map buttons
  const locationButton = document.createElement("button");
  
  locationButton.classList.add("map-user-location-button");
  locationButton.addEventListener("click", () => {
    centerOnUserLocation();
  });
  
  map.controls[google.maps.ControlPosition.TOP_RIGHT].push(locationButton);


  // Center on location
  function centerOnUserLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          map.setCenter(pos);
          map.setZoom(14);

          let cursorSizeLatitudeOffset = -0.0008;
          new google.maps.Marker({
            position: new google.maps.LatLng(position.coords.latitude + cursorSizeLatitudeOffset, position.coords.longitude),
            icon: userLocationIcon,
            map: map,
          });
        },
        () => {
          // handleLocationError(true, infoWindow, map.getCenter());
        }
      );
    }
  }

  // let circles = drawCircles(map, citymap);

  // Drawing mode
  const drawingManager = new google.maps.drawing.DrawingManager({
    // drawingMode: google.maps.drawing.OverlayType.CIRCLE,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        google.maps.drawing.OverlayType.CIRCLE,
      ],
    },
    circleOptions: {
      fillColor: "#FF0000",
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      fillOpacity: 0.35,
      strokeWeight: 2,
      clickable: true,
      editable: true,
      zIndex: 1,
    },
  });

  drawingManager.setMap(map);

  google.maps.event.addListener(drawingManager, 'circlecomplete', function(circle) {
    // var radius = circle.getRadius();

    // circle.addListener('mouseover', () => {
    //   this.circleOptions.fillColor = "#FFFFFF";
    //   console.log('nice', this);
    // });
  });

  window.GoogleMap = map;
}

function drawCircle(map, center, radius) {
  return new google.maps.Circle({
    strokeColor: "#FF0000",
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: "#FF0000",
    fillOpacity: 0.35,
    map,
    center: center,
    radius: radius,
  });
}

window.drawCircles = function (mapa, circles) {
  let drawnCircles = [];
  for (const circle in circles) {
    drawnCircles.push(
      drawCircle(mapa, circles[circle].center, circles[circle].radius * 1000)
    );
  }

  return drawnCircles;
}
