<?php

require_once($root_path.'include/inc_environment_global.php');

require_once('class_weberp.php');

function new_weberp() {
	global $sid, $is_transmit_to_weberp_enable;

	if($is_transmit_to_weberp_enable == 1)
	{
		$cache_filename = 'object_data.inc';
		if (!file_exists(sys_get_temp_dir().'/'.$_SERVER['HTTP_HOST'])) {
			mkdir(sys_get_temp_dir().'/'.$_SERVER['HTTP_HOST'], 0700);
		}
		$cachefile_full_filename = sys_get_temp_dir().'/'.$_SERVER['HTTP_HOST'].'/'.$cache_filename;
		if(file_exists($cachefile_full_filename)){
			$object_data = unserialize(file_get_contents($cachefile_full_filename));
		} else {
			$object_data = new weberp_c2x;
		}
		return $object_data;
	}
	else
	{
		return false;
	}


}

function destroy_weberp($obj_weberp) {
	global $sid;
	$cache_filename = 'object_data.inc';
	if (!file_exists(sys_get_temp_dir().'/'.$_SERVER['HTTP_HOST'])) {
		mkdir(sys_get_temp_dir().'/'.$_SERVER['HTTP_HOST'], 0700);
	}
	$cachefile_full_filename = sys_get_temp_dir().'/'.$_SERVER['HTTP_HOST'].'/'.$cache_filename;
	file_put_contents($cachefile_full_filename, serialize($obj_weberp));
	return true;
}

class weberp_c2x extends weberp {

     function transfer_Payroll_to_webERP_asCredit($payrollCredits)
    {
 		$payrollCredits[glCode] = $payrollCredits[gl_acc];
                $payrollCredits[Narrative] ='Pay Types of '.$payrollCredits[pay_type];
                $payrollCredits[trandate]= date('d/m/Y');//$payments[pdate];//date($this->defaultDateFormat)
                $payrollCredits[ovamount]= $payrollCredits[credit];
                $this->transfer_payrollCredit_to_weberp($payrollCredits);
    }
     function transfer_Payroll_to_webERP_asCost($payroll)
    {
 		$paymentData[glCode] = $payroll[gl_acc];
                $paymentData[Narrative] ='Staff Cost of '.$payroll[Department];
                $paymentData[trandate]= date('d/m/Y');//$payments[pdate];//date($this->defaultDateFormat)
                $paymentData[ovamount]= $payroll[grosspay];
                $this->transfer_payroll_to_weberp($paymentData);
    }
    
    function transfer_DB_to_webERP_asPayment($payments)
    {
                
                $paymentData[ovaTotal]= $payments[Total];
                        $paymentData[ovamount]= $payments[Amount];
                $paymentData[ledgerCode]= $payments[ledger_code];
                $paymentData[glCode] = $payments[gl_acc];
                $paymentData[glAccount] = $payments[GL_Desc];
                if( $payments[cheque_no]==''){
                    $paymentData[chequeNo] = '0';
                }else{
                    $paymentData[chequeNo] = $payments[cheque_no];
                }
                $paymentData[reference] = $payments[payee];
                $paymentData[Narrative] = $payments[toward];
                $paymentData[Voucher_No] = $payments[Voucher_No];
                
                $pSdate=new DateTime($payments[pdate]);
                 $pdateS=$pSdate->format('d/m/Y');
                $paymentData[trandate]= $pdateS;//date($this->defaultDateFormat);
                
                if($payments[pay_mode]=='CHQ'){
                   $paymentData[paymentType] = 'Cheque';
                }else{
                     $paymentData[paymentType] = 'Cash';
                }
//                $paymentData[ovamount]=$payments[ovamount];
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
                $this->transfer_dbPayment_to_weberp($paymentData);
    }

