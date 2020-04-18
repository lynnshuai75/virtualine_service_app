<?php include ('includes/header.php'); 
 include("includes/config.php") ?>
   

<div class="container">
 
        <div>
            <header class="section_header">
                <h4>Updating Your Profile</h4><hr>
                <?= display_msg(); ?>
            </header>
        <div class="col-md-6 col-md-offset-3">
       
                <form action="<?php $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
               
                    <div class="form-group">
                        <label class="" for="reason">Select Reason for Membership:</label>
                         <div class="">
                             <select class="form-control" id="" name="reason" required>
                                <option value="Not Defined">Select Reason</option>
                                <option value="Personal">Personal</option>
                                <option value="Family">Family</option>
                                <option value="Friend">Friend</option>
                                <option value="Contract">Contract</option>
                             </select>
                          </div>
                    </div>

                     <div class="form-group">
                      <label class="" for="name">Full Name</label>
                      <div class="">
                        <input type="text" class="form-control" name="fullname" placeholder="Enter Full Name" value="<?= $_SESSION['user_data']['fullname']; ?>">
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="" for="prof">Profession</label>
                      <div class="">
                        <input type="text" class="form-control" name="profession" placeholder="Enter Profession" value="">
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="" for="facebook">Facebook</label>
                      <div class="">
                        <input type="url" class="form-control" name="facebook" placeholder="Enter facebook url" value="">
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="" for="country">Country</label>
                      <div class="">
                        <input type="text" class="form-control" name="location" placeholder="Enter Country" value="">
                      </div>
                    </div>
                     <div class="form-group">
                      <label class="" for="text">Summary</label>
                      <div class="">
                          <textarea type="text" class="form-control" name="summary" value="" placeholder="In brief, about yourself" required></textarea>
                      </div>
                    </div>
                    <div class="form-group">

                      <label class="" for="file">Upload Medical Certification:</label>
                      <div class="">
                  
                        <input type="file" name="image" placeholder="Upload Profile Image" required />
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="">
                        <button type="submit" class="btn btn-primary" name="update_profile">Update Profile</button><a href="user-account.php" class="btn btn-danger pull-right" style="color: #fff;">Cancel</a><hr>
                      </div>
                    </div>

                   
            </form>

   <?php

     //** Process Update  */

  if(isset($_POST['update_profile'])) {

      $raw_name           = clean($_POST['fullname']);
      $raw_profession     = clean($_POST['profession']);
      $raw_facebook      = clean($_POST['facebook']);
      $raw_location      = clean($_POST['location']);
      $raw_summary      = clean($_POST['summary']);

       $cl_name         = santize($raw_name);
       $cl_profession    = santize($raw_profession);
       $cl_facebook      = santize($raw_facebook);
       $cl_location      = santize($raw_location);
       $cl_summary      = santize($raw_summary);
       $cl_reason        = santize($_POST['reason']);

       

      // check for the right image
      $allowed_files     = array('png', 'jpg', 'jpeg', 'pdf');
      $raw_file         = $_FILES['image']['name'];

      $file_ext         = pathinfo($raw_file, PATHINFO_EXTENSION);

      if(!in_array($file_ext, $allowed_files)){

          redirect('updateprofile.php');
           
          set_msg('<div class="alert alert-danger alert-dismissible fade show">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong> Warning! </strong> This file type is not allowed.  
               </div>');

      }
       else {
           //** Attache a random value between 1000 to 100000 to the file */
           $new_file    = rand(1000, 100000)."_".$_FILES['image']['name'];

           //** Temporary folder  */
           $tmp_folder   = $_FILES['image']['tmp_name'];

           //** will change the filename to lower case */
           $new_file_name   = strtolower($new_file);
           $cl_file         = str_replace('', '_', $new_file_name);
           $folder           = "user_files/";

           //** Check if user exist in the database */
           require_once('includes/pdo.php');
           //** Instatiate the object from the Database class */
           $db = new Database;
         
           $move = move_uploaded_file($tmp_folder, $folder.$cl_file);

           if($move){
             $profile_status = 1;
             $user_id        = $_SESSION['user_data']['id'];
             $db->query("UPDATE users SET fullname=:fullname, location=:location, reason=:reason, facebook_url=:facebook, profession=:profession, 
                           profile_status=:profile_status, summary=:summary, file=:file WHERE id=:id");
           
           $db->bind(':fullname', $cl_name, PDO::PARAM_STR);
           $db->bind(':location', $cl_location, PDO::PARAM_STR);
           $db->bind(':reason',  $cl_reason, PDO::PARAM_STR);
           $db->bind(':facebook',$cl_facebook, PDO::PARAM_STR);
           $db->bind(':profession', $cl_profession, PDO::PARAM_STR);
           $db->bind(':profile_status', $profile_status, PDO::PARAM_INT);
           $db->bind(':summary', $cl_summary, PDO::PARAM_STR);
           $db->bind(':file', $cl_file, PDO::PARAM_STR);
           $db->bind(':id', $user_id, PDO::PARAM_INT);

           $run  = $db->execute();
            if($run){
              
              redirect('user-account.php');
              set_msg('<div class="alert alert-success alert-dismissible fade show">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          <strong>Bravo! Your Profile  was Successfully Updated.   </strong> 
                     </div>');

            }
             else{
                  redirect('updateprofile.php');
                   set_msg('<div class="alert alert-danger alert-dismissible fade show">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                           <strong>Sorry! Your update was not successful  </strong> 
                          </div>');

             }

           }
            else{
                redirect('updateprofile.php');
                set_msg('<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Sorry! Failed to upload File  </strong> 
                    </div>');

            }

                       
           
       }
  }


  ?>

                   
                   
                   
                    <br />

                                
            </div>
                                    
       </div>

  
</div><!--page content-->

<?php include ('includes/footer.php'); ?>