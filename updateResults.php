<?php

/*
Damian Seals
COSC 465/565
Web Project #3
Question #7
updateResults.php
*/


  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $outcomeId = $_POST['outcomeId'];
  $sectionId = $_POST['sectionId'];
  $major = $_POST['major'];
  $perfLevel = $_POST['performanceLevel'];
  $numStudents = $_POST['numberOfStudents'];

  if ( $stmt = mysqli_prepare( $conn, 
    "INSERT INTO OutcomeResults (sectionId, outcomeId, major, performanceLevel, numberOfStudents) 
      VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE numberOfStudents = VALUES (numberOfStudents);")) {

        mysqli_stmt_bind_param($stmt, "iisii", $sectionId, $outcomeId, $major, $perfLevel, $numStudents);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
?>
