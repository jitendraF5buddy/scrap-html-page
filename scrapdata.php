
<?php
/*ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);*/

/* -----||--------|||||||--|||||||-------||||||||-------*/
/* -----||--------||-------||------------||----||-------*/
/* -----||--------|||||----|||||||-------||||||||-------*/
/* -----||--------||------------||-------||----||-------*/
/* -----||--------||-------|||||||-------||||||||-------*/
  $date_data = $_POST['date'];

  $base = "https://www.example.com/predictions/date/".$date_data."/competition";

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_URL, $base);
  curl_setopt($curl, CURLOPT_REFERER, $base);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  $html = curl_exec($curl);
  curl_close($curl);


    //$html = htmlentities($html);
   include("simple_html_dom.php");
   /*** a new dom object ***/ 
   $dom = new simple_html_dom();
  // print_r($dom); exit;
    
   /*** load the html into the object ***/ 
   $dom->load($html);   
   /*** discard white space ***/ 
  
   $node =  $dom->find("div.header");
   $second_data =  $dom->find("div.match");

  
   

   //inner result code start
   $array1 = array();
   $main_predictor = array();
   $m_cnt=0;
   foreach ($node as $key => $value) {
      preg_match_all('/<div class=\'name\'>(.*?)<\/div>/s',$value, $mainname);
      $match_host = $mainname[0][0];

    $host_name = strip_tags($match_host);
    if(isset($host_name) && $host_name !="Advertisement"){

      $main_predictor['host_name'][] = $host_name;
      
      $html_q = $value->next_sibling();
      
      // $value->next_sibling() this function is useing for get all child record 
      if($value->next_sibling()){
        

          preg_match_all('/<div class=\'coefbox\'>(.*?)<\/div>/s',$html_q, $matches);
          preg_match_all('/<div class=\'coefbox separate\'>(.*?)<\/div>/s',$html_q, $seperateclass);
          preg_match_all('/<div class=\'date\'>(.*?)<\/div>/s',$html_q, $date);
          preg_match_all('/<div class=\'name\'>(.*?)<\/div>/s',$html_q, $name);
          preg_match_all('/<div class=\'type(.*?)\'>(.*?)<\/div>/s',$html_q, $tip);
          
          
          /*-------------------------------------------------*/
          //this function is using for get host name who is host the match like India etc.
          $hostkey = 0;
          foreach ($tip[0] as $key => $tip1) {
              if($key % 2){
               $main_predictor['tips'][$m_cnt][$hostkey] = strip_tags($tip1);
               $hostkey++;
              }
          }
          //End this above function
          /*-------------------------------------------------*/  

          //Matches names this function is using for get matches name only and push in array 
          /*-------------------------------------------------*/
          //Start 
          $new_array = array();
          $i=0;
          foreach($name[0] as $key => $name1) {
              if($key % 2){
                $main_predictor['match_name'][$m_cnt][$i][]= strip_tags($name1);
                $i++;
              }else{
                $main_predictor['match_name'][$m_cnt][$i][] = strip_tags($name1);
              }
          }
          //End this below function
          /*-------------------------------------------------*/
       

          //Match time start from pregmetch function
          /*-------------------------------------------------*/
          $match_time = array();
          foreach ($date[0] as $key => $value) {
              $match_time[$key] = strip_tags($value);
          }
          $main_predictor['match_time'][$m_cnt] = $match_time;
          /*-------------------------------------------------*/
          //End this function Match time start from pregmetch function

           /*-------------------------------------------------*/
          //Start predictor data from match function but some data found//
          //Like '1','X','H1','HX','1.5','2.5','BTS' //
          // Other data availabe in below function //
          //Start function 
          $mat_key = array();
          $m = 0;
          $g = 0;
          $final_result =array();
          foreach($matches[0] as $keys => $matchess) {
             $checkarray = array('1','X','H1','HX','1.5','2.5','BTS');
              $listm = strip_tags($matchess);
              if(!in_array($listm,$checkarray)){
                $newkey = $checkarray[$g]; 
                $final_result[$m][$newkey] = $listm;
                if($g==6){
                  $g = 0;
                $m++;
                }else{
                  $g++;
                }
              }else{
                $mat_key[] = $listm; 
              }
          }

          //End this below function
          /*-------------------------------------------------*/ 

          /*-------------------------------------------------*/ 
          // Start second function to get predictor data //
          // Data like '2','H2','3.5' //
          //print_r($seperateclass[0]);
          $mat_pred_second = array();
          $mat_pred_second = array();
          $m = 0;
          $g = 0;
          foreach($seperateclass[0] as $keys => $matchess) {
              $checkarray = array('2','H2','3.5');
              $second_match_r = strip_tags($matchess);
              if(!in_array($second_match_r,$checkarray)){
                  $newkey = $checkarray[$g]; 
                  $mat_pred_second[$m][$newkey] = $second_match_r;
                  if($g==2){
                    $g = 0;
                    $m++;
                  }else{
                    $g++;
                  }
              }             
          }
          //print_r($mat_pred_second);
          //End the above function
          /*-------------------------------------------------*/ 

          /*-------------------------------------------------*/ 
          //Push predictor in new array with details
          // Start to push data in new old array only for redictor record
          foreach ($final_result as $key => $value) {
              foreach ($mat_pred_second as $key1 => $value1) {
                  foreach ($value1 as $key2 => $value2) {
                      $final_result[$key1][$key2] = $value2;
                  }
                  
              }
          }
          $main_predictor['predictor'][$m_cnt] = $final_result;
          //This only redictor data 
          /*-------------------------------------------------*/ 
 
  
      }
       $m_cnt++;
    }
      //This is counter run
      //  exit;
    }
    //print_r($main_predictor);exit;
    //print_r($main_predictor);
   /*foreach ($second_data as $key => $value) {
    echo $value."<br/> ";exit;
   }*/
   //print_r($new_array_final_result);
?>
