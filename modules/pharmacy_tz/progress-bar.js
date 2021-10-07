/*!
 * Ext JS Library 3.1.1
 * Copyright(c) 2006-2010 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){
    var btn1 = Ext.get('btn1');
    btn1.on('click', function(){
        Ext.Ajax.request({
            url : 'updatePrices.php' ,
            params : {
                action : 'getDate'
            },
            method: 'GET',
            success: function ( result ) {
            
            
                //==== Progress bar 1 ====
                var pbar1 = new Ext.ProgressBar({
                    text:'Initializing...'
                });
   

                Ext.fly('p1text').update('Working');
                if (!pbar1.rendered){
                    pbar1.render('p1');
                }else{
                    pbar1.text = 'Initializing...';
                    pbar1.show();
                }
                Runner.run(pbar1, Ext.get('btn1'), parseInt(result.responseText), function(){
                    pbar1.reset(true);
                    Ext.fly('p1text').update('Done.').show();
                });
    

            
                Ext.MessageBox.alert('Success', 'Total records updated: '+ result.responseText);
            },
            failure: function ( result) {
                Ext.MessageBox.alert('Failed', result.responseText);
            }
        });

    });
 
});

//Please do not use the following code as a best practice! :)
var Runner = function(){
    var f = function(v, pbar, btn, count, cb){
        return function(){
            if(v > count){
                btn.dom.disabled = false;
                cb();
            }else{
                if(pbar.id=='pbar4'){
                    //give this one a different count style for fun
                    var i = v/count;
                    pbar.updateProgress(i, Math.round(100*i)+'% completed...');
                }else{
                    pbar.updateProgress(v/count, 'Loading item ' + v + ' of '+count+'...');
                }
            }
        };
    };
    return {
        run : function(pbar, btn, count, cb){
            btn.dom.disabled = true;
            var ms = 1500/count;
            for(var i = 1; i < (count+2); i++){
                setTimeout(f(i, pbar, btn, count, cb), i*ms);
            }
        }
    }
}();