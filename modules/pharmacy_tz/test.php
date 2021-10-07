<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
array(78) { ["ordDate"] => string(10) "2012-07-03" ["ordIrnNo"] => string(7) "1009058" 
    ["storeID"] => string(2) "25" ["storeDesc"] => string(8) "PHARMACY" ["issiuNo"] => string(0) "" 
    ["status"] => string(7) "pending" ["issue_Type"] => string(0) "" ["supStoreid"] => string(5) 
    "pharm" ["supStoredesc"] => string(10) "DRUG STORE" ["issue_Date"] => string(10) "2012-07-04" 
    ["totalOrders"] => string(0) "" ["submit"] => string(4) "Send" 
    
    ["gridbox_1_0"] => string(5) "ANTD3" ["gridbox_1_1"] => string(6) "ANTI D" ["gridbox_1_2"] => string(1) "3" ["gridbox_1_3"] => string(0) "" ["gridbox_1_4"] => string(1) "3" ["gridbox_1_5"] => string(0) "" 
    ["gridbox_2_0"] => string(4) "H021" ["gridbox_2_1"] => string(19) "HEPATITIS-B VACCINE" ["gridbox_2_2"] => string(1) "2"["gridbox_2_3"] => string(9) "700.00000" ["gridbox_2_4"] => string(1) "2" ["gridbox_2_5"] => string(0) "" 
    ["gridbox_3_0"] => string(7) "itm3409" ["gridbox_3_1"] => string(12) "SYRINGE 5 CC" ["gridbox_3_2"] => string(3) "100" ["gridbox_3_3"] => string(2) "20" ["gridbox_3_4"] => string(2) "50" ["gridbox_3_5"] => string(0) "" 
    ["gridbox_4_0"] => string(7) "itm3412" ["gridbox_4_1"] => string(15) "NEEDLE GUAGE 21" ["gridbox_4_2"] => string(3) "400" ["gridbox_4_3"] => string(1) "3" ["gridbox_4_4"] => string(3) "300" ["gridbox_4_5"] => string(0) "" 
    
    ["gridbox_5_0"] => string(7) "itm3413" ["gridbox_5_1"] => string(16) "NEEDLE GUAGE 23" 
    ["gridbox_5_2"] => string(3) "200" ["gridbox_5_3"] => string(1) "3" ["gridbox_5_4"] => string(3) "200" 
    ["gridbox_5_5"] => string(0) "" ["gridbox_6_0"] => string(7) "itm3410" 
    ["gridbox_6_1"] => string(13) "SYRINGE 10 CC" ["gridbox_6_2"] => string(3) "100" 
    ["gridbox_6_3"] => string(2) "20" ["gridbox_6_4"] => string(3) "100" ["gridbox_6_5"] => string(0) "" 
    ["gridbox_7_0"] => string(7) "itm3408" ["gridbox_7_1"] => string(12) "SYRINGE 2 CC"
    ["gridbox_7_2"] => string(3) "100" ["gridbox_7_3"] => string(2) "20" ["gridbox_7_4"] => string(2) "50" 
    ["gridbox_7_5"] => string(0) "" ["gridbox_8_0"] => string(4) "SS01" 
    ["gridbox_8_1"] => string(10) "SOFT STYLE" ["gridbox_8_2"] => string(3) "100" 
    ["gridbox_8_3"] => string(2) "60" ["gridbox_8_4"] => string(3) "100" ["gridbox_8_5"] => string(0) ""
    ["gridbox_9_0"] => string(7) "itm3420" ["gridbox_9_1"] => string(16) "DISPOSALE GLOVES" 
    ["gridbox_9_2"] => string(3) "500" ["gridbox_9_3"] => string(1) "2" ["gridbox_9_4"] => string(1) "0" 
    ["gridbox_9_5"] => string(0) "" ["gridbox_10_0"] => string(7) "itm2618" 
    ["gridbox_10_1"] => string(18) "STAPLES NO 24/6" ["gridbox_10_2"] => string(1) "1" 
    ["gridbox_10_3"] => string(0) "" ["gridbox_10_4"] => string(1) "0" ["gridbox_10_5"] => string(0) "" 
    ["gridbox_11_0"] => string(5) "TIS06" ["gridbox_11_1"] => string(13) "TISSUE PAPER" 
    ["gridbox_11_2"] => string(2) "10" ["gridbox_11_3"] => string(2) "50" ["gridbox_11_4"] => string(2) "10" 
    ["gridbox_11_5"] => string(0) "" } rows 
    
    INSERT INTO care_ke_internalserv ( req_no, issueno, STATUS, req_date, req_time, store_loc, 
            Store_desc, sup_storeId, sup_storeDesc, item_id, Item_desc, qty, qty_issued, period, 
            input_user, balance, issue_date, issue_time) 
            VALUES('1009058', '', 'serviced', '2012-07-03', '10:47:55', '25', 'PHARMACY', 
                    'pharm', 'DRUG STORE', 'ANTD3', 'ANTI D', '3', '3', '2009', 'Admin', '0', 
                    '2012-07-04', '10:47:55');



select quantity from care_ke_locstock where stockid = "ANTD3" and loccode = "25"
    Update care_ke_locstock set quantity = "10" where stockid = "ANTD3"and loccode = "25"
    update care_ke_internalreq set status = 'Serviced', qty_issued = '3', balance = '0', 
        issue_date = '2012-07-04', issue_time = '10:47:55' where req_no = '1009058' and item_id = 'ANTD3'0
        
        INSERT INTO care_ke_internalserv ( req_no, issueno, STATUS, req_date, req_time, store_loc, 
                Store_desc, sup_storeId, sup_storeDesc, item_id, Item_desc, qty, qty_issued, period, 
                input_user, balance, issue_date, issue_time) VALUES('1009058', '', 'serviced', 
                        '2012-07-03', '10:47:55', '25', 'PHARMACY', 'pharm', 'DRUG STORE', 'H021', 
                        
                        'HEPATITIS-B VACCINE', '2', '2', '2009', 'Admin', '0', '2012-07-04', '10:47:55');
select quantity from care_ke_locstock where stockid = "H021" and loccode = "25"

Update care_ke_locstock set quantity = "3" where stockid = "H021"and loccode = "25"
update care_ke_internalreq set status = 'Serviced', qty_issued = '2', balance = '0', 
        issue_date = '2012-07-04', issue_time = '10:47:56' where req_no = '1009058' and item_id = 'H021'
?>
