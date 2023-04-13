<?php include 'inc/header.php'; ?>

<?php
$name = $password = $cPassword = $email = '';
$nameErr = $passwordErr = $cPasswordErr = $emailErr = '';


//Form submit with santization
if (isset($_POST['submit'])) {

    //Validate name
    if (empty($_POST['username'])) {
        $nameErr = 'Username is required';
    } else {
        $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //Validate Passwords using regex
    if (strlen($_POST['password']) < 8) {
        $passwordErr = "Password must be at least 8 characters long.";
    } elseif (!preg_match("#[0-9]+#", $_POST['password'])) {
        $passwordErr = "Password must include at least one number.";
    } elseif (!preg_match("#[a-zA-Z]+#", $_POST['password'])) {
        $passwordErr = "Password must include at least one letter.";
    } elseif (!preg_match("#[\W]+#", $_POST['password'])) {
        $passwordErr = "Password must include at least one special character.";
    } elseif ($_POST['password'] !== $_POST['cpassword']) {
        $cPasswordErr = "Both passwords must match each other.";
    } else {
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        //Hashing password
        $password = password_hash($password, PASSWORD_DEFAULT);
    }


    //Validate Emails
    if (empty($_POST['email'])) {
        $emailErr = 'Email is required';
    } else {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    }

    if (empty($nameErr) && empty($passwordErr) && empty($cPasswordErr) && empty($emailErr)) {
        try {
            //Add to database if no errors
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, email) VALUES (?,?,?)");
            $stmt->bind_param("sss", $name, $password, $email);
            $stmt->execute();

            //If all the info is valid generate a unique session token and expiry along with a session userid
            //This will prevent cross-site request forgery in forms the users uses.

            header('Location: login.php');
        } catch (mysqli_sql_exception $e) {
            //Username and email are unique keys so checking for duplicate error
            $MYSQLI_CODE_DUPLICATE_KEY = 1062;
            $error_number = $e->getCode();
            $error_message = $e->getMessage();

            if ($MYSQLI_CODE_DUPLICATE_KEY == $error_number) {
                //Checking if string exists within error message to determine type
                if (strpos($error_message, 'username')) {
                    $nameErr = 'Username already exists';
                }
                if (strpos($error_message, 'email')) {
                    $emailErr = 'Email already in use';
                }
            } else {
                echo 'Something went wrong ERROR: ' . $error_number;
            }
        }
    }
}
?>

<h2>Register as a new User</h2>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mt-4 w-75">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control <?php if (!empty($nameErr)) echo "is-invalid" ?>" id="username" name="username" placeholder="Enter your name" />
        <div class="invalid-feedback">
            <?php echo $nameErr; ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control <?php if (!empty($passwordErr)) echo "is-invalid" ?>" id="password" name="password" placeholder="Enter your password" />
        <div class="invalid-feedback">
            <?php echo $passwordErr; ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="cpassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control <?php if (!empty($cPasswordErr)) echo "is-invalid" ?>" id="cpassword" name="cpassword" placeholder="Reenter your password" />
        <div class="invalid-feedback">
            <?php echo $cPasswordErr; ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control <?php if (!empty($emailErr)) echo "is-invalid" ?>" id="email" name="email" placeholder="Enter your email" />
        <div class="invalid-feedback">
            <?php echo $emailErr; ?>
        </div>
    </div>
    <div class="mb-3">
        <input type="submit" name="submit" value="Send" class="btn btn-dark w-100" />
    </div>
</form>
<p class="small text-center">Alternatively <a href="login.php">Log In</a> if you already have an account</p>

<?php include 'inc/footer.php'; ?>