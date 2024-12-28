<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section id="login-container">
    <div class="login-card round-corners">
        <h2>Tworzenie konta</h2>
        <form class="login-register-form" id="register-form" action="actions/actions.php">

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
                <input type="password" id="password_rep" name="password" placeholder="Hasło" required>
            </div>

            <div style="display: flex; flex-direction: row; justify-content: center;">
                <div class="login-buttons">
                    <button class="button" type="submit" form="register-form">Stwórz konto</button>
                    <a class="button" href="index.php">Zaloguj</a>
                </div>
            </div>

        </form>


    </div>
</section>
</body>
</html>