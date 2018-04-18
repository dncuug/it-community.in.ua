<?php 
class smartlogixControls {
	private $type;
	private $plainHTML;
	private $useParagraph;
	private $helpText;
	private $id;
	private $name;
	private $value;
	private $label;
	private $className;
	private $style;
	private $required;
	private $options;
	
	private $optionName;
	private $optionIdentifier;
	
	
	private $pages;
	private $posts;
	private $categories;
		
	public $values;
	public $HTML;
	public $JS;
	
	function __construct($args = null) {
		$this->HTML = '';
		$this->JS = '';
		
		$this->type = 'text';
		$this->plainHTML = false;
		$this->useParagraph = true;
		$this->helpText = '';
		$this->id = '';
		$this->name = '';
		$this->value = '';
		$this->label = '';
		$this->className = 'input widefat';
		$this->style = '';
		$this->required = '';
		$this->options = null;
		
		$this->optionName = '';
		$this->optionIdentifier = '';
		$this->values = '';			
		
		if(isset($args) && is_array($args)) {
			if(isset($args['type']) && ($args['type'] != '')) {
				$this->type = $args['type'];
			}
			if(isset($args['plainHTML']) && ($args['plainHTML'] != '')) {
				$this->plainHTML = $args['plainHTML'];
			}
			if(isset($args['useParagraph']) && ($args['useParagraph'] != '')) {
				$this->useParagraph = $args['useParagraph'];
			}
			if(isset($args['helpText']) && ($args['helpText'] != '')) {
				$this->helpText = $args['helpText'];
			}
			if(isset($args['id']) && ($args['id'] != '')) {
				$this->id = $args['id'];
			}
			if(isset($args['name']) && ($args['name'] != '')) {
				$this->name = $args['name'];
			}
			if(isset($args['value']) && ($args['value'] != '')) {
				$this->value = $args['value'];
			}
			if(isset($args['label']) && ($args['label'] != '')) {
				$this->label = $args['label'];
			}
			if(isset($args['className']) && ($args['className'] != '')) {
				$this->className = $args['className'];
			}
			if(isset($args['style']) && ($args['style'] != '')) {
				$this->style = $args['style'];
			}
			if(isset($args['required']) && ($args['required'] != '')) {
				$this->required = $args['required'];
			}
			if(isset($args['options']) && is_array($args['options'])) {
				$this->options = $args['options'];
			}
			
			if(isset($args['optionIdentifier']) && ($args['optionIdentifier'] != '')) {
				$this->optionIdentifier = $args['optionIdentifier'];
			}
			if(isset($args['optionName']) && ($args['optionName'] != '')) {
				$this->optionName = $args['optionName'];				
			}
			if(isset($args['values']) && ($args['values'] != '')) {
				$this->values = $args['values'];				
			}
			
			if(isset($args['optionIdentifier']) && ($args['optionIdentifier'] != '') && isset($args['optionName']) && ($args['optionName'] != '')) {
				$this->id = str_replace(array('[', ']'), array('_', ''), $args['optionIdentifier']).'_'.$args['optionName'];
				$this->name = $args['optionIdentifier'].'['.$args['optionName'].']';
			} elseif(isset($this->optionIdentifier) && ($this->optionIdentifier != '') && isset($args['optionName']) && ($args['optionName'] != '')) {
				$this->id = str_replace(array('[', ']'), array('_', ''), $this->optionIdentifier).'_'.$args['optionName'];
				$this->name = $this->optionIdentifier.'['.$args['optionName'].']';
			} elseif(isset($args['optionIdentifier']) && ($args['optionIdentifier'] != '') && isset($this->optionName) && ($this->optionName != '')) {
				$this->id = str_replace(array('[', ']'), array('_', ''), $args['optionIdentifier']).'_'.$this->optionName;
				$this->name = $args['optionIdentifier'].'['.$this->optionName.']';
			} elseif(isset($this->optionIdentifier) && ($this->optionIdentifier != '') && isset($this->optionName) && ($this->optionName != '')) {
				$this->id = str_replace(array('[', ']'), array('_', ''), $this->optionIdentifier).'_'.$this->optionName;
				$this->name = $this->optionIdentifier.'['.$this->optionName.']';
			}
			
			if(isset($args['values']) && is_array($args['values'])) {
				if(isset($args['optionName']) && ($args['optionName'] != '')) {
					$this->value = $args['values'][$args['optionName']];
				} elseif(isset($this->optionName) && ($this->optionName != '')) {
					$this->value = $args['values'][$this->optionName];
				}
			} elseif(isset($this->values) && is_array($this->values)) {
				if(isset($args['optionName']) && ($args['optionName'] != '')) {
					$this->value = $this->values[$args['optionName']];
				} elseif(isset($this->optionName) && ($this->optionName != '')) {
					$this->value = $this->values[$this->optionName];
				}
			}
		}
	}
	
