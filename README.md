# Coco Travel

Travel agency website with tour management system.

## Prerequisites

- Python 3.11 or later
- Node.js 16 or later
- Docker and Docker Compose
- PostgreSQL (will be run in Docker)

## Quick Start

### Windows
```bash
# Run the setup script
setup.bat
```

### Unix/Linux/MacOS
```bash
# Make the setup script executable
chmod +x setup.sh

# Run the setup script
./setup.sh
```

## Manual Setup

If you prefer to set up manually:

1. Create `.env` file in backend directory with required environment variables
2. Start the database:
```bash
cd backend
docker-compose up -d
```

3. Install backend dependencies:
```bash
cd backend
pip install -r requirements.txt
```

4. Run database migrations:
```bash
alembic upgrade head
```

5. Create admin user:
```bash
python scripts/create_admin.py
```

6. Install frontend dependencies:
```bash
cd frontend
npm install
```

## Development

### Start Backend Server
```bash
cd backend
uvicorn app.main:app --host 0.0.0.0 --port 8000 --reload
```

### Start Frontend Development Server
```bash
cd frontend
npm run dev
```

## API Documentation

- Swagger UI: http://localhost:8000/docs
- ReDoc: http://localhost:8000/redoc

## Default Admin Credentials

- Username: admin
- Password: admin123

## Environment Variables

### Backend (.env)
- POSTGRES_USER: Database user
- POSTGRES_PASSWORD: Database password
- POSTGRES_DB: Database name
- SECRET_KEY: JWT secret key
- ADMIN_EMAIL: Default admin email
- ADMIN_PASSWORD: Default admin password
- ADMIN_USERNAME: Default admin username

## Features

- Tour Management
- Category Management
- Contact Form with Email Notifications
- Admin Panel
- Multi-language Support (English and Thai)
- Responsive Design
- Search Functionality
- User Authentication

## Project Structure

```
coco-travel/
├── backend/               # FastAPI backend
│   ├── app/              # Application code
│   │   ├── models/       # Database models
│   │   └── routers/      # API endpoints
│   ├── alembic/          # Database migrations
│   └── scripts/          # Utility scripts
└── frontend/             # React frontend
    ├── src/
    │   ├── components/   # React components
    │   ├── pages/        # Page components
    │   ├── services/     # API services
    │   └── hooks/        # Custom React hooks