<?php
// Damian Seals
// Web Project 4
// nav.php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }
  
  $login = $_SESSION["login"]; 
  $major = $_POST["major"];
  $sectionId = $_POST["sectionId"];

  if ( $stmt = mysqli_prepare( $conn, 
    "SELECT outcomeId, outcomeDescription
	    FROM Outcomes NATURAL JOIN CourseOutcomeMapping NATURAL JOIN Sections
	    WHERE major = ?
		    AND sectionId = ?
      ORDER BY outcomeId;")) {

        mysqli_stmt_bind_param($stmt, "si", $major, $sectionId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $outId, $outDesc);

        $outcomes = array();

        while(mysqli_stmt_fetch($stmt)) {
          $resObj = new \stdClass();

          $resObj->outcomeId = $outId;

          $resObj->outcomeDescription = $outDesc;

          array_push($outcomes, $resObj);
        }
        $_SESSION["outcomes"] = $outcomes;
        
        mysqli_stmt_close($stmt);
      }
  mysqli_close($conn);
?>
    <nav>
        <span class="body-label">Section</span><br>
        <div class="dropdown-container">
              <select id="sectionMenu" class="dropdown">
                  <?php
                      foreach($login as $k=>$v) {
                          if ($major == $v->major && $sectionId == $v->sectionId) { 
                              echo "<option selected value='{\"sec\": \"$v->sectionId\", \"maj\": \"$v->major\"}'>$v->courseId $v->semester $v->year $v->major</option>";
                          } else {
                              echo "<option value='{\"sec\": \"$v->sectionId\", \"maj\": \"$v->major\"}'>$v->courseId $v->semester $v->year $v->major</option>";
                          }
                      }
                  ?>
              </select>
          </div>
          <br>
          <ul id="outcomes-list" class="outcomes">
              <?php
                  foreach($outcomes as $k=>$v) {
                      if ($k == 0) {
                          echo "<li class='selected outcomes' data-major='$major' data-id='$v->outcomeId' data-desc='$v->outcomeDescription'>Outcome $v->outcomeId</li>";
                      } else {
                          echo "<li class='outcomes' data-major='$major' data-id='$v->outcomeId' data-desc='$v->outcomeDescription'>Outcome $v->outcomeId</li>";
                      }
                  }
              ?>
          </ul>
    </nav>

