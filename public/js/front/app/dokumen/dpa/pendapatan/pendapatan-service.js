app.factory('dpapendapatan', ['$http', function ($http) {
    return {

        setdpa: function(inputData){
            //return $http.post('/api/v1/set-dpa-pendapatan/');
            return $http({
                method: 'POST',
                url: '/api/v1/set-dpa-pendapatan',
                headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                data: $.param(inputData)
            });
        },

        setdpa: function(inputData){
            //return $http.post('/api/v1/set-dpa-pendapatan/');
            return $http({
                method: 'POST',
                url: '/api/v1/set-dpa-pendapatan',
                headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                data: $.param(inputData)
            });
        },

        // get data dengan pagination dan pencarian data
        get: function (page, term) {
            //return $http.get('/api/v1/pendapatan?page=' + page + '&term=' + term);
            return $http({
                method: 'get',
                url: '/api/v1/get-dpa-pendapatan?page=' + page + '&term=' + term,
                headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'}
                //data: $.param(inputData)
            });
        }
    }
}]);
