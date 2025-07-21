# Parking Reservation App

A modern minimalist parking reservation application with:
- Laravel backend (reservation management, quoting, billing, notifications)
- Vue 3 frontend (homepage, quoting tool, login/register, reservation list)

## Structure

```
├── backend/   # Laravel app
├── frontend/  # Vue 3 app
```

## Setup

### Backend (Laravel)
1. `cd backend`
2. `composer install`
3. Copy `.env.example` to `.env` and configure DB
4. `php artisan migrate`
5. `php artisan serve`

### Frontend (Vue 3)
1. `cd frontend`
2. `npm install`
3. `npm run dev`

## Features

- Quote tool for price verification
- Reservation management & cancellations
- User authentication and registration
- Rate management (multiple locations)
- Email notifications (rates on reservation)
- Push notifications (new reservations)
- Billing & reporting

## Contributing

PRs and issues welcome!