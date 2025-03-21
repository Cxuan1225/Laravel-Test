# Project Setup

## Installation

1. **Clone the repository:**

    ```sh
    git clone <repository-url>
    cd <project-folder>
    ```

2. **Install dependencies:**

    ```sh
    composer install
    npm install
    ```

3. **Copy the `.env.example` file to `.env`:**

    ```sh
    cp .env.example .env
    ```

4. **Generate an application key:**

    ```sh
    php artisan key:generate
    ```

5. **Configure the `.env` file:**

    - Set up database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

6. **Run database migrations and seeders:**

    ```sh
    php artisan migrate --seed
    ```

7. **Serve the application:**
    ```sh
    php artisan serve
    ```

---

## API Endpoints

### **General Pages**

-   `GET /` → Shows the dashboard.
-   `GET /dashboard` → Also shows the dashboard.
-   `GET /products` → Shows the products page.

### **User Management**

-   `GET /users` → Get a list of all users.
-   `POST /users/create` → Create a new user (needs `name`, `email`, `password`).
-   `PUT /users/update/{id}` → Update a user by ID (optional: `name`, `email`, `password`).
-   `POST /users/destroy/{id}` → Delete a user by ID.
-   `POST /users/bulk-delete` → Delete multiple users at once (send a list of `user_ids`).
-   `GET /users/export/` → Download user data as a file.

---

## Assumptions

-   A **logged-in admin** manages the users.
-   Only **admin users** have access to the user management features (create, update, delete, export).
-   **Export functionality** provides user data in a structured format (Excel).

---

## Design Choices

-   **AdminLTE3** is used because it provides a ready-made, responsive admin panel with Bootstrap components, reducing UI development time.
