# Messaging System

## Purpose

Allow communication between candidates and employers.

---

## Features

- One-to-one messaging
- Conversation history
- Read receipts
- File attachments

Future

- Group chats

---

## Allowed Conversations

Candidate <-> Employer

Not Allowed

Candidate <-> Candidate

Employer <-> Employer

---

## Database

conversations

messages

---

## Message Fields

- sender_id
- receiver_id
- content
- attachment
- created_at

---

## Security Rules

- Scan attachments
- Rate limit messages
- Prevent spam
- Log abuse reports

---

## Real-Time Strategy

MVP

- Polling

Future

- Server Sent Events (SSE)
