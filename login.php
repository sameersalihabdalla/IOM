<?php
# Initialize session
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='./'" . "</script>";
  exit;
}

# Include connection
require_once "./config.php";

# Define variables and initialize with empty values
$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["user_login"]))) {
    $user_login_err = "يرجى إدخال اسم المستخدم أو البريد الإلكتروني.";
  } else {
    $user_login = trim($_POST["user_login"]);
  }

  if (empty(trim($_POST["user_password"]))) {
    $user_password_err = "يرجى إدخال كلمة المرور.";
  } else {
    $user_password = trim($_POST["user_password"]);
  }

  # Validate credentials 
  if (empty($user_login_err) && empty($user_password_err)) {
    # Prepare a select statement
    $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind variables to the statement as parameters
      mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);

      # Set parameters
      $param_user_login = $user_login;

      # Execute the statement
      if (mysqli_stmt_execute($stmt)) {
        # Store result
        mysqli_stmt_store_result($stmt);

        # Check if user exists, If yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          # Bind values in result to variables
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

          if (mysqli_stmt_fetch($stmt)) {
            # Check if password is correct
            if (password_verify($user_password, $hashed_password)) {

              # Store data in session variables
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION["loggedin"] = TRUE;

              # Redirect user to index page
              echo "<script>" . "window.location.href='./'" . "</script>";
              exit;
            } else {
              # If password is incorrect show an error message
              $login_err = "البريد الإلكتروني أو كلمة المرور التي أدخلتها غير صحيحة.";
            }
          }
        } else {
          # If user doesn't exists show an error message
          $login_err = "اسم المستخدم أو كلمة المرور غير صالحة.";
        }
      } else {
        echo "<script>" . "alert('عذراً! حدث خطأ ما. يرجى المحاولة مرة أخرى لاحقاً.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php'" . "</script>";
        exit;
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل الدخول - نظام إدارة الحسابات</title>
  <!-- استخدام Bootstrap 5 مع دعم اللغة العربية RTL -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
  <link rel="stylesheet" href="./css/main.css">
  <link rel="shortcut icon" href="./img/favicon-16x16.png" type="image/x-icon">
  <style>
   
    .card-custom {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      background-color: #ffffff;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #0d6efd;
    }
  </style>
  <script defer src="./js/script.js"></script>
</head>

<body>
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center py-5">
      <div class="col-md-6 col-lg-5">
        
        <?php
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $login_err . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        ?>

        <div class="card card-custom p-4 p-md-5">
          <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">تسجيل الدخول</h2>
            <p class="text-muted">مرحباً بك مجدداً، يرجى إدخال بياناتك للمتابعة</p>
          </div>

          <!-- form starts here -->
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="user_login" class="form-label fw-semibold">البريد الإلكتروني أو اسم المستخدم</label>
              <input type="text" class="form-control form-control-lg" name="user_login" id="user_login" value="<?= $user_login; ?>" placeholder="name@example.com">
              <small class="text-danger"><?= $user_login_err; ?></small>
            </div>
            
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">كلمة المرور</label>
              <input type="password" class="form-control form-control-lg" name="user_password" id="password" placeholder="********">
              <small class="text-danger"><?= $user_password_err; ?></small>
            </div>

            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label text-secondary">إظهار كلمة المرور</label>
            </div>

            <div class="d-grid mb-3">
              <button type="submit" name="submit" class="btn btn-primary btn-lg">تسجيل الدخول</button>
            </div>

          
          </form>
          <!-- form ends here -->
        </div>

      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.com/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>