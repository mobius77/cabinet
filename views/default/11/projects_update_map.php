<?php ?>


<input id="pac-input" type="text" placeholder="Search Box">
<div id="map"><img src="\img\loader.gif" /></div>

<div class="row" id="panel" data-id='<?= Yii::$app->request->get('id') ?>' style="margin-top: 10px;">
    <div class="col-lg-4" style="float:right;">
        <button style="margin: 5px;float:right;" id="save-button" class="btn btn-primary">Зберегти</button>
        <button style="margin: 5px;float:right;" id="delete-button" class="btn btn-primary">Видалити виділене</button>
    </div>
</div>


<?php
$item = \common\models\ValTree33::find()->where('tree_id=' . Yii::$app->request->get('id') . ' AND lang="uk"')->one();
$val = unserialize($item->p_map);

if ($item->p_map != '')
{
    $gps = $val['centr'];
    $zoom = $val['zoom'];
}
else
{
    $gps = '47.266802, 35.720624';
    $zoom = 8;
}

$scr = " 
   
     function initialize() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: ".$zoom.",
          center: new google.maps.LatLng(".$gps."),
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          disableDefaultUI: false,
          zoomControl: true
        });
        
     
        
         shapes.load([";

$item = \common\models\ValTree33::find()->where('tree_id=' . Yii::$app->request->get('id') . ' AND lang="uk"')->one();
if ($item->p_map != '') {
    $cols = $val['col'];
    foreach ($cols as $col) {
        switch ($col['type']) {
            case 'polygon':
                $scr .= '{"type":"' . $col['type'] . '","path":"' . addslashes($col['path']) . '"},';
                break;
            case 'marker':
                $gps = explode(',', $col['pos']);

                $scr .= '{"type":"' . $col['type'] . '","lat":"' . ($gps[0]) . '","lng":"' . ($gps[1]) . '"},';
                break;
        }
    }
}



/*  {'type':'polygon','path':'mnngCchxvT?y{DylG{{D~tFihCng_@v{O?wiVymDdPzNblLah\\\i}LksLngJ'} */

$scr .= "   ]);


        drawingManager = new google.maps.drawing.DrawingManager({
        
         
            drawingMode: google.maps.drawing.OverlayType.MARKER,
            drawingControl: true,
            drawingControlOptions: {
              position: google.maps.ControlPosition.TOP_CENTER,
              drawingModes: [
               google.maps.drawing.OverlayType.MARKER,
                google.maps.drawing.OverlayType.POLYGON,
               
         
              ]
            },

          markerOptions: {
            draggable: true,
            editable: true,
          },
          polylineOptions: {
            editable: true
          },
          rectangleOptions: polyOptions,
          circleOptions: polyOptions,
          polygonOptions: polyOptions,
          map: map
        });

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
         
            var isNotMarker = (e.type != google.maps.drawing.OverlayType.MARKER);
      
            drawingManager.setDrawingMode(null);

           
            var newShape = e.overlay;
            newShape.type = e.type;
            
            shapes.add(e);
            
            google.maps.event.addListener(newShape, 'click', function() {
              setSelection(newShape, isNotMarker);
            });
            google.maps.event.addListener(newShape, 'drag', function() {
              updateCurSelText(newShape);
            });
            google.maps.event.addListener(newShape, 'dragend', function() {
              updateCurSelText(newShape);
            });
            setSelection(newShape, isNotMarker);
       
        });

   
        google.maps.event.addListener(drawingManager, 'drawingmode_changed',  function(){shapes.clearSelection(); clearSelection(); } );
        google.maps.event.addListener(map, 'click', function(){shapes.clearSelection(); clearSelection(); });
        google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', function(){shapes.deleteSelected(); deleteSelectedShape(); } );



       
         input =( 
            document.getElementById('pac-input'));
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
      
        var DelPlcButDiv = document.createElement('div');
    
        DelPlcButDiv.style.backgroundColor = '#fff';
        DelPlcButDiv.style.cursor = 'pointer';
        DelPlcButDiv.innerHTML = 'DEL';
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(DelPlcButDiv);
        google.maps.event.addDomListener(DelPlcButDiv, 'click', deletePlacesSearchResults);

        searchBox = new google.maps.places.SearchBox( (input));


        google.maps.event.addListener(searchBox, 'places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }
          for (var i = 0, marker; marker = placeMarkers[i]; i++) {
            marker.setMap(null);
          }

          
          placeMarkers = [];
          var bounds = new google.maps.LatLngBounds();
          for (var i = 0, place; place = places[i]; i++) {
            var image = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

           
            var marker = new google.maps.Marker({
              map: map,
              icon: image,
              title: place.name,
              position: place.geometry.location
            });

            placeMarkers.push(marker);

            bounds.extend(place.geometry.location);
          }

          map.fitBounds(bounds);
        });

        
        google.maps.event.addListener(map, 'bounds_changed', function() {
          var bounds = map.getBounds();
          searchBox.setBounds(bounds);
         /* curposdiv.innerHTML = '<b>curpos</b> Z: ' + map.getZoom() + ' C: ' + map.getCenter().toUrlValue();*/
        }); 
        
        
        google.maps.event.addDomListener(document.getElementById('save-button'), 
                                      'click', 
                                      function(){shapes.save();
         setTimeout(function (){

            window.location='update-project?id=".Yii::$app->request->get('id')."';     

           }, 1000);                        
});
        
   
      }
      
      google.maps.event.addDomListener(window, 'load', initialize);
   
   ";







$this->registerJs($scr, $this::POS_END);