     function transfer_IP_to_webERP_asPayment($payments)
    {
                $paymentData[ovaTotal]= $payments[Total];
                        $paymentData[ovamount]= $payments[Amount];
                $paymentData[ledgerCode]= $payments[ledger_code];
                $paymentData[glCode] = $payments[gl_acc];
                $paymentData[glAccount] = $payments[GL_Desc];
                if( $payments[cheque_no]==''){
                    $paymentData[chequeNo] = '0';
                }else{
                    $paymentData[chequeNo] = $payments[cheque_no];
                }
                $paymentData[reference] = $payments[payee];
                $paymentData[Narrative] = $payments[toward];
                $paymentData[Voucher_No] = $payments[Voucher_No];
                 $pSdate=new DateTime($payments[pdate]);
                 $pdateS=$pSdate->format('d/m/Y');
                $paymentData[trandate]= $pdateS;//date($this->defaultDateFormat);
                if($payments[pay_mode]=='CHQ'){
                   $paymentData[paymentType] = 'Cheque';
                }else{
                     $paymentData[paymentType] = 'Cash';
                }
//                $paymentData[ovamount]=$payments[ovamount];
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
                $this->transfer_IPPayment_to_weberp($paymentData);
    }
    
    function transfer_gl_to_webERP_asPayment($payments)
    {
                $paymentData[ovaTotal]= $payments[Total];
                $paymentData[ovamount]= $payments[Amount];
                $paymentData[ledgerCode]= $payments[ledger_code];
                $paymentData[glCode] = $payments[gl_acc];
                $paymentData[glAccount] = $payments[GL_Desc];
                if( $payments[cheque_no]==''){
                    $paymentData[chequeNo] = '0';
                }else{
                    $paymentData[chequeNo] = $payments[cheque_no];
                }
                $paymentData[reference] = $payments[payee];
                $paymentData[Narrative] = $payments[toward];
                $paymentData[VoucherNo] = $payments[Voucher_No];

                $pSdate=new DateTime($payments[pdate]);
                 $pdateS=$pSdate->format('d/m/Y');
                $paymentData[trandate]= $pdateS;//date($this->defaultDateFormat);
                if($payments[pay_mode]=='CHQ'){
                   $paymentData[paymentType] = 'Cheque';
                }else{
                     $paymentData[paymentType] = 'Cash';
                }
//                $paymentData[ovamount]=$payments[ovamount];
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
                $this->transfer_glPayment_to_weberp($paymentData);
    }

    function transfer_pc_to_webERP_asPayment($payments)
    {
        $paymentData[ovaTotal]= $payments[Total];
        $paymentData[ovamount]= $payments[Amount];
        $paymentData[ledgerCode]= $payments[ledger_code];
        $paymentData[glCode] = $payments[gl_acc];
        $paymentData[glAccount] = $payments[GL_Desc];

        if( $payments[cheque_no]==''){
            $paymentData[chequeNo] = '0';
        }else{
            $paymentData[chequeNo] = $payments[cheque_no];
        }
        $paymentData[reference] = $payments[payee];
        $paymentData[Narrative] = $payments[toward];
        $paymentData[VoucherNo] = $payments[Voucher_No];

        $pSdate=new DateTime($payments[pdate]);
        $pdateS=$pSdate->format('d/m/Y');
        $paymentData[trandate]= $pdateS;//date($this->defaultDateFormat);
        if($payments[pay_mode]=='CHQ'){
            $paymentData[paymentType] = 'Cheque';
        }else{
            $paymentData[paymentType] = 'Cash';
        }
//                $paymentData[ovamount]=$payments[ovamount];
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
        $this->transfer_pcPayment_to_weberp($paymentData);
    }

