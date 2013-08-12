<?php
\OCP\Util::addScript('appframework', 'vendor/angular/angular');
\OCP\Util::addScript('invite', 'invite');
?>
<div id="app" ng-app="Invite">
  <h1>Invites</h1>
  <ul ng-controller="ListController">
    <li>{{entry}}<li>
  </ul>
</div>