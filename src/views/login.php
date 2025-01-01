<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Logowanie</title>
    <link rel="icon" href="img/travel.png" type="image/x-icon">
    <script src="scripts\remove_error_msg.js"></script>
</head>
<body>

<section id="login-container">
    <div class="login-card round-corners">
        <h2>Logowanie</h2>
        <form class="login-register-form" id="login-form" action="/login/login_user" method="post">

            <div class="form-field">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" placeholder="Login" required>
            </div>

            <div class="form-field">
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" placeholder="Hasło" required>
            </div>

            <div style="display: flex; flex-direction: row; justify-content: center;">
                <div class="login-buttons">
                    <button class="button" type="submit" form="login-form">Zaloguj</button>
                    <a class="button" href="/register">Stwórz konto</a>
                </div>
            </div>

            <?php $error_message = isset($_GET['error_message']) ? htmlspecialchars($_GET['error_message']) : null;;
            if ($error_message): ?>
                <br><p class="error_message"><?= $error_message ?></p>
            <?php endif; ?>

        </form>
    </div>
</section>
</body>
</html>