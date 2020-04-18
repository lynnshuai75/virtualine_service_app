<?php include ('includes/header.php'); 
      include("includes/config.php"); ?>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-2 col-sm-12"></div>
        
            <div class="col-md-8 col-sm-12">
         
               <div class="text-center" style="padding: 5px; background-color: white; border: 1px solid #ddd;"><b> <h3>Your Messages </h3> </b>
             
               </div><br>
               <a href="user-account.php" type="button" class="btn btn-default pull-right">Back To Account</a><hr style="border-color: #6c1f74"> 
                <?php
                $db = new Database;
                $client_email = $_SESSION['user_data']['email'];
                $rep_id=0;
                $db->query("SELECT * FROM msgs WHERE client_email =:email AND reply_id =:replyid ");
                $db->bind(':email', $client_email, PDO::PARAM_STR);
                $db->bind(':replyid', $rep_id, PDO::PARAM_INT);
                $fetch_msgs = $db->fetchMultiple();

              ?>
                
              <?php foreach($fetch_msgs as $msg) { ?> 
                <section id="contact" class="grey_section" style="padding: 20px; border: 1px solid #ddd; background-color: #fff;">
                    <!--<div class="container"> container disabled-->
                        <div class="row">                                      
                            <div class="widget col-md-12 to_animate">
                               <h5>From : <?= $msg['admin_email'] ?> </h5> <h5>Date: <?= $msg['date'] ?> </h5> <h5>To : You |<?= $msg['id'] ?>  </h5><hr>
                               <button type="button" id="deleteMsg" class="btn btn-danger pull-left" data-toggle="modal" data-target="#deleteMessage<?php echo $msg['id']; ?>">
                                    Delete Message   
                                </button>
                            <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#showMessage<?php echo $msg['id']; ?>">
                                     Follow Update
                                </button>
                                               
                           
                            </div>

                        </div>
                    <!--<div"> container disabled-->
                </section> <br>
             
             
<!-- The Modal -->
<div class="modal" id="showMessage<?php echo $msg['id']; ?>">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title"> Messages </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

  <span>Message Body</span>
  <div class="well well-sm"> <?= $msg['msg']; ?> </div>

  <?php
    $client_email = $_SESSION['user_data']['email'];
    $reply_id      = $msg['id'];
    $db->query("SELECT * FROM msgs WHERE client_email =:email AND reply_id =:replyId");
    $db->bind(':email', $client_email, PDO::PARAM_STR);
    $db->bind(':replyId', $reply_id, PDO::PARAM_INT);
    $fetch_replies = $db->fetchMultiple();
  ?>

  <?php foreach($fetch_replies as $reply)  { ?>
  <span>Reply </span>
  <div class="well well-sm"> <?php echo $reply['msg'];  ?>  <span class="pull-right"> - <?php echo $reply['notify'];  ?></span> </div>
 <?php  } ?>


  <form action="" method="post">
  <div class="form-group">
    <div>
  <span>Reply Message</span>
  <textarea name="reply_msg" id="" class="form-control" cols="4" rows="3" required></textarea>
  </div>
  <div>
   <input type="hidden" value="<?= $_SESSION['user_data']['fullname']; ?>" name="client_name">
   <input type="hidden" value="<?= $reply_id ; ?>" name="msg_id">
  </div><br>
  <button class="btn btn-primary" name="reply"> Reply</button>
  </div>
  </form>
                            
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<!--  ******* The Modal to delete Message ***  -->
<div class="modal" id="deleteMessage<?php echo $msg['id']; ?>">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-center text-danger"> Confirm Delete </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="" method="post" role="form">
         <p class="text-center text-danger">Are you sure you want to delete all Messages </p><br><hr>
        <input type="hidden" value="<?php echo $msg['id']; ?>" name="deleteId">
        <button type="submit" name="yesdelete" class="btn btn-danger"> Go ahead and Delete</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">No Thanks</button>
        
        </form>
      </div>


    </div>
  </div>
</div>
<?php  } ?> 
<?php
if(isset($_POST['reply'])){
    $msg  = addslashes($_POST['reply_msg']);
    $name     = $_POST['client_name'];
    $msg_id    =$_POST['msg_id'];
    $c_email  = $client_email;

    $db->query("INSERT INTO msgs (id, client_email, client_name, msg, notify, reply_id ) VALUES
     (NULL, :clientemail, :clientname, :msg, :notify, :replyid)");

    $db->bind(':clientemail', $c_email, PDO::PARAM_STR);
    $db->bind(':clientname', $name, PDO::PARAM_STR);
    $db->bind(':msg', $msg, PDO::PARAM_STR);
    $db->bind(':notify', $name, PDO::PARAM_STR);
    $db->bind(':replyid', $msg_id, PDO::PARAM_INT);

   $run =  $db->execute();
   if($run){
       redirect('my-messages.php');

   }
    
}

?>
<?php
if(isset($_POST['yesdelete'])){
    $delete_id = $_POST['deleteId'];
    $db->query("DELETE FROM msgs WHERE id =:id");
    $db->bind(':id',$delete_id, PDO::PARAM_INT);
 
  
    $run = $db->execute();
    if($run){
        $db->query("DELETE FROM msgs WHERE reply_id =:replyid");
 
        $db->bind(':replyid',$delete_id, PDO::PARAM_INT);
    
        $run = $db->execute();
        redirect('my-messages.php');
    }
}

?>
            <div class="col-md-2 col-sm-12">
               
                
            </div>  
        
        </div><!--col 8 -->
    </div><!--main row-->
</div>    <br><br><br><br>


        <!-- libraries -->
        <script src="js/vendor/jquery-1.11.1.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/vendor/jquery.appear.js"></script>

        <!-- superfish menu  -->
        <script src="js/vendor/jquery.hoverIntent.js"></script>
        <script src="js/vendor/superfish.js"></script>
        
        <!-- page scrolling -->
        <script src="js/vendor/jquery.easing.1.3.js"></script>
        <script src='js/vendor/jquery.nicescroll.min.js'></script>
        <script src="js/vendor/jquery.ui.totop.js"></script>
        <script src="js/vendor/jquery.localscroll-min.js"></script>
        <script src="js/vendor/jquery.scrollTo-min.js"></script>
        <script src='js/vendor/jquery.parallax-1.1.3.js'></script>

        <!-- widgets -->
        <script src="js/vendor/jquery.easypiechart.min.js"></script><!-- pie charts -->
        <script src='js/vendor/jquery.countTo.js'></script><!-- digits counting -->
        <script src="js/vendor/jquery.prettyPhoto.js"></script><!-- lightbox photos -->
        <script src='js/vendor/jflickrfeed.min.js'></script><!-- flickr -->
        <script src='twitter/jquery.tweet.min.js'></script><!-- twitter -->

        <!-- sliders, filters, carousels -->
        <script src="js/vendor/jquery.isotope.min.js"></script>
        <script src='js/vendor/owl.carousel.min.js'></script>
        <script src='js/vendor/jquery.fractionslider.min.js'></script>
        <script src='js/vendor/jquery.flexslider-min.js'></script>
        <script src='js/vendor/jquery.bxslider.min.js'></script>

        <!-- custom scripts -->
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

<?php include ('includes/footer.php'); ?>