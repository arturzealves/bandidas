<script type="text/javascript">
    window.addEventListener('load', function () {
        // console.log('loaded');
        let locations = @js($locations);
        // console.log('locations', locations, typeof window.initMap, typeof window.GoogleMap, typeof window.drawCircles);

        window.drawCircles(window.GoogleMap, locations);
    });
</script>
