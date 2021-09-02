<?php

/*
Damian Seals
COSC 465/565
Web Project #3
Question #10
deleteAssessment.php
*/


  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $assessId = $_POST['assessmentId']; 

  if ( $stmt = mysqli_prepare( $conn, 
      "DELETE FROM Assessments
        WHERE assessmentId = ?;")) {

        mysqli_stmt_bind_param($stmt, "i", $assessId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
?>
