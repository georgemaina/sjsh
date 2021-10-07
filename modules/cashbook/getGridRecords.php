<?php
    //set content type and xml tag
    header("Content-type:text/xml");
    print("<?xml version=\"1.0\"?>");

    //define variables from incoming values
    if(isset($_GET["posStart"]))
        $posStart = $_GET['posStart'];
    else
        $posStart = 0;
    if(isset($_GET["count"]))
        $count = $_GET['count'];
    else
        $count = 100;

    //connect to database
    $link = mysql_pconnect("localhost", "admin", "chak");
    $db = mysql_select_db ("care2x");

    //create query to products table
    $sql = "SELECT * FROM care_ke_cashpoints";
    if(isset($_GET["name_mask"]))
        $sql.=" Where name like '".$_GET["name_mask"]."%'";
    //if this is the first query - get total number of records in the query result
    if($posStart==0){
        $sqlCount = "Select count(*) as cnt from ($sql) as tbl";
        $resCount = mysql_query ($sqlCount);
        $rowCount=mysql_fetch_array($resCount);
        $totalCount = $rowCount["cnt"];
    }

    //add limits to query to get only rows necessary for the output
    $sql.= " LIMIT ".$posStart.",".$count;

    //query database to retrieve necessary block of data
    $res = mysql_query ($sql);

    //output data in XML format
    print("<rows total_count='".$totalCount."' pos='".$posStart."'>");
    while($row=mysql_fetch_array($res)){
        print("<row id='".$row['id']."'>");
            print("<cell>");
                print($row['code']);  //value for product name
            print("</cell>");
            print("<cell>");
                print($row['name']);  //value for internal code
            print("</cell>");
            print("<cell>");
                print($row['prefix']);  //value for internal code
            print("</cell>");
            print("<cell>");
                print($row['next_receipt_no']);    //value for price
            print("</cell>");
            print("<cell>");
                print($row['next_voucher_no']);  //value for internal code
            print("</cell>");
            print("<cell>");
                print($row['next_shift']);  //value for internal code
            print("</cell>");
         print("</row>");
    }
    print("</rows>");



?>
