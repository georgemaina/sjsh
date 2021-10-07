
var xmlHttp = createXmlHttpRequestObject();
// retrieves the XMLHttpRequest object
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

// make asynchronous HTTP request using the XMLHttpRequest object
function process()
{
    // proceed only if the xmlHttp object isn't busy
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
    {
        // retrieve the name typed by the user on the form
        name = document.getElementById("insurance").value;
        // execute the quickstart.php page from the server
        xmlHttp.open("GET", "getInvoice.php?insured=" + name , true);
        // define the method to handle server responses
        xmlHttp.onreadystatechange = handleServerResponse;
        // make the server request
        xmlHttp.send(null);
    }
    else
        // if the connection is busy, try again after one second
        setTimeout('process()', 1000);
}
// executed automatically when a message is received from the server
function handleServerResponse()
{
    // move forward only if the transaction has completed
    if (xmlHttp.readyState == 4)
    {
     
            var str=xmlhttp.responseText;

              document.getElementById("divMessage").innerHTML =str
              alert(str);
          
    }
}

