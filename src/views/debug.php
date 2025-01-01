<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Debug Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.5;
        }
        .debug-block {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .debug-block h2 {
            margin-top: 0;
            font-size: 18px;
        }
        pre {
            background: #333;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<h1>PHP Debug Info</h1>

<!-- Sesja -->
<div class="debug-block">
    <h2>$_SESSION</h2>
    <pre><?php echo htmlspecialchars(print_r($_SESSION, true)); ?></pre>
</div>

<!-- Ciasteczka -->
<div class="debug-block">
    <h2>$_COOKIE</h2>
    <pre><?php echo htmlspecialchars(print_r($_COOKIE, true)); ?></pre>
</div>

<!-- POST -->
<div class="debug-block">
    <h2>$_POST</h2>
    <pre><?php echo htmlspecialchars(print_r($_POST, true)); ?></pre>
</div>

<!-- GET -->
<div class="debug-block">
    <h2>$_GET</h2>
    <pre><?php echo htmlspecialchars(print_r($_GET, true)); ?></pre>
</div>

<!-- SERVER -->
<div class="debug-block">
    <h2>$_SERVER</h2>
    <pre><?php echo htmlspecialchars(print_r($_SERVER, true)); ?></pre>
</div>

<!-- Informacje o konfiguracji PHP -->
<div class="debug-block">
    <h2>PHP Configuration</h2>
    <pre><?php echo htmlspecialchars(print_r(ini_get_all(null, false), true)); ?></pre>
</div>

<!-- PHP Info -->
<div class="debug-block">
    <h2>PHP Info</h2>
    <pre>
            <?php ob_start(); phpinfo(INFO_CONFIGURATION); $php_info = ob_get_clean();
            echo strip_tags($php_info); ?>
        </pre>
</div>
</body>
</html>