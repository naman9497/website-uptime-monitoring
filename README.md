## Website Uptime Monitoring

This Laravel application monitors website uptime for multiple clients by checking their websites every 15 minutes.

### Quick Start

Use `start.sh` to start all services (Docker containers, database, and queue workers):

```bash
chmod +x start.sh
./start.sh
```

This script will:
1. Start Docker containers via Laravel Sail
2. Wait for MySQL and Redis to be ready
3. Run database migrations and seeders
4. Start queue workers (configurable via `UPTIME_QUEUE_WORKERS` env variable, default: 5)

Press `Ctrl+C` to stop all services gracefully.

### Scheduler Setup
Run ```./vendor/bin/sail artisan schedule:run``` or setup a cron.

The Laravel scheduler needs to run every minute to check for due tasks. Use the provided `scheduler-cron.sh` script with system cron:

```bash
# Make the script executable
chmod +x scheduler-cron.sh

# Add to crontab (crontab -e)
* * * * * /path/to/backend/scheduler-cron.sh >> /var/log/scheduler.log 2>&1
```

The scheduler dispatches uptime check jobs every 15 minutes as configured in `routes/console.php`.
