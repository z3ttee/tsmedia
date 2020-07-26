![Header](/concepts/github_header.png)

## State of Development
For information about the state of dev, please see the roadmap: [https://github.com/z3ttee/tsmedia/projects/1](https://github.com/z3ttee/tsmedia/projects/1)

## Features [WiP]
Below is a list of a full feature set, explaining what TSMedia does and is developed for:
* Upload short video clips or full length videos
* Categorize clips and videos
* Show clips and videos by specific users
* Create playlists of your beloved clips and videos
* Create rooms to watch along together
* Mirror uploaded clips from discord via discordbot

## Setup [WiP]
Currently there is nothing special to be noticed when setting up tsmedia. Everything is managable through the integrated webinterface and api. \
The only thing for you to do is, to configure your api and then visit it on ``/v1/install``. A setup will be started that generates all needed tables in the database and creates a user for you. (NOTE: **_You should change the password immediately_**) \
That user's credentials are:
* Username: ``admin``
* Password: ``hackme``
The moment the setup finishes successfully, the install endpoint will be deleted to prevent unwanted access.

## Concept Design
#### Front page
![Main Page](/concepts/main_page.png)
#### Front page Mobile
![Front Page](/concepts/main_page_mobile.png)
#### User page
![User Page](/concepts/user_page.png)
#### Login page
![Login Page](/concepts/login_page.png)