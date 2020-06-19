<?php
/*PHP Login and registration script Version 1.0, Created by Gautam, www.w3schools.in*/

require('inc/config.php');
require('inc/functions.php');

/*Check for authentication otherwise user will be redirects to main.php page.*/
if (!isset($_SESSION['UserData'])) {
    exit(header("location:main.php"));
}
require('include/header.php');
?>

<!-- container -->
<div class="container">
<H1>User Profile</H1>
    <!-- user account data -->
    <form action="submit.php" method="post" name="edit_form" id="edit_form" autocomplete="off">
            <div class="form-group">
                <label for="Name">Full name</label>
                <input type="text" name="Name" id="Name"  value="<?php echo get_user_data($con, $_SESSION['UserData']['user_id'])['username']?>" class="form-control" 
                required pattern=".{2,100}" title="min 2 characters." autofocus readonly>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="Email" id="Email" value="<?php echo get_user_data($con, $_SESSION['UserData']['user_id'])['email']?>" class="form-control" required 
                readonly>
            </div>
            <div class="form-group">
                <label for="Password">New password</label>
                <input type="password" name="Password" id="Password" class="form-control" required pattern=".{6,12}" title="6 to 12 characters." readonly>
                <button type="button" class="btn  btn-lg  btn-default" onclick="passwordToggle(event)">Change password</button>

            </div>
                <div id="display_error" class="alert alert-danger fade in"></div><!-- Display Error Container -->

    
    
        <input type="submit" class="btn btn-lg btn-success" value="Submit" id="submit">

        <button type="button" class="btn  btn-lg  btn-default" onclick="inputToggle(event)">Edit</button>
   
        </form>
    
    <?php 
    if($_SESSION['UserData']['user_type']!=1){
        
        echo '<button type="button" class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#addBook_modal">Add Book</button>
        <div class="modal fade" role="dialog" id="addBook_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="submit.php" method="post" name="addBook_form" id="addBook_form" autocomplete="off">
          <div class="modal-body">
             <div class="form-group">
                <label for="Name">Name</label>
                <input type="text" name="Name" id="Name" class="form-control" 
                required pattern=".{2,100}" title="min 2 characters." autofocus>
            </div>
            <div class="form-group">
                <label for="ISBN">ISBN</label>
                <input type="text" name="ISBN" id="ISBN" class="form-control" 
                required pattern=".{2,100}" title="min 2 characters." autofocus>
            </div>
            <div class="form-group">
                <label for="publication_year">publication year</label>
                <input type="number" name="publication_year" id="publication_year"  class="form-control" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author"  class="form-control" required>
            </div>
                <div id="display_error" class="alert alert-danger fade in"></div><!-- Display Error Container -->
            </div>

        <div class="modal-footer">
       
        <button type="submit" class="btn btn-lg btn-primary btn-block">Add</button>

        </div>
        </form>
        

         </div>
      </div>
    </div>';
    }
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Borrowed Date</th>
                <th>Expiry_date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="booksData">
     <?php
                $con=mysqli_connect("localhost","root","","library"); /*Database Connection*/
                $user_id =$_SESSION['UserData']['user_id'];
                $Books = mysqli_query($con, "SELECT * FROM library.borrowed_books
                JOIN library.book on borrowed_books.book_id = book.id 
                WHERE borrowed_books.user_id='$user_id'  ");

                if(!empty($Books)){ $count = 0;
                    foreach($Books as $book){ $count++;
            ?>
            <tr>
                <td><?php echo $book['name']; ?></td>
                <td><?php echo $book['borrowed_date']; ?></td>
                <td><?php echo $book['expiry_date']; ?></td>
                <td><?php echo '<button type="button" id="'.$book['book_id'].'" name ="Delet" class="btn btn-lg btn-info btn-block delet"  >Return</button></td>'; ?></td>
                <td><?php echo '<button type="button" class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#Extend_modal'.$book['ID'].'">Extend</button></td>'; ?></td>
                 <?php 
                 echo '<div class="modal fade" role="dialog" id="Extend_modal'.$book['ID'].'">
                 <div class="modal-dialog">
                 <div class="modal-content">
                     <form action="submit.php" method="post" name="Extend_form" id="Extend_form" class="'.$book['ID'].'" autocomplete="off">
                     <div class="modal-body">
                     
                     <div class="form-group"> <!-- Date input -->
                         <label for="ID">ID</label>
                         <input type="text" name="ID" value="'.$book['ID'].'"id="ID" class="form-control" autofocus readonly>
  
                         <label class="control-label" for="date">Date</label>
                         <input class="form-control" id="date" name="date" placeholder="MM/DD/YYY" type="text"/>
                     </div>
                             <div id="display_error" class="alert alert-danger fade in"></div><!-- Display Error Container -->
                     </div>
  
                     <div class="modal-footer">
        
                 <button type="submit"  class="btn btn-lg btn-success btn-block extend">Extend</button>
  
                              </div>
                      </form>
                      
  
                          </div>
                      </div>
                  </div>';
                  ?>       
                </tr>
                <?php  } }else{ ?>
            <tr><td colspan="5">No user(s) found...</td></tr>
            <?php } ?>
            
        </tbody>
    </table>
    

</div>
<!-- /container -->
<?php require('include/footer.php');?>