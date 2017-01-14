<?php

/*
This data must be definied before the include of the form generation script

/***** ARRAY TO GENERATE FORM *****/
$FormDataArray = array(
	'First_name' => array(
		placeholder => 'First Name',
		input => '{text}',
	),
	'Last_name' => array(
		placeholder => 'Last Name',
		input => '{text}',
	),
	'Address1' => array(
		placeholder => 'Address',
		input => '{text}',
	),
	'Address2' => array(
		placeholder => 'Apartment, Unit, Suite',
		input => '{text}',
	),
	'Zip' => array(
		placeholder => 'Zip',
		input => '{text}',
	),
	'City' => array(
		placeholder => 'City',
		input => '{text}',
	),
	'State' => array(
		placeholder => 'State',
		input => '{select}',
		input_data => $State_List,
	),
	'Country' => array(
		placeholder => 'Country',
		input => '{hidden}',
		input_data => '',
	),
	'Language' => array(
		placeholder => 'Language',
		input => '{hidden}',
		input_data => 'EN',
	),
	'Phone' => array(
		placeholder => 'Primary Phone',
		input => '{text}',
	),
	'Phone_Work' => array(
		placeholder => 'Work Phone',
		input => '{text}',
	),
	'Email' => array(
		placeholder => 'Email',
		input => '{email}',
	),
	'VOI' => array(
		placeholder => 'Vehicle of Interest',
		input => '{custom}',
		input_data => $voi_array,
	),
	'Purchase_Time' => array(
		placeholder => 'When will you obtain your next vehicle?',
		input => '{radio}',
		input_data => array(
			'0_30Days' => '0 - 30 Days',
			'1_3Months' => '1 to 3 months',
			'4_6Months' => '4 to 6 months',
			'7PlusMonths' => '7+ months',
			'NoDefinitePlans' => 'No definite plans',
		),
	),
	'Optin' => array(
		placeholder => '',
		input => '{checkbox}',
		input_data => array(
			'Yes' => 'Yes! Please email me communications, including product information, offers and incentives, from Ford Motor Company and the local Dealer.',			
		),
	),
	'New_Used' => array(
		placeholder => 'Do you plan to purchase your next vehicle new or used?',
		input => '{select}',
		input_data => array(
			'New' => 'New',
			'Used' => 'Used',
		),
	),	
	'Current_Veh' => array(
		placeholder => 'Current Vehicle Brand',
		input => '{select}',
		input_data => $Current_Vehicle_Make
	),
	'Legal' => array(
		placeholder => '',
		input => '{checkbox}',
		input_data => array(
			'Yes' => 'I am 18+.',
		),
	),
	'Purchase_Lease' => array(
		placeholder => 'How do you plan to obtain your next vehicle?',
		input => '{select}',
		input_data => array(
			'purchase' => 'Purchase',
			'lease' => 'Lease',
		),
	),


);

$FormDataArray_Options = array(
	Required => array('First_Name', 'Last_Name', 'Email', 'Cell', 'Address', 'Zip'),
	Email => array('Email'),
//	date => array(),
//	datetime => array(),
);

$Form_Error_Messages = array(
	Field_Generic => '<span class="errorSpan">*This field is required</span>',
);

/***** SUBMIT BUTTON *****/
$FormSubmitText = 'Kowabunga!';
/***** END FORM ARRAY *****/
$label_type = 'label'; // Displays text <label> to the left of the input content
$label_type = 'placeholder'; // Displays label text inside the input content area
/***** DATETIME FORMAT *****/
$datetime_format = 'unixtime';
/***** SELECT STYLE *****/
$Select_Style = 'Plain'; // Place this here to use standard select inputs
$Select_Style = 'Plain_Empty'; // Place this here to use standard select inputs with no default option
/***** RADIO STYLE *****/
$Radio_Style = 'Plain'; // Place this here to use standard select inputs


include('./scripts/autoForm/autoForm.php');

if($valid == 'VALID'){
	foreach($_POST as $k => $v){
		if($k != 'submit' && $k != 'submit_button'){
			if(is_array($v))
				$v = 'Yes';
			$dataString .= '{{}}'.$k.'(())'.$v;
		}
	}

	$_SESSION[data] = $dataString;
	header("Location: ./voi.php");
}

?>