	private function sync_args($args) {
		$syncedArgs = array();
		if(is_array($args)) {
			$syncedArgs = $args;
		}
				
		if(isset($args['type']) && ($args['type'] != '')) {
			$syncedArgs['type'] = $args['type'];
		} else {
			$syncedArgs['type'] = $this->type;
		}
		
		if(isset($args['plainHTML']) && is_bool($args['plainHTML'])) {
			$syncedArgs['plainHTML'] = $args['plainHTML'];
		} else {
			$syncedArgs['plainHTML'] = $this->plainHTML;
		}
		
		if(isset($args['useParagraph']) && is_bool($args['useParagraph'])) {
			$syncedArgs['useParagraph'] = $args['useParagraph'];
		} else {
			$syncedArgs['useParagraph'] = $this->useParagraph;
		}
		
		if(isset($args['helpText']) && ($args['helpText'] != '')) {
			$syncedArgs['helpText'] = $args['helpText'];
		} else {
			$syncedArgs['helpText'] = $this->helpText;
		}
		
		if(isset($args['id']) && ($args['id'] != '')) {
			$syncedArgs['id'] = $args['id'];
		} else {
			$syncedArgs['id'] = $this->id;
		}
		
		if(isset($args['name']) && ($args['name'] != '')) {
			$syncedArgs['name'] = $args['name'];
		} else {
			$syncedArgs['name'] = $this->name;
		}
		
		if(isset($args['label']) && ($args['label'] != '')) {
			$syncedArgs['label'] = $args['label'];
		} else {
			$syncedArgs['label'] = $this->label;
		}
		
		if(isset($args['className']) && ($args['className'] != '')) {
			$syncedArgs['className'] = $args['className'];
		} else {
			$syncedArgs['className'] = $this->className;
		}
		
		if(isset($args['style']) && ($args['style'] != '')) {
			$syncedArgs['style'] = $args['style'];
		} else {
			$syncedArgs['style'] = $this->style;
		}
		
		if(isset($args['required']) && ($args['required'] != '')) {
			$syncedArgs['required'] = $args['required'];
		} else {
			$syncedArgs['required'] = $this->required;
		}
		
		if(isset($args['options']) && is_array($args['options'])) {
			$syncedArgs['options'] = $args['options'];
		} else {
			$syncedArgs['options'] = $this->options;
		}
		
			
		if(isset($args['optionIdentifier']) && ($args['optionIdentifier'] != '') && isset($args['optionName']) && ($args['optionName'] != '')) {
			$syncedArgs['id'] = str_replace(array('[', ']'), array('_', ''), $args['optionIdentifier']).'_'.$args['optionName'];
			$syncedArgs['name'] = $args['optionIdentifier'].'['.$args['optionName'].']';
		} elseif(isset($this->optionIdentifier) && ($this->optionIdentifier != '') && isset($args['optionName']) && ($args['optionName'] != '')) {
			$syncedArgs['id'] = str_replace(array('[', ']'), array('_', ''), $this->optionIdentifier).'_'.$args['optionName'];
			$syncedArgs['name'] = $this->optionIdentifier.'['.$args['optionName'].']';
		} elseif(isset($args['optionIdentifier']) && ($args['optionIdentifier'] != '') && isset($this->optionName) && ($this->optionName != '')) {
			$syncedArgs['id'] = str_replace(array('[', ']'), array('_', ''), $args['optionIdentifier']).'_'.$this->optionName;
			$syncedArgs['name'] = $args['optionIdentifier'].'['.$this->optionName.']';
		} elseif(isset($this->optionIdentifier) && ($this->optionIdentifier != '') && isset($this->optionName) && ($this->optionName != '')) {
			$syncedArgs['id'] = str_replace(array('[', ']'), array('_', ''), $this->optionIdentifier).'_'.$this->optionName;
			$syncedArgs['name'] = $this->optionIdentifier.'['.$this->optionName.']';
		}
			
		if(isset($args['value']) && ($args['value'] != '')) {
			$syncedArgs['value'] = $args['value'];
		} else {
			if(isset($args['values']) && is_array($args['values'])) {
				if(isset($args['optionName']) && ($args['optionName'] != '')) {
					$syncedArgs['value'] = $args['values'][$args['optionName']];
				} elseif(isset($this->optionName) && ($this->optionName != '')) {
					$syncedArgs['value'] = $args['values'][$this->optionName];
				}
			} elseif(isset($this->values) && is_array($this->values)) {
				if(isset($args['optionName']) && ($args['optionName'] != '')) {
					$syncedArgs['value'] = $this->values[$args['optionName']];
				} elseif(isset($this->optionName) && ($this->optionName != '')) {
					$syncedArgs['value'] = $this->values[$this->optionName];
				}
			} else {
				$syncedArgs['value'] = $this->value;
			}
		}
				
		return $syncedArgs;
	}
	
