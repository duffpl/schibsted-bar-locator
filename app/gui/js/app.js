requirejs.config({
    baseUrl: 'js',
    paths: {
        async: 'lib/async'
    }
});

requirejs(["client", "lib/mustache", 'async!http://maps.google.com/maps/api/js?key=<ENTER_MAPS_API_KEY_HERE>&sensor=false'], function(client, mustache) {
    var currentCoordinates = {
        lat: 54.348545,
        lng: 18.6532295
    };
    function init() {
        var lat = currentCoordinates.lat;
        var lng = currentCoordinates.lng;
        map = new google.maps.Map(document.getElementById('google-map'), {
            center: {lat: lat, lng: lng},
            zoom: 14
        });
        new google.maps.Circle({
            strokeColor: '#006600',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '#008800',
            fillOpacity: 0.1,
            map: map,
            center: {lat: lat, lng: lng},
            radius: 2000
        });

        client.getList({
            latitude: lat,
            longitude: lng
        }).done(function(data){
            var $results = $('#results');
            var markers = {};
            $results.html(mustache.render($('#radar-list-template').html(), data.items));
            data.items.forEach(function (result) {
                markers[result.placeId] = new google.maps.Marker({
                    position: {lat: result.coordinates.latitude, lng: result.coordinates.longitude},
                    map: map,
                    title: 'Fetching details'
                });
            });
            $results.find('[data-place-id]').each(function () {
                var $this = $(this);
                client.getDetail($this.data('placeId')).done(function (detailData) {
                    markers[$this.data('placeId')].setTitle(detailData.name);
                    $this.html(mustache.render($('#place-detail').html(), detailData));
                    $this.data('detailData', detailData);
                    $this.on('click', function itemOnClick() {
                        $('#detailModal').html(mustache.render($('#place-modal-detail-template').html(), $(this).data('detailData'))).modal();
                    })
                });
            });
        });
    }
    navigator.geolocation.getCurrentPosition(function(geoLocation) {
        currentCoordinates.lat = geoLocation.coords.latitude;
        currentCoordinates.lng = geoLocation.coords.longitude;
        init();
    }, function (error) {
        init();
    });
});