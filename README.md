---
description: Welcome to the TSAlliance
---

# Welcome

![Header](.gitbook/assets/github_header.png)

## Demo

[Check out the demo](https://easternexploration.de/tsmedia/)

## State of Development

For information about the state of dev, please see the roadmap: [https://github.com/z3ttee/tsmedia/projects/1](https://github.com/z3ttee/tsmedia/projects/1)

## API Documentation

You can visit the full api documentation on: [https://javadoc.zitzmann-cedric.de/apidoc/tsmedia/](https://javadoc.zitzmann-cedric.de/apidoc/tsmedia/)

## Requirements

* PHP 7+ \(NOTE: Ensure to enable php short tags on your php installation, in `php.ini` set `short_open_tag=On`\)
* FFMpeg \(Optional, only needed for automatic thumbnail generation\)

## Features \[WiP\]

Below is a list of a full feature set, explaining what TSMedia does and is developed for:

* [x] Upload short video clips or full length videos
* [x] Fully manageable user and permission system
* [x] Mirror uploaded clips from discord via discordbot
* [ ] Mobile compatible
* [ ] Categorize clips and videos
* [ ] Show clips and videos by specific users
* [ ] Create playlists of your beloved clips and videos
* [ ] Create rooms to watch along together

## Installation of Front-End

To fit every environment you need to compile the project for yourself. For that please have a look on [how to deploy vuejs applications for production](https://vuejs.org/v2/guide/deployment.html). Before compiling you want to change the baseURL in the `config.json` file to the url you have the api set up \(e.g. `http(s)://localhost/api/`\).   
 You need to install tsmedia in a subdirectory of your webserver's root document? If so, you want to keep on reading this passage, otherwise skip.   
 Because of the natural behaviour of vue-router you need to find `router` and want to change the `base` option according to your subdirectory you have chosen \(e.g. `tsmedia/`\)   
 After building for production please ensure the path to index.html and the `RewriteBase` is set correctly, otherwise rewriting the url won't work. The rule could look like this:

```text
RewriteBase /path/to/
RewriteRule ^(.*)$ /path/to/index.html?$1 [L,QSA]
- OR -
RewriteBase /tsmedia/
RewriteRule ^(.*)$ /tsmedia/index.html?$1 [L,QSA]
- OR -
RewriteBase /
RewriteRule ^(.*)$ index.html?$1 [L,QSA]
```

Additional things to consider checking:

* Is mod\_rewrite enabled? Command on linux: `a2enmod rewrite`
* Is mod\_headers enabled? Command on linux: `a2enmod headers`

  
**NOTE:** Same things must be done on `.htaccess` in your api installation \(Changing RewriteBase and RewriteRule accordingly to redirect to `index.php` in your api root   
**Another NOTE:** Ensure the webserver user has read and write permissions, otherwise no thumbnails or videos will be uploaded

## API Setup \[WiP\]

The only thing for you to do is, to configure your api and then visit it on `/v1/install`. A setup will be started that generates all needed tables in the database and creates a user for you. \(NOTE: _**You should change the password immediately**_\)   
 That user's credentials are:

* Username: `admin`
* Password: `hackme`

  ~~The moment the setup finishes successfully, the install endpoint will be deleted to prevent unwanted access.~~  

#### Automatic Thumbnails \[WiP\]

In order to use the automatic thumbnail generation by the api itself you need to install FFMpeg on your system. ONLY WINDOWS: You then specify the path to the ffmpeg bin directory in the api's config.json.

## Discord Integration

The following bot is capable of mirroring uploaded videos:  
 [DiscordBot by zettee](https://github.com/z3ttee/discordbotkt)

## Concept Design

### Front page

![Main Page](.gitbook/assets/main_page.png)

### Front page Mobile

![Front Page](.gitbook/assets/main_page_mobile.png)

### User page

![User Page](.gitbook/assets/user_page.png)

### Login page

![Login Page](.gitbook/assets/login_page.png)

