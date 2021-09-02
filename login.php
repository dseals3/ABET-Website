<?php

/*
Damian Seals
COSC 465/565
Web Project #4
login.php
*/

  session_start();

  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $email = $_POST['email'];
  $password = $_POST['password'];

  if ( $stmt = mysqli_prepare( $conn, 
    "SELECT DISTINCT instructorId, sectionId, courseId, major, semester, year
	    FROM CourseOutcomeMapping NATURAL JOIN Sections NATURAL JOIN Instructors
	    WHERE email = ?
		    AND password = PASSWORD(?)
        ORDER BY year desc, semester asc;")) {

        mysqli_stmt_bind_param($stmt, "ss", $email, $password);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $insId, $secId, $courseId, $major, $semester, $year);

        $login = array();

        while(mysqli_stmt_fetch($stmt)) {
          $resObj = new \stdClass();

          $resObj->instructorId = $insId;

          $resObj->sectionId = $secId;

          $resObj->courseId = $courseId;

          $resObj->major = $major;

          $resObj->semester = $semester;

          $resObj->year = $year;

          array_push($login, $resObj);
        }
        
        if (empty($login)) {
          echo 0;
        } else {
          $JSONobj = json_encode($login);
          echo $JSONobj;

          $_SESSION["email"] = $email;
          $_SESSION["login"] = $login;
        }

        mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
?>
