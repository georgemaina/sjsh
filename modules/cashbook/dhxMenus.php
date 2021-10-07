
<script>

       var menu;
	//function initMenu() {
		menu = new dhtmlXMenuObject("menuObj","dhx_blue");
		menu.setImagePath("../../include/dhtmlxMenu/codebase/imgs/");
		menu.setIconsPath("../../include/dhtmlxMenu/codebase/imgs/");
		menu.attachEvent("onXLS", menuXLS);
		menu.attachEvent("onXLE", menuXLE);
		menu.attachEvent("onClick", menuClick);
		menu.attachEvent("onTouch", menuTouch);
		menu.loadXML("dhxMenuitems.xml?e=" + new Date().getTime(), function(){
			//document.getElementById("ta").innerHTML += "<b>doOnLoad</b> onLoadFunction was called<br>";
		});
	//}
	function menuClick(id) {
		//document.getElementById("ta").innerHTML += "<b>onClick</b> Item "+menu.getItemText(id)+" (id:"+id+") was clicked<br>";
		switch (id){
                    case 'm11':
                        window.location="cashpoints.php";
                        //initRevPop();
                        break;
                    case 'm12':
                        initRevPop();
                        break;
                    case 'm13':
                        initlabconn();
                        break;
                    case 'm14':
                        initPharmPop();
                        break;
                     case 'm15':
                        initPharmPop();
                        break;
                     case 'm16':
                        window.location="Cash_Sale.php";
                        //initPharmPop();
                        break;
                      case 'm17':
                        initprocPop();
                        break;
                     case 'm19':
                        initPharmPop();
                        break;
                     case 'm21':
                         window.location="startShift.php?command=Start";
                        //initPharmPop();
                        break;
                     case 'm22':
                         window.location="startShift.php?command=End";
                        //initPharmPop();
                        break;
                    case 'm31':
                         window.location="Cash_Sale.php";
                        //initPharmPop();
                        break;
                    case 'm32':
                         window.location="Payments.php";
                        //cash sale
                        break;
                    case 'm33':
                         window.location="PaymentsAdj.php";
                        //payments;
                        break;
                     case 'm34':
                         window.location="Receipts.php";
                        //receipts;
                        break;
                     case 'm42':
                         window.location="reports/Shift_report.php";
                       // initPharmPop();
                        break;
                     case 'm43':
                         window.location="reports/shift_summary.php";
                        //initPharmPop();
                        break;
                     case 'm44':
                         window.location="reports/collSummary.php";
                        //initPharmPop();
                        break;
                }
                
                return true;


	}
	function menuTouch(id) {
		//document.getElementById("ta").innerHTML += "<b>onTouch</b> Menu was touched at item "+menu.getItemText(id)+" (id:"+id+")<br>";
	}
	function menuXLS() {
		//document.getElementById("ta").innerHTML += "<b>onXLS</b> XML loading has started<br>";
	}
	function menuXLE() {
		//document.getElementById("ta").innerHTML += "<b>onXLE</b> XML was loaded into menu<br>";
	}

</script>
