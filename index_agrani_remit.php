<?php 
// Load the database configuration file 
include_once 'dbConfig.php'; 
 
// Get status message 
if(!empty($_GET['status'])){ 
    switch($_GET['status']){ 
        case 'succ': 
            $statusType = 'alert-success'; 
            $statusMsg = 'Member data has been imported successfully.'; 
            break; 
        case 'err': 
            $statusType = 'alert-danger'; 
            $statusMsg = 'Something went wrong, please try again.'; 
            break; 
        case 'invalid_file': 
            $statusType = 'alert-danger'; 
            $statusMsg = 'Please upload a valid Excel file.'; 
            break; 
        default: 
            $statusType = ''; 
            $statusMsg = ''; 
    } 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RMO</title>

<!-- Bootstrap library -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<!-- Stylesheet file -->
<link rel="stylesheet" href="assets/css/style.css">


<!-- Show/hide Excel file upload form -->
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
</script>
</head>
<body>

<!-- Display status message -->
<?php if(!empty($statusMsg)){ ?>
<div class="col-xs-12 p-3">
    <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
</div>
<?php } ?>

<div class="row p-3">
    <!-- Import link -->
    <div class="col-md-12 head">
        <div class="float-end">
            <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import Excel</a>
        </div>
    </div>
    <!-- Excel file upload form -->
    <div class="col-md-12" id="importFrm" style="display: none;">
        <form class="row g-3" action="importData_agrani_remit.php" method="post" enctype="multipart/form-data">
            <div class="col-auto">
                <label for="fileInput" class="visually-hidden">File</label>
                <input type="file" class="form-control" name="file" id="fileInput" />
            </div>
            <div class="col-auto">
                <input type="submit" class="btn btn-primary mb-3" name="importSubmit" value="Import">
            </div>
        </form>
    </div>
   
    <!-- Data list table --> 
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
              
                <th>Tranno</th>
                <th>Traninfosl</th>
                <th>Entered_Date</th>
                <th>Amount</th>
                <th>Remitter</th>
                <th>Beneficiary</th>
                <th>Bene_AC</th>
                <th>Branch_Code</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        // Get member rows 
        $result = $db->query("SELECT * FROM  agrani_remit_main"); 
        if($result->num_rows > 0){ $i=0; 
            while($row = $result->fetch_assoc()){ $i++; 
        ?>
            <tr>
                <td><?php echo $i; ?></td>
                
                <td><?php echo $row['Tranno']; ?></td>
                <td><?php echo $row['Traninfosl']; ?></td>
                <td><?php echo $row['Entered_Date']; ?></td>
                <td><?php echo $row['Amount']; ?></td>
                <td><?php echo $row['Remitter']; ?></td>
                <td><?php echo $row['Beneficiary']; ?></td>
                <td><?php echo $row['Bene_AC']; ?></td>
                <td><?php echo $row['Branch_Code']; ?></td>
                <td><?php echo $row['Status']; ?></td>
            </tr>
        <?php } }else{ ?>
            <tr><td colspan="7">No member(s) found...</td></tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>