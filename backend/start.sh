#!/bin/bash

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

# Load .env file if it exists
if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

# Number of queue workers to run
NUM_WORKERS=${UPTIME_QUEUE_WORKERS:-5}

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Docker is installed and running
check_docker() {
    if ! command -v docker &> /dev/null; then
        log_error "Docker is not installed."
        log_error "Please download and install Docker from: https://www.docker.com/get-started"
        exit 1
    fi

    if ! docker info > /dev/null 2>&1; then
        log_error "Docker is not running. Please start Docker first."
        exit 1
    fi
    log_info "Docker is running"
}

# Start Docker containers
start_containers() {
    log_info "Starting Docker containers..."
    ./vendor/bin/sail up -d

    log_info "Waiting for containers to be healthy..."
    sleep 5

    # Wait for MySQL to be ready
    local max_attempts=30
    local attempt=1
    while ! ./vendor/bin/sail exec mysql mysqladmin ping -p"${DB_PASSWORD:-password}" --silent > /dev/null 2>&1; do
        if [ $attempt -ge $max_attempts ]; then
            log_error "MySQL failed to start after $max_attempts attempts"
            exit 1
        fi
        log_warn "Waiting for MySQL... (attempt $attempt/$max_attempts)"
        sleep 2
        ((attempt++))
    done
    log_info "MySQL is ready"

    # Wait for Redis to be ready
    attempt=1
    while ! ./vendor/bin/sail exec redis redis-cli ping > /dev/null 2>&1; do
        if [ $attempt -ge $max_attempts ]; then
            log_error "Redis failed to start after $max_attempts attempts"
            exit 1
        fi
        log_warn "Waiting for Redis... (attempt $attempt/$max_attempts)"
        sleep 2
        ((attempt++))
    done
    log_info "Redis is ready"
}

# Run migrations and seed database (fresh start - truncates all data)
run_migrations() {
    log_info "Running fresh migrations and seeding database..."
    ./vendor/bin/sail artisan migrate:fresh --seed
}

# Start queue workers
start_workers() {
    log_info "Starting $NUM_WORKERS queue worker(s)..."

    for i in $(seq 1 $NUM_WORKERS); do
        ./vendor/bin/sail artisan queue:work --queue=uptime-checks-$i --sleep=3 --tries=3 --max-time=3600 &
        log_info "Started worker $i (PID: $!)"
    done
}

# Cleanup function
cleanup() {
    log_info "Shutting down..."

    # Kill all background jobs
    jobs -p | xargs -r kill 2>/dev/null

    # Stop Docker containers
    ./vendor/bin/sail down

    log_info "Cleanup complete"
    exit 0
}

# Trap SIGINT and SIGTERM
trap cleanup SIGINT SIGTERM

# Main
main() {
    log_info "Starting Website Uptime Monitoring Backend..."

    check_docker
    start_containers
    run_migrations
    start_workers

    log_info "All services started successfully!"
    log_info "Press Ctrl+C to stop all services"

    # Keep script running
    wait
}

main "$@"