     function transfer_trans_to_webERP_asPayment($payments)
    {
                $paymentData[ovaTotal]= $payments[Total];
                $paymentData[ovamount]= $payments[Amount];
                $paymentData[supplierID]= $payments[ledger_code];
                $paymentData[glCode] = $payments[gl_acc];
                $paymentData[glAccount] = $payments[GL_Desc];
                if( $payments[cheque_no]==''){
                    $paymentData[chequeNo] = '0';
                }else{
                    $paymentData[chequeNo] = $payments[cheque_no];
                }
                $paymentData[reference] = $payments[payee];
                $paymentData[department] = $payments[department];
                $paymentData[Narrative] = $payments[toward];
                $paymentData[Voucher_No] = $payments[Voucher_No];
                
                $pSdate=new DateTime($payments[pdate]);
                $pdateS=$pSdate->format('d/m/Y');
                $paymentData[trandate]= $pdateS;//date($this->defaultDateFormat);
                if($payments[pay_mode]=='CHQ'){
                   $paymentData[paymentType] = 'Cheque';
                }else{
                     $paymentData[paymentType] = 'Cash';
                }
//              $paymentData[ovamount]=$payments[ovamount];
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
                $this->transfer_payment_to_weberp($paymentData);
    }
    
    function transfer_trans_to_webERP_asGLReceipt($payments)
    {
 		$paymentData[ovaTotal]= $payments[total];
                $paymentData[ovamount]= $payments[total];
 		$paymentData[gl_acc]= $payments[gl_acc];
 		$paymentData[creditGlCode] = $payments[patient];
 		$paymentData[creditGLAccount] = $payments[prec_desc];
                
                if($payments[cheque_no]){
                    $paymentData[chequeNo]= $payments[cheque_no];
                } else{
                      $paymentData[chequeNo]= 0;
                }
                $paymentData[reference] = $payments[ref_no];
                $paymentData[Narrative] = $payments[towards];
                $paymentData[payee] = $payments[payer];
                
                
                $pdate1 = $payments[currdate];
                $date1 = new DateTime(date($pdate1));
                $pdate = $date1->format("d/m/Y");
                
                $paymentData[trandate]= $pdate;//$payments[pdate];//date($this->defaultDateFormat);
                if($payments[pay_mode]=='CHQ'){
                   $paymentData[paymentType] = 'Cheque';
                }else{
                     $paymentData[paymentType] = 'Cash';
                }
//                $paymentData[ovamount]=$payments[ovamount];
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
                $this->transfer_GLReceipt_to_weberp($paymentData);
    }
    
    function transfer_trans_to_webERP_asDBReceipt($payments)
    {
 		$paymentData[ovaTotal]= $payments[total];
                $paymentData[ovamount]= $payments[total];
 		$paymentData[gl_acc]= $payments[gl_acc];
 		$paymentData[creditGlCode] = $payments[proc_code];
 		$paymentData[creditGLAccount] = $payments[prec_desc];
                
                if($payments[cheque_no]){
                    $paymentData[chequeNo]= $payments[cheque_no];
                } else{
                      $paymentData[chequeNo]= 0;
                }
                $paymentData[reference] = $payments[ref_no];
                $paymentData[Narrative] = $payments[towards];
                $paymentData[payee] = $payments[payer];
                
                 $pdate1 = $payments[currdate];
                $date1 = new DateTime(date($pdate1));
                $pdate = $date1->format("d/m/Y");
                $paymentData[trandate]= $pdate;//$payments[pdate];//date($this->defaultDateFormat);
                if($payments[pay_mode]=='CHQ'){
                   $paymentData[paymentType] = 'Cheque';
                }else{
                     $paymentData[paymentType] = 'Cash';
                }
//                $paymentData[ovamount]=$payments[ovamount];
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
                $this->transfer_DBReceipt_to_weberp($paymentData);
    }
    
    function transfer_trans_to_webERP_asIPReceipt($payments)
    {
 		$paymentData[ovaTotal]= $payments[total];
                $paymentData[ovamount]= $payments[total];
 		$paymentData[gl_acc]= $payments[gl_acc];
 		$paymentData[creditGlCode] =$payments[proc_code];
 		$paymentData[creditGLAccount] = "DEBTORS CONTROL ACCOUNT";
                
                if($payments[cheque_no]){
                    $paymentData[chequeNo]= $payments[cheque_no];
                } else{
                      $paymentData[chequeNo]= 0;
                }
                $paymentData[reference] = $payments[ref_no];
                $paymentData[Narrative] = $payments[towards];
                $paymentData[payee] = $payments[payer];
                
                
                $pdate1 = $payments[currdate];
                $date1 = new DateTime(date($pdate1));
                $pdate = $date1->format("d/m/Y");
                
                $paymentData[trandate]= $pdate;//$payments[pdate];//date($this->defaultDateFormat);
                if($payments[pay_mode]=='CHQ'){
                   $paymentData[paymentType] = 'Cheque';
                }else{
                     $paymentData[paymentType] = 'Cash';
                }

                $this->transfer_IPReceipt_to_weberp($paymentData);
    }
    
