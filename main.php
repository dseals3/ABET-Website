<?php
  session_start();

  $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $login = $_SESSION["login"]; 
  $outcomes = $_SESSION["outcomes"];

  $major = $_POST["major"];
  $sectionId = $_POST["sectionId"];
  $outcomeId = $_POST["outcomeId"];

  if ( $stmt = mysqli_prepare( $conn, 
    "SELECT description, numberOfStudents
	    FROM Sections NATURAL JOIN OutcomeResults NATURAL JOIN PerformanceLevels
      WHERE major = ?
        AND outcomeId = ?
		    AND sectionId = ?
	    ORDER BY performanceLevel;")) {

        mysqli_stmt_bind_param($stmt, "sii", $major, $outcomeId, $sectionId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $desc, $num);

        $results = array();

        while(mysqli_stmt_fetch($stmt)) {
          $resObj = new \stdClass();

          $resObj->description = $desc;

          $resObj->numberOfStudents = $num;

          array_push($results, $resObj);
        }
        
        mysqli_stmt_close($stmt);
  }

  if ( $stmt = mysqli_prepare( $conn, 
    "SELECT assessmentId, assessmentDescription, weight
	    FROM Sections NATURAL JOIN Assessments
	    WHERE major = ?
		    AND outcomeId = ?
		    AND sectionId = ?
	    ORDER BY weight desc, assessmentDescription asc;")) {

        mysqli_stmt_bind_param($stmt, "sii", $major, $outcomeId, $sectionId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $assessId, $desc, $weight);

        $assess = array();

        while(mysqli_stmt_fetch($stmt)) {
          $resObj = new \stdClass();

          $resObj->assessmentId = $assessId;
          
          $resObj->assessmentDescription = $desc;

          $resObj->weight = $weight;

          array_push($assess, $resObj);
        }
        mysqli_stmt_close($stmt);
  }

  if ( $stmt = mysqli_prepare( $conn, 
    "SELECT strengths, weaknesses, actions
	    FROM Narratives
	    WHERE major = ?
		    AND outcomeId = ?
		    AND sectionId = ?;")) {

        mysqli_stmt_bind_param($stmt, "sii", $major, $outcomeId, $sectionId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $strengths, $weaknesses, $actions);

        $narratives = array();

        while(mysqli_stmt_fetch($stmt)) {
          $resObj = new \stdClass();

          $resObj->strengths = $strengths;

          $resObj->weaknesses = $weaknesses;

          $resObj->actions = $actions;
          
          array_push($narratives, $resObj);
        }

        mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
?>
  <h1>Results</h1>
  <div id="results-Section" class="section">
      <p>Please enter the number of students who do not meet expectations, meet expectations,
          and exceed expectations. You can type directly into the boxes--you do not need to use
          the arrows.
      </p>
      <p id="outcome">
          <?php
            $outcomes = $_SESSION["outcomes"];

            echo "<strong>Outcome ", $outcomes[0]->outcomeId, " - ", $login[0]->major, ":</strong> ", $outcomes[0]->outcomeDescription;
          ?>
      </p>
      <table>
          <tr>
              <th>Not Meet Expectations</td>
              <th>Meets Expectations</td>
              <th>Exceeds Expectations</td>
              <th>Total</td>
          </tr>
          <tr>
              <form id="results-form">
              <?php
                      echo "<td><input id='resOne' type='number' min=0 value=", $results[0]->numberOfStudents, "></td>";
                      echo "<td><input id='resTwo' type='number' min=0 value=", $results[1]->numberOfStudents, "></td>";
                      echo "<td><input id='resThree' type='number' min=0 value=", $results[2]->numberOfStudents, "></td>";

                      $total = $results[0]->numberOfStudents + $results[1]->numberOfStudents + $results[2]->numberOfStudents;
                      echo "<td><label id='total-lbl' for='number'>", $total, "</label></td>";
              ?>
              </form>
          </tr>
      </table>
      <div class="button">
          <span id="results-popup" class="hidden"><p>results successfully saved</p></span>
          <input type="button" id="resultsBtn" form="results-form" class="save" value="Save Results">
      </div>
  </div>
  <h1>Assessment Plan</h1>
  <div id="assess-Section" class="section">
      <ol>
          <li>Please enter your assessment plan for each outcome. The weights are percentages from 0-100 and the weights should add up to 100%.</li>
          <li>Always press "Save Assessments" when finished, even if you removed an assessment. The trash can only removes an assessment from this screen-it does not remove it from the database until you press "Save Assessments".</li>
      </ol>
      <table id="assess-table" class="assessment">
          <tr>
              <th>Weight (%)</th>
              <th>Description</th>
              <th>Remove</th>
          </tr>
          <form>
          <?php
              foreach($assess as $k=>$v) {
                  echo "<tr data-assessid='$v->assessmentId'>";
                      echo "<td><input type='number' min=1 max=100 value=", $v->weight, "></td>";
                      echo "<td><textarea maxlength=400>", htmlentities($v->assessmentDescription), "</textarea></td>";
                      echo "<td><img class='trash' src='trash.svg'></td>";
                  echo "</tr>";
              }

          ?>
          </form>
      </table>
      <div class="button">
          <input type="button" id="newBtn" class="new" value="+ New">
      </div>
      <div class="button">
          <span id="error-empty" class=" invalid hidden"><p>Assessment descriptions must not be empty</p></span>
          <span id="assess-popup" class="hidden"><p>Assessments successfully saved</p></span>
          <input type="button" id="assessBtn" class="save" value="Save Assessments">
      </div>
  </div>
  <h1>Narrative Summary</h1>
  <div id="nar-Section" class="section">
      <p>
          Please enter your narrative for each outcome, including the student strengths for the outcome, student weaknesses for the outcomes, and suggested actions for improving student attainment of each outcome.
      </p>
      <h4>Strengths</h6>
      <?php
          echo "<textarea id='strength' maxlength=2000 placeholder='None'>", $narratives[0]->strengths, "</textarea>";
      ?>
      <h4>Weaknesses</h6>
      <?php
          echo "<textarea id='weak' maxlength=2000 placeholder='None'>", $narratives[0]->weaknesses, "</textarea>";
      ?>
      <h4>Actions</h6>
      <?php
          echo "<textarea id='action' maxlength=2000 placeholder='None'>", $narratives[0]->actions, "</textarea>";
      ?>
      <div class="button">
          <span id="nar-popup" class="hidden"><p>narratives successfully saved</p></span>
          <input type="button" id="narBtn" class="save" value="Save Narrative">
      </div>
  </div>

