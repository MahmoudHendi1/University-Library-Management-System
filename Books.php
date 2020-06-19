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
    <h2>Books Search</h2>
    <div class="form-group pull-left">
        <input type="text" class="search form-control" id="searchInput" placeholder="By Book Name or Book Author">
        <input type="button" class="btn btn-primary" value="Search" onclick="getBooks('search',$('#searchInput').val(),<?php echo $_SESSION['UserData']['user_type']?>)"/>
    </div>
    <div class="form-group pull-right">
        <select class="form-control" onchange="getBooks('sort',this.value,<?php echo $_SESSION['UserData']['user_type']?>)">
          <option value="">Sort By</option>
          <option value="asc">Ascending</option>
          <option value="desc">Descending</option>
          <option value="borrowed">Borrowed</option>
          <option value="Not_borrowed">Not Borrowed</option>
        </select>
    </div>
    <div class="loading-overlay" style="display: none;"><div class="overlay-content">Loading.....</div></div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>ISBN</th>
                <th>Publication year</th>
                <th>Author</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="booksData">
            <?php
                include 'inc\DB.php';
                $db = new DB();
                $Books = $db->getRows('book',array('order_by'=>'id DESC'));
                if(!empty($Books)){ $count = 0;
                    foreach($Books as $book){ $count++;
            ?>
            <tr>
                <td><?php echo $book['ID']; ?></td>
                <td><?php echo $book['name']; ?></td>
                <td><?php echo $book['ISBN']; ?></td>
                <td><?php echo $book['publication_year']; ?></td>
                <td><?php echo $book['author']; ?></td>
                <td><?php echo ($book['is_borrowed'] == 1)?'Borrowed':'<button type="button" class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#Borrowing_modal'.$book['ID'].'">Borrow</button>';
                        if($_SESSION['UserData']['user_type']!=1)echo'<button type="button" class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#editBook_modal'.$book['ID'].'">Edit</button>';
                        echo '</td>';
                    ?></td>
                <?php ;?>
                </tr>
               
               
               <?php 
               echo '<div class="modal fade" role="dialog" id="Borrowing_modal'.$book['ID'].'">
               <div class="modal-dialog">
               <div class="modal-content">
                   <form action="submit.php" method="post" name="Borrowing_form" id="Borrowing_form" autocomplete="off">
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
      
               <button type="submit" class="btn btn-lg btn-success btn-block">Borrow</button>

                            </div>
                    </form>
                    

                        </div>
                    </div>
                </div>';

    if($_SESSION['UserData']['user_type']!=1){
 
        echo '<div class="modal fade" role="dialog" id="editBook_modal'.$book['ID'].'">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="submit.php" method="post" class = "editBook_form" name="editBook_form" id="editBook_form'.$book['ID'].'" autocomplete="off">
          <div class="modal-body">
          <div class="form-group">
                <label for="ID">ID</label>
                <input type="text" name="ID" value="'.$book['ID'].'"id="ID" class="form-control" 
                 autofocus readonly>
            </div>
             <div class="form-group">
                <label for="Name">Name</label>
                <input type="text" name="Name" value="'.$book['name'].'"id="Name" class="form-control" 
                required pattern=".{2,100}" title="min 2 characters." autofocus>
            </div>
            <div class="form-group">
                <label for="ISBN">ISBN</label>
                <input type="text" name="ISBN" value="'.$book['ISBN'].'"id="ISBN" class="form-control" 
                required pattern=".{2,100}" title="min 2 characters." autofocus>
            </div>
            <div class="form-group">
                <label for="publication_year">publication year</label>
                <input type="number" name="publication_year" value="'.$book['publication_year'].'"id="publication_year"  class="form-control" required>
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" value="'.$book['author'].'"id="author"  class="form-control" required>
            </div>
                <div id="display_error" class="alert alert-danger fade in display_error"></div><!-- Display Error Container -->
            </div>

        <div class="modal-footer">
       
        <button type="submit" class="btn btn-lg btn-primary btn-block">Edit</button>

        </div>
        </form>
        

         </div>
      </div>
    </div>';
    }
    ?>       
            
            <?php } }else{ ?>
            <tr><td colspan="5">No user(s) found...</td></tr>
            <?php } ?>
            
        </tbody>
    </table>

    
</div>





<!-- /container -->
<?php require('include/footer.php');?>