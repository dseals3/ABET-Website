<?php

/*
Damian Seals
COSC 465/565
Web Project #3
Question #9
updateNarrative.php
*/


  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $sectionId = $_POST['sectionId'];
  $major = $_POST['major'];
  $outcomeId = $_POST['outcomeId'];
  $strengths = $_POST['strengths'];
  $weaknesses = $_POST['weaknesses'];
  $actions = $_POST['actions'];

  if ( $stmt = mysqli_prepare( $conn, 
    "INSERT INTO Narratives (sectionId, major, outcomeId, strengths, weaknesses, actions) 
      VALUES (?, ?, ?, ?, ?, ?) 
	    ON DUPLICATE KEY UPDATE
		      strengths = VALUES (strengths),
		      weaknesses = VALUES (weaknesses),
		      actions = VALUES (actions);")) {

        mysqli_stmt_bind_param($stmt, "isisss", $sectionId, $major, $outcomeId, $strengths, $weaknesses, $actions);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
?>
