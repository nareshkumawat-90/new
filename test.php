<?php

$connect = new PDO("mysql:host=localhost; dbname=sms", "root", "");

/*function get_total_row($connect)
{
  $query = "
  SELECT * FROM tbl_webslesson_post
  ";
  $statement = $connect->prepare($query);
  $statement->execute();
  return $statement->rowCount();
}

$total_record = get_total_row($connect);*/

$limit = '5';
$page = 1;
if($_POST['page'] > 1)
{
  $start = (($_POST['page'] - 1) * $limit);
  $page = $_POST['page'];
}
else
{
  $start = 0;
}


 $query = "
 SELECT students.id,students.fname, students.lname, students.email, students.contactno, students.address, students.dob, students.gender, students.class, students.subject, students.total_fee, Amount,students.status  FROM students left join (SELECT student_id, SUM(amount) as Amount FROM fees GROUP BY fees.student_id  ) as f on students.id=f.student_id 
 ";
// $query = "
// SELECT students.id,students.fname,students.lname,students.email,students.contactno,students.address,students.dob,students.gender,students.class,students.subject,students.total_fee,fees.amount,students.status  FROM students left join fees  on students.id=fees.student_id 
// ";


if($_POST['query'] != '')
{
  $query .= '
  WHERE students.fname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR students.lname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR students.email LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR students.contactno LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR students.address LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR students.gender LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR students.dob LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR Amount LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR students.class LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR students.subject LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  ';
}

$query .= 'ORDER BY id ASC ';
// $query .='GROUP BY students.id';

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $connect->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $connect->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();
$statuss=['active'=>'Active','hold'=>'Hold','passout'=>'Passout'];
$output = '
<label>Total Records - '.$total_data.'</label>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Email</th>
<th>Contact</th>
<th>Address</th>
<th>Gender</th>
<th>Date of Birth</th>
<th>Class</th>
<th>Subject</th>
<th>Total Fees</th>
<th>Amount</th>
<th>Due Amount</th>
<th>Status</th>
<th style="width:200px;">Action</th>
</tr>
</thead>
';
if($total_data > 0)
{
  foreach($result as $row)
  {
    $output .= '
    <tbody>
    <tr>
    <td>'.$row["id"].'</td>
    <td>'.$row["fname"].'</td>
    <td>'.$row["lname"].'</td>
    <td>'.$row["email"].'</td>
    <td>'.$row["contactno"].'</td>
    <td>'.$row["address"].'</td>
    <td>'.$row["gender"].'</td>
    <td>'.$row["dob"].'</td>
    <td>'.$row["class"].'</td>
    <td>'.$row["subject"].'</td>
    <td>'.$row["total_fee"].'</td>
    <td>'.$row["Amount"].'</td>
    <td>'.(int)($row["total_fee"]-$row["Amount"]).'</td>
    <td>'.$row["status"].'</td>
    <td><a class="btn btn-success btn-xs" href="edit.php?id='.$row["id"].'">Update</a>
    <button class="btn btn-danger delete-btn btn-xs" data-id='.$row["id"].'>Delete</button>
    <a class="btn btn-success btn-xs" href="pay.php?id='.$row["id"].'">Fee</a>
    
    <select name="status" id=""  data-id='.$row["id"].' class="form-control changeStatus">';
    foreach ($statuss as $key => $value) {
      $sel="";
      if($key==$row["status"]){
        $sel="selected";
      }
      $output .='<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
    }
    
    $output.='</select>
    
    
    </td>
    </tr>
    ';
  }
}
else
{
  $output .= '
  <tr >
  <td colspan="15" align="center">No Data Found</td>
  </tr>
  ';
}

$output .= '
<tbody>
</table>
<br />
<div align="center">
<ul class="pagination">
';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

//echo $total_links;

if($total_links > 4)
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}

for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
    <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
      <a class="page-link" href="#">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id >= $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
      <a class="page-link" href="#">Next</a>
      </li>
      ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
      <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
</ul>

</div>
';

echo $output;

?>
<script>
  $(document).ready(function(){
   $(document).on("click",".delete-btn",function(){
     if(confirm("Do You Really Want To Delete...!!!")){
       var studentId=$(this).data("id");
       var element=this;
        //  alert(studentId);

        $.ajax({
          url:"delete-process.php",
          type:"post",
          data:{id:studentId},
          success:function(data){
            if(data==1){
              $(element).closest("tr").fadeOut();
            }else{
              $("#error-message").html("Can't Delete Record").slideDown();
              $("#success-message").slideUp();
            }
          }

        });
      }
    });
   $('.changeStatus').on('change', function(){
      console.log("select click");
      let std_id = $(this).data('id');
      let status= $(this).val();
      $.ajax({
       url: "3.php",
       type: "POST",
       data:{id:std_id,status:status}
       ,success: function (data) {
          /*$("#id").val(data.id);
          $("#status").val(data.Status);*/
        }
      });
    }); 
 });

</script>
