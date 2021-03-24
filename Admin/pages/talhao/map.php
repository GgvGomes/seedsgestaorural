<?php
$Cor = $_REQUEST["Cor"];
?>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvA6oCDvgYXss9bGDdNPcbIkiLfFpcucQ&callback=initMap&libraries=drawing&v=weekly" defer></script>

<div id="map" style="width: 100%;height: 300px;"></div>
<script>
    jQuery(function($) {
        map;
        initDrawing = (map) => {
            if (CorZona != null && CorZona != "") {
                drawingManager = new google.maps.drawing.DrawingManager({
                    map: map,
                    drawingMode: google.maps.drawing.OverlayType.CICLE,
                    drawingControl: true,
                    drawingControlOptions: {
                        position: google.maps.ControlPosition.TOP_CENTER,
                        drawingModes: ['polygon']
                    },
                    polygonOptions: {
                        fillColor: '#<?php echo $Cor; ?>',
                        strokeColor: '#<?php echo $Cor; ?>'
                    }
                });
                letste = google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
                    const coords = polygon.getPath().getArray().map(coord => {
                        return {
                            lat: coord.lat(),
                            lng: coord.lng()
                        }
                    });

                    LocalizacaoMaps = JSON.stringify(coords, null, 1);
                    // SAVE COORDINATES HERE
                });
            }
        };

    });

    function initMap(codenacoes) {
        const myLatlng = {
            lat: -15.699223460885563,
            lng: -47.98856710942802
        };
        const map = new google.maps.Map(document.querySelector("#map"), {
            zoom: 4,
            center: myLatlng,
        });
        initDrawing(map);
        // Define the LatLng coordinates for the polygon's path.
        const triangleCoords = codenacoes;
        // Construct the polygon.
        const bermudaTriangle = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: "#<?php echo $Cor; ?>",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#<?php echo $Cor; ?>",
            fillOpacity: 0.35,
        });
        bermudaTriangle.setMap(map);
    }
</script>