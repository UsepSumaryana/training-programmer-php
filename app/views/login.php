
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>

    <?php if (!empty($errorMsg)) : ?>
        <p style="color: red;"><?= $errorMsg; ?></p>
    <?php endif; ?>

    <form method="post" action="/../user/login">

        <label>Username:</label><br>
        <input required type="text" name="username"><br><br>

        <label>Password:</label><br>
        <input required type="password" name="password"><br><br>

        <button name="submit" type="submit">Login</button>

    </form>
</body>

</html>
