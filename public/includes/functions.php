<?php 

/********Start of Helper Functions***********/
//Function to trim values

function clean($value){        //$username  = clean($_POST['username']);
    
    return trim($value);
    
}



//Function to sanitize strings

function santize($raw_value){       //$username  = santize($_POST['username']);
    
    return filter_var($raw_value, FILTER_SANITIZE_STRING);
    
}



//Function to validate email
function val_email($raw_email){         //$clean_email  = val_email($_POST['email']);
    
    return filter_var($raw_email, FILTER_VALIDATE_EMAIL);
    
}



//function to validate int

function val_int($raw_int){      //$cl_age  = val_int($_POST['age']);
    
    return filter_var($raw_int, FILTER_VALIDATE_INT);
    
}



//Function to hash passwords

function hash_pwd($raw_password){   //$hashed_password  = hash_pwd($_POST['password']);
    
    return md5($raw_password);
    
}



//Function to redirect

function redirect($url){            //redirect(index.php);
    
    return header("Location: {$url}");
    
}




//Function to display session messages

function set_msg($msg){                 //"Welcome to your account"; 
    
    if(empty($msg)){
        
        $msg = "";
        
    }else{
        
        $_SESSION['setmsg'] = $msg;
        
    }
    
}


function display_msg(){
    
    if(isset($_SESSION['setmsg'])){
        
        echo $_SESSION['setmsg'];
        
        unset($_SESSION['setmsg']);
    }
    
    
}

//** Process Registration  */
 function process_registration(){

    if(isset($_POST['submit_registration'])) {

        $raw_name      = clean($_POST['name']);
        $raw_sex      = clean($_POST['sex']);
        $raw_email      = clean($_POST['email']);
        $raw_password      = clean($_POST['password']);

        $cl_name           = santize($raw_name);
        $cl_sex            = santize($raw_sex);
        $cl_email          = val_email($raw_email);
        $cl_password       = santize($raw_password);

        //hashed Password
        $hashed_password   = hash_pwd($cl_password);

        // check for the right image
        $allowed_image     = array('png', 'jpg', 'jpeg');
        $raw_image         = $_FILES['image']['name'];

        $image_ext         = pathinfo($raw_image, PATHINFO_EXTENSION);

        if(!in_array($image_ext, $allowed_image)){

            redirect('register.php');
             
            set_msg('<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong> Warning! </strong> This file type is not allowed.  
                 </div>');

        }
         else {
             //** Attache a random value between 1000 to 100000 to the file */
             $new_image    = rand(1000, 100000)."_".$_FILES['image']['name'];

             //** Temporary folder  */
             $tmp_folder   = $_FILES['image']['tmp_name'];

             //** will change the filename to lower case */
             $new_image_name   = strtolower($new_image);
             $cl_image         = str_replace('', '_', $new_image_name);
             $folder           = "uploaded_image/";

             //** Check if user exist in the database */
             require_once('pdo.php');
             //** Instatiate the object from the Database class */
             $db = new Database;
             $db->query("SELECT id FROM users WHERE email =:email");
             $db->bind(':email',$cl_email, PDO::PARAM_STR);
             $get_user = $db->fetchSingle();

             if($get_user > 0){
                   redirect('login.php');
                  set_msg('<div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Hi! You have already registered. Please login </strong> 
                        </div>');
             }
             elseif(move_uploaded_file($tmp_folder, $folder.$cl_image)){

            
                 $db->query('INSERT INTO users(id, fullname, sex, password, image, email) VALUES (NULL, :fullname, :sex, :password,  :image, :email)');
                 $db->bind(':fullname',$cl_name, PDO::PARAM_STR);
               
                 $db->bind(':sex',$cl_sex, PDO::PARAM_STR);
                 $db->bind(':password',$hashed_password, PDO::PARAM_STR);
                 $db->bind(':image',$cl_image, PDO::PARAM_STR);
                 $db->bind(':email',$cl_email, PDO::PARAM_STR);

                
                 $run  = $db->execute();
                 if($run){

                    redirect('login.php');
                    set_msg('<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Bravo! Your registration was Successful. You can now login </strong> 
                        </div>');

                 }
                  else{
                        redirect('register.php');
                         set_msg('<div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong> Registration failed. Try again later </strong> 
                                </div>');


                  }

             }
              else{
                redirect('register.php');
                set_msg('<div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Sorry! Failed to upload File  </strong> 
                    </div>');

              }
         }
    }

} // 


//** ===================  Process login ==================== */

function process_login(){
    if(isset($_POST['submit_login'])){
       
        $raw_email         = clean($_POST['email']);
        $raw_password      = clean($_POST['password']);

        $cl_email          = val_email($raw_email);
        $cl_password       = santize($raw_password);
        //hashed the password
        $hashed_password  = hash_pwd($cl_password);
         
        require_once('pdo.php');
                
        //Instatiating our object from the dbase class
        $db     = new Database;

        $db->query("SELECT * FROM users WHERE email=:email AND password=:password");
        $db->bind(":email", $cl_email, PDO::PARAM_STR);
        $db->bind(":password", $hashed_password, PDO::PARAM_STR);

        $get_user = $db->fetchSingle();

        if($get_user > 0){

                $image_name   = $get_user['image'];
                $image_path   = "<img src='uploaded_image/$image_name' class='profile_image' />";
                 //** user exist */
                 redirect('user-account.php');

                 $_SESSION['user_logged_in']  = true;
                 $_SESSION['user_data']      = array(
                     'fullname' => $get_user['fullname'],
                     'id'       => $get_user['id'],
                     'email'    => $get_user['email'],
                     'image'    => $image_path
                 );
        }
         else{
            redirect('login.php');
                    
            set_msg('<div class="alert alert-danger text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Failed!</strong> Your login credentails do not match. Please try again.
                    </div>');
         }

    }
}
?>