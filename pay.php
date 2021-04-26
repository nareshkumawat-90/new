<?php 
include 'header.php';
include_once 'db.php'; 

$id = $_GET['id'];
$amount = " ";
$paid_date =" ";
$description = " ";

if(isset($_POST['save'])){
  $amount = $_POST['amount'];
  $paid_date =$_POST['paid_date'];
  $description = $_POST['description'];
  $sql = "SELECT * FROM students where id = '$id'";
  $result = mysqli_query($conn,$sql);
  $rowcount = mysqli_num_rows($result);
  $sql1 = "INSERT INTO fees (student_id ,amount,paid_date,description)
  VALUES ('$id','$amount','$paid_date','$description')";
  echo $sql1;
  if (mysqli_query($conn, $sql1)) {
    header('location:student_details.php');
#echo "New record created successfully !";
  } else {
    echo "Error: " . $sql1 . "

    " . mysqli_error($conn);
  }
}
?>

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
                  <h2>Student Fees Form</small></h2>

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
                  
                  

                  <form id="my" data-parsley-validate class="form-horizontal form-label-left" method="post" action="">
                    <?php echo form_open('form'); ?>
                    <div id="msg"></div>
                    <div class="form-group">
                      <label for="amountid" class="control-label col-md-3 col-sm-3 col-xs-12">Amount<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="amountid" class="form-control col-md-7 col-xs-12" value="" required="required" name="amount" type="text">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="paid_dateid" class="control-label col-md-3 col-sm-3 col-xs-12">Paid Date <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="paid_dateid" name="paid_date" value="" class="date-picker form-control col-md-7 col-xs-12" required="required" type="date">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="descriptionid" class="control-label col-md-3 col-sm-3 col-xs-12">Description<span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="descriptionid" class="form-control col-md-7 col-xs-12" value="" required="required" name="description" type="text">
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button class="btn btn-primary" type="button">Cancel</button>
                        <button type="submit" name="save" class="btn btn-success" id="btnup">Submit</button>
                      </div>
                    </div>
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