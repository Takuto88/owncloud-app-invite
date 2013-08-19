<?php
\OCP\Util::addScript('invite', 'invite');
\OCP\Util::addStyle('invite', 'invite');
?>
<div id="app" >
  <form method="POST" action="#" name="inviteForm">
   <fieldset class="personalblock">
      <legend>
        <strong><?php p($l->t('Invite people to ownCloud')); ?></strong>
      </legend>
      <label for="username"><?php p($l->t('Username')); ?></label>
      <input data-timer="0" id="username" type="text" name="username" original-title="" class="ng-pristine ng-invalid ng-invalid-required" required/>
      <label for="email"><?php p($l->t('E-Mail')); ?></label>
      <input id="email" type="email" name="email" class="ng-pristine ng-invalid ng-invalid-required" original-title="" required/>
      <select data-placeholder="groups" title="<?php p($l->t('Groups'))?>" multiple="multiple">
        <?php foreach($_["groups"] as $group): ?>
        <option value="<?php p($group);?>">
          <?php p($group);?>
        </option>
        <?php endforeach;?>
      </select>
      <br/>
      <em id="user-invalid"><?php p($l->t('Enter a valid username')); ?></em>
      <em id="user-valid" style="display: none;"><?php p($l->t('Username is OK')); ?></em>
      </br>
      <em id="email-invalid"><?php p($l->t('Enter a valid email address')); ?></em>
      <em id="email-valid" style="display: none;"><?php p($l->t('Email is OK')); ?></em>
      </br>
      <button>Invite</button>
    </fieldset>
  </form>
</div>