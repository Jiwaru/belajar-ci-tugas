# Belajar CI (CodeIgniter 4 App Starter)

This project is a starter application built with [CodeIgniter 4](https://codeigniter.com/), a powerful PHP framework with a small footprint, designed for developers who need a simple and elegant toolkit to create full-featured web applications.

## Features

- CodeIgniter 4 framework (see [composer.json](composer.json))
- Pre-configured for Composer dependency management
- Example environment configuration via `.env`
- CLI tools via [`spark`](spark)
- Example usage of third-party libraries (e.g., DomPDF, Guzzle)
- Ready for unit and feature testing with PHPUnit
- Includes a modern admin template ([NiceAdmin](public/NiceAdmin/))
- Example database configuration

## Getting Started

### Requirements

- PHP 8.1 or higher
- Composer

### Installation

1. **Clone the repository:**

   ```sh
   git clone <your-repo-url>
   cd belajar-ci

   ```

2. **Install Dependencies:**

   ```sh
   composer install

   ```

3. **Copy and configure Environment:**

   ```sh
   cp .env.example .env
   #Edit .env to match your environtment (database, API keys, etc.)

   ```

4. **Set writable permission:**
   ensure the writable/ directory and its subdirectories are writable by your web server.

5. **Run database migration (if any):**

   ```sh
   php spark migrate

   ```

6. **Start the development server**
   ```sh
   php spark serve

   ```

visit [http://localhost:8080](http://localhost:8080) in your browser

## Project Structure

- `app` - Application code (controllers, models, views, config, etc.)
- `public` - Web root (index.php, assets, NiceAdmin template)
- `writable` - Cache, logs, session, uploads (must be writable)
- `tests` - Unit and feature tests
- `vendor` - Composer dependencies

## License

This project is open source under the MIT License.

## Credits

- [CodeIgniter 4](https://codeigniter.com/)
- [NiceAdmin Bootstrap Template](public/NiceAdmin/)
