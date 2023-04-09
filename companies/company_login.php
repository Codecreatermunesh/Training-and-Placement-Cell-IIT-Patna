<?php
session_start();
 
if (isset($_POST['email']) && isset($_POST['password'])) {
  // Retrieve the form data
  $email = $_POST['email'];
  $password = $_POST['password'];
 
  // Connect to the MySQL database
  $db_host = "localhost";
  $db_name = "dblab8";
  $db_user = "newuser";
  $db_pass = "niibtarana";
  $db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
 
  // Check if the email exists in the users table
  $sql = "SELECT * FROM company_authentication WHERE email=?";
  $stmt = mysqli_prepare($db_conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
 
  if (mysqli_num_rows($result) == 1) {
    // Retrieve the user's password hash
    $user = mysqli_fetch_assoc($result);
    $hash = $user['password'];
 
    // Verify the password
    if (password_verify($password, $hash)) {
      // Set session variables for the user
      $_SESSION['company_id'] = $user['id'];
      $_SESSION['company_email'] = $user['email'];

      header("Location: company_webpage.php");
      exit();
    } else {
      // Display error message for incorrect password
      echo "Incorrect password. Please try again.";
    }
  } else {
    // Display error message for invalid email
    echo "Invalid email. Please try again.";
  }
 
  // Close the database connection
  mysqli_close($db_conn);
}

?>