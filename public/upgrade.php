<?php include_once ('includes/header.php'); 
      include_once('includes/config.php');

      if(!isset($_SESSION['user_logged_in'] )) {
        redirect('login.php');
      }   

if(isset($_GET['page'])) { 
  $pages = $_GET['page'];

}
        
?>

<div class="container">
 
        <div>
            <header class="section_header">
                <h4> Purchasing Services </h4><hr>
              
            </header>
        <div class="col-md-6 col-md-offset-3">
        <?php  if($pages == 1) { ?>
       
                <form action="upgrade.php?page=2" method="post"   class="form-horizontal" role="form">
               
                    <div class="form-group">
                        <label class="" for="reason">Select Package:</label>
                         <div class="">
                             <select class="form-control" id="" name="package" required>
                                <option value="" selected disabled>-Toggle List- </option>
                                <?php
                                $db = new Database;
                                $db->query("SELECT * FROM products");
                               $result_products = $db->fetchMultiple();
                               foreach($result_products as $product) {

                                ?>

                                <option value="<?php echo $product['item_name'] ?> "><?php echo $product['item_name'] ?> </option>
                               <?php } ?>
                             </select>
                          </div>
                    </div>

                      
                    <div class="form-group">
                      <div class="">
                        <button type="submit" class="btn btn-primary" name="make_purchase"> Proceed  </button>
                        <a href="upgrade.php?page=1" class="btn btn-danger pull-right" style="color: #fff;"> Start Over</a><hr>
                      </div>
                    </div>

                   
            </form>
          <?php  } ?>
<!-- ************************ 2nd form *******  --> 
<?php if($pages == 2) { ?>
      <?php
       //** Process Update  */

        if(isset($_POST['make_purchase'])) { 

        $selected_package            = $_POST['package'];
        $_SESSION['selected_package'] = $selected_package;

        $_SESSION['item_desc']        = $result_product['item_desc'];
        $_SESSION['item_price']       = $result_product['item_price'];

        }
        ?>
       
          <form action="upgrade.php?page=3" method="post"   class="form-horizontal" role="form">
              <span> Below is your Item Details</span><hr>
              <?php
                $db = new Database;
              $package  =  $_SESSION['selected_package'];
              $db->query("SELECT * FROM products WHERE item_name =:package");
              $db->bind(':package', $package, PDO::PARAM_STR);
              $result_product = $db->fetchSingle();

              ?>
               <div class="form-group">
                      <label class="" for="name">Item Name</label>
                      <div class="">
                        <input type="text" class="form-control" name="product_name" placeholder="<?php echo $_SESSION['selected_package']; ?>" value="<?php echo $_SESSION['selected_package']; ?>" disabled>
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="" for="item_desc"> Item Description </label>
                      <div class="">
                        <input type="text" class="form-control" name="item_desc" placeholder="<?php echo $_SESSION['item_desc']; ?>" value="<?php echo $_SESSION['item_desc']; ?>" disabled>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="" for="item_price"> Item Price </label>
                      <div class="">
                        <input type="text" class="form-control" name="item_price" placeholder="<?php echo $_SESSION['item_price'] ; ?>" value="<?php echo $_SESSION['item_price'] ; ?>" disabled>
                      </div>
                    </div>                   
                    <div class="form-group">
                      <div class="">
                        <button type="submit" class="btn btn-primary" name="checkout"> Checkout  </button>
                        <a href="upgrade.php?page=1" class="btn btn-danger pull-right" style="color: #fff;"> Start Over </a><hr>
                      </div>
                    </div>

                   
            </form>
          <?php  } ?>

          <?php if($pages == 3) { ?>

 <div class="table-responsive"> 
 <?php
   if(isset($_POST['checkout'])){
     $item_name   = $_SESSION['selected_package'];
     $item_desc   = $_SESSION['item_desc'];
     $item_price  = $_SESSION['item_price'];
?>
 <h2 class="text-center"> Order Summary</h2>
<table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th> Item Name </th>
        <th> Item Description </th>
        <th> Item Price </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><br> <?php echo $item_name; ?> </td>
        <td> <?php echo  $item_desc; ?> </td>
        <td><br><br>$ <?php echo $item_price; ?> </td>
      </tr>
     
    </tbody>
  </table>
  </div>

          <?php } ?>
                   
                    <br />

                             
            </div>
                                    
       </div>
</div><!--page content-->

<?php include('includes/footer.php'); ?>