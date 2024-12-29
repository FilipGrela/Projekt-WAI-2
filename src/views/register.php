<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Rejestracja</title>
    <link rel="icon" href="img/travel.png" type="image/x-icon">
</head>
<body>

<section id="login-container">
    <div class="login-card round-corners">
        <h2>Tworzenie konta</h2>
        <form class="login-register-form" id="register-form" action="">

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
                <input type="password" id="password_rep" name="password" placeholder="Hasło" required>
                <p style="color: #70242f">Tutaj error</p>
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