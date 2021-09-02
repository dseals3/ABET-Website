<!--Damian Seals-->
<!--Web Project 2-->

<?php
   // First a little query I added to retrieve the name of the user

   session_start();
   $login = $_SESSION["login"];
   $conn = new mysqli("dbs2.eecs.utk.edu", "dseals3", "boobaloobaloo", "cosc465_dseals3");

  if ($conn -> connect_errno) {
    echo "Failed to connect to MySQL server: " . $conn -> connect_error;
    exit();
  }

  $insId = $login[0]->instructorId;

  if ( $stmt = mysqli_prepare( $conn, 
    "SELECT firstname, lastname
	    FROM Instructors
	    WHERE instructorId = ?;")) {

        mysqli_stmt_bind_param($stmt, "i", $insId);

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $fname, $lname);

        $name = array();

        while(mysqli_stmt_fetch($stmt)) {
          $resObj = new \stdClass();

          $resObj->firstname = $fname;

          $resObj->lastname = $lname;

          array_push($name, $resObj);
        }
        mysqli_stmt_close($stmt);
    }       

    mysqli_close($conn);
?>
<link type="text/css" rel="stylesheet" href="password.css">
<script language="JavaScript" type="text/javascript" src="password.js"></script>
<main>
    <h1>Change Password</h1>
    <div class="seperator">
        <div class="row heading-row">
            Basic Info
        </div>
        <div class="row info-row">
            <div class="column">
            <?php
                echo "<p><strong>Name: </strong>", $name[0]->firstname, "&nbsp;", $name[0]->lastname, "</p>";
            ?>
            </div>
            <div class="column">
            <p><strong>Email: </strong><span id="getEmail"><?php echo $_SESSION['email'] ?></span></p>
            </div>
        </div>
        <div class="row heading-row" id="password-header">
            Change Password
        </div>
        <div class="row" id="password-box">
            <div class="column">
                <h4>New Password</h4>
                <input id="passOne" type="password">
            </div>
            <div class="column">
                <h4>Confirm Password</h4>
                <input id="passTwo" type="password">
            </div>
            <div>
                <input id="submitBtn" type="button" value="Submit" class="submit">
                <p id="success" class="success hidden">password changed</p>
                <p id="error" class="invalid hidden">passwords do not match-please make them match</p>
                <p id="emptyError" class="invalid hidden">password fields must not be empty</p>
            </div>
        </div>
    </div>
</main>
