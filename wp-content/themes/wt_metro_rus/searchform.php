<?php
/**
 * The template for displaying search forms in Well Themes
 *
 * @package  WellThemes
 * @file     searchform.php
 * @author   Well Themes Team
 * @link 	 http://wellthemes.com
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="searchfield" name="s" id="s" placeholder="<?php esc_attr_e( 'Поиск', 'wellthemes' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Искать', 'wellthemes' ); ?>" />
	</form>
