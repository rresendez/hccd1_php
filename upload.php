<!DOCTYPE html>
<html>
<head>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>
  <form action="/upload.php" method="post" enctype="multipart/form-data" name="form">
    <div class="row" align="center" style="padding:400px; margin-left:200px;">
   <div class="col-md-6 mb-3">
    <label for="form-control"> Select file to upload:</br></br>
    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"></br></br>
   <button type="submit" class="btn btn-primary">Submit</button>
   <div>
  </form>

  <?php
if(!empty($_FILES)){
  $target_dir = "./hcdd1.org/page/pdfs/";

  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {

          $uploadOk = 1;

  }
  // Check if file already exists
  if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "pdf"  ) {
      echo "Sorry, only PDF's are allowed.";
      $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

          $servername = "localhost";
          $username = "root";
          $password="B@ckd00r";
          $dbname= "time";
          $name= $_FILES["fileToUpload"]["name"];

          $conn = new mysqli($servername,$username,$password,$dbname);
          if($conn->connect_error){
            die("Connection failed ". $conn->connect_error);
          }
          $string = ltrim($target_file, '.');
          $sql = "INSERT INTO pdfs (name,path) VALUES ('$name','$string')";

          if(mysqli_query($conn,$sql)){
            echo "New record created successfully</br>";
          }else{
            echo "Error: ". $ssql . "</br>". mysqli_error($conn);
          }
          mysqli_close($conn);





          echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
          echo "Sorry, there was an error uploading your file.";
      }
  }
}

  ?>

</body>
</html>
