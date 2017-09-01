<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '1234';
$dbname='ajaxdata';
//connect to MySql database
try {
    $db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass) 
     or die("Unable to connect.");
}
catch(PDOException $e)
    {
      echo "Error: " . $e->getMessage();
    }

$page = isset($_GET['p'])?$_GET['p']:'';
if($page=='add'){
	$name = $_POST['nm'];
	$email = $_POST['em'];
	$phone = $_POST['hp'];
	$address = $_POST['al'];
	$stmt = $db->prepare("INSERT INTO crud (name, email, phone, address) VALUES('$name','$email','$phone','$address')");
	$stmt->bindParam(1,$name);
	$stmt->bindParam(2,$email);
	$stmt->bindParam(3,$phone);
	$stmt->bindParam(4,$address);
	$stmt->execute();
} elseif ($page=='edit') {
	# code...
} elseif ($page=='del') {
	# code...
} else{
	$stmt = $db->prepare("SELECT * FROM crud ORDER BY id DESC");
	$stmt->execute();

	while($row = $stmt->fetch()) {
        echo "<tr><td>".$row["id"]."</td>";
        echo "<td>".$row["name"]."</td>";
        echo "<td>".$row["email"]."</td>";
        echo "<td>".$row["phone"]."</td>";
        echo "<td>".$row["address"]."</td>";
        echo '<td>
        	<button class="btn btn-info" data-title="Edit" data-toggle="modal" data-target="#edit-$row["id"]"><span class="fa fa-pencil"></span></button>
        	<button class="btn btn-danger" data-title="Delete" data-toggle="modal" data-target="#delete"><span class="fa fa-trash-o"></span></button>
        	</td></tr>';


    }
	
	
	
}
?>