=== Display Authors Widget ===
Contributors: sami.keijonen
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E65RCFVD3QGYU
Tags: widget-only, widget, author, authors, multi, role 
Requires at least: 4.0
Tested up to: 4.3
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display authors by role.

== Description ==

This plugin register a widget to display authors by role in a sidebar. You can choose whether to display author post count, biography or gravatar.

= Usage =

Go to Appearance >> Widgets. There you can find widget called Display Authors Widget. There are some settings in a widget.

* Title: Set whatever title you want, or leave it blank to disable the title.
* Role: Choose which role authors you want to display.
* Display Post Count?: This displays the number of posts the author have published. This will only be shown if there are at least one post by author. The format
is Author name ($number). 
* Display Author Bio?: If checked, this will display the author's bio as set in their WordPress profile page.
* Display Author Gravatar?: If checked, this will display the author's gravatar.
* Gravatar Size: This is the size of the gravatar image in pixels. Do not write px or pixels in this field. If you want gravatar size to be 50px, then write "50" in this field.
* Gravatar Alignment: Choose whether you want to display gravatar on the left, right or no alignment at all.
* Limit: This will allow you to set the number of authors that are displayed in the widget.


== Installation ==

1. Upload `display-authors-widget` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to Appearance >> Widgets. There you can find widget called Display Authors Widget.

== Frequently Asked Questions ==

= I installed and activated this plugin. What to do next? =

Go to Appearance >> Widgets. There you can find widget called Display Authors Widget. Just drag it in widget area, if your theme supports them.

== Screenshots ==

1. Display Authors Widget

== Changelog ==

= 1.1.1 =

* Update Plugin and Author URL.

= 1.1 =

* Use PHP5 object constructors in Widgets (parent::__construct).
* Tested up to 4.3.

= 0.1.4 =

* Tested up to 3.9.

= 0.1.2 =

* Language files updated, .pot file added.

= 0.1.1 =

* Added posibility to not show author name.
* Language files updated.

= 0.1 =
* Everything's brand new.