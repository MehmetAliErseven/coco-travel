#!/bin/bash

echo "Starting Coco Travel setup..."

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

# Create .env file
echo "Creating environment files..."
cat > backend/.env << EOL
POSTGRES_USER=cocotravel
POSTGRES_PASSWORD=cocotravel123
POSTGRES_DB=cocotravel_db
SECRET_KEY=your-super-secret-key-here
ADMIN_EMAIL=admin@cocotravel.com
ADMIN_PASSWORD=admin123
ADMIN_USERNAME=admin
EOL

# Setup backend
echo "Setting up backend..."
cd backend
python3 -m pip install -r requirements.txt

# Start Docker services
echo "Starting Docker services..."
docker-compose down -v
docker-compose up -d

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL to be ready..."
sleep 10

# Run migrations
echo "Running database migrations..."
alembic upgrade head

# Create admin user
echo "Creating admin user..."
python3 scripts/create_admin.py

# Setup frontend
echo "Setting up frontend..."
cd ../frontend
npm install

echo "Setup completed successfully!"
echo
echo "You can now start the development servers:"
echo "Backend (from backend directory): uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload"
echo "Frontend (from frontend directory): npm run dev"
echo
echo "Default admin credentials:"
echo "Username: admin"
echo "Password: admin123"