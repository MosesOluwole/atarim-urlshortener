# Laravel URL Shortener

This is a simple Laravel application that allows you to shorten long URLs into short, easy-to-share URLs. The application uses in-memory storage (via Laravel's Cache) to store the mappings between short codes and original URLs.

---

## Prerequisites

Before running the application, ensure you have the following installed:

1. **PHP** (>= 8.0)
2. **Composer** (for dependency management)
3. **Laravel** (installed via Composer)
4. **A web server** (e.g., Apache, Nginx, or Laravel's built-in server)

---

## Installation

1. **Clone the repository**:

    ```bash
    git clone https://github.com/MosesOluwole/atarim-urlshortener.git
    cd url-shortener
    ```

2. **Install dependencies**:

    ```bash
    composer install
    ```

3. **Set up the environment file**:

    Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

4. **Generate an application key**:

    ```bash
    php artisan key:generate
    ```

5. **Configure the cache driver**:

    By default, Laravel uses the file cache driver, which is sufficient for this application. Ensure the following is set in your `.env` file:

    ```env
    CACHE_DRIVER=file
    ```

---

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Access the application

Open your browser and navigate to [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## API Endpoints

The application provides the following API endpoints:

1. **Encode a URL**

    - **Endpoint**: `POST /api/encode`
    - **Request Body**:
        ```json
        {
            "url": "https://www.example.com"
        }
        ```
    - **Response**:
        ```json
        {
            "short_url": "http://127.0.0.1:8000/GeAi9K"
        }
        ```

2. **Decode a Short URL**
    - **Endpoint**: `POST /api/decode`
    - **Request Body**:
        ```json
        {
            "short_url": "http://127.0.0.1:8000/GeAi9K"
        }
        ```
    - **Response**:
        ```json
        {
            "original_url": "https://www.example.com"
        }
        ```

---

## Example Usage

1. **Encode a URL**

    ```bash
    curl -X POST http://127.0.0.1:8000/api/encode \
         -H "Content-Type: application/json" \
         -d '{"url": "https://www.example.com"}'
    ```

    **Response**:

    ```json
    {
        "short_url": "http://127.0.0.1:8000/GeAi9K"
    }
    ```

2. **Decode a Short URL**

    ```bash
    curl -X POST http://127.0.0.1:8000/api/decode \
         -H "Content-Type: application/json" \
         -d '{"short_url": "http://127.0.0.1:8000/GeAi9K"}'
    ```

    **Response**:

    ```json
    {
        "original_url": "https://www.example.com"
    }
    ```

---

## Troubleshooting

1. **Cache Not Working**:

    Ensure the cache driver is properly configured in `.env`:

    ```env
    CACHE_DRIVER=file
    ```

2. **Application Not Running**:

    Ensure the Laravel development server is running:

    ```bash
    php artisan serve
    ```

3. **Tests Failing**:

    Clear the cache before running tests:

    ```bash
    php artisan cache:clear
    ```

---

## License

This project is open-source and available under the MIT License.

---

## Author

Moses Oluwole  
GitHub: [MosesOluwole](https://github.com/MosesOluwole)
