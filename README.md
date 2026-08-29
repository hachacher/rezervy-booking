# Rezervy - Appointment Booking System

Professional appointment booking and scheduling system for service businesses.

## Deployment on Railway.app

This app requires:
- PHP 8.2+
- MySQL Database

### Environment Variables (set in Railway):

```
DB_HOST=your_railway_mysql_host
DB_USER=root
DB_PASSWORD=your_password
DB_DATABASE=rezervy
SITE_URL=your_railway_app_url.railway.app/
```

### Setup Instructions:

1. The database will be created during first deployment
2. Import `database/rezervy.sql` into your MySQL instance
3. Set environment variables in Railway dashboard
4. Visit your app URL to access the booking system

### Features:
- Multi-service management
- Staff scheduling
- Customer booking portal
- Admin dashboard
- Payment processing support
