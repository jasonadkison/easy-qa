# Easy Q&A
 * Website: [http://emptyset.co/wordpress/easy-qa](http://emptyset.co/wordpress/easy-qa)

Easily create a responsive Q&A section for your Wordpress site. Key features include a built-in rating system, email notifications, social media sharing, search, topic categories, and the ability for visitors to ask questions.

####Requirements
npm, gulp, and a running Wordpress site.

####Setup
1. Clone this repo or download the source files to a directory.
2. Create a symlink for the plugin to the Wordpress plugins directory. e.g., `$ ln -s /path/to/plugin/build /path/to/wordpress/wp-content/plugins/easy-qa`
3. Install the plugin through the Wordpress dashboard.
4. Change your current directory to the plugin directory. `$ cd /path/to/plugin`
5. Install dependencies. `$ npm install`
4. Watch for code changes from the root project path. `$ cd /path/to/plugin && gulp`

####Development

#####watch
`$ gulp` or `$ gulp watch`

#####build
`$ gulp build`

#####dist (new release)
`$ gulp dist`

####Releases

* 2015-12-12 Initial release.
