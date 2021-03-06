<?php

//student.php

include('srms.php');

$object = new srms();

if(!$object->is_login())
{
header("location:".$object->base_url."admin");
}

if(!$object->is_master_user())
{
header("location:".$object->base_url."admin/result.php");
}

include('header.php');

$object->query = "
SELECT * FROM class_srms
WHERE class_status = 'Enable'
ORDER BY class_name ASC
";

$result = $object->get_result();

?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Student Management</h1>

<!-- DataTales Example -->
<span id="message"></span>
<div class="card shadow mb-4">
<div class="card-header py-3">
<div class="row">
<div class="col">
<h6 class="m-0 font-weight-bold text-primary">Student List</h6>
</div>
<div class="col" align="right">
<button type="button" name="add_student" id="add_student" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
</div>
</div>
</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered" id="student_table" width="100%" cellspacing="0">
<thead>
<tr>
<th>Roll No.</th>
<th>Student Name</th>
<th>Class Name</th>
<th>Email Address</th>
<th>Gender</th>
<th>Date of Birth</th>
<th>Added On</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>

</tbody>
</table>
</div>
</div>
</div>

<?php
include('footer.php');
?>

<div id="studentModal" class="modal fade">
<div class="modal-dialog">
<form method="post" id="student_form">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="modal_title">Add Student</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<span id="form_message"></span>
<div class="form-group">
<label>Class Name</label>
<select name="class_id" id="class_id" class="form-control" required>
<option value="">Select Class</option>
<?php
foreach($result as $row)
{
echo '
<option value="'.$row["class_id"].'">'.$row["class_name"].'</option>
';
}
?>
</select>
</div>
<div class="form-group">
<label>Student Name</label>
<input type="text" name="student_name" id="student_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z0-9 \s]+$/" data-parsley-trigger="keyup" />
</div>
<div class="form-group">
<label>Student Roll No.</label>
<input type="text" name="student_roll_no" id="student_roll_no" class="form-control" required data-parsley-pattern="/^[a-zA-Z0-9\s]+$/" data-parsley-trigger="keyup" />
</div>
<div class="form-group">
<label>Student Email</label>
<input type="text" name="student_email_id" id="student_email_id" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
</div>
<div class="form-group">
<label>Gender</label>
<select name="student_gender" id="student_gender" class="form-control">
<option value="Male">Male</option>
<option value="Female">Female</option>
</select>
</div>
<div class="form-group">
<label>Date of Birth</label>
<input type="text" name="student_dob" id="student_dob" class="form-control datepicker" readonly required data-parsley-trigger="keyup" />
</div>
</div>
<div class="modal-footer">
<input type="hidden" name="hidden_id" id="hidden_id" />
<input type="hidden" name="action" id="action" value="Add" />
<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</form>
</div>
</div>

<script>
$(document).ready(function(){

$('#student_dob').datepicker({
format: "yyyy-mm-dd",
autoclose: true
});

var dataTable = $('#student_table').DataTable({
"processing" : true,
"serverSide" : true,
"order" : [],
"ajax" : {
url:"student_action.php",
type:"POST",
data:{action:'fetch'}
},
"columnDefs":[
{
"targets":[8],
"orderable":false,
},
],
});

$('#add_student').click(function(){

$('#student_form')[0].reset();

$('#student_form').parsley().reset();

$('#modal_title').text('Add Student');

$('#action').val('Add');

$('#submit_button').val('Add');

$('#studentModal').modal('show');

$('#form_message').html('');

});

$('#student_form').parsley();

$('#student_form').on('submit', function(event){
event.preventDefault();
if($('#student_form').parsley().isValid())
{
$.ajax({
url:"student_action.php",
method:"POST",
data:$(this).serialize(),
dataType:'json',
beforeSend:function()
{
$('#submit_button').attr('disabled', 'disabled');
$('#submit_button').val('wait...');
},
success:function(data)
{
$('#submit_button').attr('disabled', false);
if(data.error != '')
{
$('#form_message').html(data.error);
$('#submit_button').val('Add');
}
else
{
$('#studentModal').modal('hide');
$('#message').html(data.success);
dataTable.ajax.reload();

setTimeout(function(){

$('#message').html('');

}, 5000);
}
}
})
}
});

$(document).on('click', '.edit_button', function(){

var student_id = $(this).data('id');

$('#student_form').parsley().reset();

$('#form_message').html('');

$.ajax({

url:"student_action.php",

method:"POST",

data:{student_id:student_id, action:'fetch_single'},

dataType:'JSON',

success:function(data)
{

$('#class_id').val(data.class_id);
$('#student_name').val(data.student_name);
$('#student_roll_no').val(data.student_roll_no);
$('#student_email_id').val(data.student_email_id);
$('#student_gender').val(data.student_gender);
$('#student_dob').val(data.student_dob);

$('#modal_title').text('Edit Student Details');

$('#action').val('Edit');

$('#submit_button').val('Edit');

$('#studentModal').modal('show');

$('#hidden_id').val(student_id);

}

})

});

$(document).on('click', '.status_button', function(){
var id = $(this).data('id');
var status = $(this).data('status');
var next_status = 'Enable';
if(status == 'Enable')
{
next_status = 'Disable';
}
if(confirm("Are you sure you want to "+next_status+" it?"))
{

$.ajax({

url:"student_action.php",

method:"POST",

data:{id:id, action:'change_status', status:status, next_status:next_status},

success:function(data)
{

$('#message').html(data);

dataTable.ajax.reload();

setTimeout(function(){

$('#message').html('');

}, 5000);

}

})

}
});

$(document).on('click', '.delete_button', function(){

var id = $(this).data('id');

if(confirm("Are you sure you want to remove it?"))
{

$.ajax({

url:"student_action.php",

method:"POST",

data:{id:id, action:'delete'},

success:function(data)
{

$('#message').html(data);

dataTable.ajax.reload();

setTimeout(function(){

$('#message').html('');

}, 5000);

}

})

}

});

});
</script>