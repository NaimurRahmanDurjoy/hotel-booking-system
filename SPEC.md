# Hotel Booking System - Specification Document

## 1. Project Overview

**Project Name:** Hotel Booking System  
**Project Type:** Full-stack Web Application (Laravel + Blade)  
**Core Functionality:** A comprehensive hotel booking platform with role-based access control, real-time booking, live chat support, and premium subscription discounts.  
**Target Users:** Hotel customers, hotel managers, and system administrators.

---

## 2. User Roles & Permissions

| Role | Permissions |
|------|-------------|
| **Customer** | Browse rooms/services, make bookings, chat with staff, subscribe to premium, view booking history |
| **Manager** | Manage rooms/services, confirm/reject bookings, respond to chat messages, view reports |
| **Admin** | Full system access, manage all users, view all bookings, system configuration, manage managers |

### Role Hierarchy
- Admin (highest) → Manager → Customer (lowest)

---

## 3. Core Features

### 3.1 Authentication & Authorization
- User registration with email verification
- Login with role-based redirection
- Password reset functionality
- Role-based middleware protection

### 3.2 Room Management (Manager)
- Create, edit, delete rooms
- Set room types (Standard, Deluxe, Suite, Presidential)
- Upload room images
- Set pricing per night
- Set room availability status
- Room amenities listing

### 3.3 Service Management (Manager)
- Create hotel services (spa, restaurant, gym, room service)
- Set service prices
- Set availability

### 3.4 Booking System (Customer)
- View available rooms with real-time availability
- Select check-in/check-out dates
- Choose add-on services
- Apply premium discount if subscribed
- Real-time booking confirmation
- Booking history and status tracking

### 3.5 Booking Management (Manager)
- View all bookings
- Confirm or reject pending bookings
- View booking details
- Filter by status (pending, confirmed, rejected, completed)

### 3.6 Real-time Chat System
- Customers can initiate chat with manager/staff
- Real-time messaging using WebSocket (Pusher)
- Chat history persistence
- Unread message notifications

### 3.7 Premium Subscription System
- Customers can subscribe to premium
- Premium tiers: Silver (5% discount), Gold (10% discount)
- Premium status displayed on profile
- Automatic discount application on bookings

### 3.8 Admin Dashboard
- View all users
- Manage user roles
- View system statistics
- Manage hotel settings

---

## 4. Database Schema

### Users Table
- id, name, email, password, role (customer/manager/admin), phone, address, is_premium, premium_tier (silver/gold), created_at, updated_at

### Rooms Table
- id, room_number, room_type, description, price_per_night, capacity, amenities (JSON), images, status (available/occupied/maintenance), created_at, updated_at

### Services Table
- id, name, description, price, image, is_available, created_at, updated_at

### Bookings Table
- id, user_id, room_id, check_in_date, check_out_date, total_price, discount_applied, status (pending/confirmed/rejected/completed/cancelled), created_at, updated_at

### BookingServices Table (Pivot)
- id, booking_id, service_id, quantity, price

### Messages Table
- id, sender_id, receiver_id, booking_id (nullable), message, is_read, created_at

### PremiumSubscriptions Table
- id, user_id, tier, start_date, end_date, is_active, created_at, updated_at

---

## 5. Technology Stack

- **Backend:** Laravel 12.x
- **Frontend:** Laravel Blade
- **Database:** MySQL/SQLite
- **Authentication:** Laravel Breeze
- **Real-time:** Pusher (for chat)
- **File Storage:** Local disk
- **CSS:** Bootstrap 5

---

## 6. UI/UX Design

### Color Scheme
- Primary: #1E3A5F (Deep Navy)
- Secondary: #F5A623 (Gold)
- Accent: #2ECC71 (Green for success)
- Background: #F8F9FA (Light gray)
- Text: #2C3E50 (Dark gray)

### Layout
- Responsive design (mobile-first)
- Clean, modern interface
- Card-based room display
- Modal for booking details

---

## 7. Acceptance Criteria

### Customer
- [ ] Can register and login
- [ ] Can browse available rooms
- [ ] Can view room details
- [ ] Can make a booking with date selection
- [ ] Can view booking history
- [ ] Can subscribe to premium
- [ ] Can chat with manager/staff

### Manager
- [ ] Can login to dashboard
- [ ] Can manage rooms (CRUD)
- [ ] Can manage services (CRUD)
- [ ] Can view and manage bookings
- [ ] Can confirm/reject bookings
- [ ] Can respond to chat messages

### Admin
- [ ] Can login to admin panel
- [ ] Can view all users
- [ ] Can manage user roles
- [ ] Can view system statistics
- [ ] Can access all features

### System
- [ ] Real-time availability updates
- [ ] Premium discount applied correctly
- [ ] Chat messages delivered in real-time
- [ ] Role-based access control working