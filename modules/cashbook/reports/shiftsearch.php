<script>
var xmlhttp;


function getShiftReports(str)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }
var url="collSummary.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
}

function stateChanged()
{
if (xmlhttp.readyState==4)
{
document.getElementById("txtHint").innerHTML=xmlhttp.responseText +' george';
}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}

function invoicePdf(name){
    //alert("Hellp");
     window.open('summary_invoice_pdf.php?pid='+name ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
}

function alertme(str){
    alert('the name is '+str);
}

</script>
