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
              
                $Tranno = $row[0]; 
                $Traninfosl  = $row[1]; 
                $Entered_Date = $row[2]; 
                $Amount = $row[3]; 
                $Remitter = $row[4]; 
                $Beneficiary = $row[5]; 
                $Bene_AC = $row[6]; 
                $Branch_Code = $row[7]; 
                $Status = $row[8]; 
                $Bene_Tel = $row[9]; 
                $Paid_Date = $row[10]; 
                $trmode = $row[11]; 
                $Work_Permit = $row[12]; 
                $Passport = $row[13]; 

                // Check whether member already exists in the database with the same email 
                $prevQuery = "SELECT Tranno FROM agrani_remit_main WHERE Tranno = '".$Tranno."'"; 
                $prevResult = $db->query($prevQuery); 
                 
                if($prevResult->num_rows > 0){ 
                    // Update member data in the database 
                    $db->query("UPDATE agrani_remit_main SET
                   
                    Tranno = '".$Tranno."', 
                    Traninfosl = '".$Traninfosl."', 
                    Entered_Date = '".$Entered_Date."', 
                    Amount = '".$Amount."', 
                    Remitter = '".$Remitter."', 
                    Beneficiary = '".$Beneficiary."', 
                    Bene_AC = '".$Bene_AC."', 
                    Branch_Code = '".$Branch_Code."', 
                    Status = '".$Status."', 
                    Bene_Tel = '".$Bene_Tel."', 
                    Paid_Date = '".$Paid_Date."', 
                    trmode = '".$trmode."', 
                  
                    Work_Permit = '".$Work_Permit."', 
                    Passport = '".$Passport."'
                    WHERE 
                    Tranno = '".$Tranno."'"); 
                }else{ 
                   // exit('ss');
                    // Insert member data in the database 
                    $query = "INSERT INTO agrani_remit_main (Tranno, Traninfosl, Entered_Date, 
                    Amount, Remitter, Beneficiary, Bene_AC, 
                    Branch_Code, Status, Bene_Tel, Paid_Date,trmode, Work_Permit, Passport) 
                    VALUES (
                       
                        '".$Tranno."', 
                        '".$Traninfosl."', 
                        '".$Entered_Date."', 
                        '".$Amount."', 
                        '".$Remitter."', 
                        '".$Beneficiary."', 
                        '".$Bene_AC."',
                        '".$Branch_Code."', 
                        '".$Status."', 
                        '".$Bene_Tel."', 
                        '".$Paid_Date."', 
                        '".$trmode."', 
                        '".$Work_Permit."', 
                        '".$Passport."'
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
header("Location: index_agrani_remit.php".$qstring); 
 
?>