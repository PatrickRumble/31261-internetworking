

<!DOCTYPE html>
<html>
<head>
    <title>Internetworking Project Exercise 2 - SQL Injection</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Internetworking Project Exercise 2 - SQL Injection</h1>
        <h2>Login Page</h2>
        <img src="images/UTS.png" alt="UTS Logo" class="uts-logo">
    </header>

    <main>
        <div class="login-box">  
            <form action="login.php" method="post">
                <label for="username">Username</label>
                <input type="text" placeholder="Enter Username" name="usr" id="username" required>

                <label for="password">Password</label>
                <input type="password" placeholder="Enter Password" name="psw" id="password" required>

                <input type="submit" value="Login" name="submit">
            </form>
           
        </div>
    </main>
</body>
</html>
