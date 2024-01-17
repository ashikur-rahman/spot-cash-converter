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
 
            foreach($worksheet_arr as $row){ 

                $S_No = $row[1]; 
                $CE_NUMBER = $row[2]; 
                $RECEIVE_USER = $row[3]; 
                $RECEIVE_BRANCH = $row[4]; 
                $RECEIVE_DATE = $row[5]; 
                $Source_Country = $row[6]; 
                $Type = $row[7]; 
                $Sender_Name = $row[8]; 
                $Beneficiary_Name  = $row[9]; 
                $Beneficiary_Mobile_Num = $row[10]; 
                $Receive_Amount = $row[11]; 
                $Currency = $row[12]; 

 
                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT CE_NUMBER FROM ce_main WHERE CE_NUMBER = '".$CE_NUMBER."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE ce_main SET S_No = '".$S_No."', CE_NUMBER = '".$CE_NUMBER."', RECEIVE_USER = '".$RECEIVE_USER."', RECEIVE_BRANCH = '".$RECEIVE_BRANCH."', RECEIVE_DATE = '".$RECEIVE_DATE."', 
                    Source_Country = '".$Source_Country."', Type = '".$Type."', Sender_Name = '".$Sender_Name."', Beneficiary_Name = '".$Beneficiary_Name."', Beneficiary_Mobile_Num = '".$Beneficiary_Mobile_Num."', Receive_Amount = '".$Receive_Amount."', Currency = '".$Currency."' WHERE CE_NUMBER = '".$CE_NUMBER."'"); 
                }else{ 
                    if(!empty($CE_NUMBER)){
                        // Insert member data in the database 
                        $db->query("INSERT INTO ce_main (S_No, CE_NUMBER, RECEIVE_USER, RECEIVE_BRANCH, RECEIVE_DATE, Source_Country, Type, Sender_Name, Beneficiary_Name, Beneficiary_Mobile_Num, Receive_Amount, Currency) 
                    VALUES ('".$S_No."', '".$CE_NUMBER."', '".$RECEIVE_USER."', '".$RECEIVE_BRANCH."', '".$RECEIVE_DATE."', '".$Source_Country."', '".$Type."', '".$Sender_Name."', '".$Beneficiary_Name."', '".$Beneficiary_Mobile_Num."', '".$Receive_Amount."', '".$Currency."')"); 
                    }
                    
                    
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
header("Location: index_ce.php".$qstring); 
 
?>