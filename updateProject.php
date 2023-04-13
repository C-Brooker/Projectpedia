<?php include 'inc/header.php'; ?>

<?php

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
    $phase = $_GET['phase'];
    $body = $_GET['body'];
    $projectId = $_GET['projectId'];
}

$nameErr = $phaseErr = $bodyErr = $startErr = $endErr = '';
function logout()
{
    session_unset();
    session_destroy();
}

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

$token = $_SESSION['token'];

//When submitting the form checking that there is a valid session token
if (isset($_POST['submit'])) {
    if (isset($_POST['token']) && isset($_SESSION['token']) && isset($_SESSION['token_expiry'])) {
        //Checking that the session token has not expired
        if ($_SESSION['token_expiry'] < time()) {
            logout();
            header("Location: login.php? error=expired");
            exit;
        }

        //Checking that the session and post token match eachother
        if ($_SESSION['token'] === $_POST['token']) {
            //Processing form submittion with santization

            $projectId = filter_input(INPUT_POST, 'projectId', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //Validate name
            if (empty($_POST['title'])) {
                $nameErr = 'Project title is required';
            } else {
                $name = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }

            //Validate phase
            if (empty($_POST['phase'])) {
                $phaseErr = 'Development phase is required';
            } else {
                $phase = filter_input(INPUT_POST, 'phase', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }

            //Validate body
            if (empty($_POST['body'])) {
                $bodyErr = 'Project description is required';
            } else {
                $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }

            //Validate dates
            if (empty($_POST['startDate'])) {
                echo $_POST['startDate'];
                $startErr = 'Project start date is required';
            } else if (empty($_POST['endDate'])) {
                $endErr = 'Project end date is required';
            } else if (strtotime($_POST['startDate']) > strtotime($_POST['endDate'])) {
                $startErr = $endErr = 'Project end date cannot be before start date';
            } else {
                $startDate = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }

            if (empty($nameErr) && empty($phaseErr) && empty($bodyErr) && empty($startErr) && empty($endErr)) {
                try {
                    $stmt = mysqli_prepare($conn, "UPDATE projects SET title = ?, start_date = ?, end_date = ?, phase = ?, description = ? WHERE pid = ?");
                    $stmt->bind_param("ssssss", $name, $startDate, $endDate, $phase, $body, $projectId);
                    $stmt->execute();
                    alert("Project successfully updated", "success");
                } catch (mysqli_sql_exception $e) {
                    $error_number = $e->getCode();
                    echo "Something went wrong Error: " . $error_number;
                }
            }
        } else {
            logout();
            header("Location: login.php? error=invalid_token");
            exit;
        }
        //Otherwise token does not exist
    } else {
        logout();
        header("Location: login.php? error=missing_token");
        exit;
    }
}

?>



<h2>Update Project</h2>
<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mt-4 w-75">
    <input type="hidden" name="token" value=<?php echo $token ?>>
    <input type="hidden" name="projectId" id="projectId" value=<?php echo $projectId ?>>
    <div class=" mb-3">
        <label for="title" class="form-label">Project Title</label>
        <input type="text" class="form-control <?php if (!empty($nameErr)) echo "is-invalid" ?>" id="title" name="title" value="<?php echo $name; ?>" />
        <div class="invalid-feedback">
            <?php echo $nameErr; ?>
        </div>
    </div>
    <div class="mb-3">
        <label for="phase" class="form-label">Development Phase</label>
        <select class="form-select <?php if (!empty($phaseErr)) echo "is-invalid" ?>" id="phase" name="phase" aria-label="select">
            <option value="design" <?php if ($phase === "design") echo 'selected'; ?>>design</option>
            <option value="development" <?php if ($phase === "development") echo 'selected'; ?>>development</option>
            <option value="testing" <?php if ($phase === "testing") echo 'selected'; ?>>testing</option>
            <option value="deployment" <?php if ($phase === "deployment") echo 'selected'; ?>>deployment</option>
            <option value="complete" <?php if ($phase === "complete") echo 'selected'; ?>>complete</option>'
        </select>

        <div class="invalid-feedback">
            <?php echo $phaseErr; ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="body" class="form-label">Project Description</label>
        <textarea class="form-control <?php if (!empty($bodyErr)) echo "is-invalid" ?>" rows=5 id="body" name="body" value="<?php echo $body; ?>"><?php echo $body; ?></textarea>

        <div class="invalid-feedback">
            <?php echo $bodyErr; ?>
        </div>
    </div>

    <div class="mb-3 row">
        <div class="col-md-6">
            <label for=" startDate" class="form-label">Start Date</label>
            <input type="date" class="form-control <?php if (!empty($startErr)) echo "is-invalid" ?>" id="startDate" name="startDate" value="<?php echo $startDate; ?>">
            <div class="invalid-feedback"><?php echo $startErr ?></div>
        </div>
        <div class="col-md-6">
            <label for="endDate" class="form-label">End Date</label>
            <input type="date" class="form-control <?php if (!empty($endErr)) echo "is-invalid" ?>" id="endDate" name="endDate" value="<?php echo $endDate; ?>">
            <div class="invalid-feedback"><?php echo $endErr ?></div>
        </div>
    </div>

    <div class="mb-3">
        <input type="submit" name="submit" value="Send" class="btn btn-dark w-100" />
    </div>
</form>

<?php include 'inc/footer.php'; ?>