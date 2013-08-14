<?php
\OCP\Util::addScript('appframework', 'vendor/angular/angular');
\OCP\Util::addScript('invite', 'invite');
\OCP\Util::addStyle('invite', 'invite');
?>
<div id="app" data-ng-app="Invite">
  <form novalidate data-ng-controller="FormController" method="POST" action="#" data-ng-submit="invite()" name="inviteForm">
   <fieldset class="personalblock">
      <legend>
        <strong><?php p($l->t('Invite people to ownCloud')); ?></strong>
      </legend>
      <label for="username"><?php p($l->t('Username')); ?></label>
      <input data-ng-model="user.name" id="username" type="text" name="username" original-title="" class="ng-pristine ng-invalid ng-invalid-required" required/>
      <label for="email"><?php p($l->t('E-Mail')); ?></label>
      <input data-ng-model="user.email" id="email" type="email" name="email" class="ng-pristine ng-invalid ng-invalid-required" original-title="" required/>
      <button data-ng-disabled="inviteForm.$invalid" data-ng-click="test(user)">Invite</button>
    </fieldset>
  </form>
</div>