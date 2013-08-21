<?php
p($l->t('Hello %s,', array($_['invitee'])) . "\n\n");

p($l->t('%s has invited you to join ownCloud.' . " ", array($_['inviter'])));
p($l->t('You can accept this invitation by using the following link:' . "\n\n"));

print_unescaped($_['link'] . "\n\n");

p($l->t('If you received this notification in error or if you do not wish to join, simply ignore this mail.'));