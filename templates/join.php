<?php \OCP\Util::addStyle('invite', 'invite');?>
<?php $defaults = new OC_Defaults() ?>
<form action="<?php print_unescaped(OC_Helper::linkToRoute('invite_join_submit')) ?>" method="post">
        <ul>
          <?php if((isset($_['validPassword']) && !$_['validPassword'])): ?>
          <li class="errors">
              <span style="font-weight: bold;"><?php p($l->t('Please choose a strong password!')); ?></span>
              <p>
                <small><br/><?php p($l->t('A strong password must be provided.')) ?></small><br/>
                <small><?php p('- ' . $l->t('Make sure the password is at least 6 characters long')) ?></small>
                <small><br/><?php p('- ' . $l->t('It must contain one upper and one lowercase letter')) ?></small>
                <small><br/><?php p('- ' . $l->t('It must contain at least one letter or special character')) ?></small>
              </p>
          </li>
          <?php endif; ?>
          <?php if(isset($_['passwordMissmatch']) && $_['passwordMissmatch']): ?>
            <li class="errors">
                <span style="font-weight: bold;"><?php p($l->t('The passwords did not match.')); ?></span>
            </li>
          <?php endif; ?>
          <?php if(!$_['validTokenAndUser']): ?>
          <li class="errors">
              <span style="font-weight: bold;"><?php p($l->t('Your invite link has expired!')); ?></span>
              <p>
                <small><br/><?php p($l->t('Please contact the person who has invited you and ask for a new one.')) ?></small>
              </p>
          </li>
          <?php endif; ?>
        </ul>
  <fieldset>
    <?php if($_['validTokenAndUser'] && (!isset($_['success'])|| !$_['success'])): ?>
      <p>
        <label for="username" class="join-label"><?php p($l->t('Username') . ":"); ?></label>
        <input type="text" value="<?php p($_['username']); ?>" disabled />
        <input type="hidden" name="username" value="<?php p($_['username']) ?>"/>
      </p>
      <p>
        <label for="password" class="join-label"><?php p($l->t('Choose a password') .":"); ?></label>
        <input type="password" name="password" value="" required/>
      </p>
      <p>

        <label for="password-repeat" class="join-label"><?php p($l->t('Confirm your password') .":"); ?></label>
        <input type="password" name="password-repeat" value="" required/>
      </p>
      <input type="hidden" name="token" value="<?php p($_['token']) ?>"/>
      <p style="text-align: center;">
        <input style="margin-top: 10px;" type="submit" id="submit" value="<?php p($l->t('Join %s', array($defaults->getName()))) ?>" />
      </p>
    <?php elseif(isset($_['success']) &&  $_['success']): ?>
      <ul>
        <li class="success">
            <span style="font-weight: bold;"><?php p($l->t('Success! Welcome to %s', array($defaults->getName())) . ", " . $_['username']); ?></span>
            <p>
              <small><br/><a href="<?php print_unescaped(OC_Helper::linkTo('', 'index.php')) ?>"><?php p($l->t('Please click here to log in')) ?></a></small>
            </p>
        </li>
      </ul>
    <?php endif; ?>
  </fieldset>
</form>