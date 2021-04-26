<?php
include_once 'db.php';

$id=$_POST['id'];
  // $sql="DELETE FROM admin WHERE id={$student_id}";
	$sql = "DELETE FROM fees WHERE id={$id}";
if(mysqli_query($conn,$sql)){
	echo 1;
}else{
	echo 0;
}
?>