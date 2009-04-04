<?php

$plugin_info = array(
	'pi_name'			=> 'Gallery Category Parent',
	'pi_version'		=> '1.1',
	'pi_author'			=> 'Nathan Pitman',
	'pi_author_url'		=> 'http://www.nathanpitman.com/',
	'pi_description'	=> 'Returns the name or ID of the parent category for any given gallery category',
	'pi_usage'			=> np_gallery_category_parent::usage()
);

class np_gallery_category_parent {

	var $category_id = "";
	var $return = "name";
	
	function np_gallery_category_parent()
	{
		global $TMPL, $DB;

		// Get username from template
		$category_id = $TMPL->fetch_param('category_id');
		$return = $TMPL->fetch_param('return');
        
		// If username is set
		if ($category_id != "") {
			$sql = "SELECT cat_id, cat_name FROM exp_gallery_categories WHERE cat_id=(SELECT parent_id FROM exp_gallery_categories WHERE cat_id='".$DB->escape_str($category_id)."')";
			$DB->fetch_fields = TRUE;
			$query = $DB->query($sql);
			
			// If the category_id exist in the exp_gallery_categories table
			if ($query->num_rows == 1) {
			
				if ($return=="id") {
      				$this->return_data = $query->row['cat_id'];
      			} else {
      				$this->return_data = $query->row['cat_name'];
      			}
      			return;
			}
		// Username not set, return an error in place of the status
		} else {
			$this->return_data = "Error: The category_id parameter is required!";
			return;
		}
		
	}
	

// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.
//  Make sure and use output buffering

function usage()
{
ob_start(); 
?>

BASIC USAGE:

{exp:np_gallery_category_parent category_id='1'}

PARAMETERS:

category_id = '1' (no default - must be specified)
 - The category_id parameter defines what category you want to return the parent name for.
 
return = 'id' (default - 'name')
 - The return parameter defines what you want the plug-in to return, category name or id.
	
RELEASE NOTES:

1.1 - Added ability to pass return parameter.
1.0 - Initial Release.

For updates and support check the developers website: http://nathanpitman.com


<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}


}
?>