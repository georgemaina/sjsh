<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR),

### The following arrays are the "role" levels each containing an access point or groups of access points

$all='_a_0_all';
$sysadmin='System_Admin';

$allow_area=array(

'admit'=>array('_a_1_admissionwrite','_a_1_medocswrite'),

'report' =>array('_a_1_reportingread'),

'bill'=>array('_a_1_billallwrite', '_a_2_billallread','_a_2_billpharmawrite','_a_3_billpharmaread','_a_2_billserviceswrite','_a_3_billservicesread','_a_2_billlabwrite','_a_3_billlabread'),

'cafe'=>array('_a_1_newsallwrite', '_a_1_newscafewrite'),

'medocs'=>array('_a_1_medocswrite'),

'phonedir'=>array('$all, $sysadmin'),

'doctors'=>array('_a_1_opdoctorallwrite', '_a_1_doctorsdutyplanwrite'),

'wards'=>array('_a_1_doctorsdutyplanwrite', '_a_1_opdoctorallwrite', '_a_1_nursingstationallwrite',  $all, $sysadmin),

'op_room'=>array('_a_1_opdoctorallwrite', '_a_1_opnursedutyplanwrite', '_a_2_opnurseallwrite'),

'tech'=>array('_a_1_techreception'),

'lab_r'=>array('_a_1_labresultswrite', '_a_2_labresultsread'),

'lab_w'=>array('_a_1_labresultswrite'),

'lab_results'=>array('_a_2_labresultsparams'),

'lab_parameters'=>array('_a_2_labparametersadmin'),

'lab_test_groups'=>array('_a_2_labtestgroupsadmin'),

'radio'=>array('_a_1_radiowrite'),

'pharma_db'=>array('_a_1_pharmadbadmin'),

'pharma_receive'=>array('_a_1_pharmadbadmin', '_a_2_pharmareception'),

'prescription'=>array('_a_1_pharmaPrescription'),

'stockmaster'=>array('_a_1_stockmaster'),

'orders'=>array( '_a_1_pharmaorder'),

'service'=>array( '_a_1_pharmservice'),

'transfere'=>array( '_a_1_pharmatransfere'),

'issue'=>array( '_a_1_pharmissue'),

'cancel'=>array( '_a_1_pharmacancel'),

'levels'=>array( '_a_1_pharmastocklevel'),

'cashbook' =>array('_a_1_cashbook'),

'Cashpoints' =>array('_a_1_Cashpoints'),

'StartShift' =>array('_a_1_StartShift'),

'CashSale' =>array('_a_1_CashSale'),

'Cash_Sale_Adjustment' =>array('_a_1_Cash_Sale_Adjustment'),

'Revenue_Codes' =>array('_a_1_Revenue_Codes'),

'Procedure_Codes' =>array('_a_1_Procedure_Codes'),

'Pharmacy_Codes' =>array('_a_1_Pharmacy_Codes'),

'IOU' =>array('_a_1_iou'),

'Payments' =>array('_a_1_Payments'),

'Payments_Adjustment' =>array('_a_1_Payments_Adjustment'),

'Reprint' =>array('_a_1_Reprint'),

'Receipts' =>array('_a_1_Receipts'),

'Receipts_Adjustment' =>array('_a_1_Receipts_Adjustment'),
    
'Reports' =>array('_a_1_Reports'),

'payment_modes'=>array('_a_1_payment_modes'),

 'appointments' =>array('_a_1_appointments'),
 'clinic' =>array('_a_1_clinics') ,
 'transactions'=>array('_a_1_transactions'),
 'credit'=>array('_a_2_credit'),
 'debit'=>array('_a_2_debit'),
 'creditslip'=>array('_a_2_creditslip'),
    'creditslipview'=>array('_a_2_creditslipview'),

 'finaliseInvoice'=>array('_a_2_FinaliseInvoice'),
 'reports'=>array('_a_2_reports'),

    'Occupancy'=>array('_a_1_occupancy'),
    'Ward_Management'=>array('_a_1_wardManager'),
    'billing'=>array('_a_1_billing'),
    'Accounting'=>array('_a_1_Accounting'),
    'Debit2'=>array('_a_2_debit2'),
    'Credit2'=>array('_a_2_credit2'),
    'Bed_Charge'=>array('_a_2_bedCharge') ,
    'Finalise_Invoice'=>array('_a_2_FinaliseInvoice'),
    'Nhif_Credit'=>array('_a_2_nhifCredit'),
    'IP_Reports'=>array('_a_2_ipreports'),

    'Insurance'=>array('_a_1_insurance'),
    'debtorregister'=> array('_a_2_register'),
    'debtorslist'=> array('_a_2_debtorslist'),
    'debtormembers'=>array('_a_2_members'),
    'debtortransactions'=>array('_a_2_transactions'),
    'debtorinvoices'=>array('_a_2_invoices'),
    'debtorreceipts'=>array('_a_2_receipts'),
    'debtorallocations'=>array('a_2_allocations'),

    'generalStore'=>array('_a_1_gendbadmin'),
    'genOrder'=> array('_a_1_genorder'),
    'genService'=> array('_a_1_genservice'),
    'genReturn'=>array('_a_1_genreturn'),
    'genLevels'=>array('_a_1_genstocklevel'),
    'genReports'=>array('_a_1_genreports'),


'depot_db'=>array('_a_1_meddepotdbadmin'),

'depot_receive'=>array('_a_1_meddepotdbadmin', '_a_2_meddepotreception'),

'depot'=>array('_a_1_meddepotdbadmin', '_a_2_meddepotreception', '_a_3_meddepotorder'),

'edp'=>array('no_allow_type_all',),

'news'=>array('_a_1_newsallwrite'),

'hypertension'=>array('_a_1_hypertension'),
    
'dashboard'=>array('_a_1_dashboard'),
    
//'doctors'=>array('_a_1_doctors'),
//
//'triage'=>array('_a_1_triage'),

'cafenews'=>array('_a_1_newsallwrite', '_a_2_newscafewrite'),

'op_docs'=>array('_a_1_opdoctorallwrite'),

'duty_op'=>array('_a_1_opnursedutyplanwrite'),

'fotolab'=>array('_a_1_photowrite'),

'test_diagnose'=>array('_a_1_diagnosticsresultwrite', '_a_1_labresultswrite'),

'test_receive'=>array('_a_1_diagnosticsresultwrite', '_a_1_labresultswrite', '_a_2_diagnosticsreceptionwrite'),

'test_order'=>array('_a_1_diagnosticsresultwrite', '_a_1_labresultswrite',
    '_a_2_diagnosticsreceptionwrite',   '_a_3_diagnosticsrequest')



)

?>
