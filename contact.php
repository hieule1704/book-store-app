<?php
// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load .env variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

include 'config.php';

// replace direct session_start() with secure session config
include_once __DIR__ . '/session_config.php';

// safe session read
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
if (!$user_id) {
   header('Location: login.php');
   exit;
}

if (isset($_POST['send'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   // Check for duplicate message in DB
   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if (mysqli_num_rows($select_message) > 0) {
      $message[] = 'Message sent already!';
   } else {
      // 1. Insert into Database
      $insert = mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')");

      if ($insert) {
         // 2. Send Email Notification to Admin
         $mail = new PHPMailer(true);

         try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER']; // Your Gmail
            $mail->Password   = $_ENV['SMTP_PASS']; // App Password
            $mail->SMTPSecure = $_ENV['SMTP_SECURE']; // tls
            $mail->Port       = $_ENV['SMTP_PORT'];    // 587

            // Sender & Recipient
            // Note: For Gmail SMTP, the 'From' address usually must match the authenticated user
            $mail->setFrom($_ENV['SMTP_USER'], 'Bookly Contact Form');
            $mail->addAddress($_ENV['SMTP_USER']); // Send TO yourself (Admin)
            // Optional: Add Reply-To so you can reply directly to the customer
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "New Contact Message from $name";

            $mail->Body    = "
               <h3>New User Message</h3>
               <p><strong>Name:</strong> $name</p>
               <p><strong>Email:</strong> $email</p>
               <p><strong>Phone:</strong> $number</p>
               <hr>
               <p><strong>Message:</strong><br>$msg</p>
            ";

            $mail->send();
            $message[] = 'Message sent successfully and emailed to admin!';
         } catch (Exception $e) {
            // If email fails, at least it's in the database.
            $message[] = 'Message saved to database, but email notification failed. Error: ' . $mail->ErrorInfo;
         }
      } else {
         $message[] = 'Failed to send message!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <!-- Bootstrap 5.3.x CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

   <?php include 'header.php'; ?>

   <!-- Header Banner -->
   <div class="bg-primary py-5 mb-5 text-white text-center">
      <div class="container">
         <h1 class="fw-bold display-5">Contact Us</h1>
         <p class="lead">We'd love to hear from you.</p>
      </div>
   </div>

   <section class="container mb-5">
      <div class="row g-5">
         <!-- Contact Form -->
         <div class="col-lg-7">
            <div class="card shadow-sm border-0">
               <div class="card-body p-4">
                  <h3 class="fw-bold mb-4 text-primary">Send Message</h3>
                  <form action="" method="post">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-floating">
                              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                              <label for="name">Your Name</label>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-floating">
                              <input type="email" name="email" class="form-control" id="email" placeholder="Your Email" required>
                              <label for="email">Your Email</label>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="form-floating">
                              <input type="number" name="number" class="form-control" id="number" placeholder="Phone Number" required>
                              <label for="number">Phone Number</label>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="form-floating">
                              <textarea name="message" class="form-control" placeholder="Leave a message here" id="message" style="height: 150px"></textarea>
                              <label for="message">Message</label>
                           </div>
                        </div>
                        <div class="col-12">
                           <button type="submit" name="send" class="btn btn-primary btn-lg px-5 rounded-pill w-100">Send Message</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>

         <!-- Info Sidebar -->
         <div class="col-lg-5">
            <div class="card bg-dark text-white border-0 h-100">
               <div class="card-body p-5">
                  <h3 class="fw-bold mb-4">Contact Info</h3>
                  <p class="text-white-50 mb-5">Have questions about your order or want to know more about our books? Reach out to us!</p>

                  <div class="d-flex mb-4">
                     <div class="flex-shrink-0 btn-sm-square bg-primary rounded-circle text-center pt-1" style="width:40px; height:40px;">
                        <i class="fas fa-phone-alt text-white"></i>
                     </div>
                     <div class="ms-3">
                        <h6 class="mb-1 fw-bold">Phone</h6>
                        <p class="mb-0 text-white-50">+123-456-7890</p>
                     </div>
                  </div>

                  <div class="d-flex mb-4">
                     <div class="flex-shrink-0 bg-primary rounded-circle text-center pt-2" style="width:40px; height:40px;">
                        <i class="fas fa-envelope text-white"></i>
                     </div>
                     <div class="ms-3">
                        <h6 class="mb-1 fw-bold">Email</h6>
                        <p class="mb-0 text-white-50">support@bookly.com</p>
                     </div>
                  </div>

                  <div class="d-flex mb-4">
                     <div class="flex-shrink-0 bg-primary rounded-circle text-center pt-2" style="width:40px; height:40px;">
                        <i class="fas fa-map-marker-alt text-white"></i>
                     </div>
                     <div class="ms-3">
                        <h6 class="mb-1 fw-bold">Address</h6>
                        <p class="mb-0 text-white-50">Long Xuyen, An Giang, Vietnam</p>
                     </div>
                  </div>

                  <hr class="border-secondary my-4">

                  <h5 class="fw-bold mb-3">Follow Us</h5>
                  <div class="d-flex gap-3">
                     <a href="#" class="btn btn-outline-light rounded-circle"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="btn btn-outline-light rounded-circle"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="btn btn-outline-light rounded-circle"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="btn btn-outline-light rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <!-- FAQ Section -->
   <section class="container mb-5">
      <h3 class="text-center fw-bold mb-4">Frequently Asked Questions</h3>
      <div class="accordion shadow-sm" id="faqAccordion">
         <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
               <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                  How long does shipping take?
               </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
               <div class="accordion-body text-muted">
                  Standard shipping typically takes 3-5 business days. Express shipping options are available at checkout for 1-2 day delivery.
               </div>
            </div>
         </div>
         <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                  Can I return a book if I don't like it?
               </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
               <div class="accordion-body text-muted">
                  Yes! We offer a 30-day return policy for all books in their original condition. Please contact our support team to initiate a return.
               </div>
            </div>
         </div>
         <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                  Do you offer international shipping?
               </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
               <div class="accordion-body text-muted">
                  Currently, we only ship within Vietnam. We are working on expanding our services to international locations soon!
               </div>
            </div>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>

   <!-- Bootstrap 5.3.x JS Bundle -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>