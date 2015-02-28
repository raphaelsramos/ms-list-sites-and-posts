README

Trying associate posts between Multisites? When activated, this plugins show a list of sites in the network. When the site is selected, it shows the list of posts (of same post type) for association.

For list the site in the plugin list, it must be activated in the Settings session, and can be set a specific flag for each language.


### HELPERS

* get_lsap_related_sites() : return array of sites with the plugin activated with the fields ( id, title, link, flag )
* get_lsap_related_sites_links() : return array of html link elements for activated sites ( <<< <a href="{link}" title="{title}" class="lsap-item"><img src="{flag}" alt="{title}" /></a> >>> )
* the_lsap_related_sites_links() : echo the get_lsap_related_sites_links() list imploded by "\n"
* get_lsap_related_posts( $postID ) : return array of posts associated to $postID with fields ( title, link, flag )
* get_lsap_related_posts_links( $postID ) : return array of html links ( <<< <a href="{link}" title="{title}" class="lsap-item"><img src="{flag}" alt="{title}" /></a> >>> )
* the_lsap_related_posts_links( $postID ) : echo the get_lsap_related_posts_links( $postID ) list imploded by "\n"


### TO-DO

* Shortcodes
* Filters
* Translation
* Update Flag upload with the new Media UI version
* Select2 library for better UI