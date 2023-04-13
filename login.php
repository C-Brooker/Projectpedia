<?php include 'inc/header.php'; ?>

<?php
$name = $password = '';
$nameErr = $passwordErr = '';

//Handling token expiration from addProject.php page
function alert($message, $type)
{
    echo '<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-' . $type . ' d-flex align-items-center justify-content-center h5" role="alert">
                <div>' .
        $message
        . '</div>
            </div>
        </div>
    </div>
</div>';
}
if (isset($_GET['error'])) {
    alert($_GET['error'], "danger");
}


//Form submit with santization
if (isset($_POST['submit'])) {

    //Validate name
    if (empty($_POST['username'])) {
        $nameErr = 'Username is required';
    } else {
        $name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //Validate password
    if (empty($_POST['password'])) {
        $passwordErr = 'Password is required';
    } else {
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if (empty($nameErr) && empty($passwordErr) && empty($cPasswordErr) && empty($emailErr)) {
        try {
            //running select query to check if username exists in database
            $stmt = mysqli_prepare($conn, "SELECT* FROM users WHERE username = ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                //Stores the associative array in row
                $row = $result->fetch_assoc();

                $userId = $row['uid'];
                $hashed_pass = $row['password'];

                //Checking that inputted password matches hashed password
                if (password_verify($password, $hashed_pass)) {
                    //If all the info is valid generate a unique session token and expiry along with a session userid
                    //This will prevent cross-site request forgery in forms the users uses.
                    $token = bin2hex(random_bytes(16));
                    $time = time() + 86400;
                    $_SESSION['token'] = $token;
                    $_SESSION['token_expiry'] = $time;
                    $_SESSION['userId'] = $userId;

                    header('Location: index.php');
                }
            }
            //Name error outputs if password does not match or not results returned from database
            $nameErr = 'Username or Password incorrect';
            $passwordErr = 'Username or Password incorrect';
        } catch (mysqli_sql_exception $e) {
            echo 'Something went wrong ERROR: ' . $error_number;
        }
    }
}
?>

<h2>Log In</h2>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mt-4 w-75">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control <?php if (!empty($nameErr)) echo "is-invalid" ?>" id="username" name="username" placeholder="Enter your name" autocomplete="username" />
        <div class="invalid-feedback">
            <?php echo $nameErr; ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control <?php if (!empty($passwordErr)) echo "is-invalid" ?>" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" />
        <div class="invalid-feedback">
            <?php echo $passwordErr; ?>
        </div>
    </div>
    <div class="mb-3">
        <input type="submit" name="submit" value="Send" class="btn btn-dark w-100" />
    </div>
</form>
<p class="small text-center">Alternatively <a href="register.php"> Register</a> if you do not have an account</p>

<?php include 'inc/footer.php'; ?>