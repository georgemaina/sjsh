<html>
    <head>
        <title>CLinkedSelect sample</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>
    <body>
        <?php include ("CLinkedSelect.php"); ?>
        <?php
        $value = array ( 		"value" => "1",
            "text" => "Language",
            "selected" => false,
            "items" => array ("2" => "ASP",
            "4" => "C++",
            "3" => "Javascript",
            "1" => "PHP"
        ));
        $values[] = $value;

        $value = array (	"value" => "2",
            "text" => "OS",
            "selected" => false,
            "items" => array ("8" => "FreeBSD",
            "5" => "Linux",
            "7" => "Mac OS X",
            "9" => "Unix",
            "6" => "Windows"
        ));
        $values[] = $value;

        $value = array ( 	"value" => "3",
            "text" => "DBMS",
            "selected" => true,
            "items" => array ("10" => "MySQL",
            "14" => "Oracle",
            "13" => "MS SQL Server",
            "12" => "Interbase",
            "11" => "DB2"
        ));
        $values[] = $value;

        $linkedselect = new CLinkedSelect ();
        $linkedselect->primaryFormName = "firstForm";
        $linkedselect->secondaryFormName = "secondForm";
        $linkedselect->primaryFieldName = "mainItems";
        $linkedselect->secondaryFieldName = "subItems";
        $linkedselect->fieldValues = $values;
        ?>
        <table border="0" cellspacing="5" cellpadding="0" width="400">
            <tr>
                <td colspan="2" align="center"> <strong>Linked combo boxes </strong></td>
            </tr>
            <tr>
                <td valign="top"> <form name="firstForm" method="post">
                        <strong>Main category <br>
                        </strong> <?php print ($linkedselect->get_function_js ());?>
                        <!-- somthing else -->
                        <?php print ($linkedselect->get_primary_field ()); ?>
                    </form></td>
                <td valign="top"><form name="secondForm" method="post">
                        <strong>&nbsp;&nbsp;Sub items <br>
                            &nbsp;&nbsp;</strong> <?php print ($linkedselect->get_secondary_field ()); ?>
                        <!-- somthing else -->
                        <?php print ($linkedselect->get_reset_js ()); ?>
                    </form></td>
            </tr>
        </table>
        <?php
        $linkedselect = new CLinkedSelect ();
        $linkedselect->primaryFormName = "firstForm2";
        $linkedselect->secondaryFormName = "secondForm2";
        $linkedselect->primaryFieldName = "mainItems";
        $linkedselect->secondaryFieldName = "subItems";
        $linkedselect->fieldValues = $values;
        ?>
        <table border="0" cellspacing="10" cellpadding="0" width="400">
            <tr>
                <td colspan="2" align="center"> <strong>Linked list boxes </strong></td>
            </tr>
            <tr>
                <td valign="top"> <form name="firstForm2" method="post">
                        <strong>Main category </strong> <?php print ($linkedselect->get_function_js ());?> <br /> <!-- somthing else --> <?php print ($linkedselect->get_primary_field ("size='10'")); ?> </form></td>
                <td valign="top"><form name="secondForm2" method="post">
                        <strong>&nbsp;&nbsp;Sub items&nbsp;&nbsp;</strong> <br /> <?php print ($linkedselect->get_secondary_field ("size='10' multiple")); ?>
                        <!-- somthing else -->
                    <?php print ($linkedselect->get_reset_js ()); ?> </form></td>
            </tr>
        </table>

        <?php
        $linkedselect = new CLinkedSelect ();
        $linkedselect->primaryFormName = "firstForm3";
        $linkedselect->secondaryFormName = "secondForm3";
        $linkedselect->primaryFieldName = "mainItems";
        $linkedselect->secondaryFieldName = "subItems";
        $linkedselect->fieldValues = $values;
        ?>
        <table border="0" cellspacing="10" cellpadding="0" width="400">
            <tr>
                <td colspan="2" align="center"> <strong>Mixed version </strong></td>
            </tr>
            <tr>
                <td valign="top"> <form name="firstForm3" method="post">
                        <strong>Main category<br>
                        </strong> <?php print ($linkedselect->get_function_js ());?>
                        <!-- somthing else -->
                        <?php print ($linkedselect->get_primary_field ()); ?>
                    </form></td>
                <td valign="top"><form name="secondForm3" method="post">
                        <strong>&nbsp;&nbsp;Sub items&nbsp;&nbsp;</strong><br /> <?php print ($linkedselect->get_secondary_field ("size='10' multiple")); ?>
                        <!-- somthing else -->
                    <?php print ($linkedselect->get_reset_js ()); ?> </form></td>
            </tr>
        </table>
    </body>
