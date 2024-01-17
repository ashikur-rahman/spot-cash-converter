<?php 
 
// Load the database configuration file 
include_once 'dbConfig.php'; 
 
// Include PhpSpreadsheet library autoloader 
require_once 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\Reader\Csv; 
 
if(isset($_POST['importSubmit'])){ 
     
    // Allowed mime types 
    $excelMimes = array('text/csv', 'text/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); 
     
    // Validate whether selected file is a Excel file 
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $excelMimes)){ 
         
        // If the file is uploaded 
        if(is_uploaded_file($_FILES['file']['tmp_name'])){ 
            $reader = new Csv(); 
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
                $ICTC_Number = $row[0]; 
                $Remitter_Name = $row[1]; 
                $Remitter_Address  = $row[2]; 
                $Originating_Country = $row[3]; 
                $Paying_Amount = $row[4]; 
                $Beneficiary_Name = $row[5]; 
                $Beneficiary_Id = $row[6]; 
                $Paid_Date = $row[7]; 
                $BRANCH_CODE = $row[8]; 
                $EDITEDBY = $row[9]; 
             

                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT ICTC_Number FROM ic_main WHERE ICTC_Number = '".$ICTC_Number."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE ic_main SET
                    ICTC_Number = '".$ICTC_Number."', 
                    Remitter_Name = '".$Remitter_Name."', 
                    Remitter_Address = '".$Remitter_Address."', 
                    Originating_Country = '".$Originating_Country."', 
                    Paying_Amount = '".$Paying_Amount."', 
                    Beneficiary_Name = '".$Beneficiary_Name."', 
                    Beneficiary_Id = '".$Beneficiary_Id."', 
                    Paid_Date = '".$Paid_Date."', 
                    BRANCH_CODE = '".$BRANCH_CODE."', 
                    EDITEDBY = '".$EDITEDBY."'
                    WHERE 
                    ICTC_Number = '".$ICTC_Number."'"); 
                }else{ 
                   // exit('ss');
                    // Insert member data in the database 
                    $query = "INSERT INTO ic_main (ICTC_Number,Remitter_Name,Remitter_Address,Originating_Country,
                    Paying_Amount,Beneficiary_Name,Beneficiary_Id,Paid_Date,BRANCH_CODE,EDITEDBY) 
                    VALUES (
                        '".$ICTC_Number."', 
                        '".$Remitter_Name."', 
                        '".$Remitter_Address."', 
                        '".$Originating_Country."', 
                        '".$Paying_Amount."', 
                        '".$Beneficiary_Name."', 
                        '".$Beneficiary_Id."', 
                        '".$Paid_Date."',
                        '".$BRANCH_CODE."', 
                        '".$EDITEDBY."'
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
header("Location: index_ic.php".$qstring); 
 
?>