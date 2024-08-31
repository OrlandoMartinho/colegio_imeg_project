Here's the updated README with the link to your repository:

---

# Mini Management System for the IMEG Private School Complex

## Description

This project aims to develop a school management system using a database to store and manage information about courses, subjects, students, and grades. The goal is to provide an easy-to-use interface for administrators and system users to efficiently access and manipulate school data.

## Repository

You can find the project repository on GitHub: [colegio_imeg_project](https://github.com/OrlandoMartinho/colegio_imeg_project/tree/full-stack-feacture).

## Database Structure

The `colegio_imeg_bd` database contains the following main tables:

- **courses**: Manages the courses offered by the school.
- **subjects**: Records the subjects offered within each course.
- **users**: Contains information about users, including students, teachers, and administrators.
- **grades**: Records the students' grades in different subjects.
- **highlights**: Stores news and important events related to the school.
- **contacts**: Keeps contact information for users and administrators.

## Documentation

The project documentation is located in the `docs` folder. Inside this folder, you will find:

- **database**: Contains detailed documentation about the database and the SQL script located at `docs/database/script.sql` to create the database schema and insert initial data.
- **UML**: Contains the class diagram and the entity-relationship diagram of the system.

## Technologies Used

- **MySQL**: Relational database used to store information.
- **PHP**: Programming language for backend development.
- **HTML/CSS/JavaScript**: For the user interface.
- **Bootstrap**: CSS framework for responsive design and modals.

## Requirements

- **PHP** 7.4 or higher
- **MySQL** 5.7 or higher
- Apache or Nginx Web Server

## Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/OrlandoMartinho/colegio_imeg_project/tree/full-stack-feacture.git
   ```

2. **Set Up the Database**:
   - Create a MySQL database named `colegio_imeg_bd`.
   - **Import the SQL script** located at `docs/database/script.sql` to create the tables and insert initial data.
   - Use the command line or MySQL client to import the file:
   ```bash
   mysql -u root -p colegio_imeg_bd < docs/database/script.sql
   ```

3. **Configure the `.env` File**:
   - Rename the `.env.example` file to `.env`.
   - Set up the environment variables with your database credentials as shown below:

   ```env
   DB_SERVERNAME=localhost
   DB_USERNAME=root
   DB_PASSWORD=
   DB_NAME=colegio_imeg_bd
   ```

4. **Configure XAMPP for Sending Emails**:
   - Open the `php.ini` file located in the `xampp/php` directory.
   - Find the `[mail function]` section and configure it as follows:
   ```ini
   [mail function]
   SMTP=smtp.example.com
   smtp_port=587
   sendmail_from = your-email@example.com
   sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
   ```
   - Open the `sendmail.ini` file located in the `xampp/sendmail` directory.
   - Configure the file with your email provider's SMTP settings:
   ```ini
   [sendmail]
   smtp_server=smtp.example.com
   smtp_port=587
   auth_username=your-email@example.com
   auth_password=your-email-password
   ```

5. **Run the Local Server**:
   - Use the PHP built-in server:
   ```bash
   php -S localhost:8000
   ```

## Usage

- Access the system in your browser at `http://localhost:8000`.
- Log in with the administrator user:
  - **Username**: `admin@admin.com`
  - **Password**: `admin`

## Features

- **Course Management**: View, add, edit, and delete courses.
- **Subject Management**: View, add, edit, and delete subjects associated with courses.
- **User Management**: Create, view, edit, and delete users, including teachers, students, and administrators.
- **Grade Management**: Record and view students' grades.
- **Highlights and Contacts**: Publish news and maintain important contacts.