	public function add_control($args = null) {
		$control = $this->get_control($args, true);
		$this->HTML .= $control['HTML'];
		$this->JS .= $control['JS'];
	}
	
	public function get_control($args = null, $ignorePlainHTML = false) {
		$HTML = '';
		$JS = '';
		$args = $this->sync_args($args);
		
		switch($args['type']) {
			case 'hidden':
				$HTML .= '<input type="hidden" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' />';
				break;
			case 'text':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<input type="text" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'number':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<input type="number" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'password':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<input type="password" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'textarea':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<textarea '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>'.stripslashes((($args['value'] != '')?$args['value']:'')).'</textarea>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'checkbox':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if(isset($args['value']) && (filter_var($args['value'], FILTER_VALIDATE_BOOLEAN))) {
					$HTML .= '<input type="checkbox" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="1" '.checked(true, true, false).' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';					
				} else {
					$HTML .= '<input type="checkbox" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="1" '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				}
				if($args['label'] != '') { $HTML .= '&nbsp;<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label>'; }
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'radio':
				if($args['useParagraph']) { $HTML .= '<p>'; }	
				if(isset($args['value']) && (filter_var($args['value'], FILTER_VALIDATE_BOOLEAN))) {
					$HTML .= '<input type="radio" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="1" '.checked(true, true, false).' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				} else {
					$HTML .= '<input type="radio" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="1" '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				}
				if($args['label'] != '') { $HTML .= '&nbsp;<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label>'; }
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'select':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($args['options'])) {
					foreach($args['options'] as $option) {
						$metadata = '';
						if(isset($option['metadata']) && is_array($option['metadata'])) {
							foreach($option['metadata'] as $key => $value) {
								$metadata .= 'data-'.$key.'="'.$value.'"';
							}
						}
						$HTML .= '<option '.$metadata.''.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected((($args['value'] != '')?$args['value']:''), (($option['value'] != '')?$option['value']:''), false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'choosen-multiselect':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select data-placeholder="'.(($args['label'] != '')?$args['label']:'Select Your Options').'" multiple '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($args['options'])) {
					foreach($args['options'] as $option) {
						if(in_array((($option['value'] != '')?$option['value']:''), ((is_array($args['value']))?$args['value']:array()))) {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected(1, 1, false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						} else {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						}
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").chosen({ width: "100%" }).on("change", function(evt, params) { jQuery("#'.(($args['id'] != '')?$args['id']:'').'_chosen .search-field input").click(); }).trigger("chosen:open");';
				break;
			case 'radio-group':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				if(is_array($args['options'])) {
					$index = 1;
					foreach($args['options'] as $option) {
						$HTML .= '<input type="radio" '.(($args['id'] != '')?'id="'.$args['id'].'_'.$index.'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.checked(((isset($args['value']))?$args['value']:''), ((isset($option['value']))?$option['value']:''), false).' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' />';
						if($option['text'] != '') { $HTML .= '&nbsp;<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').'>'.(($option['text'] != '')?$option['text']:'').'</label><br />'; }
						$index++;
					}
				}
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'ipCheckbox':
				if($args['useParagraph']) { $HTML .= '<p>'; }	
				if(isset($args['value']) && (filter_var($args['value'], FILTER_VALIDATE_BOOLEAN))) {	
					$HTML .= '<input type="checkbox" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="true" '.checked(true, true, false).' '.(($args['className'] != '')?'class="ipCheckbox '.$args['className'].'"':'class="ipCheckbox"').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';				
				} else {
					$HTML .= '<input type="checkbox" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="true" '.(($args['className'] != '')?'class="ipCheckbox '.$args['className'].'"':'class="ipCheckbox"').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				}
				if($args['label'] != '') { $HTML .= '&nbsp;<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label>'; }
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").ipCheckbox();';
				break;
			case 'minicolors':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<input type="text" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").minicolors();';
				break;
			case 'textarea-wysiwyg':
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<textarea '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>'.stripslashes((($args['value'] != '')?$args['value']:'')).'</textarea>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").jqte();';
				break;
			case 'checkbox-button':
				if($args['useParagraph']) { $HTML .= '<p>'; }	
				if(isset($args['value']) && (filter_var($args['value'], FILTER_VALIDATE_BOOLEAN))) {
					$HTML .= '<input type="checkbox" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="true" '.checked(true, true, false).' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				} else {
					$HTML .= '<input type="checkbox" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' value="true" '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').' />';
				}
				if($args['label'] != '') { $HTML .= '<label for="'.(($args['id'] != '')?$args['id']:'').'">'.$args['label'].'</label>'; }
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").button({ create:  function(event, ui) { jQuery(this).button("option", "label", (jQuery(this).is(":checked")?"'.(($args['checkedLabel'] != '')?$args['checkedLabel']:'').'":"'.(($args['uncheckedLabel'] != '')?$args['uncheckedLabel']:'').'")) }}).change(function () { jQuery(this).button("option", "label", (jQuery(this).is(":checked")?"'.(($args['checkedLabel'] != '')?$args['checkedLabel']:'').'":"'.(($args['uncheckedLabel'] != '')?$args['uncheckedLabel']:'').'")); });';
				break;
			case 'pages-select':
				$this->load_data('pages');
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($this->pages)) {
					foreach($this->pages as $option) {
						$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected((($args['value'] != '')?$args['value']:''), (($option['value'] != '')?$option['value']:''), false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'pages-chosen-multiselect':
				$this->load_data('pages');
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select multiple data-placeholder="'.(($args['label'] != '')?$args['label']:'Select Your Options').'" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($this->pages)) {
					foreach($this->pages as $option) {
						if(in_array((($option['value'] != '')?$option['value']:''), ((is_array($args['value']))?$args['value']:array()))) {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected(1, 1, false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						} else {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						}
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").chosen({ width: "100%" }).on("change", function(evt, params) { jQuery("#'.(($args['id'] != '')?$args['id']:'').'_chosen .search-field input").click(); }).trigger("chosen:open");';
				break;
			case 'posts-select':
				$this->load_data('posts');
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($this->posts)) {
					foreach($this->posts as $option) {
						$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected((($args['value'] != '')?$args['value']:''), (($option['value'] != '')?$option['value']:''), false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'posts-chosen-multiselect':
				$this->load_data('posts');
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select multiple data-placeholder="'.(($args['label'] != '')?$args['label']:'Select Your Options').'" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($this->posts)) {
					foreach($this->posts as $option) {
						if(in_array((($option['value'] != '')?$option['value']:''), ((is_array($args['value']))?$args['value']:array()))) {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected(1, 1, false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						} else {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						}
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").chosen({ width: "100%" }).on("change", function(evt, params) { jQuery("#'.(($args['id'] != '')?$args['id']:'').'_chosen .search-field input").click(); }).trigger("chosen:open");';
				break;
			case 'categories-select':
				$this->load_data('categories');
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($this->categories)) {
					foreach($this->categories as $option) {
						$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected((($args['value'] != '')?$args['value']:''), (($option['value'] != '')?$option['value']:''), false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				break;
			case 'categories-chosen-multiselect':
				$this->load_data('categories');
				if($args['useParagraph']) { $HTML .= '<p>'; }
				if($args['label'] != '') { $HTML .= '<label '.(($args['name'] != '')?'for="'.$args['name'].'"':'').'>'.$args['label'].'</label><br />'; }
				$HTML .= '<select multiple data-placeholder="'.(($args['label'] != '')?$args['label']:'Select Your Options').'" '.(($args['id'] != '')?'id="'.$args['id'].'"':'').' '.(($args['name'] != '')?'name="'.$args['name'].'"':'').' '.(($args['value'] != '')?'value="'.$args['value'].'"':'').' '.(($args['className'] != '')?'class="'.$args['className'].'"':'').' '.(($args['style'] != '')?'style="'.$args['style'].'"':'').' '.(($args['required'])?'required':'').'>';
				if(is_array($this->categories)) {
					foreach($this->categories as $option) {
						if(in_array((($option['value'] != '')?$option['value']:''), ((is_array($args['value']))?$args['value']:array()))) {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').' '.selected(1, 1, false).'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						} else {
							$HTML .= '<option '.(($option['value'] != '')?'value="'.$option['value'].'"':'').'>'.(($option['text'] != '')?$option['text']:'').'</option>';
						}
					}
				}
				$HTML .= '</select>';
				if($args['helpText'] != '') { $HTML .= '<small>'.$args['helpText'].'</small>'; }
				if($args['useParagraph']) { $HTML .= '</p>'; }
				$JS .= 'jQuery("#'.(($args['id'] != '')?$args['id']:'').'").chosen({ width: "100%" }).on("change", function(evt, params) { jQuery("#'.(($args['id'] != '')?$args['id']:'').'_chosen .search-field input").click(); }).trigger("chosen:open");';
				break;
		}
		
		if($args['plainHTML'] && !$ignorePlainHTML) {
			return $HTML;
		} else {
			return array(
				'HTML' => $HTML,
				'JS' => $JS
			);
		}
	}
	
	private function load_data($type) {
		switch($type) {
			case 'pages':
				if(!is_array($this->pages)) {
					$pages = get_pages('numberposts=100');
					if(isset($pages) && is_array($pages)) {
						$this->pages = array();
						foreach($pages as $page) {
							$this->pages[] = array('text' => (($page->post_title != '')?$page->post_title:'Untitled Page ('.$page->ID.')'), 'value' => $page->ID);
						}
					}
				}
				break;
			case 'posts':
				if(!is_array($this->posts)) {
					$posts = get_posts('numberposts=100');
					if(isset($posts) && is_array($posts)) {
						$this->posts = array();
						foreach($posts as $post) {
							$this->posts[] = array('text' => $post->post_title, 'value' => $post->ID);
						}
					}
				}
				break;
			case 'categories':
				if(!is_array($this->categories)) {
					$categories = get_categories('number=200&hide_empty=0');
					if(isset($categories) && is_array($categories)) {
						$this->categories = array();
						foreach($categories as $category) {
							$this->categories[] = array('text' => $category->name, 'value' => $category->term_id);
						}
					}
				}
				break;
		}
	}
	
	public function create_section($sectionTitle = '', $includeBlock = true) {
		$wrapperHTML = '<div class="smartlogixControlsSectionWrapper">';
			if($sectionTitle != '') {
				$wrapperHTML .= '<label class="smartlogixControlsSectionTitle">'.$sectionTitle.'</label>';
			}
			if($includeBlock) {
				$wrapperHTML .= $this->create_block();
			}
			$wrapperHTML .= $this->HTML;			
		$wrapperHTML .= '</div>';
		$this->HTML = $wrapperHTML;
	}
	
	public function create_block() {
		$this->HTML = '<div class="smartlogixControlsSectionInner">'.$this->HTML.'</div>';
	}
	
	public function set_HTML($HTML) {
		$this->HTML = $HTML;
	}
	
	public function clear_controls($clearHTML = true, $clearJS = false) {
		if($clearHTML) {
			$this->HTML = '';
		}
		if($clearJS) {
			$this->JS = '';
		}
	}
	
	public static function enqueue_assets($path, $version = '1') {
		wp_register_style('smartlogix-controls-css', $path.'/css/controls.css', array(), $version);
		wp_enqueue_style('smartlogix-controls-css');
		wp_register_script('smartlogix-controls-js', $path.'/js/controls.js', array('jquery', 'jquery-ui-core'), $version);
		wp_enqueue_script('smartlogix-controls-js');
	}
}
?>