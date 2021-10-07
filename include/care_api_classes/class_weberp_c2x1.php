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


    function transfer_bill_to_webERP_asSalesInvoice($billelems)
    {
 		$billdata[ovamount]= $billelems[price]*$billelems[amount];
 	        //$billdata[user_Id]=$billelems[user_Id];
 		$billdata[order_]= $billelems[bill_number];
 		$billdata[debtorno] = $billelems[pid];
 		$billdata[partcode] = $billelems[partcode];
                $billdata[invtext] = $billelems[article];
                $billdata[trandate]= date('Y/m/d');
                $billdata[branchcode] = $billelems[pid];
 		$billdatafinal=$this->generateWebERPCustSalesInvoiceData($billdata);
                $this->transfer_invoice_to_weberp($billdata);
    }

    function transfer_bill_to_webERP_asCreditInvoice($billelems)
    {
 		$billdata[ovamount]= -intval($saleInvoiceData[mytotal]);
 	        //$billdata[user_Id]=$billelems[user_Id];
 		$billdata[order_]= $billelems[ref_no];
 		$billdata[debtorno] = $billelems[patient];
                $billdata[invtext] = 'bill payment for '.$billelems[name];
                $billdata[trandate]= date('Y/m/d');// date($this->defaultDateFormat);
                $billdata[branchcode] = $billelems[patient];
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

        $webERPCustSalesInvoiceData = array (
                debtorno=>$saleInvoiceData[pid],
                branchcode=>$saleInvoiceData[pid],
                transno=>"0",
                trandate=>date('Y/m/d'),
                settled=>"0",
                reference=>"0",
                type=>"CA",
                order_=>$saleInvoiceData[article],
                rate=>"1",
                ovamount=>$saleInvoiceData[price],
                ovgst=>"0",
                ovfreight=>"0",
                ovdiscount=>"0",
                diffonexch=>"0",
                alloc=>"0",
                invtext=>$saleInvoiceData[article],
                shipvia=>"1",
                edisent=>"0",
                consignment=>$saleInvoiceData[consignment],
                partcode=>$saleInvoiceData[partcode],
                salesarea=>"R01"
	);


	$webERPCustSalesInvoiceData = $this->delete_empty_data_entries($webERPCustSalesInvoiceData);

	return $webERPCustSalesInvoiceData;
	}

        function generateWebERPCustCreditInvoiceData($saleInvoiceData)
	{

        $webERPCustSalesInvoiceData = array (
                debtorno=>$saleInvoiceData[patient],
                branchcode=>$saleInvoiceData[patient],
                transno=>"0",
                trandate=>date('Y/m/d'),//date($this->defaultDateFormat),
                settled=>"0",
                reference=>$saleInvoiceData[ref_no],
                type=>"10",
                tpe=>"CA",
                order_=>$saleInvoiceData[ref_no],
                rate=>"1",
                ovamount=>-intval($saleInvoiceData[mytotal]),
                ovgst=>"0",
                ovfreight=>"0",
                ovdiscount=>"0",
                diffonexch=>"0",
                alloc=>"0",
                invtext=>$saleInvoiceData[name],
                shipvia=>"1",
                edisent=>"0",
                consignment=>"0",
                partcode=>$saleInvoiceData[rev_code],
                salesarea=>"R01"
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
//		$webERPCustBranchData = $this->delete_empty_data_entries($webERPCustBranchData);
//
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

//		$webERPCustomerData = $this->delete_empty_data_entries($webERPCustomerData);
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