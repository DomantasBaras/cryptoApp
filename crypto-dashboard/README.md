# Laravel and Vue.js Cryptocurrency Dashboard

This project is a Laravel application integrated with Vue.js, featuring a cryptocurrency dashboard that includes table and card views with pagination. This guide provides step-by-step instructions to set up the project.

---

## Prerequisites

Ensure your system meets the following requirements:

- **PHP**: >= 8.0
- **Composer**: Latest version
- **Node.js**: >= 14.x
- **npm**: >= 6.x
- **MySQL** or other database system supported by Laravel

---

## Installation Guide

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js Dependencies**
   ```bash
   npm install
   ```

4. **Configure Environment Variables**
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with your database credentials and other settings.

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

7. **Build Frontend Assets**
   ```bash
   npm run build
   ```

---

## Running the Application

1. **Start the Development Server**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://127.0.0.1:8000`.

2. **Start the Vite Development Server** (if making frontend changes):
   ```bash
   npm run dev
   ```
   This will enable hot module replacement for Vue.js components.

---

## Key Features

- **Cryptocurrency Dashboard**
  - View cryptocurrency data in both table and card layouts.
  - Search, filter, and sort data dynamically.
  - Pagination support for both views.

- **Authentication**
  - Login and registration functionality.
  - Profile management.

- **Welcome Page**
  - A landing page linking to the login and registration pages.

---

## Folder Structure

- **`app`**: Contains Laravel backend logic.
- **`resources/js/Pages`**: Vue.js components for the application.
- **`routes/web.php`**: Application routes.
- **`routes/api.php`**: API routes.
- **`public`**: Contains the entry point for the application.
- **`.env`**: Environment configuration file.

---

## Troubleshooting

- **Error: Class 'PDO' not found**
  - Ensure the PDO extension is enabled in your PHP installation.

- **Error: Node modules missing after cloning**
  - Run `npm install` to reinstall dependencies.

- **Cannot access application on localhost**
  - Verify that the development server is running (`php artisan serve`).

---

## Additional Commands

- **Clear Cache**:
  ```bash
  php artisan cache:clear
  ```

- **Run Tests**:
  ```bash
  php artisan test
  ```

---

## License

This project is open-source and available under the [MIT License](LICENSE).

---
