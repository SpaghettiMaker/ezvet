<?php
    include('ShoppingCart.class.php');
?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <title>PHP Test</title>
    </head>
    <script>
        if ( window.history.replaceState ) 
        {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
    <body>
        <?php
            // ######## please do not alter the following code ########
            $products = [
            [ "name" => "Sledgehammer", "price" => 125.75 ],
            [ "name" => "Axe", "price" => 190.50 ],
            [ "name" => "Bandsaw", "price" => 562.131 ],
            [ "name" => "Chisel", "price" => 12.9 ],
            [ "name" => "Hacksaw", "price" => 18.45 ],
            ];
            // ########################################################
            
            if ($_SERVER['REQUEST_METHOD'] == "POST") 
            {
                if(isset($_POST['deleteItem'])) 
                {
                    $deleteKey = $_POST['deleteItem']; 
                    ShoppingCart::getInstance()->deleteKey($deleteKey);
                } else 
                {
                    $postItem = $_POST;
                    $key = array_key_first($postItem);
                    if ( filter_var($postItem[$key], FILTER_VALIDATE_INT) !== false ) 
                    {
                        ShoppingCart::getInstance()->addItem($postItem);
                    } else 
                    {
                        echo "Your variable needs to be an integer";
                    }
                }
            }
        ?>
        <div class="product-item">
            <?php
                function format($num1, $num2)
                {
                    return number_format(round($num1 * $num2, 2, PHP_ROUND_HALF_DOWN), 2, '.', ',');
                }

                foreach($products as $key=>$value) 
                {
                    $product_name = $products[$key]["name"];
                    $product_price = $products[$key]["price"];
                    $product_price = format($product_price, 1);
                    echo "<form action=\"\" method=\"POST\">";
                    echo "<div class=\"product\">Product Name: $product_name, Price: $product_price</div>";
                    echo "<div class=\"cart-action\"><div class=\"form-group\"><input type=\"text\" class=\"form-control\" name=$product_name id=$product_name value=0 size=\"2\" /><button type=\"submit\" class=\"form-control\">Add $product_name to Cart</button></div></div>";
                    echo "</form>";
                }
                $items = ShoppingCart::getInstance()->getItems();
                
                foreach($items as $key=>$value) 
                {   
                    $price = ShoppingCart::getInstance()->getPrice($products, $key);
                    $total = format($price, $value);
                    $price = format($price, 1);
                    echo "<form action=\"\" method=\"POST\">";
                    echo "<div class=\"item\">Product Name: $key,  Price: $price,  Quantity: $value,  Total: $total</div>";
                    echo "<button type=\"submit\" name=\"deleteItem\" value=\"$key\">Delete $key from Cart</button>";
                    echo "</form>";
                }
                $total_price = ShoppingCart::getInstance()->getTotalPrice($products);
                echo "<div class=\"product-price\">Grand Total: $total_price</div>";
            ?>
        </div>
    </body>
</html>

<!--php -S localhost:8000 index.php-->