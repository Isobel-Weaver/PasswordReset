<!doctype html>
<?php if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  include '../connect.php';
  try{
    $sth=$db->prepare("SELECT imageName FROM products WHERE productId = :id");
    $sth->bindparam(':id', $_GET['product_id'], PDO::PARAM_STR, 10);
    $sth->execute();
    $img=str_replace("jpg", "png",$sth->fetch(PDO::FETCH_ASSOC));
    if(is_array($img))
          {
            $img = implode($img);
          }
  }
  catch(PDOException $ex)
  {
    ?>
    <p>Sorry, a database error occurred.<p>
    <p>Error details: <em> <?= $ex->getMessage() ?></em></p>
    <?php

  }
?>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Drag and drop furniture into room.</title>
      <link rel="stylesheet" href="dragAndDrop.css">
      <link rel="stylesheet" type="text/css" href="../css/product.css">
    </head>
    <body>
    <div class="colour">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
        <link rel="stylesheet" href="https://use.typekit.net/maf1fpm.css">
        </a>
    </div>
    <section>
        <div class="topnav">
            <nav>
                <h1 class="logo">Furniche</h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="../basket/basket.php">Basket</a></li>
                    <li><a href="loginview.php">Login</a></li>
                    <li><a href="signUpPage.php">Sign up</a></li>
                    <li><a href="history.php">Previous Orders</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="aboutus.php">About Us</a></li>
                    <?php
                    session_start();
                    if (isset($_SESSION['user'])) {
                        echo '<li><a href="#">' . $_SESSION['user'] . '</a>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </section>
  <div class="heading">
        <h2>Upload an image of your room to see how the furniture would look</h2>
                  </div>
                  <div class="image">
        <input type="file" id="inpimage">
        <img src="" id="displayImg">
        <?php
        echo '<div id="drag1"> <img id="img1" src="../PicturesForDragAndDrop/'.$img.'"></div>'; ?> </div>
        <script>
        
          
// Make the DIV element draggable:
imageDiv = document.getElementById("drag1");
dragElement(imageDiv);

function dragElement(imageDiv) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
      imageDiv.onmousedown = dragMouseDown;
}

  function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    document.onmousemove = elementDrag;
  }

  function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    imageDiv.style.top = (imageDiv.offsetTop - pos2) + "px";
    imageDiv.style.left = (imageDiv.offsetLeft - pos1) + "px";
  }

  function closeDragElement() {
    document.onmouseup = null;
    document.onmousemove = null;
  }
  var inpImg = document.getElementById("inpimage"),
    displayImg = document.getElementById("displayImg");
    
inpImg.addEventListener("change", function() {
  changeImage(this);
});

function changeImage(input) {
  var reader;

  if (input.files && input.files[0]) {
    reader = new FileReader();

    reader.onload = function(e) {
      displayImg.setAttribute('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}
        </script>
     
         
        </body>
        <?php } ?>
        </html>