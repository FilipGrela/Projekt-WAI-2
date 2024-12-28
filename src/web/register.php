<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section id="login-container">
    <div class="login-card round-corners">
        <h2>Tworzenie konta</h2>
        <form id="register-form" action="actions/actions.php">

            <div class="form-field">
                <label for="login">Login:</label><br>
                <input type="text" id="login" name="login" placeholder="Login" required><br><br>
            </div>

            <div class="form-field">
                <label for="password">Hasło:</label><br>
                <input type="password" id="password" name="password" placeholder="Hasło" required><br><br>
            </div>
            <div class="form-field">
                <label for="password_rep">Powtórz hasło:</label><br>
                <input type="password" id="password_rep" name="password" placeholder="Hasło" required><br><br>
            </div>

            <div style="display: flex; flex-direction: row">
                <div class="login-buttons" style="padding-right: 20px">
                    <button class="button" type="submit" form="register-form">Stwórz konto</button>
                </div>
                <div class="login-buttons">
                    <a class="button" href="index.php">Zaloguj</a>
                </div>
            </div>

        </form>


    </div>
</section>
</body>
</html>