<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<section id="login-container">
    <div class="login-card round-corners">
        <h2>Logowanie</h2>
        <form id="login-form">

            <div class="form-field">
                <label for="login">Login:</label><br>
                <input type="text" id="login" name="login" placeholder="Login" required><br><br>
            </div>

            <div class="form-field">
                <label for="password">Hasło:</label><br>
                <input type="password" id="password" name="password" placeholder="Hasło" required><br><br>
            </div>

            <div style="display: flex; flex-direction: row">
                <div class="login-buttons">
                    <button class="button" type="submit" form="login-form">Zaloguj</button>
                </div>
                <div class="login-buttons" style="padding-left: 20px">
                    <a class="button" href="register.php">Stwórz konto</a>
                </div>
            </div>

        </form>


    </div>
</section>
</body>
</html>