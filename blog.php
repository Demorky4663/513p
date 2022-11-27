<?php
session_start();




 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include 'DBController.php';
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Luca’s Bread</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->

  <link href="style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top header-inner-pages">
    <div class="container d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>

      
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto " href="#Home">Home</a></li>
          <li><a class="nav-link scrollto" href="#about">About us</a></li>
          <li><a class="nav-link scrollto" href="#services">Careers</a></li>      
          <li><a class="active" href="blog.html">Order online</a></li>    
          <li><a class="nav-link scrollto" href="#team">Contact Us</a></li>  
          <li><a href="register.php">Register</a></li>  

        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
      

    </div>
  </header><!-- End Header -->
  

  <main id="main">
    <div style="height:70px"></div>

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs">
      <div class="container">

        <ol>
          <li><a href="index.php">Home</a></li>
          <li>Order online</li>
        </ol>
        <h2>Order online</h2>

      </div>
    </section><!-- End Breadcrumbs -->

   
 <!-- ======= Order online Section ======= -->


<div style="color:rgb(255, 255, 255)">
 <section id="portfolio" class="portfolio">

   <div class="container">

     <div class="section-title">
       <h2>Order online
       </h2>
       <h3>Choose your favorite<span>bread</span></h3>
       <p>You can select the products you want through the shopping cart and other functions below.</p>
     </div>

     
     <a href="logout.php" class="button3">Sign Out of Your Account</a>
     <div class="wrapper">
     <div id="shopping-cart">
     <div class="txt-heading">Shopping Cart</div>
     
     <a id="btnEmpty" href="blog.php?action=empty">Empty Cart</a>
     <?php
     if(isset($_SESSION["cart_item"])){
         $total_quantity = 0;
         $total_price = 0;
     ?>	
     <table class="tbl-cart" cellpadding="10" cellspacing="1">
     <tbody>
     <tr>
     <th style="text-align:left;">Name</th>
     <th style="text-align:left;">Code</th>
     <th style="text-align:right;" width="5%">Quantity</th>
     <th style="text-align:right;" width="10%">Unit Price</th>
     <th style="text-align:right;" width="10%">Price</th>
     <th style="text-align:center;" width="5%">Remove</th>
     </tr>	
     <?php		
         foreach ($_SESSION["cart_item"] as $item){
             $item_price = $item["quantity"]*$item["price"];
         ?>
             <tr>
             <td><img src="<?php echo $item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
             <td><?php echo $item["code"]; ?></td>
             <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
             <td  style="text-align:right;"><?php echo "$ ".$item["price"]; ?></td>
             <td  style="text-align:right;"><?php echo "$ ". number_format($item_price,2); ?></td>
             <td style="text-align:center;"><a href="blog.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
             </tr>
             <?php
             $total_quantity += $item["quantity"];
             $total_price += ($item["price"]*$item["quantity"]);
         }
         ?>
     
     <tr>
     <td colspan="2" align="right">Total:</td>
     <td align="right"><?php echo $total_quantity; ?></td>
     <td align="right" colspan="2"><strong><?php echo "$ ".number_format($total_price, 2); ?></strong></td>
     <td></td>
     </tr>
     </tbody>
     </table>		
       <?php
     } else {
     ?>
     <div class="no-records">Your Cart is Empty</div>
     <?php 
     }
     ?>
     </div>
     
     <div id="product-grid">
       <div class="txt-heading">Products</div>
       <?php
       $product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
       if (!empty($product_array)) { 
         foreach($product_array as $key=>$value){
       ?>
       
         <div class="image">
           
           <div class="image"><img src="<?php echo $product_array[$key]["image"]; ?>"/>
           <button class="quick_look" data-id="<?php echo $product_array[$key]["id"] ; ?>">Quick Look</button>
           </div>
           <form method="post" action="blog.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
           <div class="product-tile-footer">
           <div class="product-title"><?php echo $product_array[$key]["name"]; ?></div>
           <div class="product-price"><?php echo "$".$product_array[$key]["price"]; ?></div>
           <div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
           </div>
           
           </form>
           
         </div>
       <?php
         }
       }
       
       ?>
     </div>
     </div>	
     
         <div id="demo-modal"></div>
     
         <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
         <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     
         <script>
         $(".quick_look").on("click", function() {
             var product_id = $(this).data("id");
               var options = {
                   modal: true,
                   height: 'auto',
                   width:'70%'
                 };
               $('#demo-modal').load('get-product-info.php?id='+product_id).dialog(options).dialog('open');
         });
     
         $(document).ready(function() {
               $(".image").hover(function() {
                     $(this).children(".quick_look").show();
                 },function() {
                      $(this).children(".quick_look").hide();
                 });
         });
         </script>


 </section><!-- End Order online Section -->
</main><!-- End #main -->

   <!-- ======= Footer ======= -->
   <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>Luca’s Loaves
            </h3>
            <p>
              36 Garden Ave<br>
              Mullumbimby NSW 2482<br>
              <br><br>
              <strong>Phone:</strong> +86 18858068977<br>
              <strong>Email:</strong> Luca'sbread@qq.com<br>
            </p>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Home</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Careers</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Order online</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Contact Us
              </a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Bread shop</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Self collection at the store</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Take-out food</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">New Bread Suggestions</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Contact</a></li>
            </ul>
          </div>

          <div class="col-lg-4 col-md-6 footer-newsletter">
            <h4>Join Luca's bread</h4>
            <p>If you have any questions about us, contact us</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </form>
          </div>

        </div>
      </div>
    </div>

    <div class="container d-md-flex py-4">

      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span>Luca’s Bread</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
          Designed by <a href="https://bootstrapmade.com/">Demorky(WuRonghua)20ITA1</a>
        </div>
      </div>
      <div class="social-links text-center text-md-right pt-3 pt-md-0">
        <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
        <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
        <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
        <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>