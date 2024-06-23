# Requirements
Please make sure the environment fulfills the following requirements
- PHP v8.x
- Node v18.x
- ---
# Steps to Setup the Project

1. Clone this repo
2. Copy `.env.example` to `.env` (Create new file if `.env` doesn't exists)
3. Ensure the variables in `.env` are set as per your preference (Database Settings, App URL, etc.)
4. Create a schema named `compasia` in your selected database server (Local server by default)
5. Run the following commands in **chronological order**
	- `composer install`
	- `npm i`
	- `npm run dev`
	- `php artisan migrate`
	- `php artisan serve --port 9000` (If port 9000 is occupied in your environment feel free to modify that, be sure to modify the `APP_URL` in `.env` file too)
6. You're all set! Head to your selected browser and start browsing.
