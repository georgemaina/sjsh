<?php
$cashpoint=$_POST[cashPoint];
$payMode=$_POST[paymode];
$chequeNo=$_POST[chequeNo];
$vouchNo=$_POST[vouchNo];
?>
<script>


    Ext.onReady(function(){
        var win;
        var button = Ext.get('show-btn');
        button.on('click', function(){

            var chequeStore=new Ext.data.JsonStore({
                url: 'getCheques.php',
                root: 'unpaidCheques',
                id: 'ID',//
                baseParams:{
                    task: "getCheques"
                },//This
                fields:['ID','cash_point','Voucher_No','Pay_mode','pdate','cheque_no','payee','Total']
            });

            chequeStore.load();
            var sm2 = new Ext.grid.CheckboxSelectionModel({
                width:20
//                listeners: {
//                    // On selection change, set enabled state of the removeButton
//                    // which was placed into the GridPanel using the ref config
//                    selectionchange: function(sm) {
//                        if (sm.getCount()) {
//                            allocPanel.removeButton.enable();
//                        } else {
//                            allocPanel.removeButton.disable();
//                        }
//                    }
//                }
            });

            var chequesColModel=function(finish,start){
                var  columns= [
                    sm2,
                    {header: "ID",width:30,dataIndex: 'ID',sortable: true, hidden:false},
                    {header: "cash_point",width: 60,dataIndex: 'cash_point',sortable: true},
                    {header: "Voucher_No",width: 80,dataIndex: 'Voucher_No',sortable: true},
                    {header: "Pay_mode",width: 80,dataIndex: 'Pay_mode',sortable: true},
                    {header: "cheque_no",width: 80,dataIndex: 'cheque_no',sortable: true},
                    {header: "Total",width: 120,dataIndex: 'Total',sortable: true},
                    {header: "payee",width: 120,dataIndex: 'payee',sortable: true},
                    {header: "pdate",width: 120,dataIndex: 'pdate',sortable: true}
                ];
                return new Ext.grid.ColumnModel({
                    columns: columns.slice(start || 0, finish),
                    defaults: {
                        sortable: true
                    }
                });
            }

            var chequeDetails = new Ext.grid.EditorGridPanel({
                id:'chequeDetails',
                store: chequeStore,
                colModel: chequesColModel(7),
                frame:true,
                width:600,
                height:250,
                clicksToEdit:2,
                sm:sm2,
                stripeRows: true,
                getSelections : function(){
                    var myselect= [].concat(this.selections.items);
                    return myselect;
//                    Ext.MessageBox.alert('selected',[].concat(this.selections.items))
                },buttons: [{
                    text: 'Print Cheque', handler: function() {
                        var cheqID = '';
                        var selectedKeys=chequeDetails.selModel.selections.keys;
                        Ext.each(selectedKeys, function(node){
                            if(cheqID.length > 0){
                                cheqID += ', ';
                            }
                            cheqID = selectedKeys;
                        });

                        printCheque(cheqID);

                    }
                },{
                    text: 'Print Voucher', handler: function() {
                        var cheqID = '';
                        var selectedKeys=chequeDetails.selModel.selections.keys;
                        Ext.each(selectedKeys, function(node){
                            if(cheqID.length > 0){
                                cheqID += ', ';
                            }
                            cheqID = selectedKeys;
                        });

                        displayVoucher(cheqID);

                    }
                },{
                    text: 'Close', handler: function() {
                        win.hide();


                    }
                }]
            });

            function refreshGrid(){
                chequeStore.reload();
            }

//            var admForm = new Ext.FormPanel({
//                labelWidth: 75, // label settings here cascade unless overridden
//                url:'getCheques.php',
//                frame:true,
//                title: 'Print Cheque/Voucher',
//                bodyStyle:'padding:5px 5px 0',
//                width: 600,
//                record: null,
//                layout: 'column',
//                items:[chequeDetails]
//
//            });
            // create the window on the first click and reuse on subsequent clicks
            if(!win){
                win = new Ext.Window({
                    applyTo:'hello-win',
                    layout:'fit',
                    title: 'Payment Voucher and Cheques to Print',
                    closeAction:'hide',
                    plain: true,
                    items: [chequeDetails]
                });
            }
            win.show(this);

            //        });
        })
    })


        function displayVoucher(CashPoint,payMode,voucherNo) {
//      alert(CashPoint+''+payMode+' '+voucherNo);
                window.open('reports/payment_voucher.php?cashpoint='+CashPoint+'&voucherNo='+voucherNo+'&payMode='+payMode
            ,"receipt","menubar=yes,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");
        }

        function printCheque(CashPoint,payMode,voucherNo ) {   
            window.open('reports/payment_cheque.php?cashpoint='+CashPoint+'&voucherNo='+voucherNo+'&payMode='+payMode
            ,"receipt","menubar=no,toolbar=no,width=300,height=550,location=yes,resizable=no,scrollbars=no,status=yes");
        }


</script>
<?php
// displayPayForm();
?>