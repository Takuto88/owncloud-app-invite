<?php \OCP\Util::addStyle('invite', 'invite');?>
<?php $defaults = new OC_Defaults() ?>
<form action="<?php p(OC_Helper::linkToRoute('invite_join_submit')) ?>" method="post">
    <?php if (isset($_['invalidpassword']) && ($_['invalidpassword'])): ?>
    <ul>
      <li class="errors">
          <?php p($l->t('Passwords do not match!')); ?>
      </li>
    </ul>
    <?php endif; ?>
  <fieldset>
    <?php if($_['validTokenAndUser']): ?>
      <p>
        <label for="username" class="join-label"><?php p($l->t('Username') . ":"); ?></label>
        <input type="text" name="username" value="<?php p($_['username']); ?>" disabled required />
      </p>
      <p>
        <label for="password" class="join-label"><?php p($l->t('Choose a password') .":"); ?></label>
        <input type="password" name="password" value="" required/>
      </p>
      <p>

        <label for="password-repeat" class="join-label"><?php p($l->t('Confirm your password') .":"); ?></label>
        <input type="password" name="password-repeat" value="" required/>
      </p>
      <p style="text-align: center;">
        <input style="margin-top: 10px;" type="submit" id="submit" value="<?php p($l->t('Join %s', array($defaults->getName()))) ?>" />
      </p>
    <?php else: ?>
      <ul>
        <li class="errors">
            <span style="font-weight: bold;"><?php p($l->t('Your invite link has expired!')); ?></span>
            <p>
              <small><br/><?php p($l->t('Please contact the person who has invited you and ask for a new one.')) ?></small>
            </p>
        </li>
      </ul>
    <?php endif; ?>
  </fieldset>
</form>