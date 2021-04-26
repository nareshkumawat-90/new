<?php
   require_once "db.php";
   $id = $_POST['id'];
   $status=$_POST['status'];
   
   $result = "UPDATE students  SET  status='$status' WHERE id={$id} ";
   $res=mysqli_query($conn,$result) or die("Query Unsuccessful,");
?>