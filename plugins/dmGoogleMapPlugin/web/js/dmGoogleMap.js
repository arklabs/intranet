(function($)
  {
    $.fn.dmGoogleMap = function(opt)
    {
      if(typeof google == "undefined" || typeof google.maps == "undefined")
      {
        alert('Please reload the page to activate the map');
        return this;
      }
    
      var
      self = this,
      options = $.extend({
        zoom: 14,
        mapTypeId: google.maps.MapTypeId.HYBRID
      }, self.metadata(), opt || {}),
      map,
      markers = [],
      infos = [];

      // use a precise center without marker
      if(options.center)
      {
        map = new google.maps.Map(self.get(0), $.extend(options, {
          center: new google.maps.LatLng(options.center[0], options.center[1])
        }));
      }
      // or center on an adress, with a marker
      else if(options.address)
      {
        new google.maps.Geocoder().geocode({address: options.address}, function(results, status)
        {
          if (status == google.maps.GeocoderStatus.OK && results.length && status != google.maps.GeocoderStatus.ZERO_RESULTS)
          {
            map = new google.maps.Map(self.get(0), $.extend(options, {
              center: results[0].geometry.location
            }));

            new google.maps.Marker({
              position: results[0].geometry.location,
              map: map
            });
          }
          else
          {
            self.text('Sorry, the address "'+options.address+'" can not be found');
          }
        });
      }

      // show other markers
      $.each(options.markers || [], function(it, data)
      {
        markers[it] = new google.maps.Marker({
          position:   new google.maps.LatLng(data.lat, data.lng),
          map:        map,
          clickable:  true,
          visible:    true,
          title:      data.title
        });

        if(data.icon) markers[it].icon = data.icon;

        if(data.html)
        {
          infos[it] = new google.maps.InfoWindow({
            content:    data.html
          });

          google.maps.event.addListener(markers[it], 'click', function() {
            infos[it].open(map, markers[it]);
          });
        }
      });

      return this;
    }
  })(jQuery);
