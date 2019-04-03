<?php
function wp_insert_form_accordion_tabs_notes($control, $identifier, $location) {
	echo '<h3>Notes</h3>';
	echo '<div>';
		
		$title = $identifier;
		if(!isset($control->values['title']) || ($control->values['title'] == '')) {
			switch($identifier) {
				case 'above':
					$title = 'Above Post Content';
					break;
				case 'middle':
					$title = 'Middle of Post Content';
					break;
				case 'below':
					$title = 'Below Post Content';
					break;
				case 'left':
					$title = 'To the Left of Post Content';
					break;
				case 'right':
					$title = 'To the Right of Post Content';
					break;
				default:
					$title = $identifier;
					break;
			}
		} else {
			$title = $control->values['title'];
		}
		$control->add_control(array('type' => 'text', 'optionName' => 'title', 'helpText' => 'The title is used to identify your Ad Widget easily in future.  A Random Title will be assigned to your Ad widget by default.', 'value' => $title));
		$control->create_section('Title');
		echo $control->HTML;
		$control->clear_controls();
		
		$control->add_control(array('type' => 'textarea', 'optionName' => 'notes', 'style' => 'height: 220px;'));
		$control->create_section('Notes');
		echo $control->HTML;
		$control->clear_controls();
	echo '</div>';
	return $control;
}
?>