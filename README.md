# frasear.io

A Laravel-based web application (frasear.io).  
This app is essentially a digital _commonplace book_ — the centuries-old practice of collecting lines you've read or heard into a personal notebook.  
That's the grounding concept, not a generic "quotes SaaS."  
This README covers local setup, common commands, and contribution notes.

## Requirements
- PHP 8.1+ with extensions required by Laravel
- Composer
- MySQL / PostgreSQL (or other supported DB)
- Node.js & npm (for frontend assets)

## Setup
1. Clone repo  
   `git clone <repo-url>`
2. Install PHP & JS dependencies  
   `composer install`  
   `npm install`
3. Copy env and generate key  
   `cp .env.example .env`  
   `php artisan key:generate`
4. Configure DB in `.env` and run migrations  
   `php artisan migrate --seed`  
5. Build assets  
   `npm run build`

## Running locally
- Start Laravel dev server:  
  `php artisan serve --host=127.0.0.1 --port=8000`
- Visit http://127.0.0.1:8000

## Tests
- Run PHP tests:  
  `php artisan test`  
- Run JS tests (if present):  
  `npm test`

## Contributing
- Open an issue for bugs or feature requests
- Use feature branches and open pull requests
- Follow PSR-12 and project coding standards
