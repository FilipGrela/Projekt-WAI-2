<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Rejestracja</title>
    <link rel="icon" href="img/travel.png" type="image/x-icon">
    <meta charset="UTF-8">
    <script src="scripts\remove_error_msg.js"></script>
</head>
<body>

<section id="login-container">
    <div class="login-card round-corners">
        <h2>Tworzenie konta</h2>
        <form class="login-register-form" id="register-form" action="/register/add_user" method="post">

            <div class="form-field">
                <label for="email">Login:</label>
                <input type="email" id="email" name="email" placeholder="your@email.here" required>
            </div>

            <div class="form-field">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" placeholder="Login" required>
            </div>

            <div class="form-field">
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" placeholder="Hasło" required>
            </div>
            <div class="form-field">
                <label for="password_rep">Powtórz hasło:</label>
                <input type="password" id="password_rep" name="password_rep" placeholder="Hasło" required>
                <?php $error_message = isset($_GET['error_message']) ? htmlspecialchars($_GET['error_message']) : null;;
                if ($error_message): ?>
                    <br><p class="error_message"><?= $error_message ?></p>
                <?php endif; ?>
            </div>

            <div style="display: flex; flex-direction: row; justify-content: center;">
                <div class="login-buttons">
                    <button class="button" name="SubmitButton" type="submit" >Stwórz konto</button>
                    <a class="button" href="/login">Zaloguj</a>
                </div>
            </div>

        </form>

    </div>
</section>
</body>
</html>