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
 
            foreach($worksheet_arr as $row){ 
                $Reference_Number = $row[0]; 
                $Remitter_Name = $row[1]; 
                $Remitter_Address = $row[2]; 
                $Remitter_Country = $row[3]; 
                $Paying_Amount = $row[4]; 
                $Beneficiary_Name = $row[5]; 
                $Beneficiary_ID_Number = $row[6]; 
                $ENTERED_DATE = $row[7]; 
                $BRANCH_CODE = $row[8]; 
                $ENTEREDBY = $row[9]; 

 
                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT Reference_Number FROM ez_main WHERE Reference_Number = '".$Reference_Number."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE ez_main SET Reference_Number = '".$Reference_Number."', Remitter_Name = '".$Remitter_Name."', Remitter_Address = '".$Remitter_Address."', Remitter_Country = '".$Remitter_Country."', Paying_Amount = '".$Paying_Amount."', Beneficiary_Name = '".$Beneficiary_Name."', Beneficiary_ID_Number = '".$Beneficiary_ID_Number."', ENTERED_DATE = '".$ENTERED_DATE."', BRANCH_CODE = '".$BRANCH_CODE."', ENTEREDBY = '".$ENTEREDBY."' WHERE Reference_Number = '".$Reference_Number."'"); 
                }else{ 
                    // Insert member data in the database 
                    $db->query("INSERT INTO ez_main (Reference_Number, Remitter_Name, Remitter_Address, Remitter_Country, Paying_Amount, Beneficiary_Name, Beneficiary_ID_Number, ENTERED_DATE, BRANCH_CODE, ENTEREDBY) 
                    VALUES ('".$Reference_Number."', '".$Remitter_Name."', '".$Remitter_Address."', '".$Remitter_Country."', '".$Paying_Amount."', '".$Beneficiary_Name."', '".$Beneficiary_ID_Number."', '".$ENTERED_DATE."', '".$BRANCH_CODE."', '".$ENTEREDBY."')"); 
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
header("Location: index_ez.php".$qstring); 
 
?>