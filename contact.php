<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/config.php';

$ok = false; 
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) $errors[] = 'Invalid name';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
    if ($message === '' || mb_strlen($message) < 10) $errors[] = 'Message too short';

    if (!$errors) {
        $stmt = $mysqli->prepare('INSERT INTO messages (name, email, message) VALUES (?, ?, ?)');
        if (!$stmt) { 
            $errors[] = 'Server error'; 
        } else {
            $stmt->bind_param('sss', $name, $email, $message);
            $ok = $stmt->execute();
            if (!$ok) { 
                $errors[] = 'Unable to save the message'; 
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Contact — Raffaele Cammi</title>
<link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
<header>
<div class="container nav">
<strong>RC</strong>
<nav>
<a href="index.html">Home</a>
<a href="about.html">About</a>
<a href="portfolio.html">Projects</a>
<a href="cv.html">CV</a>
<a href="contact.php">Contact</a>
</nav>
</div>
</header>

<main class="container section">
<h1>Contact</h1>
<p class="lead">Write me a message: I’ll reply as soon as possible.</p>

<?php if ($ok): ?>
<div class="card" role="status">✅ Message successfully sent! I’ll get back to you soon.</div>
<?php endif; ?>

<?php if ($errors): ?>
<div class="card" role="alert">
<strong>Some errors occurred:</strong>
<ul>
<?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li><?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form method="post" action="contact.php" novalidate>
<label for="name">Name</label>
<input id="name" name="name" type="text" required minlength="2" />

<label for="email">Email</label>
<input id="email" name="email" type="email" required />

<label for="message">Message</label>
<textarea id="message" name="message" rows="6" required minlength="10"></textarea>

<button type="submit">Send</button>
</form>
</main>

<footer>
<div class="container">© <?= date('Y') ?> Raffaele Cammi</div>
</footer>
</body>
</html>
