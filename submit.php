<?php
if((empty($_SERVER['HTTP_X_REQUESTED_WITH']) or strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') or empty($_POST)){/*Detect AJAX and POST request*/
  exit("Unauthorized Accessss");
}
require('inc/config.php');
require('inc/functions.php');

/* Check Login form submitted */
if(!empty($_POST) && $_POST['Action']=='login_form'){

    /* Define return | here result is used to return user data and error for error message */
    $Return = array('result'=>array(), 'error'=>'');
    $email = safe_input($con, $_POST['Email']);
    $password = safe_input($con, $_POST['Password']);

    /* Server side PHP input validation */
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $Return['error'] = "Please enter a valid Email address.";
    }elseif($password===''){
        $Return['error'] = "Please enter Password.";
    }

    if($Return['error']!=''){
        output($Return);
    }

    /* Check Email and Password existence in DB */
    $result = mysqli_query($con, "SELECT * FROM library.users WHERE email='$email' AND password='".md5($password)."' LIMIT 1");
    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_assoc($result);
        /* Success: Set session variables and redirect to Protected page */
        $Return['result'] = $_SESSION['UserData'] = array('user_id'=>$row['id'] ,'user_type'=>$row['userType'] );
    } else {
        /* Unsuccessful attempt: Set error message */
        $Return['error'] = 'Invalid Login Credential.';
    }
    /*Return*/
    output($Return);
}
/* Check Registration form submitted */
if(!empty($_POST) && $_POST['Action']=='registration_form'){
    /* Define return | here result is used to return user data and error for error message */
    $Return = array('result'=>array(), 'error'=>'');

    $name = safe_input($con, $_POST['Name']);
    $email = safe_input($con, $_POST['Email']);
    $password = safe_input($con, $_POST['Password']);
    $user_type = safe_input($con, $_POST['user_type']);

    /* Server side PHP input validation */
    if($name===''){
        $Return['error'] = "Please enter Full name.";
    }elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $Return['error'] = "Please enter a valid Email address.";
    }elseif($password===''){
        $Return['error'] = "Please enter Password.";
    }
    if($Return['error']!=''){
        output($Return);
    }

    /* Check Email existence in DB */
    $result = mysqli_query($con, "SELECT * FROM library.users WHERE email='$email' LIMIT 1");
    if(mysqli_num_rows($result)==1){
        /*Email already exists: Set error message */
        $Return['error'] = 'You have already registered with us, please login.';
    }else{
        /*Insert registration data to user table*/
        mysqli_query($con, "INSERT INTO users ( username, email, password,userType) values('$name', '$email', '".md5($password)."','$user_type' )");
        $result = mysqli_query($con, "SELECT id From users WHERE username='$name'");
        $row = mysqli_fetch_assoc($result);      

        /* Success: Set session variables and redirect to Protected page */
        $Return['result'] = $_SESSION['UserData'] = array('user_id'=>$row['id'], 'user_type'=>$user_type);
    }
    /*Return*/
    output($Return);
}
/* Check Edit User Info Form submitted */
if(!empty($_POST) && $_POST['Action']=='edit_form'){
    /* Define return | here result is used to return user data and error for error message */
    $Return = array('result'=>array(), 'error'=>'');

    $name = safe_input($con, $_POST['Name']);
    $email = safe_input($con, $_POST['Email']);
    $password = safe_input($con, $_POST['Password']);

    /* Server side PHP input validation */
    if($name===''){
        $Return['error'] = "Please enter Full name.";
    }elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $Return['error'] = "Please enter a valid Email address.";
    }
    
    if($Return['error']!=''){
        output($Return);
    }

    $user_id = $_SESSION['UserData']['user_id'];
    $userType = $_SESSION['UserData']['user_type'];
    
    /* Check Email existence in DB */
    $result = mysqli_query($con, "SELECT * FROM library.users WHERE email='$email' && id !=  $user_id LIMIT 1");
    if(mysqli_num_rows($result)==1){
        /*Email already exists: Set error message */
        $Return['error'] = 'The email is already used.';
    }else{
        /* Check whether to update password or not */
        if($password ===''){
            mysqli_query($con, "UPDATE library.users SET email = '$email', username = '$name' 
             WHERE id='$user_id' ");

        }else{
              mysqli_query($con, "UPDATE library.users SET email = '$email', username = '$name', password = '".md5($password)."' 
              WHERE id='$user_id' ");
        }
        /* Success: Set session variables and redirect to Protected page */
        $Return['result'] = array('user_id'=>$user_id ,'user_type'=>$userType );
    }
    /*Return*/
    output($Return);
}
/* Check Add Book form submitted */
if(!empty($_POST) && $_POST['Action']=='addBook_form'){
    /* Define return | here result is used to return Book data and error for error message */
    $Return = array('result'=>array(), 'error'=>'');

    $name = safe_input($con, $_POST['Name']);
    $ISBN = safe_input($con, $_POST['ISBN']);
    $publication_year = safe_input($con, $_POST['publication_year']);
    $author = safe_input($con, $_POST['author']);

    /* Server side PHP input validation */
    if($name===''){
        $Return['error'] = "Please enter Full name.";
    }elseif ($ISBN==='') {
        $Return['error'] = "Please enter ISBN.";
    }elseif($publication_year ===''){
        $Return['error'] = "Please enter The Publication year.";
    }elseif($author ===''){
        $Return['error'] = "Please enter The author.";
    }
    if($Return['error']!=''){
        output($Return);
    }
    /* Check Book existence in DB */
    $result = mysqli_query($con, "SELECT * FROM library.book WHERE name='$name' LIMIT 1");
    if(mysqli_num_rows($result)==1){
        /*Book already exists: Set error message */
        $Return['error'] = 'The book is already added.';
    }else{
        /*Insert book data in book table*/
        $book_id = mysqli_insert_id($con); /* Get the auto generated id used in the last query */
        mysqli_query($con, "INSERT INTO library.book (id, name, ISBN, publication_year, author) values('$book_id', '$name', '$ISBN','$publication_year', '$author' )");
        /* Success: Set session variables and redirect to Protected page */
        $Return['result'] = array('book_id'=>$book_id);
    }
    /*Return*/
    output($Return);
}
/* Check Edit Book Form submitted */
if(!empty($_POST) && $_POST['Action']=='editBook_form'){
    /* Define return | here result is used to return Book data and error for error message */

    $Return = array('result'=>array(), 'error'=>'');
   
    $name = safe_input($con, $_POST['Name']);
    $ISBN = safe_input($con, $_POST['ISBN']);
    $publication_year = safe_input($con, $_POST['publication_year']);
    $author = safe_input($con, $_POST['author']);
    $book_id = $_POST['ID'];
    /* Server side PHP input validation */
    if($name===''){
        $Return['error'] = "Please enter Full name.";
    }elseif ($ISBN==='') {
        $Return['error'] = "Please enter ISBN.";
    }elseif($publication_year ===''){
        $Return['error'] = "Please enter The Publication year.";
    }elseif($author ===''){
        $Return['error'] = "Please enter The author.";
    }
    if($Return['error']!=''){
        output($Return);
    }

    /* Check if there's a book with the same name in DB */
    $result = mysqli_query($con, "SELECT * FROM library.book WHERE name='$name' && ID != $book_id LIMIT 1");
    if(mysqli_num_rows($result)==1){
        /*Book already exists: Set error message */
        $Return['error'] = 'The book is already added.';
    }else{
        /*Edit book data in book table*/
        mysqli_query($con, "UPDATE library.book SET name = '$name', ISBN = '$ISBN', publication_year = '$publication_year', 
        author = '$author'
        WHERE ID= '$book_id' ");
        /* Success: Set session variables and redirect to Protected page */
        $Return['result'] = array('book_id'=>$book_id);
    }
    /*Return*/
    output($Return);
}
/* Check Return Book submitted*/
if(!empty($_POST) && $_POST['Action']=='Delet'){
    /* Define return | here result is used to return Book data and error for error message */
    $Return = array('result'=>array(), 'error'=>'');

    $book_id = safe_input($con, $_POST['book_id']);
    if($Return['error']!=''){
        output($Return);
    }
    /*Set book to be not borrowed*/
    mysqli_query($con, "UPDATE library.book SET is_borrowed = 0
    WHERE ID= '$book_id' ");
    mysqli_query($con, "DELETE FROM borrowed_books WHERE book_id= '$book_id'");
    output($Return);
}
/* Check Extend Book Form submitted */
if(!empty($_POST) && $_POST['Action']=='Extend_form'){
    /* Define return | here result is used to return Book data and error for error message */
    $Return = array('result'=>array(), 'error'=>'');

     $date = safe_input($con, $_POST['date']);
     $id = safe_input($con, $_POST['id']);
     /* Server side PHP input validation */
     $today_dt = new DateTime(date('d-m-Y'));
     $expire_dt = new DateTime($date);
 
     if($date==='' ){
         $Return['error'] = "pick a date";
     }else if( $expire_dt <$today_dt){
         $Return['error'] = "Choose a Valid Date";
 
     }
     if($Return['error']!=''){
         output($Return);
     }
     /* Check Book existence in DB */
        $today_date=date("Y-m-d");
         $date =date("Y-m-d",strtotime($date));
         mysqli_query($con, "UPDATE library.borrowed_books SET expiry_date = '$date' WHERE book_id = '$id'");
         /* Success: Set session variables and redirect to Protected page */
         $Return['result'] = array('book_id'=>$id);
     /*Return*/
     output($Return);
}
/* Check Borrowe Book Form submitted*/
if(!empty($_POST) && $_POST['Action']=='Borrowing_form'){
    /* Define return | here result is used to return Book data and error for error message */
    $Return = array('result'=>array(), 'error'=>'');

    $date = safe_input($con, $_POST['date']);
    $book_id = safe_input($con, $_POST['ID']);
    $user_id = $_SESSION['UserData']['user_id'];
    /* Server side PHP input validation */
    $today_dt = new DateTime(date('d-m-Y'));
    $expire_dt = new DateTime($date);

    if($date==='' ){
        $Return['error'] = "pick a date";
    }else if( $expire_dt <$today_dt){
        $Return['error'] = "Choose a Valid Date";

    }

    if($Return['error']!=''){
        output($Return);
    }
    /* Check Book existence in DB */
    $result = mysqli_query($con, "SELECT * FROM library.borrowed_books WHERE book_id='$book_id' LIMIT 1");
    if(mysqli_num_rows($result)==1){
        /*Book already exists: Set error message */
        $Return['error'] = 'The book is already borrowed.';
    }else{
        /*Insert borrowed book data in borrowed_books table*/
        $today_date=date("Y-m-d");
        $date =date("Y-m-d",strtotime($date));
        $true = 1;
        mysqli_query($con, "INSERT INTO library.borrowed_books (book_id, user_id, borrowed_date, expiry_date) values('$book_id', '$user_id', '$today_date','$date' )");
        mysqli_query($con, "UPDATE library.book SET is_borrowed = '$true' WHERE ID = '$book_id'");
 
        /* Success: Set session variables and redirect to Protected page */
        $Return['result'] = array('book_id'=>$book_id);
    }
    /*Return*/
    output($Return);
}

?>