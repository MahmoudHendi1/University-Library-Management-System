
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Web Project</title>
   

</head>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">University Library Management</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Home</a></li>
      <li><a href="Books.php">Books</a></li>
      <li><a href="#">About</a></li>
      <?php 
      if (isset($_SESSION['UserData'])) {
       echo ' <li><a href="logout.php">Logout</a></li>';
       }
       ?>
     
      

 
    </ul>
  </div>
</nav>
<body>