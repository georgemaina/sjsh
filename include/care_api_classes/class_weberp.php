<?php

require_once ('xmlrpc/lib/xmlrpc.inc');
require_once ('xmlrpc/lib/xmlrpcs.inc');
require_once ($root_path . 'classes/adodb/adodb.inc.php');

class weberp {

    var $weberpcalls = array('getCustomer' => "weberp.xmlrpc_GetCustomer",
        'insertCustomer' => "weberp.xmlrpc_InsertCustomer",
        'insertBranch' => "weberp.xmlrpc_InsertBranch",
        'modifyCustomer' => "weberp.xmlrpc_ModifyCustomer",
        'searchCustomer' => "weberp.xmlrpc_SearchCustomers",
        'getCurrencyList' => "weberp.xmlrpc_GetCurrencyList",
        'getCurrencyDetails' => "weberp.xmlrpc_GetCurrencyDetails",
        'getSalesTypeList' => "weberp.xmlrpc_GetSalesTypeList",
        'getSalesTypeDetails' => "weberp.xmlrpc_GetSalesTypeDetails",
        'insertSalesType' => "weberp.xmlrpc_InsertSalesType",
        'getHoldReasonList' => "weberp.xmlrpc_GetHoldReasonList",
        'getHoldReasonDetails' => "weberp.xmlrpc_GetHoldReasonDetails",
        'getPaymentTermsList' => "weberp.xmlrpc_GetPaymentTermsList",
        'getPayemtTermsDetails' => "weberp.xmlrpc_GetPaymentTermsDetails",
        'insertStockItem' => "weberp.xmlrpc_InsertStockItem",
        'modfiyStockItem' => "weberp.xmlrpc_ModifyStockItem",
        'getStockItem' => "weberp.xmlrpc_GetStockItem",
        'searchStockItems' => "weberp.xmlrpc_SearchStockItems",
        'getStockBalance' => "weberp.xmlrpc_GetStockBalance",
        'insertGLPayrollCredit' => "weberp.xmlrpc_InsertGLPayrollCredit",
        'insertGLPayroll' => "weberp.xmlrpc_InsertGLPayroll",
        'insertGLPayment' => "weberp.xmlrpc_InsertGLPayment",
        'insertPCPayment' => "weberp.xmlrpc_InsertPCPayment",
        'insertSupplierPayment' => "weberp.xmlrpc_InsertSupplierPayment",
        'insertGLReceipt' => "weberp.xmlrpc_InsertGLReceipt",
        'insertDBReceipt' => "weberp.xmlrpc_InsertDBReceipt",
        'insertIPReceipt' => "weberp.xmlrpc_InsertIPReceipt",
        'insertDebtorPayment' => "weberp.xmlrpc_InsertDebtorPayment",
        'insertIPPayment' => "weberp.xmlrpc_InsertIPPayment",
        'insertSalesInvoice' => "weberp.xmlrpc_InsertSalesInvoice",
        'insertIPSalesInvoice' => "weberp.xmlrpc_InsertIPSalesInvoice",
        'insertSalesCredit' => "weberp.xmlrpc_InsertSalesCredit",
        'modifyBranch' => "weberp.xmlrpc_ModifyBranch",
        'getStockReorderLevel' => "weberp.xmlrpc_GetStockReorderLevel",
        'setStockReorderLevel' => "weberp.xmlrpc_SetStockReorderLevel",
        'stockCatPropertyList' => "weberp.xmlrpc_StockCatPropertyList",
        'getAllocatedStock' => "weberp.xmlrpc_GetAllocatedStock",
        'getOrderedStock' => "weberp.xmlrpc_GetOrderedStock",
        'setStockPrice' => "weberp.xmlrpc_SetStockPrice",
        'getStockPrice' => "weberp.xmlrpc_GetStockPrice",
        'getCustomerBranch' => "weberp.xmlrpc_GetCustomerBranch",
        'insertSalesOrderHeader' => "weberp.xmlrpc_InsertSalesOrderHeader",
        'modifySalesOrderHeader' => "weberp.xmlrpc_ModifySalesOrderHeader",
        'insertSalesOrderLine' => "weberp.xmlrpc_InsertSalesOrderLine",
        'modifySalesOrderLine' => "weberp.xmlrpc_ModifySalesOrderLine",
        'insertGLAccount' => "weberp.xmlrpc_InsertGLAccount",
        'insertGLAccountSection' => "weberp.xmlrpc_InsertGLAccountSection",
        'insertGLAccountGroup' => "weberp.xmlrpc_InsertGLAccountGroup",
        'getLocationList' => "weberp.xmlrpc_GetLocationList",
        'getLocationDetails' => "weberp.xmlrpc_GetLocationDetails",
        'getShipperList' => "weberp.xmlrpc_GetShipperList",
        'getShipperDetails' => "weberp.xmlrpc_GetShipperDetails",
        'getSalesAreasList' => "weberp.xmlrpc_GetSalesAreasList",
        'getSalesAreaDetails' => "weberp.xmlrpc_GetSalesAreaDetails",
        'getSalesAreaDetailsFromName' => "weberp.xmlrpc_GetSalesAreaDetailsFromName",
        'insertSalesArea' => "weberp.xmlrpc_InsertSalesArea",
        'getSalesmanList' => "weberp.xmlrpc_GetSalesmanList",
        'getSalesmanDetails' => "weberp.xmlrpc_GetSalesmanDetails",
        'getSalesmanDetailsFromName' => "weberp.xmlrpc_GetSalesmanDetailsFromName",
        'insertSalesman' => "weberp.xmlrpc_InsertSalesman",
        'getTaxGroupList' => "weberp.xmlrpc_GetTaxgroupList",
        'getTaxGroupDetails' => "weberp.xmlrpc_GetTaxgroupDetails",
        'getCustomerTypeList' => "weberp.xmlrpc_GetCustomerTypeList",
        'getCustomerTypeDetails' => "weberp.xmlrpc_GetCustomerTypeDetails",
        'insertStockCategory' => "weberp.xmlrpc_InsertStockCategory",
        'modifyStockCategory' => "weberp.xmlrpc_ModifyStockCategory",
        'getStockCategory' => "weberp.xmlrpc_GetStockCategory",
        'searchStockCategories' => "weberp.xmlrpc_SearchStockCategories",
        'getGLAccountList' => "weberp.xmlrpc_GetGLAccountList",
        'getGLAccountDetails' => "weberp.xmlrpc_GetGLAccountDetails",
        'getStockTaxRate' => "weberp.xmlrpc_GetStockTaxRate",
        'insertSupplier' => "weberp.xmlrpc_InsertSupplier",
        'modifySupplier' => "weberp.xmlrpc_ModifySupplier",
        'getSupplier' => "weberp.xmlrpc_GetSupplier",
        'getSuppliersList' => "weberp.xmlrpc_GetSuppliersList",
        'searchSuppliers' => "weberp.xmlrpc_SearchSuppliers",
        'stockAdjustment' => "weberp.xmlrpc_StockAdjustment",
        'workOrderIssue' => "weberp.xmlrpc_WorkOrderIssue",
        'searchWorkOrders' => "weberp.xmlrpc_SearchWorkOrders",
        'insertPurchData' => "weberp.xmlrpc_InsertPurchData",
        'modifyPurchData' => "weberp.xmlrpc_ModifyPurchData",
        'insertWorkOrder' => "weberp.xmlrpc_InsertWorkOrder",
        'workOrderReceive' => "weberp.xmlrpc_WorkOrderReceive",
        'getBatches' => "weberp.xmlrpc_GetBatches",
        'getDefaultDateFormat' => "weberp.xmlrpc_GetDefaultDateFormat",
        'getDefaultCurrency' => "weberp.xmlrpc_GetDefaultCurrency",
        'getDefaultPriceList' => "weberp.xmlrpc_GetDefaultPriceList",
        'getDefaultLocation' => "weberp.xmlrpc_GetDefaultLocation",
        'insertToTest' => "weberp.xmlrpc_InsertToTest");
    public $client;
    private $DebugLevel;
    private $ServerURL;
    private $user;
    private $password;
    public  $response;
    public  $parms;
    public $defaultDateFormat;
    public $defaultLocation;
    public $defaultCurrency;
    public $defaultPriceList;

