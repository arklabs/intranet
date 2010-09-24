<?php

use_helper('DmGoogleMap');

echo _map()                           // get a map
->address($address)                   // search an address
->zoom(12)                            // zoom level (1-20)
->navigationControl(true)             // show navigation controls
->mapTypeControl(true)                // show map type controls
->scaleControl(true)                  // show zoom controls
->style('width: 100%; height: 250px') // hardcode width and height (use CSS instead)
->splash('El Mapa se está cargando ....');     // show a wait message while the map loads

