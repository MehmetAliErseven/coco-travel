@echo off
echo Starting Coco Travel setup...

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

:: Create .env file if it doesn't exist
echo Creating environment files...
(
echo POSTGRES_USER=cocotravel
echo POSTGRES_PASSWORD=cocotravel123
echo POSTGRES_DB=cocotravel_db
echo SECRET_KEY=your-super-secret-key-here
echo ADMIN_EMAIL=admin@cocotravel.com
echo ADMIN_PASSWORD=admin123
echo ADMIN_USERNAME=admin
) > backend\.env

:: Setup backend
echo Setting up backend...
cd backend
pip install -r requirements.txt

:: Start Docker services
echo Starting Docker services...
docker-compose down -v
docker-compose up -d

:: Wait for PostgreSQL to be ready
echo Waiting for PostgreSQL to be ready...
timeout /t 10 /nobreak

:: Run migrations
echo Running database migrations...
alembic upgrade head

:: Create admin user
echo Creating admin user...
python scripts/create_admin.py

:: Setup frontend
echo Setting up frontend...
cd ..\frontend
npm install

echo Setup completed successfully!
echo.
echo You can now start the development servers:
echo Backend (from backend directory): uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload
echo Frontend (from frontend directory): npm run dev
echo.
echo Default admin credentials:
echo Username: admin
echo Password: admin123
echo.
pause