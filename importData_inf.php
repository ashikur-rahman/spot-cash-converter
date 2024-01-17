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
            $worksheet = $spreadsheet->getActiveSheet()->removeColumn('A');  
          
            $worksheet_arr = $worksheet->toArray(); 
 
            // Remove header row 
            unset($worksheet_arr[0]); 
            unset($worksheet_arr[1]);
       
            // echo '<pre>';
            // print_r($worksheet_arr );
            // echo '</pre>';
            // exit();
            foreach($worksheet_arr as $row){ 
                	
                $Remittance_No= $row[0];
                $Applicant_Name= $row[1];
                $Beneficiary_Name= $row[2];
                $Benifiniciary_Mobile= $row[3];
                $Issuing_Branch= $row[4];
                $Trans_Date= $row[5];
                $Amount= $row[6];
                $payment_Mode= $row[7];
                $Payment_Branch= $row[8];
                $Payment_Branch_Code= $row[9];
                $Payment_Account_No= $row[10];
                $Security_Code= $row[11];
                $Status= $row[12];

                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT Remittance_No FROM inf_main WHERE Remittance_No = '".$Remittance_No."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE inf_main SET
                    Remittance_No = '".$Remittance_No."', 
                    Applicant_Name = '".$Applicant_Name."', 
                    Beneficiary_Name = '".$Beneficiary_Name."', 
                    Benifiniciary_Mobile = '".$Benifiniciary_Mobile."', 
                    Issuing_Branch = '".$Issuing_Branch."', 
                    Trans_Date = '".$Trans_Date."', 
                    Amount = '".$Amount."', 
                    payment_Mode = '".$payment_Mode."', 
                    Payment_Branch = '".$Payment_Branch."', 
                    Payment_Branch_Code = '".$Payment_Branch_Code."', 
                    Payment_Account_No = '".$Payment_Account_No."', 
                    Security_Code = '".$Security_Code."', 
                    Status = '".$Status."'
                    WHERE 
                    Remittance_No = '".$Remittance_No."'"); 
                }else{ 
                   // exit('ss');
                    // Insert member data in the database 
                    $query = "INSERT INTO inf_main (Remittance_No, 	
                    Applicant_Name,
                    Beneficiary_Name, 	
                    Benifiniciary_Mobile, 	
                    Issuing_Branch, 	
                    Trans_Date, 	
                    Amount, 	
                    payment_Mode, 	
                    Payment_Branch, 	
                    Payment_Branch_Code, 	
                    Payment_Account_No, 	
                    Security_Code, 	
                    Status ) 
                    VALUES (
                        '".$Remittance_No."', 
                        '".$Applicant_Name."', 
                        '".$Beneficiary_Name."', 
                        '".$Benifiniciary_Mobile."', 
                        '".$Issuing_Branch."', 
                        '".$Trans_Date."', 
                        '".$Amount."', 
                        '".$payment_Mode."', 
                        '".$Payment_Branch."', 
                        '".$Payment_Branch_Code."', 
                        '".$Payment_Account_No."', 
                        '".$Security_Code."', 
                        '".$Status."'
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
header("Location: index_inf.php".$qstring); 
 
?>