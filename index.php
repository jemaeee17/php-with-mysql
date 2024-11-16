<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registrationdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <title>Online Registration</title>
</head>
<body>
    <h1>Online Registration</h1>

    <?php
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Display error messages
    $lastnameErr = $firstnameErr = $ageErr = $contactnumErr = $emailErr = $addressErr = "";
    $isFormValid = true;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $lastname = $firstname = $middleInitial = $age = $contactnum = $email = $address = "";

        if (!empty($_POST["lastname"])) {
            $lastname = test_input($_POST["lastname"]);
        } else {
            $lastnameErr = "Last Name is required!";
            $isFormValid = false;
        }
        if (!empty($_POST["firstname"])) {
            $firstname = test_input($_POST["firstname"]);
        } else {
            $firstnameErr = "First Name is required!";
            $isFormValid = false;
        }
        if (!empty($_POST["middleInitial"])) {
            $middleInitial = test_input($_POST["middleInitial"]);
        }
        if (!empty($_POST["age"])) {
            $age = test_input($_POST["age"]);
        } else {
            $ageErr = "Age is required!";
            $isFormValid = false;
        }
        if (!empty($_POST["contactnum"])) {
            $contactnum = test_input($_POST["contactnum"]);
        } else {
            $contactnumErr = "Contact No. is required!";
            $isFormValid = false;
        }
        if (!empty($_POST["email"])) {
            $email = test_input($_POST["email"]);
        } else {
            $emailErr = "E-mail is required!";
            $isFormValid = false;
        }
        if (!empty($_POST["address"])) {
            $address = test_input($_POST["address"]);
        } else {
            $addressErr = "Address is required!";
            $isFormValid = false;
        }

        if ($isFormValid) {
            // Insert data into database
            $stmt = $conn->prepare("INSERT INTO users (lastname, firstname, middleInitial, age, contactnum, email, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisss", $lastname, $firstname, $middleInitial, $age, $contactnum, $email, $address);

            if ($stmt->execute()) {
                echo "<p>Registration successful! Redirecting to display page...</p>";
                $_SESSION["lastname"] = $lastname;
                $_SESSION["firstname"] = $firstname;
                $_SESSION["middleInitial"] = $middleInitial;
                $_SESSION["age"] = $age;
                $_SESSION["contactnum"] = $contactnum;
                $_SESSION["email"] = $email;
                $_SESSION["address"] = $address;

                header("Location: index.php");
                exit();
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        }
    }
    $conn->close();
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="lastname">Last Name</label><span class="error">* <?php echo $lastnameErr; ?></span><br>
        <input id="lastname" type="text" name="lastname"><br>

        <label for="firstname">First Name</label><span class="error">* <?php echo $firstnameErr; ?></span><br>
        <input id="firstname" type="text" name="firstname"><br>

        <label for="middleInitial">Middle Initial</label><br>
        <input id="middleInitial" type="text" name="middleInitial"><br>

        <label for="age">Age</label><span class="error">* <?php echo $ageErr; ?></span><br>
        <input id="age" type="number" name="age"><br>

        <label for="contactnum">Contact No.</label><span class="error">* <?php echo $contactnumErr; ?></span><br>
        <input id="contactnum" type="text" name="contactnum"><br>

        <label for="email">E-mail</label><span class="error">* <?php echo $emailErr; ?></span><br>
        <input id="email" type="email" name="email"><br>

        <label for="address">Address</label><span class="error">* <?php echo $addressErr; ?></span><br>
        <input id="address" type="text" name="address"><br><br>

        <button id="submit-btn" type="submit">Submit</button>
    </form>
</body>
</html>
