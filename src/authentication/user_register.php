<?php
include "../common.php";
?>

    <html>
    <body>

    <center>
        <h1>User Register</h1> <br>

        <form action="user_register.php" method="post">
            <br>
            First Name <br><input type="text" name="first_name" required><br><br>
            Last Name <br><input type="text" name="last_name" required><br><br>
            E-mail <br><input type="text" name="email"><br><br>
            Phone Number <br><input type="tel" name="phone"><br><br>
            Username <br><input type="text" name="username" required><br><br>
            Password <br><input type="password" name="password1" required><br><br>
            Password (Again) <br><input type="password" name="password2" required><br><br>

            <input type="submit" name="submit" value="Register!">
        </form>
    </center>

    </body>
    </html>

<?php

if (isset($_POST['submit'])) {
    // Check the password equality
    if ($_POST['password1'] == $_POST['password2']) {
        $user_save_sql = oci_parse($connection, '
            BEGIN
                INSERT_USER(:user_type, :first_name, :last_name, :username, :email, :phone, :password);
                COMMIT;
            END;'
        );

        // Encrypt the password
        $encrypted_password = md5($_POST['password1']);

        // Add arguments
        oci_bind_by_name($user_save_sql, ":user_type", $USER_TYPE_MEMBER);
        oci_bind_by_name($user_save_sql, ":first_name", $_POST['first_name']);
        oci_bind_by_name($user_save_sql, ":last_name", $_POST['last_name']);
        oci_bind_by_name($user_save_sql, ":username", $_POST['username']);
        oci_bind_by_name($user_save_sql, ":email", $_POST['email']);
        oci_bind_by_name($user_save_sql, ":phone", $_POST['phone']);
        oci_bind_by_name($user_save_sql, ":password", $encrypted_password);

        // Execute the SQL code
        oci_execute($user_save_sql);

        echo "<center><p style='color: green'>You have successfully registered.</p></center>";
        create_user_log('Registered to the website. Welcome! :)');
    } else {
        echo "<center><p style='color: red'>The passwords you entered are not equal!</p></center>";
    }
}
?>