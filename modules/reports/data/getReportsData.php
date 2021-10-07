
    $sql = 'SELECT `Parent`,`OpCode`,`Description`,`ReportDate`,`ReportTime`,`New`,`Return`,(`new`+`return`) AS Total
                FROM  `care_ke_opworkload`';
    $result = $db->Execute($sql);
    echo '[';
    while ($row = $result->FetchRow()) {      
        echo '{"parent":"' . $row['Parent'] . '","opCode":"' . $row['OpCode'] . '",
                "Description":"' . $row['Description'] . '","New":"' . number_format($row['New'],0)
                . '","Ret":"' . number_format($row['return'],0) . '","Total":"' .number_format($row['Total'],0) . '"},';
    }
    echo ']';
}




function getOPvisits_old($date1,$date2) {
    global $db;
    $debug=false;
