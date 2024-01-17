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

                $SrNo= $row[0];
                $Remitted_On= $row[1];
                $Paid_Date= $row[2];
                $Paid_Time= $row[3];
                $Tran_No= $row[4];
                $Remit_No= $row[5];
                $Sender= $row[6];
                $Receiver= $row[7];
                $Collecting_Agent= $row[8];
                $Payout_Mode= $row[9];
                $Pay_Amount= $row[10];
                $Status= $row[11];
                $USD_Comm= $row[12];
                $Paid_Branch_Code= $row[13];
                $Paid_By= $row[14];
                $Branch_Name= $row[15];
                $routing_no= $row[16];
                $USD_Amount= $row[15];
                $Settlement_Rate= $row[16];


 
                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT Tran_No FROM mrt_main WHERE Tran_No = '".$Tran_No."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE mrt_main SET
                    SrNo = '".$SrNo."', 
                    Remitted_On = '".$Remitted_On."', 
                    Paid_Date = '".$Paid_Date."', 
                    Paid_Time = '".$Paid_Time."', 
                    Tran_No = '".$Tran_No."', 
                    Remit_No = '".$Remit_No."', 
                    Sender = '".$Sender."', 
                    Receiver = '".$Receiver."', 
                    Collecting_Agent = '".$Collecting_Agent."', 
                    Payout_Mode = '".$Payout_Mode."', 
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
                    $query = "INSERT INTO mrt_main (SrNo,Remitted_On, Paid_Date,Paid_Time,Tran_No,Remit_No, Sender,Receiver, Collecting_Agent,
                    Payout_Mode,Pay_Amount,Status,USD_Comm,Paid_Branch_Code, Paid_By,Branch_Name,routing_no,
                    USD_Amount,Settlement_Rate) 
                    VALUES (
                        '".$SrNo."', 
                        '".$Remitted_On."', 
                        '".$Paid_Date."', 
                        '".$Paid_Time."', 
                        '".$Tran_No."', 
                        '".$Remit_No."', 
                        '".$Sender."', 
                        '".$Receiver."', 
                        '".$Collecting_Agent."', 
                        '".$Payout_Mode."', 
                        '".$Pay_Amount."', 
                        '".$Status."', 
                        '".$USD_Comm."', 
                        '".$Paid_Branch_Code."', 
                        '".$Paid_By."', 
                        '".$Branch_Name."', 
                        '".$routing_no."',
                        '".$USD_Amount."', 
                        '".$Settlement_Rate."'
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