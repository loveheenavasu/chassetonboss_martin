<?php 

$servername = "localhost";
$username = "root";
$password = "Zn_Cc*xeVN?XnIX";
$dbname = "referer";
$conn = new mysqli($servername, $username, $password, $dbname);

$row_num = 0;
if(isset($_POST["submit"])) {
	$result = $conn->query("SELECT * FROM `keywords` ");
	$allKeywords = [];
	$lines = new SplFileObject($_FILES['fileToUpload']['tmp_name']);
	$event_id = isset($_POST['list_id'])?$_POST['list_id']:0;

	$listDataQuery =$conn->query("SELECT * FROM `lead_validators` WHERE `id` = $event_id"); 
	$listData = $listDataQuery->fetch_assoc();
	if(!empty($listData)){
		$csv_filename = str_replace(' ', '', strtolower($listData['name']));
        $csv_filename = str_replace(array(':', '\\', '/', '*','-','_'), '', $csv_filename);
	}else{
		$csv_filename = time();
	}
	$j = 0;
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$allKeywords[] = $row['name'];
		}
	}

	$countallKeywords = count($allKeywords);
	$all_db_webmails = '';
	$all_cop_webmails = '';
	$all_db_webmails .= "Email \r\n";
	$num_of_emails =0;
	$all_emails =[];
	while(!$lines->eof()) {
	    $lines->next();       //Skipping first line
	    $row = explode(',',$lines);
	    for($i = 0; $i<1; $i++){
	        if(!isset($row[$i])){
	            $row[$i] = $lines;
	        }
	    }
	    $num_of_emails++;
	    array_push($all_emails,$row[0]);
	}

	$count = count($all_emails);

    for($k=0; $k<$count; $k++){
     	$string = $all_emails[$k];
		$explode = explode("@",$string);
		$email_prefix = trim($explode[1]);
        if(in_array($email_prefix, $allKeywords)) {
            $all_db_webmails .= "".$all_emails[$k]."";
        }else{  
            $all_cop_webmails .= "".$all_emails[$k]."";
        }   
    }

	$num_of_emails =$num_of_emails-2;
	$lines = null;
	$webmail_filename = '/var/private/public/';
	$webmail_csv_filename = $webmail_filename.$csv_filename."-allwebemail.csv";
	$fd_webmail = fopen ($webmail_csv_filename, "w");
	fputs($fd_webmail, $all_db_webmails);
	fclose($fd_webmail);


	$copmail_filename = '/var/private/public/';
	$copmail_csv_filename = $copmail_filename.$csv_filename."-allcorpemail.csv";
	$fd_copmail = fopen ($copmail_csv_filename, "w");
	fputs($fd_copmail, $all_cop_webmails);
	fclose($fd_copmail);

	echo "<h3>CSV of ".$num_of_emails." Emails Created successfully</h3>";


}
?>
<form action="" method="post" enctype="multipart/form-data">
	Enter the Lead validator id:
	<input type="number" name="list_id" required> <br /> <br />
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"> <br />
    <input type="submit" value="Import" name="submit">
</form>