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
SELECT fees.id, students.fname, students.lname, fees.amount, fees.paid_date, fees.description from fees inner join students on students.id=fees.student_id 
";
if($_POST['query'] != '')
{
  $query .= '
  WHERE students.fname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR students.lname LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR fees.amount LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR fees.paid_date LIKE "%'.str_replace(' ', '%', $_POST['query']).'%" 
  OR fees.description LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  ';
}

$query .= 'ORDER BY fees.id ASC ';
$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $connect->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $connect->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();
$total_filter_data = $statement->rowCount();

$output = '
<label>Total Records - '.$total_data.'</label>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th>ID</th>
<th>First Name</th>
<th>Last Name</th>
<th>Amount</th>
<th>Paid Date</th>
<th>Description</th>
<th>Action</th>
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
    <td>'.$row["amount"].'</td>
    <td>'.$row["paid_date"].'</td>
    <td>'.$row["description"].'</td>
    <td><a class="btn btn-success" href="payedit.php?id='.$row["id"].'">Update</a>
    <button class="btn btn-danger delete-btn" data-id='.$row["id"].'>Delete</button>
    </td>
    </tr>
    ';
  }
}
else
{
  $output .= '
  <tr>
  <td colspan="2" align="center">No Data Found</td>
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
// alert(studentId);

$.ajax({
  url:"paydelete.php",
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
  });
</script>