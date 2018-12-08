# Cockpit Next Dokku

**WARNING: This repository is meant to get Cockpit Next deployed on a Dokku instance. It is meant for personal use, and while you are more than welcome to use it, do not expect support with it. For all information about Cockpit itself, please visit [Cockpit on Github](https://github.com/agentejo/cockpit).**

* Homepage: [http://getcockpit.com](http://getcockpit.com)
* Twitter: [@getcockpit](http://twitter.com/getcockpit)
* Support Forum: [https://discourse.getcockpit.com](https://discourse.getcockpit.com)

### Changes

Some modifications where made in order for the deployment to work as expected:

* Change to the Dockerfile to include the repository
* Add docker-config.php from [cockpit-docker](https://github.com/COCOPi/cockpit-docker)
* Change in the docker-config.php script to use the default `MONGO_URL` instead of the COCKPIT_DATABASE_SERVER and `COCKPIT_DATABASE_NAME`
* Change the Dockerfile to use docker-config.php as the config script

### Requirements

* Docker
* Dokku (in sub domain config if possible)
* Dokku Mongo Addon
* Dokku Letsencrypt Addon


### Installation

1. Download this Cockpit Dokku repository (as a repo, not as git) to your development machine
2. Make sure your development machine has the rights to push to the Dokku instance
3. Add the Dokku remote address: `git remote add dokku dokku@your.dokku.url:cockpit` where `your.dokku.url` points to your machine and `cockpit` is the name of your Cockpit application
4. Push to your code to Dokku: either use `git push dokku next:master` or `git push dokku next:refs/heads/master` when the first one fails (sometimes this happens on first builds). (Note: first build takes long enough to grab yourself a coffee)
5. Login to your Dokku instance and create a new MongoDB: `dokku mongo:create cockpitdb` where `cockpitdb` is the name of your mongo database.
6. Link your Mongo Database to your application: `dokku mongo:link cockpitdb cockpit` where `cockpitdb` is the name of your db in step 5 and `cockpit` is the name of your app in step 3.
7. Mount a persistent storage volume to your application: `dokku storage:mount cockpit /var/lib/dokku/data/storage/cockpit:/var/www/html/storage` where once again `cockpit` stands for the chosen application name in Step 3
8. Make the storage folder `mkdir -p /var/lib/dokku/data/storage/cockpit` (might require sudo)
9. Go to your freshly created folder: `cd /var/lib/dokku/data/storage/cockpit` 
10. Make the necessary subfolders: `mkdir cache data thumbs tmp uploads` (might require sudo)
11. Set the correct rights to the folder and subfolders: `chmod 777 -R /var/lib/dokku/data/storage/cockpit` (might require sudo)
12. Rebuild the application for the changes to take effect: `dokku ps:rebuild cockpit`
13. Go to cockpit.your.dokku.url/install to receive a new username/password (usually admin/admin)
14. Click the login button at the bottom of the page & log in with admin/admin
15. Go to your account cirle in the top right > press Account and change your password & email!

You might want to go further and test if everything is set up correctly with the next section.

### Testing the installation

To test the installation, the easiest way is to create something and then rebuild the app.

1. On the dashboard press 'Create a singleton'
2. Set the following: `name: test` `label: test` `description: test`
3. Add a field and call the fieldname `test`
4. Press SAVE
5. Next to save press SHOW FORM
6. enter `test` as value and press save

Now the database has some data (the singleton content) and the storage has some data (the singleton format & fields). Proceed by restarting the app

7. `dokku ps:rebuild cockpit`
8. Go to cockpit.your.dokku.url and log in with your new password (and new account name if you changed that in the settings)
9. If all went well you will be able to log in; if not try to visit cockpit.your.dokku.url/install and check if the instalation already happened. If not, you've got a problem with your MongoDB

If you see the `test` singleton, both your storage and MongoDB are running as expected. If you don't see the singleton there is a problem with your storage mount

To further check your MongoDB when your storage is broken, you can recreate steps 1 to 5. If Mongo was configured correctly you'll see that `test` has been prefilled by the DB.

### Securing the installation

To ensure save data tranfer, get a free ssl certificate from letsencrypt:
1. Set your contact details: `dokku config:set --no-restart cockpit DOKKU_LETSENCRYPT_EMAIL=YOUR_EMAIL` where cockpit is the name of your application and YOUR_EMAIL is your actual email address (this is important)
2. Generate the certificate: `dokku letsencrypt cockpit`
3. Make sure you always have a fresh certificate: `dokku letsencrypt:cron-job --add`

If you now log out and go back to cockpit.your.dokku.url you should be getting the https connection & lock.
You'll keep having it when you log back in.

### Extending the installation, useful plugins

- For easy adding of groups to your cockpit, see: https://github.com/serjoscha87/cockpit_GROUPS
- For group based assets, see: https://github.com/serjoscha87/cockpit_GroupBoundAssets
- For external storage, you can use: https://github.com/agentejo/CloudStorage (note that with https://github.com/agentejo/CloudStorage/pull/3, we might see Minio support soon. Minio also works on dokku)

- For more official plugins, see: https://github.com/agentejo

### Removal instructions

1. `dokku apps:destroy cockpit`, confirm by typing cockpit
2. `dokku mongo:destroy cockpitdb`, configry by typing cockpitdb
3. `rm -rf /var/lib/dokku/data/storage/cockpit` (might require sudo)



### Copyright and license

Copyright 2015 [Agentejo](http://www.agentejo.com) under the MIT license.

The MIT License (MIT)

Copyright (c) 2015 Agentejo, http://agentejo.com

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### 💐 SPONSORED BY

[![ginetta](https://user-images.githubusercontent.com/321047/29219315-f1594924-7eb7-11e7-9d58-4dcf3f0ad6d6.png)](https://www.ginetta.net)<br>
We create websites and apps that click with users.


[![BrowserStack](https://user-images.githubusercontent.com/355427/27389060-9f716c82-569d-11e7-923c-bd5fe7f1c55a.png)](https://www.browserstack.com)<br>
Live, Web-Based Browser Testing


# OpenCollective

## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/cockpit#backer)]

<a href="https://opencollective.com/cockpit#backers" target="_blank"><img src="https://opencollective.com/cockpit/backers.svg?width=890"></a>

## Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website. [[Become a sponsor](https://opencollective.com/cockpit#sponsor)]

<a href="https://opencollective.com/cockpit/sponsor/0/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/1/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/2/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/3/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/4/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/5/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/6/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/7/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/8/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/cockpit/sponsor/9/website" target="_blank"><img src="https://opencollective.com/cockpit/sponsor/9/avatar.svg"></a>


# OTHERS

## EXPOSES
27017->17229 27018->7796 27019->4058 28017->15788 

## BACKUP

```bash
mongodump --host matryoshka.com --port 24575 --db cockpitdb -u admin -p db66643ac72e9304353d812e9880638d --archive=./dump-2018-11-27 --authenticationDatabase admin
```
## RESTORE
```bash
mongorestore --noIndexRestore --host 127.0.0.1 --db cockpitdb --port 17229 --username admin --password 75693265d95ba416bc4937b706efa566 --archive=dump-2018-11-27 --drop --authenticationDatabase=admin
```
