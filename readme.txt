=== Semantic Pullquote ===
Contributors: kylereicks
Donate link: http://shakopee.dollarsforscholars.org/
Tags: pullquote, pull quote, CSS, HTML5
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A Wordpress plugin for easy pullquotes using CSS.

== Description ==

A Wordpress plugin inspired by the blog post "[Pull Quotes with HTML5 and CSS](http://miekd.com/articles/pull-quotes-with-html5-and-css/)" by [Maykel Loomans](http://www.maykelloomans.com/).

> Blatantly copying the excerpt of the pull quote into it’s own element is not the way to go. A pull quote is a purely visual technique, and therefore should not change the structure of the body. Next to that, a structural representation of the excerpt would be seen twice by people using feed readers or services like Instapaper, as well as be re-read for people who use screen readers.

To Use
------

###The Shortcode

This plugin adds one shortcode `[pullquote]`, which is used to select the text for the pullquote. For example, in the following paragraph, the text, "Etiam laoreet cursus euismod. Vivamus non nisi nec magna accumsan hendrerit." has been selected to be pullquoted.

    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sed enim
    justo. Quisque tempus est a arcu tempor eget condimentum sapien
    venenatis. Nulla facilisi. Cras fringilla ante ut nunc imperdiet ac
    accumsan ante accumsan. [pullquote]Etiam laoreet cursus euismod.
    Vivamus non nisi nec magna accumsan hendrerit.[/pullquote] Etiam
    quis nulla vitae odio sodales fringilla. Pellentesque eros arcu,
    euismod vitae adipiscing non, convallis vel mauris. Suspendisse dapibus
    ligula interdum leo ultrices id accumsan lorem ullamcorper.

The `[pullquote]` shortcode takes one attribute, `position`. The `position` attribute adds a class of `pullquote-{position}` to the pullquote's parent paragraph. This class signals to the CSS how to display and sytle the pullquote.

The default CSS has three positions: `right`, `left`, and `top`. Whith no `position` attribute defined, the pullquote will default to `right`. When the default CSS is finally applied, the above example looks something like this:

![Pullquote screenshot](https://raw.github.com/kylereicks/semantic-pullquotes/master/assets/pullquote-example.png)
== Installation ==

1. First, make sure that the image sizes set in your media settings reflect useful breakpoints in your design.
2. Upload the plugin folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is this plugin on GitHub? =

Yes it is. [Picturefill.WP](https://github.com/kylereicks/semantic-pullquotes)

== Screenshots ==

![Pullquote screenshot](https://raw.github.com/kylereicks/semantic-pullquotes/master/assets/pullquote-example.png)

== Changelog ==

= 1.0 =
* Release 1.0.

== Upgrade Notice ==

= 1.0 =
This is the initial public release.
