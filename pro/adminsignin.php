<?php
session_start();
require_once '../conn.php';
$file = "admin";
?>

<?php
$cur_page = 'signup';
include 'includes/inc-header.php';

if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
?>
        <script>
            alert("Please fill in all the fields.");
        </script>
<?php
    } else {
        //Check for login
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);

        if (!$check->execute()) {
            die("Form Filled With Error");
        }

        $res = $check->get_result();
        $no_rows = $res->num_rows;

        if ($no_rows == 1) {
            $row = $res->fetch_assoc();
            $hashed_password = $row['password']; // Fetch hashed password from database

            if (password_verify($password, $hashed_password)) {
                // Password matches, proceed with login
                $id = $row['id'];
                session_regenerate_id(true);
                $_SESSION['category'] = "super";
                $_SESSION['admin'] = $id;
?>
                <script>
                    alert("Access Granted!");
                    window.location = "admin.php";
                </script>
<?php
            } else {
?>
                <script>
                    setTimeout(function() {
                        alert("Incorrect password. Please try again.");
                    }, 2000);
                </script>
<?php
            }
        } else {
?>
            <script>
                alert("Access Denied. User not found.");
            </script>
<?php
        }
    }
}
?>

<div class="signup-page">
    <div class="form">
        <h2>Admin Sign In</h2>
        <br>
        <form class="login-form" method="post" role="form" id="signup-form" autocomplete="off">
            <!-- json response will be here -->
            <div id="errorDiv"></div>
            <!-- json response will be here -->

            <div class="col-md-12">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="text" required name="email">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password">
                    <span class="help-block" id="error"></span>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <button type="submit" id="btn-signup">
                        SIGN IN
                    </button>
                </div>
            </div>
            <p class="message">
                <a href="#">.</a><br>
            </p>
        </form>
    </div>
</div>
</div>
<script src="assets/js/jquery-1.12.4-jquery.min.js"></script>
</body>
</html>