</html>
<?php 
$values = array ();

$value = array ( 		"value" => "1", 
    "text" => "Language",
    "selected" => false,
    "items" => array ("3" => "ASP",
    "4" => "C++"));
$values[] = $value;

$value = array (	"value" => "2", 
    "text" => "OS",
    "selected" => false,
    "items" => array ("5" => "FreeBSD",
    "6" => "Linux"));
$values[] = $value;

// -------------------------------------------------------------------- //
$value = array ( 		"value" => "3", 
    "text" => "ASP",
    "selected" => false,
    "items" => array ("7" => "3.0",
    "8" => "dotNet"));
$values2[] = $value;

$value = array ( 		"value" => "C++", 
    "text" => "Language",
    "selected" => false,
    "items" => array ("9" => "GNU/gcc",
    "10" => "Borland C++",
    "3" => "Ms Visual C++"));
$values2[] = $value;

$value = array (	"value" => "5", 
    "text" => "FreeBSD",
    "selected" => false,
    "items" => array ("8" => "Sparc",
    "5" => "Morotola",
    "7" => "i368",
    "9" => "Alpha"));
$values2[] = $value;

$value = array ( 	"value" => "6", 
    "text" => "Linux",
    "selected" => true,
    "items" => array ("10" => "Debian",
    "14" => "Suse",
    "13" => "Fedora",
    "12" => "Slackware"));
$values2[] = $value;


$linkedselect = new CLinkedSelect ();
$linkedselect->primaryFormName = "firstForm4";
$linkedselect->secondaryFormName = "secondForm4";
$linkedselect->primaryFieldName = "mainItems";
$linkedselect->secondaryFieldName = "subItems";
$linkedselect->fieldValues = $values;

$linkedselect2 = new CLinkedSelect ();
$linkedselect2->primaryFormName = "secondForm4";
$linkedselect2->secondaryFormName = "thirdForm4";
$linkedselect2->primaryFieldName = "subItems";
$linkedselect2->secondaryFieldName = "subsubItems";
$linkedselect2->fieldValues = $values2;

?>
<table border="0" cellspacing="5" cellpadding="0" width="600">
    <tr>
        <td colspan="3" align="center"> <strong>Linked combo boxes </strong></td>
    </tr>
    <tr>
        <td valign="top"> <form name="firstForm4" method="post">
                <strong>Main category <br>
                </strong> <?php print ($linkedselect->get_function_js ());?>
                <!-- somthing else -->
                <?php print ($linkedselect->get_primary_field ()); ?>
            </form></td>
        <td valign="top"><form name="secondForm4" method="post">
                <strong>&nbsp;&nbsp;Sub items <br>
                    &nbsp;&nbsp;</strong>
                <?php print ($linkedselect2->get_function_js ());?>
                <?php print ($linkedselect2->get_primary_field ()); ?>
                <!-- somthing else -->
                <?php print ($linkedselect->get_reset_js ()); ?>
            </form></td>
        <td valign="top"><form name="thirdForm4" method="post">
                <strong>&nbsp;&nbsp;Subsub items <br>
                    &nbsp;&nbsp;</strong> <?php print ($linkedselect2->get_secondary_field ()); ?>
                <!-- somthing else -->
                <?php print ($linkedselect2->get_reset_js ()); ?>
            </form></td>
    </tr>
</table>