from logging.config import fileConfig
import os
import sys
from pathlib import Path

# Add the project root directory to Python path
backend_dir = Path(__file__).resolve().parent.parent
sys.path.append(str(backend_dir))

from sqlalchemy import engine_from_config
from sqlalchemy import pool

from alembic import context

# Import your models here
from app.models.user import User
from app.models.tour import Tour, Category
from app.models.contact import Message
from app.models.base import TimestampMixin
from app.database import Base

# this is the Alembic Config object, which provides
# access to the values within the .ini file in use.
config = context.config

# Interpret the config file for Python logging.
# This line sets up loggers basically.
if config.config_file_name is not None:
    fileConfig(config.config_file_name)

# add your model's MetaData object here
# for 'autogenerate' support
target_metadata = Base.metadata

def get_url():
    user = os.getenv("POSTGRES_USER", "cocotravel")
    password = os.getenv("POSTGRES_PASSWORD", "cocotravel123")
    db = os.getenv("POSTGRES_DB", "cocotravel_db")
    # In Docker, use service name 'postgres' instead of 'localhost'
    return f"postgresql://{user}:{password}@postgres/{db}"

def run_migrations_offline() -> None:
    """Run migrations in 'offline' mode."""
    url = get_url()
    context.configure(
        url=url,
        target_metadata=target_metadata,
        literal_binds=True,
        dialect_opts={"paramstyle": "named"},
    )

    with context.begin_transaction():
        context.run_migrations()


def run_migrations_online() -> None:
    """Run migrations in 'online' mode."""
    configuration = config.get_section(config.config_ini_section)
    if not configuration:
        configuration = {}
    configuration["sqlalchemy.url"] = get_url()
    
    connectable = engine_from_config(
        configuration,
        prefix="sqlalchemy.",
        poolclass=pool.NullPool,
    )

    with connectable.connect() as connection:
        context.configure(
            connection=connection, target_metadata=target_metadata
        )

        with context.begin_transaction():
            context.run_migrations()


if context.is_offline_mode():
    run_migrations_offline()
else:
    run_migrations_online()
