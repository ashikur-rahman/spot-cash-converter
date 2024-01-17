<?php 
 
// Load the database configuration file 
include_once 'dbConfig.php'; 
 
// Include PhpSpreadsheet library autoloader 
require_once 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
 
if(isset($_POST['importSubmit'])){ 
     
    // Allowed mime types 
    $excelMimes = array('text/xls', 'text/xlsx', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
     
    // Validate whether selected file is a Excel file 
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)){ 
         
        // If the file is uploaded 
        if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
            $reader = new Xlsx(); 
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']); 
            $worksheet = $spreadsheet->getActiveSheet();  
            $worksheet_arr = $worksheet->toArray(); 
 
            // Remove header row 
            unset($worksheet_arr[0]); 
            unset($worksheet_arr[1]); 
            unset($worksheet_arr[2]); 
            unset($worksheet_arr[3]);   
            unset($worksheet_arr[4]);   
            unset($worksheet_arr[5]); 
            unset($worksheet_arr[6]); 
            // echo '<pre>';
            // print_r($worksheet_arr );
            // echo '</pre>';
            // exit();
            foreach($worksheet_arr as $row){ 
                $Invoice = $row[0]; 	
                $TF_Pin	= $row[1]; 
                $Reference	= $row[2]; 
                $DateInvoice	= $row[3]; 
                $DatePaid	= $row[4]; 
                $Status	= $row[5]; 
                $Sender_Name	= $row[6]; 
                $Receiver_Name	= $row[7]; 
                $PaymentMode	= $row[8]; 
                $AccountNo	= $row[9]; 
                $Branch_Name	= $row[10]; 
                $BranchId	= $row[11]; 
                $PayerBankBranch= $row[12]; 	
                $PayingBankBranchName	= $row[13]; 
                $BankName	= $row[14]; 
                $State	= $row[15]; 
                $City	= $row[16]; 
                $Cashier	= $row[17]; 
                $Total_Pay_Dollar= $row[18]; 	
                $Total_Pay_Local= $row[19]; 	
                $Receiver_Tel_No	= $row[20]; 
                $Send_Country	= $row[21]; 
                $Sender_Name_In_English= $row[22]; 

 
                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT TF_Pin FROM tft_main WHERE TF_Pin = '".$TF_Pin."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE tft_main SET
                    Invoice = '".$Invoice."', 
                    TF_Pin = '".$TF_Pin."', 
                    Reference = '".$Reference."', 
                    DateInvoice = '".$DateInvoice."', 
                    DatePaid = '".$DatePaid."', 
                    Status = '".$Status."', 
                    Sender_Name = '".$Sender_Name."', 
                    Receiver_Name = '".$Receiver_Name."', 
                    PaymentMode = '".$PaymentMode."', 
                    AccountNo = '".$AccountNo."', 
                    Branch_Name = '".$Branch_Name."', 
                    BranchId = '".$BranchId."', 
                    PayerBankBranch = '".$PayerBankBranch."', 
                    PayingBankBranchName = '".$PayingBankBranchName."', 
                    BankName = '".$BankName."',
                    State = '".$State."', 
                    City = '".$City."', 
                    Cashier = '".$Cashier."', 
                    Total_Pay_Dollar = '".$Total_Pay_Dollar."', 
                    Total_Pay_Local = '".$Total_Pay_Local."', 
                    Receiver_Tel_No = '".$Receiver_Tel_No."',
                    Send_Country = '".$Send_Country."', 
                    Sender_Name_In_English = '".$Sender_Name_In_English."'
                    WHERE 
                    TF_Pin = '".$TF_Pin."'"); 
                }else{ 
                   // exit('ss');
                    // Insert member data in the database 
                    $query = "INSERT INTO tft_main (Invoice, TF_Pin, Reference, DateInvoice, 
                    DatePaid, Status, Sender_Name, Receiver_Name, 
                    PaymentMode, AccountNo, Branch_Name, BranchId, 
                    PayerBankBranch, PayingBankBranchName, BankName, State, 
                    City, Cashier, Total_Pay_Dollar, Total_Pay_Local, 
                    Receiver_Tel_No, Send_Country, Sender_Name_In_English) 
                    VALUES (
                        '".$Invoice."', 
                        '".$TF_Pin."', 
                        '".$Reference."', 
                        '".$DateInvoice."', 
                        '".$DatePaid."', 
                        '".$Status."', 
                        '".$Sender_Name."', 
                        '".$Receiver_Name."',
                        '".$PaymentMode."', 
                        '".$AccountNo."', 
                        '".$Branch_Name."', 
                        '".$BranchId."', 
                        '".$PayerBankBranch."', 
                        '".$PayingBankBranchName."', 
                        '".$BankName."', 
                        '".$State."',
                        '".$City."', 
                        '".$Cashier."', 
                        '".$Total_Pay_Dollar."', 
                        '".$Total_Pay_Local."', 
                        '".$Receiver_Tel_No."', 
                        '".$Send_Country."',
                        '".$Sender_Name_In_English."'
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
header("Location: index_tft.php".$qstring); 
 
?>