<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header>
   <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
      <div class="container">
         <a class="navbar-brand fw-bold" href="admin_page.php">Admin<span class="text-primary">Panel</span></a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span></button>
         <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
               <li class="nav-item"><a class="nav-link" href="admin_page.php">Home</a></li>
               <li class="nav-item"><a class="nav-link" href="admin_products.php">Products</a></li>
               <li class="nav-item"><a class="nav-link" href="admin_orders.php">Orders</a></li>
               <li class="nav-item"><a class="nav-link" href="admin_users.php">Users</a></li>
               <li class="nav-item"><a class="nav-link" href="admin_contacts.php">Messages</a></li>
            </ul>
            <div class="dropdown">
               <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user fa-lg me-2"></i>
                  <?php echo $_SESSION['admin_name']; ?>
               </a>
               <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                  <li><span class="dropdown-item-text">Username: <strong><?php echo $_SESSION['admin_name']; ?></strong></span></li>
                  <li><span class="dropdown-item-text">Email: <strong><?php echo $_SESSION['admin_email']; ?></strong></span></li>
                  <li>
                     <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                  <li>
                     <div class="dropdown-item">
                        New <a href="login.php">login</a> | <a href="register.php">register</a>
                     </div>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </nav>
</header>