<?php
session_start();

$message = null;

// ✅ Use your working DB credentials here
$conn = new mysqli("localhost", "socialuser", "SocialPass!123", "SocialMediaDB");
if ($conn->connect_error) {
  die("Database connection failed.");
}

// Handle POST (login attempt)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $username = trim($_POST["username"] ?? "");
  $password = $_POST["password"] ?? "";

  $stmt = $conn->prepare("SELECT password FROM Users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->bind_result($stored_hash);

  $user_found = $stmt->fetch();
  $stmt->close();

  if ($user_found && password_verify($password, $stored_hash)) {
    $_SESSION["flash_message"] = "Login Successful";
  } else {
    $_SESSION["flash_message"] = "Login Unsuccessful";
  }

  $conn->close();

  // ✅ PRG: redirect so refresh does NOT resubmit the form
  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

$conn->close();

// Show message once after redirect
if (isset($_SESSION["flash_message"])) {
  $message = $_SESSION["flash_message"];
  unset($_SESSION["flash_message"]);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

<h2>Login Page</h2>

<form method="POST" action="">
  Username: <input type="text" name="username" required><br><br>
  Password: <input type="password" name="password" required><br><br>
  <button type="submit">Login</button>
</form>

<?php if ($message !== null): ?>
  <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

</body>
</html>