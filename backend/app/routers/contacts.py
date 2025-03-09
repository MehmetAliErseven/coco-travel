from fastapi import APIRouter, Depends, HTTPException, BackgroundTasks
from sqlalchemy.orm import Session
from typing import List, Optional
from ..database import get_db
from ..models.contact import Message
from ..routers.auth import get_current_user
from pydantic import BaseModel, EmailStr
from datetime import datetime
import smtplib
import os
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

router = APIRouter()

class MessageBase(BaseModel):
    name: str
    email: EmailStr
    subject: Optional[str] = None
    message: str
    phone: Optional[str] = None

class MessageCreate(MessageBase):
    pass

class MessageResponse(MessageBase):
    id: int
    created_at: datetime
    is_read: bool

    class Config:
        from_attributes = True

async def send_notification_email(message: Message):
    # Configure these with real values in production
    admin_email = os.getenv("ADMIN_EMAIL", "admin@example.com")
    smtp_server = os.getenv("SMTP_SERVER", "smtp.gmail.com")
    smtp_port = int(os.getenv("SMTP_PORT", "587"))
    smtp_username = os.getenv("SMTP_USERNAME", "your-email@gmail.com")
    smtp_password = os.getenv("SMTP_PASSWORD", "your-app-password")

    msg = MIMEMultipart()
    msg["From"] = smtp_username
    msg["To"] = admin_email
    msg["Subject"] = f"New Contact Form Message: {message.subject or 'No Subject'}"

    body = f"""
    New message received from contact form:
    
    Name: {message.name}
    Email: {message.email}
    Phone: {message.phone or 'Not provided'}
    Subject: {message.subject or 'No Subject'}
    
    Message:
    {message.message}
    """

    msg.attach(MIMEText(body, "plain"))

    try:
        with smtplib.SMTP(smtp_server, smtp_port) as server:
            server.starttls()
            server.login(smtp_username, smtp_password)
            server.send_message(msg)
    except Exception as e:
        print(f"Failed to send notification email: {e}")

@router.post("/contact", response_model=MessageResponse)
async def create_message(
    message: MessageCreate,
    background_tasks: BackgroundTasks,
    db: Session = Depends(get_db)
):
    db_message = Message(**message.dict())
    db.add(db_message)
    db.commit()
    db.refresh(db_message)
    
    # Send notification email in background
    background_tasks.add_task(send_notification_email, db_message)
    
    return db_message

@router.get("/admin/messages", response_model=List[MessageResponse])
async def get_messages(
    skip: int = 0,
    limit: int = 50,
    unread_only: bool = False,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    query = db.query(Message)
    if unread_only:
        query = query.filter(Message.is_read == False)
    return query.order_by(Message.created_at.desc()).offset(skip).limit(limit).all()

@router.get("/admin/messages/{message_id}", response_model=MessageResponse)
async def get_message(
    message_id: int,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    message = db.query(Message).filter(Message.id == message_id).first()
    if not message:
        raise HTTPException(status_code=404, detail="Message not found")
    return message

@router.put("/admin/messages/{message_id}/read")
async def mark_message_as_read(
    message_id: int,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    message = db.query(Message).filter(Message.id == message_id).first()
    if not message:
        raise HTTPException(status_code=404, detail="Message not found")
    
    message.is_read = True
    db.commit()
    return {"message": "Message marked as read"}

@router.delete("/admin/messages/{message_id}")
async def delete_message(
    message_id: int,
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    message = db.query(Message).filter(Message.id == message_id).first()
    if not message:
        raise HTTPException(status_code=404, detail="Message not found")
    
    db.delete(message)
    db.commit()
    return {"message": "Message deleted successfully"}

@router.get("/admin/messages/unread/count")
async def get_unread_count(
    db: Session = Depends(get_db),
    current_user: dict = Depends(get_current_user)
):
    count = db.query(Message).filter(Message.is_read == False).count()
    return {"unread_count": count}