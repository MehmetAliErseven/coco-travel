@echo off
echo Coco Travel Setup Script
echo ----------------------

:: Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Python is not installed! Please install Python 3.11 or later.
    exit /b 1
)

:: Check if Docker is installed
docker --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker is not installed! Please install Docker Desktop.
    exit /b 1
)

:: Check if Node.js is installed
node --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Node.js is not installed! Please install Node.js 16 or later.
    exit /b 1
)

:: Ask for environment
set /p ENV_TYPE="Choose environment (dev/prod) [dev]: "
if "%ENV_TYPE%"=="" set ENV_TYPE=dev

:: Ask for installation type
set /p CLEAN_INSTALL="Do you want a clean installation? This will delete all data! (y/N): "

:: Handle installation type
if /i "%CLEAN_INSTALL%"=="y" (
    echo Performing clean installation...
    docker-compose down -v
) else (
    echo Performing update installation...
    docker-compose down
)

:: Create root .env file if it doesn't exist
if not exist .env (
    echo Creating root .env file...
    (
    echo POSTGRES_USER=cocotravel
    echo POSTGRES_PASSWORD=cocotravel123
    echo POSTGRES_DB=cocotravel_db
    echo SECRET_KEY=your-super-secret-key-here
    echo ADMIN_EMAIL=admin@cocotravel.com
    echo ADMIN_PASSWORD=admin123
    echo ADMIN_USERNAME=admin
    echo NODE_ENV=%ENV_TYPE%
    echo FRONTEND_PORT=5173
    ) > .env
)

:: Start services based on environment
if "%ENV_TYPE%"=="prod" (
    echo Starting production services...
    docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d --build
) else (
    echo Starting development services...
    docker-compose up -d --build
)

:: Wait for services to be ready
echo Waiting for services to be ready...
timeout /t 10 /nobreak

:: Run migrations and create admin user only for clean installation
if /i "%CLEAN_INSTALL%"=="y" (
    echo Running clean installation tasks...
    
    :: Check database existence
    echo Verifying database setup...
    docker-compose exec -T postgres psql -U cocotravel -c "\l"
    
    :: Reset migrations and clean database tables
    echo Resetting alembic migrations...
    docker-compose exec -T backend alembic current
    docker-compose exec -T backend alembic stamp base
    
    :: Drop all tables explicitly to ensure a clean state
    echo Dropping all existing tables...
    docker-compose exec -T postgres psql -U cocotravel -d cocotravel_db -c "DROP TABLE IF EXISTS alembic_version, categories, messages, tours, users CASCADE;"
    
    :: Run migrations
    echo Running database migrations...
    docker-compose exec -T backend alembic upgrade head
    
    :: Create admin user
    echo Creating admin user...
    docker-compose exec -T backend python scripts/create_admin.py
) else (
    echo Checking if migrations are needed...
    docker-compose exec -T backend alembic upgrade head
)

echo Setup completed successfully!
echo.
if "%ENV_TYPE%"=="prod" (
    echo The application is now running at:
    echo Frontend: http://localhost
    echo Backend API: http://localhost/api
) else (
    echo The application is now running at:
    echo Frontend: http://localhost:5173
    echo Backend API: http://localhost:8000/api
)
echo API Documentation: http://localhost:8000/api/docs
echo.
echo Default admin credentials:
echo Username: admin
echo Password: admin123
echo.
pause