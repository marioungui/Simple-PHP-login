# PHP Login Page

This is a simple login page for a web application built using PHP, HTML, CSS, and Bootstrap. The login page allows users to log in with their credentials and provides password reset functionality through email.

## Features

- **Login Form**: Users can enter their username and password to log in to the web application.
- **Validation**: The login form validates user inputs and displays appropriate error messages for invalid credentials or non-existent user accounts.
- **Forgot Password**: Users can reset their passwords by clicking on the "Forgot Password?" link. They will receive a password reset link via email.
- **Password Reset**: The password reset link sent via email allows users to create a new password.
- **Security**: User passwords are securely hashed before storing them in the database using PHP's `password_hash` function. The application also uses prepared statements to prevent SQL injection.
- **Session Handling**: User sessions are managed using PHP's `$_SESSION` superglobal to maintain user login status across pages.
- **Database**: The application uses a MySQL database to store user information, including usernames, passwords, full names, and businesses.
- **Role-Based Access**: Users have different roles (admin, supervisor, user) based on which certain pages or features are accessible.

## Pages

The login application consists of the following pages:

1. `login.php`: The main login page where users can enter their credentials and log in. It handles user authentication and displays error messages for invalid login attempts.
2. `create-user.php`: A page for admin users to create new user accounts. It includes form fields for username, password, full name, and business. Passwords are hashed before storage in the database.
3. `create_user_process.php`: The backend script that handles user creation and inserts user data into the database.
4. `dashboard.php`: An example dashboard page accessible to authenticated users. It shows user-specific information.
5. `header.php`: A header file included in various pages to check user authentication and load necessary CSS and meta tags.
6. `password-forgot.php`: The page to request a password reset link. Users can enter their email address to receive the reset link via email.
7. `password-forgot-confirm.php`: The confirmation page shown after a password reset link has been requested.
8. `password-reset.php`: The page to reset the password after clicking the password reset link received via email.
9. `user-created.php`: The success page displayed after successfully creating a new user account.
10. `index.php`: The main page of application with the user logged in.

## Technologies Used

- PHP 8.1
- HTML
- CSS (Bootstrap framework)
- MySQL database
- PHPMailer (for sending password reset emails)
- PDO (for database interactions)
- JavaScript (Bootstrap's JavaScript for UI enhancements)

## Installation and Setup

1. Clone the repository to your local machine.
2. Execute the SQL file in order to install necessary tables
3. Update the database credentials in the PHP files (e.g., `create_user_process.php`, `password-reset.php`) to connect to your database.
4. Install PHPMailer via Composer to enable password reset email functionality.

## Getting Started

1. Start a local server to run the PHP application.
2. Open your web browser and navigate to the login page (`login.php`).
3. Login with the famous "admin" user and "admin" password
4. Create a new user account with the `create-user.php` page if you have admin privileges.

Feel free to explore the application and make any necessary modifications for your specific use case.

## Contributions

Contributions to the project are welcome! If you find any issues or have improvements to suggest, please open an issue or create a pull request.

## License

This project is licensed under the [MIT License](LICENSE). Feel free to use and modify it as needed.

For any questions or assistance, please feel free to contact the project maintainers.

---
