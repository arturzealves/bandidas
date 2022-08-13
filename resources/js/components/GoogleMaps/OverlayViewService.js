export class OverlayViewService {
    mapOverlayView = class extends google.maps.OverlayView {
        circle
        image
        div
        constructor(circle, image) {
            super()
            this.circle = circle
            this.image = image
        }

        /**
         * onAdd is called when the map's panes are ready and the overlay has been
         * added to the map.
         */
        onAdd() {
            this.div = document.createElement('div')
            this.div.style.borderStyle = 'solid'
            this.div.style.borderWidth = '1px'
            this.div.style.borderColor = 'black'
            this.div.style.position = 'absolute'

            // Create the img element and attach it to the div.
            const img = document.createElement('img')

            img.src = this.image
            img.style.width = '30px'
            img.style.height = '30px'
            img.style.position = 'relative'
            this.div.appendChild(img)

            // Add the element to the "overlayMouseTarget" pane.
            const panes = this.getPanes()

            panes.overlayMouseTarget.appendChild(this.div)

            this.div.addEventListener('click', (element) => {
                console.log('nice click', element)
            })
        }

        draw() {
            // We use the south-west and north-east
            // coordinates of the overlay to peg it to the correct position and size.
            // To do this, we need to retrieve the projection from the overlay.
            const overlayProjection = this.getProjection()
            // Retrieve the south-west and north-east coordinates of this overlay
            // in LatLngs and convert them to pixel coordinates.
            // We'll use these coordinates to resize the div.

            // const point = new google.maps.Point(this.circle.center);
            
            const point = overlayProjection.fromLatLngToContainerPixel(this.circle.center);
            console.log(point, this.circle.radius);
            // const sw = overlayProjection.fromLatLngToDivPixel(
            //     region.getSouthWest()
            // )
            // const ne = overlayProjection.fromLatLngToDivPixel(
            //     region.getNorthEast()
            // )

            // Resize the image's div to fit the indicated dimensions.
            if (this.div) {
                this.div.style.left = point.x - 700 + 'px';
                this.div.style.top = point.y - 280 + 'px'
                // this.div.style.width = ne.x - sw.x + 'px'
                // this.div.style.height = sw.y - ne.y + 'px'
                console.log(this.div.style.left, this.div.style.top);
            }


        }

        /**
         * The onRemove() method will be called automatically from the API if
         * we ever set the overlay's map property to 'null'.
         */
        onRemove() {
            if (this.div) {
                this.div.parentNode.removeChild(this.div)
                delete this.div
            }
        }

        /**
         *  Set the visibility to 'hidden' or 'visible'.
         */
        hide() {
            if (this.div) {
                this.div.style.visibility = 'hidden'
            }
        }
        show() {
            if (this.div) {
                this.div.style.visibility = 'visible'
            }
        }
        toggle() {
            if (this.div) {
                if (this.div.style.visibility === 'hidden') {
                    this.show()
                } else {
                    this.hide()
                }
            }
        }
        toggleDOM(map) {
            if (this.getMap()) {
                this.setMap(null)
            } else {
                this.setMap(map)
            }
        }
    }

    createMapOverlayView(circle, image) {
        return new this.mapOverlayView(circle, image)
    }
}
