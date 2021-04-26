<?php
include_once 'db.php';

$id=$_POST['id'];
  $sql="DELETE FROM students WHERE id={$id}";
// $sql = "DELETE FROM students USING students LEFT JOIN fees ON students.id = fees.student_id WHERE students.id={$id} AND fees.id IS NULL";
if(mysqli_query($conn,$sql)){
	echo 1;
	
}else{
	echo 0;
}
?>