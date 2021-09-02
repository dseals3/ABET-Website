<?php

/*
Damian Seals
COSC 465/565
Web Project #3
Question #6
checkWeight.php
*/


  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  if ( $stmt = mysqli_prepare( $conn, 
    "SELECT sectionId, email, outcomeId, major, sum(weight) AS weightTotal
	    FROM Instructors NATURAL JOIN Sections NATURAL JOIN Assessments
	    GROUP BY sectionId, outcomeId
	    HAVING sum(weight) != 100
	    ORDER BY email, major, outcomeId;")) {

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $secId, $email, $outId, $major, $sumWeight);

        $results = array();

        while(mysqli_stmt_fetch($stmt)) {
          $resObj = new \stdClass();

          $resObj->sectionId = $secId;

          $resObj->email = $email;

          $resObj->outcomeId = $outId;
          
          $resObj->major = $major;

          $resObj->weightTotal = $sumWeight;

          array_push($results, $resObj);
        }

        $JSONobj = json_encode($results);
        echo $JSONobj;

        mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
?>
