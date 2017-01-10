# Easy Q&A
 * Demo: [http://wpdev.jasonadkison.com/](http://wpdev.jasonadkison.com/)

Easily create a responsive Q&A section for your Wordpress site. Key features include a built-in rating system, email notifications, social media sharing, search, topic categories, and the ability for visitors to ask questions.

## Shortcodes
Paste these shortcodes into any page.
```
[easy_qa partials="search-form"]

[easy_qa partials="results"]

[easy_qa partials="topics-list"]
```

## Development

Clone this repository and choose an option below. Run all commands from within the main repository directory.

#### Option 1 (Preferred)
1. [install docker compose](https://docs.docker.com/compose/install/) (if not already installed)
1. build and start docker services: `docker-compose up -d`
1. configure wordpress at `http://localhost:8000/`
1. activate plugin at `http://localhost:8000/wp-admin/plugins.php`
1. configure recaptcha at `http://localhost:8000/wp-admin/options-general.php?page=easy-qa-admin`
1. install gulp and bower `sudo npm install -g gulp bower`
1. install dependencies `npm install && bower install`
1. start development processes with gulp watch `gulp`

#### Option 2 (Symlinking into an existing Wordpress instance)
1. Create a symlink for the plugin to the Wordpress plugins directory. e.g., `$ ln -s /path/to/easy-qa/build /path/to/your/wordpress/wp-content/plugins/easy-qa`
1. Install the plugin through the Wordpress dashboard.
1. Change your current directory to the plugin directory. `$ cd /path/to/easy-qa`
1. Install Gulp and Bower globally. `$ sudo npm install gulp bower -g`
1. Install dependencies. `$ npm install && bower install`
1. Watch for code changes from the root project path. `$ cd /path/to/plugin && gulp`

##### main development task (build + watch)
`$ gulp` or `$ gulp watch`

##### creates a manual build
`$ gulp build`

##### dist (creates a release)
`$ gulp dist`

#### Releases

* 2015-12-12 Initial release.
