<?php
/***** GENERAL PRE-DEFINED ARRAY GROUPINGS *****/
$State_List = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio", 'OK'=>"Oklahoma", 'OR'=>"Oregon", 'PA'=>"Pennsylvania", 'RI'=>"Rhode Island", 'SC'=>"South Carolina", 'SD'=>"South Dakota",'TN'=>"Tennessee", 'TX'=>"Texas", 'UT'=>"Utah", 'VT'=>"Vermont", 'VA'=>"Virginia", 'WA'=>"Washington", 'WV'=>"West Virginia", 'WI'=>"Wisconsin", 'WY'=>"Wyoming");

/***** HTML FOR FORM FIELDS *****/
function generateHTML($placeholder, $data_key, $input_type, $input_data, $special_classes, $post_val, $error, $error_msg){
	global $Form_Error_Messages, $label_type, $Select_Style;

	if($label_type == 'placeholder')
		$placeholder_code = "placeholder='$placeholder'";
	else
		$label = '<label for="'.$data_key.'">'.$placeholder.'</label>';


	$short_array = array(
		'{text}',
		'{number}',
		'{hidden}',
		'{email}',
		'{password}',
	);


	if(in_array($input_type, $short_array)){
		$type = str_replace('}', '', str_replace('{', '', $input_type));

		if($type == 'hidden' && $input_data != '')
			$post_val = $input_data;

		if(is_array($post_val)){
			foreach($post_val as $k => $v){
				$field .= '<input type="'.$type.'" '.$placeholder_code.' name="'.$data_key.'" value="'.$v.'" id="'.$data_key.'" class="'.$special_classes.'">';
			}
		}
		else
			$field = '<input type="'.$type.'" '.$placeholder_code.' name="'.$data_key.'" value="'.$post_val.'" id="'.$data_key.'" class="'.$special_classes.'">';		
	}
	elseif($input_type == '{textarea}'){
		if(is_array($post_val)){
			foreach($post_val as $k => $v){
				$field .= '<textarea '.$placeholder_code.' name="'.$data_key.'" id="'.$data_key.'" class="'.$special_classes.'">'.$v.'</textarea>';
			}
		}
		else
			$field = '<textarea '.$placeholder_code.' name="'.$data_key.'" id="'.$data_key.'" class="'.$special_classes.'">'.$post_val.'</textarea>';
	}
	elseif($input_type == '{checkbox}'){
		if(is_array($input_data)){
			$html_array = '[]';

			foreach($input_data as $key => $value){
				unset($checked);
				if(is_array($post_val)){
					if(!empty($post_val) && in_array($value, $post_val)){
						$checked = 'checked="checked"';
					}
				}
				elseif(is_string($post_val) && array_key_exists($post_val, $input_data)){
					$checked = 'checked="checked"';
				}

				$field .= '<span class="Checkbox"><input type="checkbox" name="'.$data_key.''.$html_array.'" value="'.$key.'" id="'.$data_key.'_'.$key.'" class="'.$special_classes.'" '.$checked.'><label id="'.$data_key.'_'.$key.'" for="'.$data_key.'_'.$key.'">'. $value.'</label></span>';
			}
		}
		else{
 			if(!empty($post_val))
				$checked = 'checked="checked"';

			$field .= '<span class="Checkbox"><input type="checkbox" name="'.$data_key.'" value="Yes" id="'.$data_key.'" class="'.$special_classes.'" '.$checked.'>'. $label.'</span>';
			unset($label);
		}
	}
	elseif($input_type == '{select}'){
		if($Select_Style == 'Plain'){
			foreach($input_data as $k => $v){
				unset($selected);
				if($k == $post_val)
					$selected = 'selected="selected"';

				$field .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
			}
			$field = '<select name="'.$data_key.'" class="'.$special_classes.'" id="'.$data_key.'"><option value="" selected="selected">'.$placeholder.'</option>'.$field.'</select>';
		}
		elseif($Select_Style == 'Plain_Empty'){
			foreach($input_data as $k => $v){
				unset($selected);
				if($k == $post_val)
					$selected = 'selected="selected"';

				$field .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
			}
			$field = '<select name="'.$data_key.'" class="'.$special_classes.'" id="'.$data_key.'">'.$field.'</select>';
		}
		else{
			foreach($input_data as $k => $v){
				if($k == $post_val){
					$placeholder_text = $v;
					$field_value = $v;
				}
				$field .= '<li data-value="'.$k.'"><div>'.$v.'</div></li>';
			}
			if($placeholder_text == '')
				$placeholder_text = $placeholder;
			$field = '<div class="Select'.$special_classes.'" data-name="'.$data_key.'"><span data-value="'.$placeholder.'">'.$placeholder_text.'</span><ul>'.$field.'</ul><input type="hidden" name="'.$data_key.'" value="'.$field_value.'"></div>';
		}
	}
	elseif($input_type == '{radio}'){
		foreach($input_data as $key => $value){
			unset($checked);

			if($key == $post_val)
				$checked = 'checked="checked"';
			
			$field .= '<div class="Radio"><input type="radio" name="'.$data_key.'" value="'.$key.'" id="'.$data_key.'_'.$key.'" class="'.$special_classes.'" '.$checked.'><label for="'.$data_key.'_'.$key.'">'. $value.'</label></div>';
		}
		$field = '<div class="Label">'.$placeholder.'</div>'.$field;
	}
	elseif($input_type == '{custom}'){
		/********** START Vehicles of Interest **********/
		if($data_key == 'VOI'){
			if(is_array($input_data)){
					$html_array = '[]';

				foreach($input_data as $k => $v){
					$k_name = str_replace(' ', '_', $k);
					if($v != ''){
						$filename = './vehicles/'.$v;
						if(file_exists($filename)){
							$vehicles_array = explode('||', $post_val);
							if(in_array($k_name, $vehicles_array)){
								$field .= '<div class="VOI Checked">
											<img src="'.$filename.'">
											<input type="checkbox" name="'.$k_name.'" value="'.$v.'" checked="checked">
											<span>'.$k.'</span>
										</div>';
							}
							else{
								$field .= '<div class="VOI">
											<img src="'.$filename.'">
											<input type="checkbox" name="'.$k_name.'" value="'.$v.'">
											<span>'.$k.'</span>
										</div>';
							}
						}
					}
				}

				$field .= '<div id="Break"></div>';

			}
		}
		/********** END Vehicles of Interest **********/
	}

	if($error != '')
		$field .= $error_msg;
	if($label_type == 'placeholder')
		unset($label);

	if($type != 'hidden')
		$autoForm_display = '<div class="Field '.$error.'">'.$label.$field.'</div>';
	else
		$autoForm_display = $field;

	return $autoForm_display;
}

