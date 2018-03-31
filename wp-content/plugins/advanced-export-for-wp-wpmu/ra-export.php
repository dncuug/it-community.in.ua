<?php
/*
Plugin Name: Advanced Export
Plugin URI: http://wpmututorials.com/plugins/advanced-export/
Description: Adds an Advanced Export to the Tools menu which allows selective exporting of pages, posts, specific categories and/or post statuses by date.
Version: 2.9
Author: Ron Rennick
Author URI: http://ronandandrea.com/
 
*/
/* Copyright:	(C) 2009 Ron Rennick, All rights reserved.  

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
if(!defined('ABSPATH')) {
	die("Don't call this file directly.");
}
if(isset($_GET['page']) && $_GET['page'] == 'ra_export' && isset( $_GET['download'] ) ) {
	add_action('init', 'ra_do_export');
}
function ra_do_export() {
	if(current_user_can('edit_files')) {
		$author = isset($_GET['author']) ? $_GET['author'] : 'all';
		$category = isset($_GET['category']) ? $_GET['category'] : 'all';
		$post_type = isset($_GET['post_type']) ? stripslashes($_GET['post_type']) : 'all';
		$status = isset($_GET['status']) ? stripslashes($_GET['status']) : 'all';
		$mm_start = isset($_GET['mm_start']) ? $_GET['mm_start'] : 'all';
		$mm_end = isset($_GET['mm_end']) ? $_GET['mm_end'] : 'all';
		$aa_start = isset($_GET['aa_start']) ? intval($_GET['aa_start']) : 0;
		$aa_end = isset($_GET['aa_end']) ? intval($_GET['aa_end']) : 0;
		$terms = isset($_GET['terms']) ? stripslashes($_GET['terms']) : 'all';
		if($mm_start != 'all' && $aa_start > 0) {
			$start_date = sprintf( "%04d-%02d-%02d", $aa_start, $mm_start, 1 );
		} else {
			$start_date = 'all';
		}
		if($mm_end != 'all' && $aa_end > 0) {
			if($mm_end == 12) {
				$mm_end = 1;
				$aa_end++;
			} else {
				$mm_end++;
			}
			$end_date = sprintf( "%04d-%02d-%02d", $aa_end, $mm_end, 1 );
		} else {
			$end_date = 'all';
		}
		ra_export_setup();
		ra_export_wp( $author, $category, $post_type, $status, $start_date, $end_date, $terms );
		die();
	}
}	
function ra_export_wp($author='', $category='', $post_type='', $status='', $start_date='', $end_date='', $terms = '') {
	global $wpdb, $post_ids, $post;

	define('WXR_VERSION', '1.0');

	do_action('export_wp');

	if(strlen($start_date) > 4 && strlen($end_date) > 4) {
		$filename = 'wordpress.' . $start_date . '.' . $end_date . '.xml';
	} else {
		$filename = 'wordpress.' . date('Y-m-d') . '.xml';
	}
	header('Content-Description: File Transfer');
	header("Content-Disposition: attachment; filename=$filename");
	header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

	if ( $post_type and $post_type != 'all' ) {
		$where = $wpdb->prepare("WHERE post_type = %s ", $post_type);
	} else {
		$where = "WHERE post_type != 'revision' ";
	}
	if ( $author and $author != 'all' ) {
		$author_id = (int) $author;
		$where .= $wpdb->prepare("AND post_author = %d ", $author_id);
	}
	if ( $start_date and $start_date != 'all' ) {
		$where .= $wpdb->prepare("AND post_date >= %s ", $start_date);
	}
	if ( $end_date and $end_date != 'all' ) {
		$where .= $wpdb->prepare("AND post_date < %s ", $end_date);
	}
	if ( $category and $category != 'all' and version_compare($wpdb->db_version(), '4.1', 'ge')) {
		$taxomony_id = (int) $category;
		$where .= $wpdb->prepare("AND ID IN (SELECT object_id FROM {$wpdb->term_relationships} " .
			"WHERE term_taxonomy_id = %d) ", $taxomony_id);
	}
	if ( $status and $status != 'all' ) {
		$where .= $wpdb->prepare("AND post_status = %s ", $status);
	}

	// grab a snapshot of post IDs, just in case it changes during the export
	$post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts $where ORDER BY post_date_gmt ASC");

	$categories = (array) get_categories('get=all');
	$tags = (array) get_tags('get=all');

	while ( $parents = wxr_missing_parents($categories) ) {
		$found_parents = get_categories("include=" . join(', ', $parents));
		if ( is_array($found_parents) && count($found_parents) )
			$categories = array_merge($categories, $found_parents);
		else
			break;
	}

	// Put them in order to be inserted with no child going before its parent
	$pass = 0;
	$passes = 1000 + count($categories);
	while ( ( $cat = array_shift($categories) ) && ++$pass < $passes ) {
		if ( $cat->parent == 0 || isset($cats[$cat->parent]) ) {
			$cats[$cat->term_id] = $cat;
		} else {
			$categories[] = $cat;
		}
	}
	unset($categories);

	echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . '"?' . ">\n";
?>
<!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your blog. -->
<!-- It contains information about your blog's posts, comments, and categories. -->
<!-- You may use this file to transfer that content from one site to another. -->
<!-- This file is not intended to serve as a complete backup of your blog. -->

<!-- To import this information into a WordPress blog follow these steps. -->
<!-- 1. Log into that blog as an administrator. -->
<!-- 2. Go to Tools: Import in the blog's admin panels (or Manage: Import in older versions of WordPress). -->
<!-- 3. Choose "WordPress" from the list. -->
<!-- 4. Upload this file using the form provided on that page. -->
<!-- 5. You will first be asked to map the authors in this export file to users -->
<!--    on the blog.  For each author, you may choose to map to an -->
<!--    existing user on the blog or to create a new user -->
<!-- 6. WordPress will then import each of the posts, comments, and categories -->
<!--    contained in this file into your blog -->

<?php the_generator('export');?>
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/"
>

<channel>
	<title><?php bloginfo_rss('name'); ?></title>
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
	<generator>http://wordpress.org/?v=<?php bloginfo_rss('version'); ?></generator>
	<language><?php echo get_option('rss_language'); ?></language>
	<wp:wxr_version><?php echo WXR_VERSION; ?></wp:wxr_version>
	<wp:base_site_url><?php echo wxr_site_url(); ?></wp:base_site_url>
	<wp:base_blog_url><?php bloginfo_rss('url'); ?></wp:base_blog_url>
<?php if ( $cats && ($terms == 'all' || $terms == 'cats')) : foreach ( $cats as $c ) : ?>
	<wp:category><wp:category_nicename><?php echo $c->slug; ?></wp:category_nicename><wp:category_parent><?php echo $c->parent ? $cats[$c->parent]->name : ''; ?></wp:category_parent><?php wxr_cat_name($c); ?><?php wxr_category_description($c); ?></wp:category>
<?php endforeach; endif; ?>
<?php if ( $tags && ($terms == 'all' || $terms == 'tags')) : foreach ( $tags as $t ) : ?>
	<wp:tag><wp:tag_slug><?php echo $t->slug; ?></wp:tag_slug><?php wxr_tag_name($t); ?><?php wxr_tag_description($t); ?></wp:tag>
<?php endforeach; endif; ?>
	<?php do_action('rss2_head'); ?>
	<?php if ($post_ids) {
		global $wp_query;
		$wp_query->in_the_loop = true;  // Fake being in the loop.
		// fetch 20 posts at a time rather than loading the entire table into memory
		while ( $next_posts = array_splice($post_ids, 0, 20) ) {
			$where = "WHERE ID IN (".join(',', $next_posts).")";
			$posts = $wpdb->get_results("SELECT * FROM $wpdb->posts $where ORDER BY post_date_gmt ASC");
				foreach ($posts as $post) {
					setup_postdata($post); ?>
<item>
<title><?php echo apply_filters('the_title_rss', $post->post_title); ?></title>
<link><?php the_permalink_rss() ?></link>
<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
<dc:creator><?php echo wxr_cdata(get_the_author()); ?></dc:creator>
<?php wxr_post_taxonomy() ?>

<guid isPermaLink="false"><?php the_guid(); ?></guid>
<description></description>
<content:encoded><?php echo wxr_cdata( apply_filters('the_content_export', $post->post_content) ); ?></content:encoded>
<excerpt:encoded><?php echo wxr_cdata( apply_filters('the_excerpt_export', $post->post_excerpt) ); ?></excerpt:encoded>
<wp:post_id><?php echo $post->ID; ?></wp:post_id>
<wp:post_date><?php echo $post->post_date; ?></wp:post_date>
<wp:post_date_gmt><?php echo $post->post_date_gmt; ?></wp:post_date_gmt>
<wp:comment_status><?php echo $post->comment_status; ?></wp:comment_status>
<wp:ping_status><?php echo $post->ping_status; ?></wp:ping_status>
<wp:post_name><?php echo $post->post_name; ?></wp:post_name>
<wp:status><?php echo $post->post_status; ?></wp:status>
<wp:post_parent><?php echo $post->post_parent; ?></wp:post_parent>
<wp:menu_order><?php echo $post->menu_order; ?></wp:menu_order>
<wp:post_type><?php echo $post->post_type; ?></wp:post_type>
<wp:post_password><?php echo $post->post_password; ?></wp:post_password>
<?php
if ($post->post_type == 'attachment') { ?>
<wp:attachment_url><?php echo wp_get_attachment_url($post->ID); ?></wp:attachment_url>
<?php } ?>
<?php
$postmeta = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID) );
if ( $postmeta ) {
?>
<?php foreach( $postmeta as $meta ) { ?>
<wp:postmeta>
<wp:meta_key><?php echo $meta->meta_key; ?></wp:meta_key>
<wp:meta_value><?Php echo $meta->meta_value; ?></wp:meta_value>
</wp:postmeta>
<?php } ?>
<?php } ?>
<?php
$comments = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d", $post->ID) );
if ( $comments ) { foreach ( $comments as $c ) { ?>
<wp:comment>
<wp:comment_id><?php echo $c->comment_ID; ?></wp:comment_id>
<wp:comment_author><?php echo wxr_cdata($c->comment_author); ?></wp:comment_author>
<wp:comment_author_email><?php echo $c->comment_author_email; ?></wp:comment_author_email>
<wp:comment_author_url><?php echo $c->comment_author_url; ?></wp:comment_author_url>
<wp:comment_author_IP><?php echo $c->comment_author_IP; ?></wp:comment_author_IP>
<wp:comment_date><?php echo $c->comment_date; ?></wp:comment_date>
<wp:comment_date_gmt><?php echo $c->comment_date_gmt; ?></wp:comment_date_gmt>
<wp:comment_content><?php echo wxr_cdata($c->comment_content) ?></wp:comment_content>
<wp:comment_approved><?php echo $c->comment_approved; ?></wp:comment_approved>
<wp:comment_type><?php echo $c->comment_type; ?></wp:comment_type>
<wp:comment_parent><?php echo $c->comment_parent; ?></wp:comment_parent>
<wp:comment_user_id><?php echo $c->user_id; ?></wp:comment_user_id>
</wp:comment>
<?php } } ?>
	</item>
<?php } } } ?>
</channel>
</rss>
<?php
}

function ra_export_page() {
	global $wpdb, $wp_locale; 

	if ( ! current_user_can( 'edit_files' ) )
		die( 'You don\'t have permissions to use this page.' );

	load_plugin_textdomain( 'ra-export', false, '/advanced-export-for-wp-wpmu/languages/' );

	$months = "";
	for ( $i = 1; $i < 13; $i++ ) {
		$months .= "\t\t\t<option value=\"" . zeroise($i, 2) . '">' . 
			$wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) . "</option>\n";
	} ?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php esc_html_e( 'Advanced Export', 'ra-export' ); ?></h2>

<p><?php esc_html_e('When you click the button below WordPress will create an XML file for you to save to your computer.'); ?></p>
<p><?php esc_html_e('This format, which we call WordPress eXtended RSS or WXR, will contain your posts, pages, comments, custom fields, categories, and tags.'); ?></p>
<p><?php esc_html_e('Once you&#8217;ve saved the download file, you can use the Import function on another WordPress blog to import this blog.'); ?></p>
<form action="" method="get">
<input type="hidden" name="page" value="ra_export" />
<h3><?php esc_html_e('Options', 'ra-export' ); ?></h3>

<table class="form-table">
<tr>
<th><label for="mm_start"><?php esc_html_e('Restrict Date', 'ra-export' ); ?></label></th>
<td><strong><?php esc_html_e('Start:', 'ra-export' ); ?></strong> <?php esc_html_e('Month', 'ra-export' ); ?>&nbsp;
<select name="mm_start" id="mm_start">
<option value="all" selected="selected"><?php esc_html_e('All Dates', 'ra-export' ); ?></option>
<?php echo $months; ?>
</select>&nbsp;<?php esc_html_e('Year', 'ra-export' ); ?>&nbsp;
<input type="text" id="aa_start" name="aa_start" value="" size="4" maxlength="5" />
</td>
<td><strong><?php esc_html_e('End:', 'ra-export' ); ?></strong> <?php esc_html_e('Month', 'ra-export' ); ?>&nbsp;
<select name="mm_end" id="mm_end">
<option value="all" selected="selected"><?php esc_html_e('All Dates', 'ra-export' ); ?></option>
<?php echo $months; ?>
</select>&nbsp;<?php esc_html_e('Year', 'ra-export' ); ?>&nbsp;
<input type="text" id="aa_end" name="aa_end" value="" size="4" maxlength="5" />
</td>
</tr>
<tr>
<th><label for="author"><?php esc_html_e('Restrict Author', 'ra-export' ); ?></label></th>
<td>
<select name="author" id="author">
<option value="all" selected="selected"><?php esc_html_e('All Authors', 'ra-export' ); ?></option>
<?php
$authors = $wpdb->get_col( "SELECT post_author FROM $wpdb->posts GROUP BY post_author" );
foreach ( $authors as $id ) {
	$o = get_userdata( $id );
	echo "<option value='{$o->ID}'>{$o->display_name}</option>\n";
}
?>
</select>
</td>
</tr>
<?php if(version_compare($wpdb->db_version(), '4.1', 'ge')) { ?>
<tr>
<th><label for="category"><?php esc_html_e('Restrict Category', 'ra-export' ); ?></label></th>
<td>
<select name="category" id="category">
<option value="all" selected="selected"><?php esc_html_e('All Categories', 'ra-export' ); ?></option>
<?php
$categories = (array) get_categories('get=all');
if($categories) {
	foreach ( $categories as $cat ) {
		echo "<option value='{$cat->term_taxonomy_id}'>{$cat->name}</option>\n";
	}
}
?>
</select>
</td>
</tr>
<?php } ?>
<tr>
<th><label for="post_type"><?php  esc_html_e('Restrict Content', 'ra-export' ); ?></label></th>
<td>
<select name="post_type" id="post_type">
<option value="all" selected="selected"><?php esc_html_e('All Content', 'ra-export' ); ?></option>
<option value="page"><?php esc_html_e('Pages', 'ra-export' ); ?></option>
<option value="post"><?php esc_html_e('Posts', 'ra-export' ); ?></option>
</select>
</td>
</tr>
<tr>
<th><label for="status"><?php esc_html_e('Restrict Status', 'ra-export' ); ?></label></th>
<td>
<select name="status" id="status">
<option value="all" selected="selected"><?php esc_html_e('All Statuses', 'ra-export' ); ?></option>
<option value="draft"><?php esc_html_e('Draft', 'ra-export' ); ?></option>
<option value="private"><?php esc_html_e('Privately published', 'ra-export' ); ?></option>
<option value="publish"><?php esc_html_e('Published', 'ra-export' ); ?></option>
<option value="future"><?php esc_html_e('Scheduled', 'ra-export' ); ?></option>
</select>
</td>
</tr>
<tr>
<th><label for="terms"><?php esc_html_e('Include Blog Tag/Category Terms', 'ra-export' ); ?></label></th>
<td>
<select name="terms" id="terms">
<option value="all" selected="selected"><?php esc_html_e('All Terms', 'ra-export' ); ?></option>
<option value="cats"><?php esc_html_e('Categories', 'ra-export' ); ?></option>
<option value="tags"><?php esc_html_e('Tags', 'ra-export' ); ?></option>
<option value="none"><?php esc_html_e('None', 'ra-export' ); ?></option>
</select>
</td>
</tr>
</table>
<p class="submit"><input type="submit" name="submit" class="button" value="<?php esc_html_e('Download Export File', 'ra-export' ); ?>" />
<input type="hidden" name="download" value="true" />
</p>
</form>
</div>
<?php
}
function ra_add_export_page() {
   	add_management_page('Advanced Export', 'Advanced Export', 'manage_options', 'ra_export', 'ra_export_page');
}
add_action('admin_menu', 'ra_add_export_page');

function ra_export_setup() {
	if(!function_exists('wxr_missing_parents')) {
		function wxr_missing_parents($categories) {
			if ( !is_array($categories) || empty($categories) )
				return array();

			foreach ( $categories as $category )
				$parents[$category->term_id] = $category->parent;

			$parents = array_unique(array_diff($parents, array_keys($parents)));

			if ( $zero = array_search('0', $parents) )
				unset($parents[$zero]);

			return $parents;
		}
	}
	if(!function_exists('wxr_cdata')) {
		function wxr_cdata($str) {
			if ( seems_utf8($str) == false )
				$str = utf8_encode($str);

			// $str = ent2ncr(wp_specialchars($str));

			$str = "<![CDATA[$str" . ( ( substr($str, -1) == ']' ) ? ' ' : '') . "]]>";

			return $str;
		}
	}
	if(!function_exists('wxr_site_url')) {
		function wxr_site_url() {
			global $current_site;

			// mu: the base url
			if ( isset($current_site->domain) ) {
				return 'http://'.$current_site->domain.$current_site->path;
			}
			// wp: the blog url
			else {
				return get_bloginfo_rss('url');
			}
		}
	}
	if(!function_exists('wxr_cat_name')) {
		function wxr_cat_name($c) {
			if ( empty($c->name) )
				return;

			echo '<wp:cat_name>' . wxr_cdata($c->name) . '</wp:cat_name>';
		}
	}
	if(!function_exists('wxr_category_description')) {
		function wxr_category_description($c) {
			if ( empty($c->description) )
				return;

			echo '<wp:category_description>' . wxr_cdata($c->description) . '</wp:category_description>';
		}
	}
	if(!function_exists('wxr_tag_name')) {
		function wxr_tag_name($t) {
			if ( empty($t->name) )
				return;

			echo '<wp:tag_name>' . wxr_cdata($t->name) . '</wp:tag_name>';
		}
	}
	if(!function_exists('wxr_tag_description')) {
		function wxr_tag_description($t) {
			if ( empty($t->description) )
				return;

			echo '<wp:tag_description>' . wxr_cdata($t->description) . '</wp:tag_description>';
		}
	}
	if(!function_exists('wxr_post_taxonomy')) {
		function wxr_post_taxonomy() {
			$categories = get_the_category();
			$tags = get_the_tags();
			$the_list = '';
			$filter = 'rss';

			if ( !empty($categories) ) foreach ( (array) $categories as $category ) {
				$cat_name = sanitize_term_field('name', $category->name, $category->term_id, 'category', $filter);
				// for backwards compatibility
				$the_list .= "\n\t\t<category><![CDATA[$cat_name]]></category>\n";
				// forwards compatibility: use a unique identifier for each cat to avoid clashes
				// http://trac.wordpress.org/ticket/5447
				$the_list .= "\n\t\t<category domain=\"category\" nicename=\"{$category->slug}\"><![CDATA[$cat_name]]></category>\n";
			}

			if ( !empty($tags) ) foreach ( (array) $tags as $tag ) {
				$tag_name = sanitize_term_field('name', $tag->name, $tag->term_id, 'post_tag', $filter);
				$the_list .= "\n\t\t<category domain=\"tag\"><![CDATA[$tag_name]]></category>\n";
				// forwards compatibility as above
				$the_list .= "\n\t\t<category domain=\"tag\" nicename=\"{$tag->slug}\"><![CDATA[$tag_name]]></category>\n";
			}

			echo $the_list;
		}
	}
}
?>