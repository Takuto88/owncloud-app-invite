README
======

The Invitations app allows you to invite people by mail to your ownCloud instance. 

After installing, a new app will be avaliable to both administrators and group administrators called 'Invitations'.

* It asks for a username that will be used for the users login
* It asks for a email address. This will be used to send the invitation. Also, it will setup this email inside the users 'Personal' settings so they can recover their password at any time.
* One can assign groups. This is optional for administrators. Group administrators must assign at least one of the groups that they administer.
* The invited user will receive an email containing a link where he can set his own password and login afterwards.

The source code is `available on GitHub <https://github.com/Takuto88/owncloud-app-invite>`_


Maintainers
-----------
* `Lennart Rosam <https://github.com/Takuto88>`_


Bugs
----
DRAGONS AHEAD!
~~~~~~~~~~~~~~

**THIS APP IS STILL EXPERIMENTAL**. It works - for me. It may or may not work for you. Bugreports are always welcome of course.

Before reporting bugs please make sure you have done the following:
* get the newest version of the App Framework
* get the newest version of the Invitations app
* `check if they have already been reported <https://github.com/Takuto88/owncloud-app-invite/issues?state=open>`_


Before you install the Invitations app
--------------------------------------
Before you install the app check that the following requirements are met:

* ownCloud Server 5.0.10 or greater is installed (might work with lower versions down to 5.0.0, untested though)
* App Framework 0.102 or greater

Installation
------------

App Store
~~~~~~~~~
As of now, this app is still **experimental** and therefore not on the appstore yet.

Git (development version)
~~~~~~~~~~~~~~~~~~~~~~~~~

- Clone the **App Framework** app into the **/var/www** directory::

	git clone https://github.com/owncloud/appframework.git

- Clone the **Invitations** app into the **/var/www** directory::

	git clone https://github.com/Takuto88/owncloud-app-invite.git


- Link both into ownCloud's apps folder::

	ln -s /var/www/appframework /var/www/owncloud/apps
	ln -s /var/www/owncloud-app-invite /var/www/owncloud/apps/invite

- Activate the **App Framework** App first, then activate the **Invitations** app in the apps menu


Keep up to date
~~~~~~~~~~~~~~~

To get the newest update you can use git. To update the appframework use::

    cd /var/www/appframework
    git pull --rebase origin master


To update the Invitations app use::

    cd /var/www/owncloud-app-invite
    git pull --rebase origin master


Thanks to
=========
* My employing company, MSP - Medien Systempartner GmbH (Bremen, Germany)
    * For letting me work on this project during work hours and open source it
* The entire awesome ownCloud community, especially the team behind the App Framework.
    * This framework made developing apps a lot more sane! Thank you for your work!