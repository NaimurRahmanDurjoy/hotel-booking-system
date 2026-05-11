# Hotel Booking System - Flow Chart

This document describes the workflow of the Hotel Booking System. You can view this diagram in GitHub or any Markdown viewer that supports Mermaid.

## System Workflow

```mermaid
graph TD
    Start([User Visits Website]) --> RoleDecision{Already Logged In?}
    
    RoleDecision -- No --> Guest[Guest/Visitor]
    RoleDecision -- Yes --> RoleCheck{Check User Role}
    
    Guest --> Auth[Login / Register]
    Auth --> RoleCheck
    
    RoleCheck -- Customer --> CustFlow[Customer Flow]
    RoleCheck -- Manager --> ManFlow[Manager Flow]
    RoleCheck -- Admin --> AdminFlow[Admin Flow]
    
    subgraph Customer Workflow
        CustFlow --> Browse[Browse Rooms & Services]
        Browse --> Selection[Select Room & Dates]
        Selection --> PremiumCheck{Is Premium User?}
        PremiumCheck -- Yes --> Discount[Apply 5%/10% Discount]
        PremiumCheck -- No --> NoDiscount[Standard Price]
        Discount --> Booking[Confirm Booking]
        NoDiscount --> Booking
        Booking --> Chat[Live Chat with Concierge]
    end
    
    subgraph Manager Workflow
        ManFlow --> ManageRooms[CRUD Rooms & Services]
        ManFlow --> ReviewBookings[Review Pending Bookings]
        ReviewBookings --> Action{Approve or Reject?}
        Action -- Approve --> Confirmed[Booking Confirmed]
        Action -- Reject --> Cancelled[Booking Cancelled]
        ManFlow --> Support[Reply to Customer Chats]
    end
    
    subgraph Admin Workflow
        AdminFlow --> UserMgmt[Manage All Users & Roles]
        AdminFlow --> Stats[View System Statistics]
        AdminFlow --> SysSettings[General System Configuration]
    end
```

## How to use this for your DOCX file:
1. Copy the code block above.
2. Go to **[Mermaid Live Editor](https://mermaid.live/)**.
3. Paste the code in the left panel.
4. Download the generated diagram as a **PNG** or **SVG** image.
5. Insert the image into your `.docx` file.
