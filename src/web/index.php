<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section id="login-container">
    <div class="login-card round-corners">
        <h2>Logowanie</h2>
        <form class="login-register-form" id="login-form">

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
                    <a class="button" href="register.php">Stwórz konto</a>
                </div>
            </div>

        </form>
    </div>
</section>
</body>
</html>