/***** VALIDATE POST DATA *****/
function cleanInput($data) {
	$data = trim($data);
	$data = stripcslashes($data);
	$data = urlencode($data);
	return $data;
}

function validateEmail($email) {
	$emailReg = '/^.*?\@.*?\..*?$/';
	if( !preg_match($emailReg, $email)) {
	    return false;
	} else {
		return true;
	}
}

function validatePhone($phone) {
	$number = preg_replace("/[^0-9,.]/", "", $phone);
	if( strlen($number) == 10 ) {
		return true;
	} else {
		return false;
	}
}

/***** PHP VALIDATION *****/
if($_SERVER["REQUEST_METHOD"] == "POST") {
	global $Form_Error_Messages, $FormDataArray_Options;

	foreach($FormDataArray_Options[Required] as $k => $v){
		if($_POST[$v] == ''){
			$error[$v] = 'Error';
			$error_msg[$v] = $Form_Error_Messages[Field_Generic];
		}
		elseif(is_array($FormDataArray_Options[Email]) && in_array($v, $FormDataArray_Options[Email])){
			if(validateEmail($_POST[$v]) === false){
				$error[$v] = 'Error';
				$error_msg[$v] = $Form_Error_Messages[Invalid_Email];
			}
		}
		elseif(is_array($FormDataArray_Options[Phone]) && in_array($v, $FormDataArray_Options[Phone])){
			if(validatePhone($_POST[$v]) === false){
				$error[$v] = 'Error';
				$error_msg[$v] = $Form_Error_Messages[Invalid_Phone];
			}
		}
		if($v == 'Zip'){
			$check_zip = preg_replace("/[^0-9]/", "", $_POST[$v]);
			if(strlen($check_zip) != 5){
				$error[$v] = 'Error';
				$error_msg[$v] = $Form_Error_Messages[Field_Generic];
			}
		}
		$data[$k] = $v;
	}

	if(!$error){
		/** SET VAID POST HANDLING **/
		$valid = 'VALID';
	}
}


/***** PHP ARRAY PARSE AND DISPLAY *****/
foreach($FormDataArray as $k => $v){

	unset($placeholder, $data_key, $input_type, $input_data, $special_classes);
	foreach($FormDataArray_Options as $c => $f){
		if(in_array($k, $f))
			$special_classes .= ' '.$c;
	}

	$data_key = $k;
	$placeholder = $v[placeholder];
	$input_type = $v[input];
	$input_data = $v[input_data];

	$autoForm_display .= generateHTML($placeholder, $data_key, $input_type, $input_data, $special_classes, $_POST[$k], $error[$k], $error_msg[$k]);
}

$autoForm_display = '
	<div class="Fields">
		'.$autoForm_display.'
		<div id="Break"></div>
		<div class="Submit">
			<div class="ShadowUnder"></div>
			<div class="ShadowOver"></div>
			<div class="Inner"></div>
		</div>
	</div>
';

ob_start();
require_once('autoForm-js.php');
$autoForm_display .= ob_get_clean();

$css_js .= '
	<link rel="stylesheet" href="./scripts/autoForm/autoForm.css" />
';

?>