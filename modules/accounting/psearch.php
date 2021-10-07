
<script type="text/javascript" src="selectInvoice.js"></script>
        
<table>
    <tr>
        <td>
            <input type="submit" id="search" value="Search Pid" onclick="initPsearch()"/>
            <input type="text" name="pid" size="10" id="pid" onblur="getPnames(this.value)" />
            <input type="text" name="pname"  size="40" id="pname" onclick="getPatient(document.getElementById('pid').value)" />
            <input type="hidden"  disabled id="invCaller" value="<?php echo $_REQUEST['caller'] ?>">
            <input type="text"  disabled id="invType" name="invType" value="<?php echo $_REQUEST['final'] ?>">
            Include the Receipts:<input type="checkbox" name="receipt" ID="receipt" value="" />
            <input type="submit" id="getPatient" value="Preview" 
             onclick="getInvoice(document.getElementById('pid').value,document.getElementById('invCaller').value,
                 document.getElementById('billNumbers').selectedIndex)"/>
            <button onclick="invoicePdf(document.getElementById('pid').value,
                document.getElementById('receipt').value, document.bills.billNumbers.value)" id="printInv">Print Invoices</button>
            <button onclick="invoicePdf(document.getElementById('pid').value,
                document.getElementById('receipt').value, document.bills.billNumbers.value)" id="printInv">Print Mini Invoice</button>

        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td id="datefield">Start Date:</td>
                    <td id="datefield2">End Date:</td>
                    <td> &nbsp;</td>
                    <td>Bill Number:<div id="billNumbers"></div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>



<script>

</script>
