<?php

/*
Damian Seals
COSC 465/565
Web Project #4
updateResults.php
*/


  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $email = $_POST['email'];
  $password = $_POST['password'];

  if ( $stmt = mysqli_prepare( $conn, 
    "UPDATE Instructors
      SET password = PASSWORD(?)
      WHERE email = ?;")) {

        mysqli_stmt_bind_param($stmt, "ss", $password, $email);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
?>
