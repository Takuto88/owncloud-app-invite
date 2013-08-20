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
      <div class="invite-input">
        <label for="username"><?php p($l->t('Username')); ?></label>
        <input data-timer="0" id="username" type="text" name="username" original-title="" required/>
        <div>
            <em id="user-invalid"><?php p($l->t('Please enter a valid username')); ?></em>
            <em id="user-valid" style="display: none;"><?php p($l->t('Username OK')); ?></em>
        </div>
      </div>
      <div class="invite-input">
        <label for="email"><?php p($l->t('E-Mail')); ?></label>
        <input id="email" type="email" name="email" original-title="" required/>
        <div>
          <em id="email-invalid"><?php p($l->t('Please enter a valid  email address')); ?></em>
          <em id="email-valid" style="display: none;"><?php p($l->t('Email OK')); ?></em>
        </div>
      </div>
      <div class="invite-group-header">
        <label><?php p($l->t('The user belongs to these groups:')); ?></label>
      </div>
      <div>
        <?php foreach($_["groups"] as $group): ?>
        <label class="checkbox inline">
          <input type="checkbox" name="<?php p($group);?>"/> <?php p($group);?>
        </label>
        <?php endforeach;?>
      </div>
      <button>Invite</button>
    </fieldset>
  </form>
</div>