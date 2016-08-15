define(function client() {
    return {
        getList: function(coords) {
            return $.get('/place', coords);
        },
        getDetail: function(placeId) {
            return $.get('/place/' + placeId);
        }
    }
});