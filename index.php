<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$con= mysqli_connect("localhost",'root',"")or die("Error to connect host");
mysqli_select_db($con,"imonke")or die("error to connect db");

$result = mysqli_query($con,"select `value_id`,`value` from catalog_product_entity_text");
$main_result = array();
while($row= mysqli_fetch_assoc($result)){
	$main_result[] = $row;
}
//print_r($main_result[0]['value']);exit;
include("simple_html_dom.php");

 $dom = new simple_html_dom();
  // print_r($dom); exit;
    
/*** load the html into the object ***/ 

if(isset($main_result) && count($main_result)>0){
	foreach ($main_result as $key => $value) {

		if($value['value'] != strip_tags($value['value'])) {
			
			$dom->load($value['value']);   
			/*** discard white space ***/ 
			$node =  $dom->find("div.org-listingarea-box");

			$second_dev = "";
			if(array_key_exists(1,$node)){
				$second_dev = addslashes($node[1]);
			}

			$value_id = $value['value_id'];
			if(isset($second_dev) && $second_dev!=""){
	
				$update_record = mysqli_query($con,"UPDATE catalog_product_entity_text SET value = '".$second_dev."' where `value_id` = $value_id");
				if(isset($update_record) && $update_record!=""){
					echo "Update"."<br>";
				}else{
					echo "faild";
				}
			}
			
		    
		}
		
	}
}

?>