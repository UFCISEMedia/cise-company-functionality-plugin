<?php
	$values = get_field('company_accepting');
	$field = get_field_object('company_accepting');
	$choices = $field['choices'];
	foreach ($choices as $choice_value => $choice_label) {
		$found = false;
		foreach ($values as $value) {
		  if ($value['value'] == $choice_value) {
			$found = true;
			echo '<span style="color:#008000">&#10003;</span> ';
		  }
		} // end foreach $values
		if (!$found) {
		  echo '<span style="color:#FF0000">&#10005;</span> ';
		}
		echo $choice_label,' | ';				
	 } // end foreach $choices			
?>