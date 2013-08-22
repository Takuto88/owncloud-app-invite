<?php
p($l->t('Hello %s,', array($_['invitee'])) . "\n\n");

p($l->t('%s has invited you to join %s.' . " ", array($_['inviter'], $_['productname'])));
p($l->t('You can accept this invitation by using the following link:' . "\n\n"));

print_unescaped($_['link'] . "\n\n");

p($l->t('If you have received this notification in error or if you do not wish to join, simply ignore this mail.'));