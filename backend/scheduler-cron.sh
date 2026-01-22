#!/bin/bash

# Scheduler cron script for Laravel schedule:run
# Add this to your crontab with: crontab -e
# * * * * * /path/to/backend/scheduler-cron.sh >> /var/log/scheduler.log 2>&1

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Run the Laravel scheduler
./vendor/bin/sail artisan schedule:run