    function __construct() {

        $this->DebugLevel =0;
        $this->ServerURL = "http://localhost/weberp/api/api_xml-rpc.php";

        $this->user = new xmlrpcval("admin");
        $this->password = new xmlrpcval("weberp");
        $this->client = new xmlrpc_client($this->ServerURL);
        $this->client->setDebug($this->DebugLevel);

//        $dateFormat = $this->transfer ( "", $this->weberpcalls ['getDefaultDateFormat'] );
//        if ($dateFormat [0] == 0) {
//            $this->defaultDateFormat = $dateFormat [1] ['confvalue'];
//        } else {
//            $this->defaultDateFormat = 'd/m/Y';
//        }
//        $location = $this->transfer ( "", $this->weberpcalls ['getDefaultLocation'] );
//        if ($location [0] == 0) {
//            $this->defaultLocation = $location [1] ['defaultlocation'];
//        } else {
//            $this->defaultLocation = 'MAIN';
//        }
//        $currency = $this->transfer ( "", $this->weberpcalls ['getDefaultCurrency'] );
//        if ($currency [0] == 0) {
//            $this->defaultCurrency = $currency [1] ['currencydefault'];
//        } else {
//            $this->defaultCurrency = 'Ksh';
//        }
//        $pricelist = $this->transfer ( "",$this->weberpcalls ['getDefaultPriceList'] );
//        if ($pricelist [0] == 0) {
//            $this->defaultPriceList = $this->verifyPriceList ( $pricelist [1] ['confvalue'] );
//        } else {
//            $this->defaultPriceList = '0';
//        }
    }

