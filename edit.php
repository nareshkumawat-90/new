<?php 
include 'header.php';
include_once 'db.php';
$valid=$emailErr='';
if(isset($_POST["id"]) && !empty($_POST["id"])){
  $cid = $_POST["id"];
  $fname=$_POST["fname"];
  $lname=$_POST["lname"];
  $contactno = $_POST["contactno"];
  $gender= $_POST["gender"];
  $address = $_POST["address"];
  $dob= $_POST["dob"];
  $class= $_POST["class"];
  $subject= $_POST["subject"];
  $total_fee= $_POST["total_fee"];
  $email= $_POST["email"];
//Email Address Validation
  $duplicate=mysqli_query($conn,"select * from students where email='$email' and id>'$cid' or email='$email' and id<'$cid' ");
  if (mysqli_num_rows($duplicate)>0)
  {
       // echo "Email id already exists.";
   $emailErr="Email id already exists.";
 }
// check all fields are valid or not
 else
 {

   // here you can write Sql Query to insert user data into database table
   
   $sql="UPDATE students SET 
   fname='$fname', 
   lname='$lname', 
   email='$email', 
   dob='$dob',
   gender='$gender', 
   contactno='$contactno', 
   class='$class',
   subject='$subject',
   total_fee='$total_fee',
   address='$address' 
   WHERE id='$cid' ";
   header("Location: student_details.php");
   $res=mysqli_query($conn,$sql) or die("Query Unsuccessful,");

 }
}
?>
<style type="text/css">
  .err-msg{
    color: red;
  }
</style>
<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <?php include 'sidebar.php';?>
      <!-- top navigation -->
      <?php include 'top.php';?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Student Mangement</h3>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Student Form</small></h2>

                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>

                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />
                  <?php
                  include_once 'db.php';
                  $id=$_GET['id'];
                  $sql="SELECT * FROM students WHERE id='{$id}'";
                  $res=mysqli_query($conn,$sql) or die("Query Unsuccessful.");
                  if(mysqli_num_rows($res)>0){
                    while($row=mysqli_fetch_assoc($res)){

                     ?>

                     <form id="my" data-parsley-validate class="form-horizontal form-label-left" method="post" action="">
                      <div id="msg"></div>
                      <div class="form-group">
                        <input type="hidden" name="id" class="txtField" value="<?php echo $row['id']; ?>">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fnameid">First Name <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="fnameid" required="required" value="<?php echo $row['fname']; ?>" name="fname" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"  for="lnameid">LastName <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="lnameid" value="<?php echo $row['lname']; ?>" required="required" name="lname" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="emailid">Email <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="email" id="emailid" name="email" value="<?php echo $row['email']; ?>" required="required" class="form-control col-md-7 col-xs-12">
                          <span class="err-msg">
                           <?php if($emailErr!=1){ echo $emailErr; } ?>
                         </span>
                       </div>
                     </div>
                     <div class="form-group">
                      <label for="dobid" class="control-label col-md-3 col-sm-3 col-xs-12">Date Of Birth <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="dobid" name="dob" value="<?php echo $row['dob']; ?>" class="date-picker form-control col-md-7 col-xs-12" required="required" type="date">
                      </div>
                    </div>
                    <div class="form-group">
                      <label  class="control-label col-md-3 col-sm-3 col-xs-12">Gender</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                            <input type="radio"  name="gender" value="male" <?php if($row['gender']=="male"){ echo "checked";}?>> &nbsp; Male &nbsp;
                          </label>
                          <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                            <input type="radio"  name="gender" value="female" <?php if($row['gender']=="female"){ echo "checked";}?>> Female
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="contactid" class="control-label col-md-3 col-sm-3 col-xs-12">Contact Number<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="contactid" class="form-control col-md-7 col-xs-12" value="<?php echo $row['contactno']; ?>" required="required" name="contactno" type="text">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="dobid" class="control-label col-md-3 col-sm-3 col-xs-12">Class <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="dobid" name="class" value="<?php echo $row['class']; ?>" class="form-control col-md-7 col-xs-12" required="required" type="text">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="dobid" class="control-label col-md-3 col-sm-3 col-xs-12">Subject <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="dobid" name="subject" value="<?php echo $row['subject']; ?>" class="form-control col-md-7 col-xs-12" required="required" type="text">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="total_feeid" class="control-label col-md-3 col-sm-3 col-xs-12">Total Fee<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="total_feeid" class="form-control col-md-7 col-xs-12" value="<?php echo $row['total_fee']; ?>" required="required" name="total_fee" type="text">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="addid" class="control-label col-md-3 col-sm-3 col-xs-12">Address<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="addid" class="form-control col-md-7 col-xs-12" value="<?php echo $row['address']; ?>" required="required" name="address" type="text">
                      </div>
                    </div>
                    
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button class="btn btn-primary" type="button">Cancel</button>
                        <button type="submit" name="update" class="btn btn-success" id="btnup">Submit</button>
                      </div>
                    </div>
                    <?php
                  }
                }
                ?>
              </form>
            </div>
          </div>
        </div>
      </div>
      

      
    </div>
  </div>
  <!-- /page content -->

  <!-- footer content -->
  <?php include 'footer.php';?>