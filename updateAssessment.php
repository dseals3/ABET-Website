<?php

/*
Damian Seals
COSC 465/565
Web Project #4
updateAssessment.php
*/


  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $assessId = $_POST['assessmentId']; 
  $outcomeId = $_POST['outcomeId'];
  $sectionId = $_POST['sectionId'];
  $assessDesc = $_POST['assessmentDescription'];
  $major = $_POST['major'];
  $weight = $_POST['weight'];

  if ( $stmt = mysqli_prepare( $conn, 
    "INSERT INTO Assessments (assessmentId, sectionId, assessmentDescription, outcomeId, major, weight) 
      VALUES (?, ?, ?, ?, ?, ?) 
	    ON DUPLICATE KEY UPDATE
		      sectionId = VALUES (sectionId),
		      assessmentDescription = VALUES (assessmentDescription),
		      outcomeId = VALUES (outcomeId),
		      major = VALUES (major),
		      weight = VALUES (weight);")) {

        mysqli_stmt_bind_param($stmt, "iisisi", $assessId, $sectionId, $assessDesc, $outcomeId, $major, $weight);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
?>
