<?php 

$servername = "localhost";
$username = "root";
$password = "Zn_Cc*xeVN?XnIX";
$dbname = "referer";

$conn = new mysqli($servername, $username, $password, $dbname);
$row_num = 0;
if(isset($_POST["submit"])) {
	$lines = new SplFileObject($_FILES['fileToUpload']['tmp_name']);
	$event_id = isset($_POST['list_id'])?$_POST['list_id']:0;
	$load_query = "INSERT IGNORE INTO emails (email,rule_id,created_at,updated_at)
	    VALUES ";
	$load_query_eventlisting = "INSERT IGNORE INTO listing_email (listing_id,email_id,in_pool)
	    VALUES ";
	while(!$lines->eof()) {
	    $lines->next();       //Skipping first line
	    $row = explode(',',$lines);
	    for($i = 0; $i<4; $i++){
	        if(!isset($row[$i])){
	            $row[$i] = $lines;
	        }
	    }
	    $y =date('Y-m-d h-m-s');
	    $z =date('Y-m-d h-m-s');
	    $load_query .= "('".$row[0]."','".$event_id."','".$y."','".$z."'),";
	    $row_num++;
	}

	if(!$conn->query(rtrim($load_query, ','))) {
	    die("CANNOT EXECUTE".$conn->error."\n");
	}else{
		echo "<h3>File Imported successfully</h3>";
	}
	$ids = $conn->insert_id;
	for ($i=0; $i < $row_num ; $i++) { 
		$load_query_eventlisting .= "($event_id,".$ids.",1),";
		$ids++;
	}
	if(!$conn->query(rtrim($load_query_eventlisting, ','))) {
	    die("CANNOT EXECUTE".$conn->error."\n");
	}else{
		echo "<h3>Total Number of records ".$row_num."</h3>";
	}
	$lines = null;
}

?>
<form action="" method="post" enctype="multipart/form-data">
	Enter the Drip Feed list id:
	<input type="number" name="list_id" required> <br /> <br />
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"> <br />
    <input type="submit" value="Import" name="submit">
</form>