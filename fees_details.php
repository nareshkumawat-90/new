  <?php 
  include 'header.php';
  include_once 'db.php';

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
                  <h2>Student Fees Details</h2>
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
                    <li><li>
                      <input type=button class="btn btn-primary" onClick="location.href='student_form.php'" value='add'>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="text-left" style="margin-top: 20px;" class="col-md-3">
                      <form method="POST" action="">Show
                        <select name="limit-records" id="limit-records">
                          <option value="1" selected="selected">1</option>
                        </select>
                        entries
                      </form>
                    </div>
                    
                    <div class="form-group">
                      <div class="col-md-3 col-sm-3 col-xs-6 navbar-right">
                        <div class="input-group float-right">
                          <input type="text" name="search_text" id="search_text" class="form-control col-md-12 col-xs-12" value="" />
                          <span class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
                        </div>
                      </div>
                    </div>
                    <div id="result" class="x_content">
                      
                     
                    </div>
                  </div>
                </div>
              </div>           
            </div>
          </div>
          <!-- /page content -->

          <!-- footer content -->
          <?php include 'footer.php';?>
          <script>
            $(document).ready(function(){

              load_data(1);

              function load_data(page, query = '')
              {
                $.ajax({
                  url:"fee_fetch.php",
                  method:"POST",
                  data:{page:page, query:query},
                  success:function(data)
                  {
                    $('#result').html(data);
                  }
                });
              }

              $(document).on('click', '.page-link', function(){
                var page = $(this).data('page_number');
                var query = $('#search_box').val();
                load_data(page, query);
              });

              $('#search_text').keyup(function(){
                var query = $('#search_text').val();
                load_data(1, query);
              });

            });
          </script>