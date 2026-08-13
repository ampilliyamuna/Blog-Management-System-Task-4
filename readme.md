# Blog Management System

## Objective

Develop a simple web application using PHP and MySQL to perform CRUD (Create, Read, Update, Delete) operations with user authentication.

In Task 4, the application was enhanced with security features including prepared statements, server-side and client-side form validation, password security, and role-based access control.

---

## Features

- User Registration
- User Login
- Password Hashing
- Session Management
- Create Blog Posts
- Read Blog Posts
- Update Blog Posts
- Delete Blog Posts
- Search Blog Posts
- Pagination
- User Roles and Permissions
- Role-Based Access Control
- Server-Side Form Validation
- Client-Side Form Validation
- Prepared Statements
- HTML Escaping
- Logout Functionality

---

## Technologies Used

- HTML
- CSS
- PHP
- MySQL
- XAMPP
- Bootstrap
- Bootstrap Icons

---

## Database

**Database Name:** `blog`

### Tables

1. `users`
2. `posts`

The `users` table contains user information including username, password, and role.

The `posts` table stores blog post information including title, content, and creation date.

---

# TASK 4 – Security, Validation and User Roles

## 1. Prepared Statements

Prepared statements were implemented using MySQLi for database operations.

Example:

```php
$stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $content);
$stmt->execute();
```

Prepared statements help protect the application against SQL injection attacks.

---

## 2. Server-Side Form Validation

Server-side validation was implemented using PHP before processing form submissions.

### Registration Validation

The registration form checks:

- Username is provided
- Password is provided
- Confirm password is provided
- Password and confirm password match
- Username does not already exist

Example:

```php
if ($password != $confirm) {
    $message = "Passwords do not match!";
}
```

### Post Validation

The Add Post and Edit Post forms check that the required title and content fields are provided.

User input is also processed using:

```php
$title = trim($_POST['title']);
$content = trim($_POST['content']);
```

This helps maintain data integrity.

---

## 3. Client-Side Form Validation

HTML5 validation was added to improve the user experience.

For example:

```html
<input type="text" name="username" required>
```

and:

```html
<input type="password" name="password" required>
```

The `required` attribute prevents users from submitting empty fields through the normal browser form interface.

Server-side validation is also implemented because client-side validation alone is not sufficient for security.

---

## 4. Password Security

Passwords are not stored as plain text.

During registration, passwords are hashed using:

```php
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
```

During login, the password is verified using:

```php
password_verify($password, $user['password']);
```

This provides better protection for stored user passwords.

---

## 5. User Roles

The `users` table contains a `role` field.

The application currently uses the following roles:

- `admin`
- `user`

### Example

| Username | Role |
|----------|------|
| yamu | admin |
| user1 | user |

The user's role is stored in the session after successful login.

```php
$_SESSION['role'] = $user['role'];
```

---

## 6. Role-Based Access Control

Role-Based Access Control (RBAC) was implemented to restrict operations according to the user's role.

### Admin

An administrator can:

- View posts
- Add posts
- Edit posts
- Delete posts

### User

A normal user can:

- View posts
- Search posts
- Navigate through posts

A normal user cannot:

- Add posts
- Edit posts
- Delete posts

### Permission Table

| Operation | Admin | User |
|-----------|-------|------|
| Login | ✅ | ✅ |
| View Posts | ✅ | ✅ |
| Search Posts | ✅ | ✅ |
| Add Post | ✅ | ❌ |
| Edit Post | ✅ | ❌ |
| Delete Post | ✅ | ❌ |

---

## 7. Access Control Testing

The role permissions were tested using two accounts.

### Admin Testing

**Username:** `yamu`

**Role:** `admin`

The admin successfully accessed:

- Add Post
- Edit Post
- Delete Post

### User Testing

**Username:** `user1`

**Role:** `user`

The user was able to view blog posts.

When the user attempted to edit a post, the application displayed:

> Access Denied! You do not have permission to edit posts.

When the user attempted to add a post, the application displayed:

> Access Denied! You do not have permission to add posts.

This confirms that unauthorized operations are blocked.

---

## 8. Security Measures Implemented

The following security measures were implemented:

- Prepared statements to help prevent SQL Injection
- Password hashing using `password_hash()`
- Password verification using `password_verify()`
- Server-side form validation
- Client-side form validation
- Session authentication
- Role-Based Access Control
- HTML escaping using `htmlspecialchars()` when displaying user-generated content
- Access-denied checks for unauthorized users

---

## 9. Testing Results

| Test Case | Expected Result | Status |
|-----------|-----------------|--------|
| Register new user | User registered successfully | ✅ Pass |
| Duplicate username | Registration rejected | ✅ Pass |
| Password mismatch | Registration rejected | ✅ Pass |
| Valid login | User redirected to dashboard | ✅ Pass |
| Invalid login | Error message displayed | ✅ Pass |
| Admin add post | Post added | ✅ Pass |
| Admin edit post | Post updated | ✅ Pass |
| Admin delete post | Post deleted | ✅ Pass |
| User view posts | Posts displayed | ✅ Pass |
| User add post | Access denied | ✅ Pass |
| User edit post | Access denied | ✅ Pass |
| User delete post | Access denied | ✅ Pass |
| Search posts | Matching posts displayed | ✅ Pass |
| Pagination | Posts displayed page by page | ✅ Pass |

---

## 10. Conclusion

Task 4 enhanced the security of the Blog Management System by implementing prepared statements, form validation, password security, and Role-Based Access Control.

The application was tested using both admin and normal user accounts. Unauthorized operations were successfully blocked for normal users, while administrators were able to perform the required blog management operations.

### Task 4 – Security, Validation and User Roles: COMPLETED ✅

---

# How to Run the Project

1. Install XAMPP.
2. Start Apache and MySQL.
3. Open phpMyAdmin.
4. Create/import the `blog` database.
5. Import the `database.sql` file.
6. Copy the project folder into the `htdocs` directory.
7. Open the browser and visit:

```text
http://localhost/blog/task4/
```

---

## Author

**Yamu**