<?php
require('db.php'); // Ensure this connects to your database

// Fetch items from the database
$query = "SELECT id, name, image_url, price FROM items";
$result = mysqli_query($con, $query);

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>BB Shop</title>
    <style>
        body {
            background-image: url("mountains.jpg");
            background-color: rgb(36, 36, 36);
            background-blend-mode: multiply;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            margin: 0;
        }

        .container {
            padding: 16px;
            width: 100%;
            max-width: calc(100vw - 128px);
            margin-right: 128px; 
        }

        .container img {
            object-fit: cover;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .container img:hover {
            border-radius: 8px;
            box-shadow: 0px 0px 10px 8px rgba(255, 255, 255, 0.212);
        }
        .btn{
            background-color: green;
            color:white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
        }
        .btn:hover {
            background-color:rgb(23, 76, 17);
            color:white;
        }
        .btn:active {
            transform: scale(0.98); 
        }
        .modal-body p {
            color: black;
        }   
    </style>
</head>
<body>
    <header>
        <i class="fa-solid fa-bug" style="color: #ffffff; font-size: 80px; display: block; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
        <h1 id='p1' style="color: rgb(255, 255, 255); text-align: left; font-weight: bolder; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:32px;">
        BEE <br> BUY <br>
        </h1>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">HOME</a></li>
                <li><a href="gallery.php"class="active">SHOP</a></li>
                <li><a href="bio.html">BIO</a></li>
                <li><a href="contactus.html">CONTACT</a></li>
                <li><a href="login.php" >LOGIN</a></li>
                <li><a href="cart.html">CART</a></li>
                <li><a href="dashboard.php">ADMIN</a></li>
                <li><a href="additem.php">POST</a></li>
                <a href="https://facebook.com" target="_blank"> 
                    <i class="fa-brands fa-facebook-f" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a><br>
                <a href="https://x.com" target="_blank"> 
                    <i class="fa-brands fa-x-twitter" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a><br>
                <a href="https://instagram.com" target="_blank"> 
                    <i class="fa-brands fa-instagram" style="color: #ffffff; align-content: center;margin-right: 15px;padding: 8px 16px; border-radius: 0px;"></i>
                </a>
                </ul>
        </nav>
    </header>

    <div class="container mt-5 pt-5">
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <?php foreach ($items as $item): ?>
                <div class="col">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                         class="img-fluid" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                         data-bs-toggle="modal" 
                         data-bs-target="#open-modal" 
                         data-id="<?php echo $item['id']; ?>" 
                         data-name="<?php echo htmlspecialchars($item['name']); ?>" 
                         data-price="<?php echo $item['price']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="modal" id="open-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" class="img-fluid" id="m_image">
                    <p id="m_text" class="mt-2"style="color: black;"></p>
                    <p id="m_price" class="mt-2" style="color: black;"s></p>
                    <button type="button" class="btn" onclick="addToCart()">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <script>
        // Handle modal data update
        document.querySelectorAll('.container img[data-bs-toggle="modal"]').forEach(image => {
            image.addEventListener('click', () => {
                document.getElementById('m_image').src = image.src;
                document.getElementById('m_text').textContent = image.getAttribute('data-name');
                document.getElementById('m_price').textContent = "$" + image.getAttribute('data-price');
            });
        });

        // Add to cart function
        function addToCart() {
            let name = document.getElementById('m_text').textContent;
            let price = document.getElementById('m_price').textContent;
            
            let cart = JSON.parse(localStorage.getItem("cart")) || [];
            cart.push({ name, price });
            localStorage.setItem("cart", JSON.stringify(cart));
            //alert(name + " added to your cart");
        }
    </script>
</body>
</html>