    function transfer_bill_to_webERP_asIPSalesInvoice($billelems)
    {
        global $db;
        $sql='select category from care_tz_drugsandservices where partcode="'.$billelems[partcode].'"';
        $result=$db->Execute($sql);
        $row=$result->FetchRow();
        
 		$billdata[debtorno] = $billelems[pid];
                $billdata[salesarea] = $billelems[ward_id];
                $billdata[trandate]=  date("d/m/Y");
                $billdata[ovamount]=$billelems[total];
                $this->transfer_IPinvoice_to_weberp($billdata);
    }

    
    function transfer_bill_to_webERP_asSalesInvoice($billelems)
    {
        global $db;

        $sdate1 = $billelems[bill_date];
        $date1 = new DateTime(date($sdate1));
        $sdate = $date1->format("d/m/Y");

        $billdata[ovamount]= $billelems[price]*$billelems[amount];

        if($billelems[bill_number]==''){
            $order=$billelems[sale_id];
        }else{
            $order=$billelems[bill_number];
        }
        $billdata[order_]= $order;
        $billdata[debtorno] = $billelems[pid];
        $billdata[partcode] = $billelems[partcode];
        $billdata[stockCat] = $billelems[category];
        if($billelems[encounter_class_nr]==1){
             $billdata[salesarea] = $billelems[ward_id];
        }else{
             $billdata[salesarea] = $billelems[salesArea];
        }
        
        $billdata[invtext] = 'Invoice for '.$billelems[partcode].'--'.$billelems[article];
        $billdata[trandate]= $sdate;//date($this->defaultDateFormat);
        $billdata[branchcode] = $billelems[pid];
        $billdata[ovamount]=$billelems[ovamount];
        $billdata[reference]=$billelems[category];//.'-'.$billelems[ref_no];
                
// 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
        $this->transfer_invoice_to_weberp($billdata);
    }

    function transfer_bill_to_webERP_asCreditInvoice($billelems)
    {
 		$billdata[ovamount]= -intval($saleInvoiceData[mytotal]);
 	        //$billdata[user_Id]=$billelems[user_Id];
 		$billdata[order_]= $billelems[ref_no];
 		$billdata[debtorno] = $billelems[pid];
                
                $pdate1 = $billelems[currdate];
                $date1 = new DateTime(date($pdate1));
                $pdate = $date1->format("d/m/Y");
                
                $billdata[invtext] = 'bill payment for '.$billelems[name];
                $billdata[trandate]=$pdate;//  date($this->defaultDateFormat);
                $billdata[branchcode] = $billelems[pid];
 		$billdatafinal=$this->generateWebERPCustCreditInvoiceData($billelems);
                $this->transfer_credit_to_weberp($billdatafinal);
    }

    function transfer_patient_to_webERP_asCustomer($pid,$persondata)
    {
    	$customerdata=$this->generateWebERPCustomerData($persondata);
    	$branchdata=$this->generateWebERPCustBranchData($persondata);
    	$this->transfer_customer_to_weberp($customerdata,$branchdata);
    }

    function transfer_test_to_webERP_asTest($TestDetails)
    {
    	$testdata=$this->generateTestData($TestDetails);
    	$this->transfer_Test_to_weberp($testdata);
    }

