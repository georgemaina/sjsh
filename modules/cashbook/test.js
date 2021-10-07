/**
 * Created by George on 2/5/2016.
 */
Ext.Msg.show({
    title:'Payments',
    msg: 'Are you sure you want to close the selected Payments? ',
    buttons: Ext.Msg.YESNOCANCEL,
    icon: Ext.Msg.QUESTION,
    fn: function(rec) {
        if (rec === "yes") {
            Ext.Ajax.request({
                url: 'cashbookFns.php?caller=closePayments',
                params: {
                    ids:cheqID)
                },
        waitMsg: 'Closing Payments ...',
            success: function(response) {
            var text = response.responseText;
            Ext.Msg.alert('Payments', 'payments Successfully Closed');
            var chequeStore = Ext.data.StoreManager.lookup('chequeStore');
            chequeStore.load({});
        }

        },
        failure:function(response){
            var resp = JSON.parseJSON(response);
            Ext.Msg.alert('Error','There was a problem Closing the Payments, Contact System Administrator');
        }
});

}
}
});
