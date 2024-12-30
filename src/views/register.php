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
        <form class="login-register-form" id="register-form" action="/register/add_user" method="post">

            <div class="form-field">
                <label for="email">Login:</label>
                <input type="email" id="email" name="email" placeholder="your@email.here" required value="your@email.here">
            </div>

            <div class="form-field">
                <label for="login">Login:</label>
                <input type="text" id="login" name="login" placeholder="Login" required value="Login">
            </div>

            <div class="form-field">
                <label for="password">Hasło: 123</label>
                <input type="password" id="password" name="password" placeholder="Hasło" required value="123">
            </div>
            <div class="form-field">
                <label for="password_rep">Powtórz hasło:</label>
                <input type="password" id="password_rep" name="password_rep" placeholder="Hasło" required>
                <?php if (isset($_SESSION['password_message']) && $_SESSION['password_message']): ?>
                    <br><p class="error_message"><?= $_SESSION['password_message'] ?></p>
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