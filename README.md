# PMS

## Requirements

- PHP \>= 8.2
- Composer
- Node.js & npm
- MySQL

## Setup

1. Clone the repository  
   `git clone https://github.com/dipesh79/pms.git`  
   `cd pms`

2. Install PHP dependencies  
   `composer install`

3. Install JavaScript dependencies  
   `npm install`

4. Copy and configure environment  
   `cp .env.example .env`  
   _Edit `.env` with your database and app settings_

5. Generate application key  
   `php artisan key:generate`

6. Run migrations  
   `php artisan migrate`

7. Seed the database  
   `php artisan db:seed`

8. Start the development server  
   `composer run dev`

## Testing

Run tests with coverage:  
`php artisan test --coverage`

## Api Docs
http://localhost:8000/docs/api

## Features

- User registration & login
- Project creation
- Task update
- Comment addition
- Email Notifications
- Email Log Viwer For Local Development
- Role-based access control


