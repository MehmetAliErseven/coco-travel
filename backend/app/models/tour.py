from sqlalchemy import Column, Integer, String, Text, Boolean, ForeignKey, Float, JSON
from sqlalchemy.orm import relationship
from ..database import Base
from .base import TimestampMixin

class Category(Base, TimestampMixin):
    __tablename__ = "categories"

    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(100), nullable=False)
    slug = Column(String(100), unique=True, nullable=False)
    description = Column(Text)
    is_active = Column(Boolean, default=True)
    
    # Relationships
    tours = relationship("Tour", back_populates="category")

class Tour(Base, TimestampMixin):
    __tablename__ = "tours"

    id = Column(Integer, primary_key=True, index=True)
    title = Column(String(200), nullable=False)
    slug = Column(String(200), unique=True, nullable=False)
    description = Column(Text)
    price = Column(Float)
    duration = Column(String(50))  # e.g., "2 days", "4 hours"
    location = Column(String(100))
    images = Column(JSON)  # Store array of image URLs
    featured = Column(Boolean, default=False)
    is_active = Column(Boolean, default=True)
    
    # Foreign keys
    category_id = Column(Integer, ForeignKey("categories.id"))
    
    # Relationships
    category = relationship("Category", back_populates="tours")