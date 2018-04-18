<?php
function wp_insert_form_accordion_tabs_rules($control, $identifier, $location) {
	$posts_per_page = get_option('posts_per_page');
	$instances = array();
	for($i = 1; $i <= $posts_per_page; $i++) {
		$instances[] = array('text' => 'Hide on '.wp_insert_add_ordinal_number_suffix($i).' Post', 'value' => $i);
	}
	
	echo '<h3>Rules</h3>';
	if($location == 'inpostads') {		
		echo '<div>';
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_loggedin'));
			$control->create_section('Logged in Users');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_mobile_devices'));
			$control->create_section('Mobile Devices');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_404'));
			$control->create_section('404 Pages');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_home'));
			$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_home_instances', 'options' => $instances));
			$control->create_section('Home');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_archives'));
			$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_archives_instances', 'options' => $instances));
			$control->create_section('Archives');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_search'));
			$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_search_instances', 'options' => $instances));
			$control->create_section('Search Results');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_page'));
			$control->add_control(array('type' => 'pages-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_page_exceptions'));
			$control->create_section('Single Pages');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_post'));
			$control->add_control(array('type' => 'posts-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_post_exceptions'));
			$control->add_control(array('type' => 'categories-chosen-multiselect', 'label' => 'Category Exceptions', 'optionName' => 'rules_post_categories_exceptions'));
			$control->create_section('Single Posts');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_categories'));
			$control->add_control(array('type' => 'choosen-multiselect', 'label' => 'Instances', 'optionName' => 'rules_categories_instances', 'options' => $instances));
			$control->add_control(array('type' => 'categories-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_categories_exceptions'));
			$control->create_section('Category Archives');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();				
		echo '</div>';
	} else {
		echo '<div>';
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_loggedin'));
			$control->create_section('Logged in Users');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_mobile_devices'));
			$control->create_section('Mobile Devices');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_404'));
			$control->create_section('404 Pages');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_home'));
			$control->create_section('Home');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_archives'));
			$control->create_section('Archives');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_search'));
			$control->create_section('Search Results');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_page'));
			$control->add_control(array('type' => 'pages-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_page_exceptions'));
			$control->create_section('Single Pages');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div>');
			echo $control->HTML;
			$control->clear_controls();
					
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_categories'));
			$control->add_control(array('type' => 'categories-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_categories_exceptions'));
			$control->create_section('Category Archives');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();
			
			$control->add_control(array('type' => 'checkbox-button', 'label' => 'Status : Show Ads', 'checkedLabel' => 'Status : Hide Ads', 'uncheckedLabel' => 'Status : Show Ads', 'optionName' => 'rules_exclude_post'));
			$control->add_control(array('type' => 'posts-chosen-multiselect', 'label' => 'Exceptions', 'optionName' => 'rules_post_exceptions'));
			$control->add_control(array('type' => 'categories-chosen-multiselect', 'label' => 'Category Exceptions', 'optionName' => 'rules_post_categories_exceptions'));
			$control->create_section('Single Posts');
			$control->set_HTML('<div class="wp_insert_rule_block">'.$control->HTML.'</div><div style="clear: both;"></div>');
			echo $control->HTML;
			$control->clear_controls();				
		echo '</div>';
	}
	return $control;
}
?>