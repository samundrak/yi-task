<?php
require("class/CSV.class.php");
$csv1 = new CSV("_files/contracts.csv");
$csv2 = new CSV("_files/awards.csv");

/*
 * CSV::joinCSV($csv1,$csv2) returns all the old ,new table data
 * $allData = CSV::joinCSV($csv1,$csv2);
 *        $allData["tableOneHeader"] =  header of csv1
          $allData["tableOneData"] = Data of csv1
          $allData["tableTwoHeader"] = header of csv2
          $allData["tableTwoData"]  = data of csv2
          $allData["newTableHeader"] = new table header
          $allData["newTableData"]  = new table data
 **/
$newTableData = CSV::join($csv1,$csv2,"contractname");
CSV::Write($newTableData["newTableHeader"],$newTableData["newTableData"],"final.csv");
$total = CSV::getAmount($newTableData["newTableData"],"Current","Amount");
echo "In my case i have got this amount which are currently running: ".$total."NPR";
?>