    function params($call) {
        if (isset($this->parms [$call])) {
            $answer [0] = $this->parms [$call];
            return $answer [0];
        }
        $m2 = new xmlrpcmsg('system.methodSignature');
        $m2->addParam(array($call));
//        $msg = new xmlrpcmsg ("system.methodSignature", array ($call));
        $client = new xmlrpc_client($this->ServerURL);
        $client->setDebug($this->DebugLevel);
        $response = $client->send($m2);
        $answer = php_xmlrpc_decode($response->value());
        $this->parms [$call] = $answer;
        return $answer;
    }
    
        function transfer_payrollCredit_to_weberp($payrollCredits) {

        $payment = php_xmlrpc_encode($payrollCredits);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertGLPayrollCredit", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
        }
        
        function transfer_payroll_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertGLPayroll", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
       function transfer_glPayment_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertGLPayment", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }

    function transfer_pcPayment_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertPCPayment", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
    function transfer_IPPayment_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertIPPayment", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
    function transfer_dbPayment_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertDebtorPayment", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
    
    function transfer_payment_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertSupplierPayment", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }

    function transfer_GLReceipt_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertGLReceipt", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
    function transfer_DBReceipt_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertDBReceipt", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
     function transfer_IPReceipt_to_weberp($paymentData) {

        $payment = php_xmlrpc_encode($paymentData);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertIPReceipt", array($payment,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
    function transfer_invoice_to_weberp($invoicedata) {

        $invoice = php_xmlrpc_encode($invoicedata);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertSalesInvoice", array($invoice,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }

    function transfer_IPinvoice_to_weberp($invoicedata) {

        $invoice = php_xmlrpc_encode($invoicedata);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertIPSalesInvoice", array($invoice,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
    
    function transfer_credit_to_weberp($creditdata) {
//        $transmit = $this->transfer($creditdata, $this->weberpcalls ['insertSalesCredit']);
//        if ($transmit == 0) {
//            return true;
//        } else {
//            return false;
//        }
         $invoice = php_xmlrpc_encode($creditdata);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertSalesCredit", array($invoice,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }

    function transfer_customer_to_weberp($customerdata, $branchdata) {
//        $transmit = $this->transfer ( $customerdata, $this->weberpcalls ['insertCustomer'] );
////        $transmit = $this->transfer ( $branchdata, $this->weberpcalls ['insertBranch'] );
//        if ($transmit == 0) {
//            return true;
//        } else if ($transmit == 1001) {
//                return true;
//            } else {
//                return false;
//            }


        $customer = php_xmlrpc_encode($customerdata);
        $branch = php_xmlrpc_encode($branchdata);
        $user = new xmlrpcval("admin");
        $password = new xmlrpcval("weberp");

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertCustomer", array($customer,  $this->user ,  $this->password));
        $msg2 = new xmlrpcmsg("weberp.xmlrpc_InsertBranch", array($branch,  $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/litein/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        $response2 = $client->send($msg2);

        echo $response->faultstring();
        echo $response2->faultstring();
    }

    function transfer_Test_to_weberp($testdata) {
        $transmit = $this->transfer($testdata, $this->weberpcalls ['insertToTest']);

        if ($transmit == 0) {
            return true;
        } else if ($transmit == 1001) {
            return true;
        } else {
            return false;
        }
    }

    function transfer_workorder_to_weberp($woData) {
        $transmit = $this->transfer($woData, $this->weberpcalls ['insertWorkOrder']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function issue_to_workorder_in_weberp($woIssueData) {
        $transmit = $this->transfer($woIssueData, $this->weberpcalls ['workOrderIssue']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function receive_workorder_in_weberp($woReceiveData) {
        $transmit = $this->transfer($woReceiveData, $this->weberpcalls ['workOrderReceive']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function create_stock_item_in_webERP($stockData) {

         $stockData = php_xmlrpc_encode($stockData);

        $msg = new xmlrpcmsg("weberp.xmlrpc_InsertStockItem", array($stockData, $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }

//    function create_stock_item_in_webERP($stockData) {
//        $transmit = $this->transfer ( $stockData, $this->weberpcalls ['insertStockItem'] );
//        if ($transmit [0] == 0) {
//            return $transmit [0];
//        } else {
//            return false;
//        }
//    }

    function modify_stock_item_in_webERP($stockData) {

         $stockData = php_xmlrpc_encode($stockData);

        $msg = new xmlrpcmsg("weberp.xmlrpc_ModifyStockItem", array($stockData, $this->user ,  $this->password));
        $ServerURL =  $this->ServerURL;//"http://localhost/weberp/api/api_xml-rpc.php";
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
        echo $response->faultstring();
    }
//    
//    function modify_stock_item_in_webERP($stockData) {
//        $transmit = $this->transfer($stockData, $this->weberpcalls ['modifyStockItem']);
//        if ($transmit [0] == 0) {
//            return $transmit [0];
//        } else {
//            return false;
//        }
//    }

    function get_stock_item_from_webERP($stockID) {
        $transmit = $this->transfer($stockID, $this->weberpcalls ['getStockItem']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function search_stock_items_in_webERP($field, $criteria) {
        $searchData [0] = $field;
        $searchData [1] = $criteria;
        $transmit = $this->transfer($searchData, $this->weberpcalls ['searchStockItems']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function get_stock_balance_webERP($stockID) {
        $transmit = $this->transfer($stockID, $this->weberpcalls ['getStockBalance']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function get_stock_price_webERP($stockID) {

        $transmit = $this->transfer($stockID, $this->weberpcalls ['getStockPrice']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function get_stock_reorder_level_in_webERP($stockID) {
        $transmit = $this->transfer($stockID, $this->weberpcalls ['getStockReorderLevel']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }
    
    function get_suppliersList_webERP($SupplierList, $stockID) {
        $supData[0]=$SupplierList;
        $supData[1]=$SupplierList;
        $transmit = $this->transfer($stockID, $this->weberpcalls ['getSuppliersList']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function get_stock_items_from_category_property($property, $category) {
        $catdata [0] = $property;
        $catdata [1] = $category;
        $transmit = $this->transfer($catdata, $this->weberpcalls ['stockCatPropertyList']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function set_stock_reorder_level_in_webERP($StockID, $Location, $ReorderLevel) {
        $reorderData [0] = $StockID;
        $reorderData [1] = $Location;
        $reorderData [2] = $ReorderLevel;
        $transmit = $this->transfer($reorderData, $this->weberpcalls ['setStockReorderLevel']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function get_allocated_stock_in_webERP($stockID) {
        $transmit = $this->transfer($stockID, $this->weberpcalls ['getAllocatedStock']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function get_ordered_stock_in_webERP($stockID) {
        $transmit = $this->transfer($stockID, $this->weberpcalls ['getOrderedStock']);
        if ($transmit [0] == 0) {
            return $transmit [1];
        } else {
            return false;
        }
    }

    function stock_adjustment_in_webERP($StockID, $Location,$OrderingLoc,$Quantity, $TranDate) {

        $StockID1 = new xmlrpcval($StockID);
        $Location1= new xmlrpcval($Location); 
        $OrderingLoc1=new xmlrpcval($OrderingLoc);
        $Quantity1= new xmlrpcval($Quantity); 
        $TranDate1= new xmlrpcval($TranDate);
        
        $user=  new xmlrpcval("admin");
        $pass=  new xmlrpcval("weberp");
        
        $msg = new xmlrpcmsg("weberp.xmlrpc_StockAdjustment", array($StockID1, $Location1,$OrderingLoc1,$Quantity1,$TranDate1, $this->user , $this->password));
        $ServerURL = $this->ServerURL;
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug($this->DebugLevel);

        $response = $client->send($msg);
//         $error_code = php_xmlrpc_decode($response->value());
       // echo $response->faultstring();
        if($response->value()==0){
           //  echo 'failure ';
        }else{
            // echo 'success ';
        }
        //return $response->value();
    }

    function getAreaCode($district) {
        $areadetails = $this->transfer($district, $this->weberpcalls ['getSalesAreaDetailsFromName']);
        if ($areadetails [0] == 0) {
            return $areadetails [1] ['areacode'];
        } else if ($areadetails [0] == 1156) {
            $i = 0;
            $area ['areacode'] = substr($district, 0, 2) . $i;
            $area ['areadescription'] = $district;
            $transmit = $this->transfer($area, $this->weberpcalls ['insertSalesArea']);
            while ($transmit [0] != 0) {
                $i++;
                $area ['areacode'] = substr($district, 0, 2) . $i;
                $transmit = $this->transfer($area, $this->weberpcalls ['insertSalesArea']);
            }
            if ($transmit [0] == 0) {
                return $area ['areacode'];
            } else {
                return - 1;
            }
        } else {
            return - 1;
        }
    }

    function getSalesmanCode() {
        $salesmandetails = $this->transfer('Default', $this->weberpcalls ['getSalesmanDetailsFromName']);
        if ($salesmandetails [0] == 0) {
            return $salesmandetails [1] ['salesmancode'];
        } else if ($salesmandetails [0] == 1157) {
            $salesman ['salesmancode'] = '1';
            $salesman ['salesmanname'] = 'Default';
            $transmit = $this->transfer($salesman, $this->weberpcalls ['insertSalesman']);
            if ($transmit [0] == 0) {
                return $salesman ['salesmancode'];
            } else {
                return - 1;
            }
        } else {
            return - 1;
        }
    }

    function verifyPriceList($pricelist) {
        $pricelistdetails = $this->transfer($pricelist, $this->weberpcalls ['getSalesTypeDetails']);
        if ($pricelistdetails [0] == 0) {
            return $pricelistdetails [1] ['typeabbrev'];
        } else if ($pricelistdetails [0] == 1005) {
            $pricelistdata ['typeabbrev'] = $pricelist;
            $pricelistdata ['sales_type'] = 'Default';
            $transmit = $this->transfer($pricelistdata, $this->weberpcalls ['insertSalesType']);
            if ($transmit [0] == 0) {
                return $pricelist;
            } else {
                return - 1;
            }
        } else {
            return - 1;
        }
    }

    function delete_empty_data_entries($data) {
        foreach ($data as $key => $value) {
            if ($value != '' || is_numeric($value)) {
                $dataDetails [$key] = $value;
            }
        }
        return $dataDetails;
    }

    function transfer($data, $call) {
        $parameters = $this->params($call);
        $parameter_number = sizeOf($parameters) - 3;
        echo $parameter_number;
        if ($parameter_number == 0) {
            $msg = new xmlrpcmsg($call, array($this->user, $this->password));
        } else if ($parameter_number == 1) {
            $details = php_xmlrpc_encode($data);
            $msg = new xmlrpcmsg($call, array($details, $this->user, $this->password));
        } else if ($parameter_number == 2) {
            for ($i = 0; $i < $parameter_number; $i++) {
                $details [$i] = $data [$i];
            }
            $msg = new xmlrpcmsg($call, array($details [0], $details [1], $this->user, $this->password));
        } else if ($parameter_number == 3) {
            for ($i = 0; $i < $parameter_number; $i++) {
                $details [$i] = $data [$i];
            }
            $msg = new xmlrpcmsg($call, array($details [0], $details [1], $details [2], $this->user, $this->password));
        } else if ($parameter_number == 4) {
            for ($i = 0; $i < $parameter_number; $i++) {
                $details [$i] = $data [$i];
            }
            $msg = new xmlrpcmsg($call, array($details [0], $details [1], $details [2], $details [3], $this->user, $this->password));
        } else if ($parameter_number == 5) {
            for ($i = 0; $i < $parameter_number; $i++) {
                $details [$i] = $data [$i];
            }
            $msg = new xmlrpcmsg($call, array($details [0], $details [1], $details [2], $details [3], $details [4], $this->user, $this->password));
        } else if ($parameter_number == 6) {
            for ($i = 0; $i < $parameter_number; $i++) {
                $details [$i] = $data [$i];
            }
            $msg = new xmlrpcmsg($call, array($details [0], $details [1], $details [2], $details [3], $details [4], $details [5], $this->user, $this->password));
        }
        $this->client->setDebug($this->DebugLevel);
        //$this->client->setDebug(2);
        $response = $this->client->send($msg);
        $error_code = php_xmlrpc_decode($response->value());
        return $error_code;
    }

}

?>