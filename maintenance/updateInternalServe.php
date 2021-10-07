<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('./roots.php');
require('../include/inc_environment_global.php');

      $debug=false;
        $sql2="SELECT DISTINCT item_id,item_desc FROM `care_ke_internalserv` WHERE req_date='2017-05-03' 
            AND input_user='Martha Kathei' 
                ORDER BY item_id ASC";
                $result=$db->Execute($sql2);
                if($debug) echo $sql2.'<br>';
                
                $transNo=12274;
                
            while($row2=$result->FetchRow()) {
                    $total=getTotals($row2[item_id]);
                     $sql4 = "UPDATE maua.locstock SET Quantity =Quantity+$total WHERE stockid ='$row2[item_id]'";
                      if($debug) echo $sql4.'<br>';
                    $db->Execute($sql4);  
                    $qty=getQuantity($row2[item_id]);
                    $newqoh=$qty+$total;
                        $sql2="INSERT INTO maua.stockmoves (
                                `stockid`, `type`, `transno`, `loccode`, `trandate`, `prd`, `reference`, `qty`, 
                                `newqoh`,`narrative`
                              ) 
                              VALUES (
                                  '$row2[item_id]','17', '$transNo','MAIN', '2017-05-03','7',
                                  'Stock Adjustment after wrong servicing','$total','$qty','Stock Adjustment after wrong servicing')";
                                   if($debug) echo $sql2.'<br>';  
                                   
                              if($db->Execute($sql2)){ 
                                  $transNo=$transNo+1;
                                  echo "Updated stock " .$row2[item_id] ." ". $row2[item_desc]." from $qty to ".$total . "<br>";
                              }
                                
                        
            }
           
            
            function getQuantity($itemID){
                global $db;
                $debug=false;
                 $sql3="Select Quantity from maua.locstock where stockid='$itemID'";
                  if($debug) echo $sql3.'<br>';
                 $result=$db->Execute($sql3);
                 $row=$result->FetchRow();
                 $qty=$row[0];
                 
                 return $qty;
            }
            
            function getTotals($itemID){
                global $db;
                $debug=false;
                $sql="SELECT * FROM `care_ke_internalserv`  WHERE req_date='2017-05-03' AND
                        input_user='Martha Kathei' AND item_id='$itemID'
                            ORDER BY item_id ASC LIMIT 18446744073709551615 OFFSET 1";
                if($debug) echo $sql.'<br>';
                $results=$db->Execute($sql);
                    $total=0;
                    while($row=$results->FetchRow()) {
                          $total=$total+$row[qty_issued];  
                    }
                    return $total;
            }
//    }
?>
