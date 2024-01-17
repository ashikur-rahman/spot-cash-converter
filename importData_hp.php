<?php 
 
// Load the database configuration file 
include_once 'dbConfig.php'; 
 
// Include PhpSpreadsheet library autoloader 
require_once 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Reader\Xls; 
 
if(isset($_POST['importSubmit'])){ 
     
    // Allowed mime types 
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
     
    // Validate whether selected file is a Excel file 
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)){ 
         
        // If the file is uploaded 
        if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
            $reader = new Xls(); 
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']); 
            $worksheet = $spreadsheet->getActiveSheet();  
            $worksheet_arr = $worksheet->toArray(); 
 
            // Remove header row 
            unset($worksheet_arr[0]); 
       
            // echo '<pre>';
            // print_r($worksheet_arr );
            // echo '</pre>';
            // exit();
            foreach($worksheet_arr as $row){ 

                $PartnerName= $row[0];
                $BranchName= $row[1];
                $BranchUser= $row[2];
                $SupportUser= $row[3];
                $ReferenceNumber= $row[4];
                $SenderName= $row[5];
                $SenderNumber= $row[6];
                $FromCountry= $row[7];
                $ReceiverName= $row[8];
                $ReceiverNumber= $row[9];
                $TransactionDate= $row[10];
                $TransactionStatus= $row[11];
                $StatusDate= $row[12];
                $Amount= $row[13];
                $Reward= $row[14];
                $Rebate= $row[15];
                $TotalAmount= $row[16];


 
                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT ReferenceNumber FROM hp_main WHERE ReferenceNumber = '".$ReferenceNumber."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE hp_main SET
                    PartnerName = '".$PartnerName."', 
                    BranchName = '".$BranchName."', 
                    BranchUser = '".$BranchUser."', 
                    SupportUser = '".$SupportUser."', 
                    ReferenceNumber = '".$ReferenceNumber."', 
                    SenderName = '".$SenderName."', 
                    SenderNumber = '".$SenderNumber."', 
                    FromCountry = '".$FromCountry."', 
                    ReceiverName = '".$ReceiverName."', 
                    ReceiverNumber = '".$ReceiverNumber."', 
                    TransactionDate = '".$TransactionDate."', 
                    TransactionStatus = '".$TransactionStatus."', 
                    StatusDate = '".$StatusDate."', 
                    Amount = '".$Amount."', 
                    Reward = '".$Reward."',
                    Rebate = '".$Rebate."', 
                    TotalAmount = '".$TotalAmount."'
                    WHERE 
                    ReferenceNumber = '".$ReferenceNumber."'"); 
                }else{ 
                   // exit('ss');
                    // Insert member data in the database 
                    $query = "INSERT INTO hp_main (PartnerName,BranchName,BranchUser,SupportUser,ReferenceNumber,SenderName,
                    SenderNumber, FromCountry, ReceiverName,
                    ReceiverNumber,TransactionDate,TransactionStatus,StatusDate,Amount,
                    Reward,Rebate,TotalAmount) 
                    VALUES (
                        '".$PartnerName."', 
                        '".$BranchName."', 
                        '".$BranchUser."', 
                        '".$SupportUser."', 
                        '".$ReferenceNumber."', 
                        '".$SenderName."', 
                        '".$SenderNumber."', 
                        '".$FromCountry."', 
                        '".$ReceiverName."', 
                        '".$ReceiverNumber."', 
                        '".$TransactionDate."', 
                        '".$TransactionStatus."', 
                        '".$StatusDate."', 
                        '".$Amount."', 
                        '".$Reward."', 
                        '".$Rebate."', 
                        '".$TotalAmount."'
                        )";
                       // var_dump($query); exit('a');
                    $db->query($query); 
                       
                } 
            } 
             
            $qstring = '?status=succ'; 
        }else{ 
            $qstring = '?status=err'; 
        } 
    }else{ 
        $qstring = '?status=invalid_file'; 
    } 
} 
 
// Redirect to the listing page 
header("Location: index_hp.php".$qstring); 
 
?>