<?php 

$servername = "localhost";
$username = "root";
$password = "Zn_Cc*xeVN?XnIX";
$dbname = "referer";

$conn = new mysqli($servername, $username, $password, $dbname);
$row_num = 0;
if(isset($_POST["submit"])) {
	$lines = new SplFileObject($_FILES['fileToUpload']['tmp_name']);

	$read_csv = array_map("str_getcsv", file($_FILES['fileToUpload']['tmp_name'],FILE_SKIP_EMPTY_LINES));
	$columns_array = array_shift($read_csv);
	$event_id = isset($_POST['list_id'])?$_POST['list_id']:0;
	$load_query = "INSERT IGNORE INTO emails (email,rule_id,created_at,updated_at)
	    VALUES ";
	$load_query_eventlisting = "INSERT IGNORE INTO listing_email (listing_id,email_id,in_pool)
	    VALUES ";
	$load_query_email_info = "INSERT IGNORE INTO email_infos (email_id,type,value,created_at,updated_at)
	    VALUES ";
	$load_query_email_info_arr = [];
	while(!$lines->eof()) {
		$lines->next();       //Skipping first line
	    $row = explode(',',$lines);
	    if($row_num != 0) {
	    	$count_columns = count($columns_array);
		    for($i = 1; $i<$count_columns; $i++){
		        $load_query_email_info_arr[$row_num][] =$row[$i]; 
		        $load_query_email_info_key[] = strtolower($columns_array[$i]);
		    }
		    $y =date('Y-m-d h-m-s');
		    $z =date('Y-m-d h-m-s');
		    $load_query .= "('".$row[0]."','".$event_id."','".$y."','".$z."'),";
		 }
	    $row_num++;

	}
	if(!$conn->query(rtrim($load_query, ','))) {
	    die("CANNOT EXECUTE".$conn->error."\n");
	}else{
		echo "<h3>File Imported successfully</h3>";
	}
	$n = 1;
	$ids = $conn->insert_id;
	for ($i=1; $i < $row_num ; $i++) { 
		$load_query_eventlisting .= "($event_id,".$ids.",1),";
		for ($j = 0; $j < $count_columns-1; $j++) { 
			$load_query_email_info .= "($ids,'".$load_query_email_info_key[$j]."','".$load_query_email_info_arr[$n][$j]."','".date('Y-m-d h-m-s')."','".date('Y-m-d h-m-s')."'),";
		}
		$ids++; 
		$n++;
	}

	if(!$conn->query(rtrim($load_query_eventlisting, ','))) {
	    die("CANNOT EXECUTE".$conn->error."\n");
	}else{
		echo "<h3>Total Number of records ".$row_num."</h3>";
	}

	if(!$conn->query(rtrim($load_query_email_info, ','))) {
	    die("CANNOT EXECUTE".$conn->error."\n");
	}

	$lines = null;
}

?>
<form action="" method="post" enctype="multipart/form-data">
	Enter the Drip Feed list id:
	<input type="number" name="list_id" required> <br /> <br />
    Select file to upload the CSV file Comma Separated:
    <input type="file" name="fileToUpload" id="fileToUpload"> <br />
    <input type="submit" value="Import" name="submit">
</form>