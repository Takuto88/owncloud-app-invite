var inviteApp = angular.module('Invite', ['ngResource']);

inviteApp.controller('FormController', function($scope){
  $scope.user = {name: '', email: 'test'};

  $scope.test = function(user) {
    if(!user.$valid) {
      alert(user);
    }
  }

});

inviteApp.factory('User', function($resource){
  return $resource('user/exists/:userId', {}, {
    query: {method:'GET', params:{userId:'userId'}}
  });
})

inviteApp.directive('unique-username', ['email', function(email){
  // Runs during compile
  return {
    require: 'ngModel', // Array = multiple requires, ? = optional, ^ = check parent elements
    restrict: 'A', // E = Element, A = Attribute, C = Class, M = Comment
    link: function($scope, iElm, iAttrs, controller) {

    }
  };
}]);