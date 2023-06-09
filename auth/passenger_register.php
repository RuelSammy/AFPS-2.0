<?php
session_start();
// check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get the user inputs
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    require "../db_conn.php";

    // hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // check if the user already exists in the database
    $query = "SELECT * FROM passengers WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        // if user already exists, display an error message
        header("location: ../passenger/register.php?error=userexists");
    } else {
        // if user does not exist, insert user information into the database
        $query = "INSERT INTO passengers (username, email, password) VALUES ('$username', '$email', '$hashed_password');";
        if (mysqli_query($conn, $query)) {
            $query2 = "INSERT INTO accounts (passenger) VALUES ('$username');";
            if (mysqli_query($conn, $query2)) {
                header("location: ../passenger/register.php?success=usercreated");
            }
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }

    // close the database connection
    mysqli_close($conn);
}
