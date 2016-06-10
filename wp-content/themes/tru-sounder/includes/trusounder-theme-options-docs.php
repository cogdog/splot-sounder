<h4>Setting Up a New Sound Collector site</h4>

<p>(1) Create a fresh Wordpress site. </p>

<p>(2) Create a User with login name of "sounder" with Author role. No one actually uses the account, the site invisibly logs in guests as this user. Make note of the password. Make it cryptic.</p>


<p>(3) Install the <a href="https://wordpress.org/themes/baskerville">Baskerville theme</a>.</p>

<p>(4) Install the TRU Sound Collector theme downloaded as part of this repo; either by uploading to your wp-content/themes directory or making  ZIP if just the tru-collector contents and uploading as a theme.</p>

<p>(5) Activate TRU Sound Collector as the site's theme. In this theme, Posts are renamed <strong>sounds</strong> and thus on the dashboard:</p>

<p> In this theme Wordpress <code>Posts</code> are renamed <code>Sounds</code> but have all the attributes of garden variety blog posts.</p>

<p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/sounder-menu.jpg" alt="Renamed Posts Menu" title="sound menu"></p>


<h4>Access Code and Hint</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/access-code.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>Leave this field blank if you want any visitor to be able to access the <a href="<?php echo site_url(); ?>/share">submission form on this site</a> (you can always make it less accessible by not having any links as menus for the form page. </p>

<p>If you want to provide an access code (a very weak password), just enter it. Any requests to access to form will be sent to the <a href="<?php echo site_url(); ?>/desk">front desk</a> form where a visitor must enter in the code you have entered here to see the form.</p>

<p>Enter a decent <strong>Access Hint</strong> that will be displayed if someone does not enter the correct code.</p>


<h4>Comments</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/sounder-comments.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>Kind of self explanatory, eh? Check the box if you want comments on items.


<h4>Publication Options and Default Categories</h4>
<p>The Status for New Sounds lets you set up moderation for new submissions (by choosing <code>Set to draft</code>) whereas <code>Publish immediately</code> provides instant gratification to your visitors though leaves open the problems of a site where anyone can publish (the latter option thus works for sites where you set up an <strong>Access Code</strong> as described above.</p>

<p>If you have not set up any categories, the <strong>Default Category for New Sounds</strong> menu will not do much. You might want to save your options, and edit your <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=category')?>">Sound Categories</a> (standard Wordpress Categories). Hierarchal ones are not supported (e.g. don't waste your time, use a flat Category structure)</p>


<h4>Notification Emails</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/notification.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />
<p>Enter any email addresses who should be notified of new submissions; you can use multiple ones if you separate them by a comma.</p>


<h4>Author Account Setup</h4>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/author-account-none.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>To provide access to the media uploader, this site uses a Wordpress Authoring Role account that is logged into invisibly to your site visitors (for anyone logged in with an Editor or Administrator account, like you this account is not used).. So your site needs an active user with a name of <strong>sounder</strong> and a role of <strong>Author</strong>.</p>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-author.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>You can follow the link to create an account; for an email you can use a fictitious one on your domain. We suggest using the strong password that Wordpress  suggests. Copy that password, and perhaps save it in a safe place. On a stand-alone Wordpress install of the Collector, you can just paste it into the option for the password field.</p>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/add-to-site.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>If this site is on a mulitsite Wordpress install, and the TRU Collector has been used on another site, the <code>sounder</code> account already exists, so you need to add it to the site via the Author tools. However, you still have to enter the password, so make sure you know the passord that was used on another site. If you do not have access to it, you will have to reset the password at the Network Admin level, and then update the password on the options of all sites using the TRU Sound Collector.</p>

<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/authoring-account.jpg" alt="" style="border:3px solid #000; margin-bottom:2em;" />

<p>When everything is set up correctly, the options will indicate that you have been a good options configurator! </p>

<p>If the account is incorrectly set up, when trying the Sound Collector on a site where you are <em>not</em> logged in as an Administrator or Editor, any attempts to upload an image will generate an error message in the Media Uploader.</p>

