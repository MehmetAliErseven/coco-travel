#!/bin/bash

echo "Coco Travel Setup Script"
echo "----------------------"

# Check if Python is installed
if ! command -v python3 &> /dev/null; then
    echo "Python is not installed! Please install Python 3.11 or later."
    exit 1
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "Docker is not installed! Please install Docker."
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "Node.js is not installed! Please install Node.js 16 or later."
    exit 1
fi

# Ask for environment
read -p "Choose environment (dev/prod) [dev]: " ENV_TYPE
ENV_TYPE=${ENV_TYPE:-dev}

# Ask for installation type
read -p "Do you want a clean installation? This will delete all data! (y/N): " CLEAN_INSTALL

# Handle installation type
if [[ $CLEAN_INSTALL =~ ^[Yy]$ ]]; then
    echo "Performing clean installation..."
    docker-compose down -v
else
    echo "Performing update installation..."
    docker-compose down
fi

# Create root .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating root .env file..."
    cat > .env << EOL
POSTGRES_USER=cocotravel
POSTGRES_PASSWORD=cocotravel123
POSTGRES_DB=cocotravel_db
SECRET_KEY=your-super-secret-key-here
ADMIN_EMAIL=admin@cocotravel.com
ADMIN_PASSWORD=admin123
ADMIN_USERNAME=admin
NODE_ENV=${ENV_TYPE}
FRONTEND_PORT=5173
EOL
fi

# Start services based on environment
if [ "$ENV_TYPE" = "prod" ]; then
    echo "Starting production services..."
    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
else
    echo "Starting development services..."
    docker-compose up -d --build
fi

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 10

# Run migrations and create admin user only for clean installation
if [[ $CLEAN_INSTALL =~ ^[Yy]$ ]]; then
    echo "Running clean installation tasks..."
    
    # Check database existence
    echo "Verifying database setup..."
    docker-compose exec -T postgres psql -U cocotravel -c "\l"
    
    # Reset migrations and clean database tables
    echo "Resetting alembic migrations..."
    docker-compose exec -T backend alembic current
    docker-compose exec -T backend alembic stamp base
    
    # Drop all tables explicitly to ensure a clean state
    echo "Dropping all existing tables..."
    docker-compose exec -T postgres psql -U cocotravel -d cocotravel_db -c "DROP TABLE IF EXISTS alembic_version, categories, messages, tours, users CASCADE;"
    
    # Run migrations
    echo "Running database migrations..."
    docker-compose exec -T backend alembic upgrade head
    
    # Create admin user
    echo "Creating admin user..."
    docker-compose exec -T backend python scripts/create_admin.py
else
    echo "Checking if migrations are needed..."
    docker-compose exec -T backend alembic upgrade head
fi

echo "Setup completed successfully!"
echo
if [ "$ENV_TYPE" = "prod" ]; then
    echo "The application is now running at:"
    echo "Frontend: http://localhost"
    echo "Backend API: http://localhost/api"
else
    echo "The application is now running at:"
    echo "Frontend: http://localhost:5173"
    echo "Backend API: http://localhost:8000/api"
fi
echo "API Documentation: http://localhost:8000/api/docs"
echo
echo "Default admin credentials:"
echo "Username: admin"
echo "Password: admin123"