    function make_patient_workorder_in_webERP($treatmentID)
    {
    	$woData['loccode']=$this->defaultLocation;
    	$woData['requiredby']=date('Y-m-d');
    	$woData['startdate']=date('Y-m-d');
    	$woData['costissued']=0;
    	$woData['closed']=0;
    	$woData['stockid']='TREATMENT';
    	$woData['qtyreqd']=1;
    	$woData['qtyrecd']=0;
    	$woData['stdcost']=0;
    	$woData['nextlotsnref']=$treatmentID;
    	$this->transfer_workorder_to_weberp($woData);
    }

    function issue_to_patient_workorder_in_weberp($SerialNumber, $StockID, $Location, $Quantity, $Batch)
    {
    	$woSearchData[0]='nextlotsnref';
    	$woSearchData[1]=$SerialNumber;
    	$answer=$this->transfer($woSearchData,$this->weberpcalls['searchWorkOrders']);
    	$woIssueData[0]=$answer[0];
    	if (!$this->get_stock_item_from_webERP($StockID))
    	{
    		$stockData=$this->get_stock_info_for_webERP($StockID);
			$this->create_stock_item_in_webERP($stockData);
    	}
    	$woIssueData[1]=$StockID;
    	$woIssueData[2]=$Location;
    	$woIssueData[3]=$Quantity;
    	$woIssueData[4]=date('Y-m-d');
    	$woIssueData[5]=$Batch;
    	$this->issue_to_workorder_in_weberp($woIssueData);
    }

    function receive_patient_workorder_in_weberp($WONumber)
    {
    	$woReceiveData[0]=$WONumber;
    	$woReceiveData[1]='TREATMENT';
    	$woReceiveData[2]=$this->defaultLocation;
    	$woReceiveData[3]=1;
    	$woReceiveData[4]=date($this->defaultDateFormat);
    	$this->receive_workorder_in_weberp($woReceiveData);
    }

    function get_stock_info_for_webERP($StockID)
    {
		global $db;
	  	$sql="SELECT * FROM care_tz_drugsandservices where item_number='".$StockID."'";
  		$result=$db->Execute($sql);
  		$myrow=$result->FetchRow();
		$StockData['stockid']=$StockID;
		$StockData['description']=$myrow['item_description'];
		$StockData['longdescription']=$myrow['item_full_description'];
		//if ($myrow['purchasing_class']=="service")
		if ($myrow['purchasing_class']=="drug_list")
		{
			$StockData['mbflag'] = 'D';
		}
		if (($myrow['is_pediatric']+$myrow['is_adult']+$myrow['is_other']+$myrow['is_consumable']+$myrow['is_labtest'])==0)
		{
			$StockData['categoryid']='ZZ';
		}
		return $StockData;
    }

	function generateWebERPCustSalesInvoiceData($saleInvoiceData)
	{
            global $db;
                $sdate1 = $saleInvoiceData[currdate];
                $date1 = new DateTime(date($sdate1));
                $sdate = $date1->format("d/m/Y");
     $sql='SELECT d.category,c.item_cat,d.item_description FROM care_tz_drugsandservices d 
            LEFT JOIN care_tz_itemscat c ON d.category=c.catID 
            where d.partcode="'.$saleInvoiceData[partcode].'"';     
//     echo $sql;
        $result=$db->Execute($sql);
        $row=$result->FetchRow();
        $item_desc=$row[item_description];
        $item_cat=$row[item_cat];
        $lab=  substr($saleInvoiceData[partcode], 0, 3);
        $xray=   substr($saleInvoiceData[partcode], 0, 3); 
        
        if($row[0]=='10'){
            $salesarea=10;
        }else if($row[0]=='24'){
            $salesarea=29;
        }else if($row[0]=='19'){
            $salesarea=22;
        }else if($row[0]=='THT'){
            $salesarea=27;
        }else if($row[0]=='PHY'){
            $salesarea='PHY';
        }else{
            $sql="select pid,partcode,description,service_type,ledger from care_ke_billing where pid=$saleInvoiceData[pid] 
                    and partcode not in('IP','') and partcode='$saleInvoiceData[partcode]' and service_type<>'Payment'";
            $result=$db->Execute($sql);
            $row=$result->FetchRow();
            $salesarea=$row[ledger];
        }

        if($salesarea=='' || !isset($salesarea)){
            $salesarea='Dispens';
        }
        
        $webERPCustSalesInvoiceData = array (
                debtorno=>$saleInvoiceData[pid],
                branchcode=>$saleInvoiceData[pid],
                transno=>"",
                trandate=>$sdate,//date($this->defaultDateFormat),
                settled=>"",
                reference=>"",
                tpe=>"CA",
                order_=>$saleInvoiceData[article],
                rate=>"1",
                ovamount=>$saleInvoiceData[ovamount],
                ovgst=>"",
                ovfreight=>"",
                ovdiscount=>"",
                diffonexch=>"",
                alloc=>"0",
                invtext=>$saleInvoiceData[article],
                shipvia=>"",
                edisent=>"",
                consignment=>$saleInvoiceData[consignment],
                partcode=>$saleInvoiceData[partcode],
                salesarea=>$salesarea
	);


	$webERPCustSalesInvoiceData = $this->delete_empty_data_entries($webERPCustSalesInvoiceData);

	return $webERPCustSalesInvoiceData;
	}

