<?php
session_start();

$message = null;

// ✅ Use your working DB credentials here
$conn = new mysqli("localhost", "socialuser", "SocialPass!123", "SocialMediaDB");
if ($conn->connect_error) {
  die("Database connection failed.");
}

// -------------------------------
// 1) LOGIN HANDLER (your original)
// -------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["doLogin"])) {

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

  // ✅ PRG redirect
  header("Location: " . $_SERVER["PHP_SELF"]);
  exit;
}

// Show message once after redirect
if (isset($_SESSION["flash_message"])) {
  $message = $_SESSION["flash_message"];
  unset($_SESSION["flash_message"]);
}

// -------------------------------------
// 2) Helper: print any SQL result table
// -------------------------------------
function renderTableFromQuery(mysqli $conn, string $title, string $sql): void
{
  echo "<h3>" . htmlspecialchars($title) . "</h3>";
  echo "<pre style='background:#f6f6f6;padding:10px;border:1px solid #ddd;'>" . htmlspecialchars($sql) . "</pre>";

  $result = $conn->query($sql);

  if ($result === false) {
    echo "<p style='color:red;'>Query error: " . htmlspecialchars($conn->error) . "</p>";
    return;
  }

  if ($result->num_rows === 0) {
    echo "<p>No records found.</p>";
    return;
  }

  // Table header (dynamic)
  echo "<table border='1' cellpadding='6' cellspacing='0'>";
  echo "<tr>";
  foreach ($result->fetch_fields() as $field) {
    echo "<th>" . htmlspecialchars($field->name) . "</th>";
  }
  echo "</tr>";

  // Table rows
  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach ($row as $value) {
      $cell = ($value === null) ? "NULL" : (string)$value;
      echo "<td>" . htmlspecialchars($cell) . "</td>";
    }
    echo "</tr>";
  }

  echo "</table><br>";
  $result->free();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

<h2>Login Page</h2>

<!-- ✅ LOGIN FORM -->
<form method="POST" action="">
  <input type="hidden" name="doLogin" value="1">
  Username: <input type="text" name="username" required><br><br>
  Password: <input type="password" name="password" required><br><br>
  <button type="submit">Login</button>
</form>

<?php if ($message !== null): ?>
  <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<hr>

<!-- ✅ JOIN QUERIES BUTTON -->
<form method="POST" action="">
  <button type="submit" name="showJoins">Show JOIN Results</button>
</form>

<?php
// -------------------------------------
// 3) JOIN QUERIES (Natural, Inner, Outer)
// -------------------------------------
if (isset($_POST["showJoins"])) {

  // NATURAL JOIN (joins by common column name(s), here: username)
  $sqlNatural = "
    SELECT *
    FROM Users NATURAL JOIN UserDetails
  ";

  // INNER JOIN (same matching rows only)
  $sqlInner = "
    SELECT *
    FROM Users INNER JOIN UserDetails
    ON Users.username = UserDetails.username
  ";

  // LEFT OUTER JOIN (all Users even if no match in UserDetails)
  $sqlLeft = "
    SELECT *
    FROM Users LEFT OUTER JOIN UserDetails
    ON Users.username = UserDetails.username
  ";

  // RIGHT OUTER JOIN (all UserDetails even if no match in Users)
  $sqlRight = "
    SELECT *
    FROM Users RIGHT OUTER JOIN UserDetails
    ON Users.username = UserDetails.username
  ";

  // FULL OUTER JOIN (MySQL doesn't support it directly → simulate with UNION)
  // This follows the slide approach: LEFT JOIN UNION RIGHT JOIN. :contentReference[oaicite:1]{index=1}
  $sqlFull = "
    SELECT *
    FROM Users LEFT OUTER JOIN UserDetails
    ON Users.username = UserDetails.username
    UNION
    SELECT *
    FROM Users RIGHT OUTER JOIN UserDetails
    ON Users.username = UserDetails.username
  ";

  echo "<h2>JOIN Query Outputs</h2>";

  renderTableFromQuery($conn, "NATURAL JOIN", $sqlNatural);
  renderTableFromQuery($conn, "INNER JOIN", $sqlInner);
  renderTableFromQuery($conn, "LEFT OUTER JOIN", $sqlLeft);
  renderTableFromQuery($conn, "RIGHT OUTER JOIN", $sqlRight);
  renderTableFromQuery($conn, "FULL OUTER JOIN (Simulated with UNION)", $sqlFull);
}

$conn->close();
?>

</body>
</html>