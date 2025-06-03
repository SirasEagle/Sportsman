# Sportsman

This project focuses on managing your own workouts by creating and adding exercises and more. Statistics for workouts can be viewed in calendar format and as graphs.

---

## Table of Contents

1. [About](#about)  
2. [Features](#features)  
3. [Installation](#installation)  
4. [Usage](#usage)  
5. [Configuration](#configuration)  
6. [Upcoming changes](#upcoming-changes)  
7. [License](#license)

---

## About

This project is a workout tracker that allows users to log their workouts, including exercises, sets, and weights. It provides features for adding, editing, and viewing workout units, as well as managing exercises and workouts. The application is built using Symfony and follows best practices for security and user management. It includes functionality for handling user authentication, form submissions, and database interactions. The project aims to help users track their fitness progress and improve their workout routines.

---

## Features

- **Acount:** account management and user profile (registration, login, logout)
- **Workout:** workout management (create, edit, delete workouts)
- **Exercise:** manage exercises (add, edit exercises) and customize them (e.g. with or without weight, etc.)
- **Unit:** unit management (add, edit units)
- **Statistics:** view automatically generated statistics of your workouts and exercises
- **Calendar:** calendar integration (view workouts in a calendar)
- **Product:** product management (add, edit products for nutrition tracking)
- **Muscle group:** muscle group management (add muscle groups)

---

## Installation

Step-by-step instructions on how to get a development environment running.

```bash
# Clone the repository
git clone https://github.com/SirasEagle/Sportsman.git

# Enter the project directory
cd Sportsman

# Install dependencies
npm install       # or pip install -r requirements.txt, composer install, etc.
```

---

## Usage

1. Start the Apache Server and MySQL (for example with [XAMPP](https://www.apachefriends.org/de/index.html))
2. Create an empty database on http://localhost/phpmyadmin/ with the name: workoutdb
3. Modify the file `config/packages/doctrine.yaml`
   - `doctrine.dbal.dbname`: to the name of your database (workoutdb)
   - `doctrine.dbal.host`: to localhost or 127.0.0.1
   - `doctrine.dbal.server_version`: to the version of your database, e.g. '10.4.14' or 'mariadb-10.4.14' for MariaDB (to check the version, open the Shell on the XAMPP panel and type `mysql -V`)
4. Visit **localhost:8000/user/new** to create a new account and then **localhost:8000/login** to login (when doctrine.dbal.host is set to localhost or 127.0.0.1)

---

## Configuration

ToDo

---

## Upcoming changes

- **Better user registration process**
- **Be able to track usage of products**
- **View generated graph of nutritional intake**

---

## License


This project is licensed under the MIT License.

---