        function generateWebERPCustCreditInvoiceData($saleInvoiceData)
	{
         global $db;
        $sql='SELECT d.category,c.item_cat,d.item_description,d.purchasing_class FROM care_tz_drugsandservices d 
            LEFT JOIN care_tz_itemscat c ON d.category=c.catID 
            where d.partcode="' . $saleInvoiceData[partcode] . '"';
        $result=$db->Execute($sql);
        $row=$result->FetchRow();
        $item_desc=$row[item_description];
        $item_cat=$row[item_cat];
//        $lab=  substr($saleInvoiceData[partcode], 0, 3);
//        $xray=   substr($saleInvoiceData[partcode], 0, 3);  
        
        if($saleInvoiceData[partcode]=='IP' && $saleInvoiceData[salesType]=='rctAdj'){
            $sql="select e.pid,e.encounter_nr,e.current_ward_nr,w.ward_id,w.name from care_encounter e 
                    left join care_ward w on e.current_ward_nr=w.nr
                    where e.encounter_class_nr=1 and e.pid=$saleInvoiceData[patient]";
            $result3=$db->Execute($sql);
            $row3=$result3->FetchRow();
            $salesarea=$row3[ward_id];
        }else if($row[0]=='10'){
            $salesarea=10;
        }else if($row[0]=='24'){
            $salesarea=29;
        }else if($row[0]=='19'){
            $salesarea=22;
        }else if($row[0]=='THT'){
            $salesarea=27;
        }else if($row[0]=='2' && $row[3]=='DIABETES FEES'){
            $salesarea='05';
        }else if($row[3]=='DENTAL'){
            $salesarea='04';
        }else if($row[3]=='MCH FEES'){
            $salesarea='14';
        }else if($row[3]=='PHY'){
            $salesarea='PHY';
        }else if($saleInvoiceData[salesType]=='rctAdj' && $saleInvoiceData[partcode]<>'IP'){
            $sql="select pid,partcode,description,service_type,ledger from care_ke_billing where pid=$saleInvoiceData[pid] 
                    and partcode='$saleInvoiceData[partcode]' 
            and service_type not in('Payment','Payment Adjustment')";
            $result=$db->Execute($sql);
            $row=$result->FetchRow();
            $salesarea=$row[ledger];
        }else{
            $sql="select pid,partcode,description,service_type,ledger from care_ke_billing where pid='$saleInvoiceData[pid]' 
                    and partcode not in('IP','') and partcode='$saleInvoiceData[partcode]' and service_type<>'Payment'";
            //echo $sql;
            $result=$db->Execute($sql);
            $row=$result->FetchRow();
            $salesarea=$row[ledger];
        }

            if($salesarea=='' || !isset($salesarea)){
                $salesarea='Dispens';
            }

//        $billdata[salesarea] = $billelems[salesArea];
             $pdate1 = $saleInvoiceData[currdate];
                $date1 = new DateTime(date($pdate1));
                $pdate = $date1->format("d/m/Y");
                
        $webERPCustSalesInvoiceData = array (
                debtorno=>$saleInvoiceData[pid],
                branchcode=>$saleInvoiceData[pid],
                transno=>"",
                trandate=>$pdate,  //date($this->defaultDateFormat),
                settled=>"",
                reference=>$saleInvoiceData[ref_no],
                tpe=>"CA",
                order_=>$saleInvoiceData[ref_no],
                rate=>"1",
                ovamount=>intval($saleInvoiceData[ovamount]),
                ovgst=>"0",
                ovfreight=>"0",
                ovdiscount=>"0",
                diffonexch=>"0",
                alloc=>"0",
                invtext=>'Payment for '.$item_cat.'-- '.$item_desc,
                shipvia=>"",
                edisent=>"",
                consignment=>"",
                partcode=>$saleInvoiceData[partcode],
                salesarea=>$salesarea,
                salesType=>$saleInvoiceData[salesType]
//                stockCat=>$row[0]
	);


	$webERPCustSalesInvoiceData = $this->delete_empty_data_entries($webERPCustSalesInvoiceData);

	return $webERPCustSalesInvoiceData;
	}

