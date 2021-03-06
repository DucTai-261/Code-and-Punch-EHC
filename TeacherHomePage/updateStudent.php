<?php
    include "../config.php";
    require_once('../dbhelp.php');
    session_start();
    if(!isset($_SESSION["username"]) || $_SESSION["usertype"] != "teacher" || !isset($_POST))
    {
        header("location: ..");
    }

    $current_student_username =  $_GET["student_username"];
    $_POST["ID"] = $_GET["id"];

    $s_username = $s_email =$s_fullname = $s_password = $s_phone = $s_confirm_password = "";
    $msg_username =  $msg_fullname = $msg_password = $msg_phone = $msg_success ="";

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"])){
        $_POST["teacher_username"] = $_SESSION["username"];
        
        $s_username = $_POST["username"];
        $s_password = $_POST["password"];
        $s_confirm_password = $_POST["confirm_password"];
        $s_fullname = $_POST["fullname"];
        $s_email = $_POST["email"];
        $s_phone = $_POST["phone"];
        // check sql injection 
        $s_password= mysqli_real_escape_string($db, $s_password);
        $s_username= mysqli_real_escape_string($db, $s_username);
        $s_confirm_password= mysqli_real_escape_string($db, $s_confirm_password);
        $s_email= mysqli_real_escape_string($db, $s_email);
        $s_phone= mysqli_real_escape_string($db, $s_phone);
        $s_fullname= mysqli_real_escape_string($db, $s_fullname);
        if(!is_valid_username($db,$s_username)){
            $msg_username = "Username must have lowercase characters (a-z) or numbers (0-9) or 
                            underscores(_), no special character and length from 5 to 30!";
        }
        elseif(!check_password($db, $s_password)){
            $msg_password ="Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter.";
        }
        elseif($s_password != $s_confirm_password){
            $msg_password = "The password confirmation does not match";
        }
        elseif(!is_valid_fullname($db,$s_fullname)){
            $msg_fullname = "Full name must have characters (a-z or A-Z), start with upper case and
                                length from 5 to 30";
        }elseif(!is_valid_phone($db,$s_phone)){
            $msg_phone = "Invalid phone number";
        }
        else{
            $_POST["usertype"] = "student";
            if(update_student($db, $current_student_username , $_POST)){
                $msg_success = "Update student's profile successful !";
            }
        }

    }

?>
<!DOCTYPE html>
<html>
<head>
	<title>UPDATE STUDENT'S PROFILE</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <form action="ListStudents.php">
        <input class="btn btn-success" type="submit" value="Go back!" >
    </form>
	<div class="container">
		<div class="panel panel-primary">
            <h2 class="text-center">Update Student's Profile </h2>
			<div class="panel-body">
                <form method="post">
			    	<div class="form-group">
				        <label for="usr">Username:</label>
				        <input required="true" type="text" class="form-control"name="username" value="<?=$s_username?>">
				    </div>
                    <?php
                        echo "<p style=\"color:red; font-size: 85%\">". $msg_username ."</p>"
                    ?>
                    <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input required="true" type="password" class="form-control" name="password" value="<?=$s_password?>">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Confirm password:</label>
                        <input required="true" type="password" class="form-control" name="confirm_password" value="<?=$s_confirm_password?>">
                    </div>
                    <?php
                        echo "<p style=\"color:red; font-size: 85%\">". $msg_password ."</p>"
                    ?>
                    <div class="form-group">   
                        <label for="usr">Fullname:</label>
                        <input required="true" type="text" class="form-control" name="fullname" value="<?=$s_fullname?>">
                    </div>
                    <?php
                        echo "<p style=\"color:red; font-size: 85%\">". $msg_fullname ."</p>"
                    ?>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input required="true" type="email" class="form-control" name="email" value="<?=$s_email?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Phone number:</label>
                        <input type="text" class="form-control" name="phone" value="<?=$s_phone?>">
                    </div>
                    <?php
                        echo "<p style=\"color:red; font-size: 85%\">". $msg_phone ."</p>"
                    ?>

                    <?php
                        echo "<p style=\"color:green; font-size: 85%\">". $msg_success ."</p>"
                    ?>
                    <button class="btn btn-success" style="width: 100%;">Update</button>
                </form>
			</div>
		</div>
	</div>
</body>
</html> 