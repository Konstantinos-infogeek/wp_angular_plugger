var AngApp = angular.module('AngApp', []);


var displayData = function ($scope, $http) {

    $scope.setNewTitle = function () {
        var request = {
            url: angvars.admin,
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'},
            params: { action: 'ap_get_data'},
            data : serialize({ 'id': $scope.postId})
        };

        $http(request).success(function(data){
            $scope.title = data.post
        });
    }
}

var serialize = function (object) {
    var _temp = [];
    for(prop in object){
        _temp.push(prop+'='+object[prop]);
    }
    return _temp.join('&');
}


//MyController
AngApp.controller('MyController', ['$scope', '$http', displayData]);