	function generateWebERPCustBranchData($customerdata)
	{
		$webERPCustBranchData = array (

			branchcode=>$customerdata[pid],
			debtorno=>$customerdata[pid],
			brname=>"".$customerdata[name_first]." ".$customerdata[name_last]."",
			braddress1=>"".$patientdata[addr_zip],
			braddress2=>"kikuyu",
			braddress3=>"kenya",
			braddress4=>"00100",
			braddress5=>"",
			braddress6=>"",
			estdeliverydays =>"0",
			area=>"R01",//$this->getAreaCode($customerdata[district]),
			salesman=>"01",
			fwddate=>"0",
			phoneno=>"0",
			faxno=>"0",
			contactname=>"0",
			email=>"0",
			defaultlocation=>"MAIN",
			taxgroupid=>"1",
			defaultshipvia=>"1",
			deliverblind=>"1",
			disabletrans=>"0",
			brpostaddr1=>"0",
			brpostaddr2=>"0",
			brpostaddr3=>"0",
			brpostaddr4=>"0",
			brpostaddr5=>"0",
			brpostaddr6=>"0",
			specialinstructions=>"0",
			custbranchcode=>"1"

		);
		$webERPCustBranchData = $this->delete_empty_data_entries($webERPCustBranchData);

		return $webERPCustBranchData;
	}

	function generateWebERPCustomerData($patientdata){
		$webERPCustomerData = array (
			debtorno=>$patientdata[pid],
			name=>"".$patientdata[name_first]." ".$patientdata[name_last]."",
			address1=>"".$patientdata[addr_zip],
			address2=>"kikuyu",
			address3=>"Kenya",
			address4=>"00100",
			address5=>"",
			address6=>"",
			currcode=>"Ksh",
			salestype=>"CA",//$this->defaultPriceList,
			clientsince=>date('Y/m/d'),
			holdreason=>"1",
			paymentterms=>"20",
			discount=>"0",
			pymtdiscount=>"0",
			lastpaid=>"0",
			lastpaiddate=>date('Y/m/d'),
			creditlimit=>"2000",
			invaddrbranch=>"0",
			discountcode=>"0",
			ediinvoices=>"0",
			ediorders=>"0",
			edireference=>"0",
			editransport=>"email",
			ediaddress=>"0",
			ediserveruser=>"0",
			ediserverpwd=>"0",
			taxref=>"0",
			customerpoline =>"0"

		);

		$webERPCustomerData = $this->delete_empty_data_entries($webERPCustomerData);
		return $webERPCustomerData;
	}

        function generateTestData($testdata){
		$webERPTestData = array (
			test1=>$testdata[test1],
			test2=>$testdata[test2],
                        );

		$webERPTestData = $this->delete_empty_data_entries($webERPTestData);
		return $webERPTestData;
	}
}
?>