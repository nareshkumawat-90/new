<?php
include_once 'db.php';

// by default,set input values are empty
// $set_fname=$set_lname=$set_email=$set_gender=$set_contact=$set_add=$set_dob='';    

if(isset($_POST["save"]))){
  $id=$_POST['id'];
  echo $id;
  $amount = $_POST['amount'];
  echo $amount;
  $pay_date= $_POST['pay_date'];
  echo $pay_date;
  $description= $_POST['description'];
  echo $description;
  $sql = "INSERT INTO fees (student_id,amount,pay_date,description)
  VALUES ('$id','$amount','$pay_date','$description')";
      // header("Location: student_details.php");

// $res=mysqli_query($conn,$sql) or die("Query Unsuccessful". $conn->connect_error);

//    }
  echo $sql;
  if (mysqli_query($conn, $sql)) {
    echo "New record created successfully !";
  } else {
   echo "Error: " . $sql . "
   " . mysqli_error($conn);
 }
 mysqli_close($conn);
  // }
}
?> 