# Recipe Management API
Welcome to the Recipe Management project!

## Getting Started

Follow the steps below to run the Laravel app locally on your machine.

### Prerequisites

Make sure you have the following software installed on your machine:

- [PHP](https://www.php.net) (version 8.1 or later)
- [Composer](https://getcomposer.org)
- [Git](https://git-scm.com)
- [A Database Server] (e.g., MySQL, PostgreSQL, SQLite)

### Installation

1. **Clone the Repository:**

    ```bash
    git clone https://github.com/nsakib176/recipe-management.git
    ```

2. **Navigate to the Project Directory:**

    ```bash
    cd recipe-management
    ```

3. **Install Composer Dependencies:**

    ```bash
    composer install
    ```

4. **Copy the Environment File:**

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database configuration and other settings.

5. **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

6. **Run Migrations:**

    ```bash
    php artisan migrate
    ```

7. **Start the Development Server:**

    ```bash
    php artisan serve
    ```

    The application will be accessible at [http://localhost:8000](http://localhost:8000).

