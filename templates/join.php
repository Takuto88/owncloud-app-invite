<?php \OCP\Util::addStyle('invite', 'invite');?>
<?php $defaults = new OC_Defaults() ?>
<form action="<?php echo OC_Helper::linkToRoute('core_lostpassword_reset', $_['args']) ?>" method="post">
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
    <?php endif; ?>
  </fieldset>
</form>