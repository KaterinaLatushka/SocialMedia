<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Login Page</h2>

<form method ="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

<?php
  $conn = new mysqli("localhost", "root", "", "SocialMediaDB");
  $message = "";

  if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
      $message = "Login successful!";
    } else {
      $message = "Invalid username or password.";
    }
  }
?>

<p style="color:red;">
    <?php echo $message;?></p>

</body>
</html>