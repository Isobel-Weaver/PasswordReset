<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Basket</title>
    <link rel="stylesheet" href="css/basket.css">
</head>
<body>

<h2>Your Basket</h2>

<?php
$dsn = "mysql:host=localhost;dbname=furniche";
$username = "root";
$password = "";

$basketId = 1;

$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $stmtBasket = $pdo->prepare("
            SELECT products.productId, products.productName, products.price, products.imageName, basketproducts.quantity
            FROM basketproducts
            JOIN products ON basketproducts.productId = products.productId
            WHERE basketproducts.basketId = :user_id
            
        ");
    $stmtBasket->bindParam(':user_id', $basketId);
    $stmtBasket->execute();

    if ($stmtBasket->rowCount() > 0) {
        echo '<div class="basket-items">';
        while ($row = $stmtBasket->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="basket-item" data-productId="' . $row['productId'] . '">';
            echo '<div class="item-image"><img src=Pictures for Website/"' . $row['imageName'] . '" alt="' . $row['imageName'] . '"></div>';
            echo '<div class="item-details">';
            echo '<p><strong>' . $row['productName'] . '</strong></p>';
            echo '<p>Price: $' . $row['price'] . '</p>';
            echo '<div class="quantity-controls">';
            echo '<button onclick="adjustQuantity(' . $row['productId'] . ', -1)">-</button>';
            echo '<span> </span><span class="quantity">' . $row['quantity'] . '</span><span> </span>';
            echo '<button onclick="adjustQuantity(' . $row['productId'] . ', 1)">+</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<a href="product_index.php"><button>Add More Products?</button></a>';

    } else {
        echo "<p>Your basket is empty.</p>";
        echo '<a href="product_index.php"><button>Add Products?</button></a>';

    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
echo "<br>";
$pdo = null;
include 'connectdb.php';
$basket_id = 1;
$sql = "SELECT price, quantity FROM products JOIN basketproducts ON products.productId = basketproducts.productId WHERE basketId = $basket_id";
$result = $conn->query($sql);
$basketcost = 0;
if ($result->rowCount() > 0) {
  while ($row = $result->fetch()) {
    $basketcost = $basketcost + $row["quantity"] * $row["price"];
  }
  echo "£" . $basketcost . " before discount</br>";
} else {
  echo "0 results";
}



$discount_name = "Discount 1"; #$discount_name = $_POST['discount'];
$sql = "SELECT value FROM discounts WHERE discountTitle = '" . $discount_name . "'";
$value = $conn->query($sql);
$basketcost = $basketcost * (1 - $value->fetch()["value"] / 100);
echo "£" . $basketcost . " total</br>";


#stock availability check
function availability($conn, $basket_id)
{
  $available = true;
  $sql = "SELECT productName, countStock, quantity FROM products join basketproducts ON products.productId = basketproducts.productId  WHERE basketId = $basket_id";
  $result = $conn->query($sql);
  if ($result->rowCount() > 0) {
    while ($row = $result->fetch()) {
      if ($row["quantity"] > $row["countStock"]) {
        echo $row["productName"] . " is unavailable </br>";
        $available = false;
      }
    }
  }
  return $available;
}
if (availability($conn, $basket_id)) {
  echo "available";
}
?>

<script>
    function adjustQuantity(productId, change) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'basket_quantity.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var quantityElement = document.querySelector('.basket-item[data-productId="' + productId + '"] .quantity');
                var newQuantity = parseInt(quantityElement.textContent) + change;

                quantityElement.textContent = newQuantity;

                if (newQuantity === 0) {
                    var basketItem = document.querySelector('.basket-item[data-productId="' + productId + '"]');
                    if (basketItem) {
                        basketItem.remove();
                    }
                }
            }
        };
        xhr.send('product_id=' + productId + '&change=' + change);
    }
</script>

</body>
</html>
