from sqlalchemy import Column, Integer, String, Text, Boolean
from ..database import Base
from .base import TimestampMixin

class Message(Base, TimestampMixin):
    __tablename__ = "messages"

    id = Column(Integer, primary_key=True, index=True)
    name = Column(String(100), nullable=False)
    email = Column(String(100), nullable=False)
    subject = Column(String(200))
    message = Column(Text, nullable=False)
    phone = Column(String(20))
    is_read = Column(Boolean, default=False)