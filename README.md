# Foundly: Lost and Found Management System

**Documentation**
Prepared for PUP Taguig
Prepared by: Group 16 — BSIT 2-1 (Cervales, Yuann Czedriehck D., Marondo, John Immanuel C., Talosig, Jhun Francis M.)
2026

---

## Table of Contents
1. [USER MANUAL](#user-manual)
   - [1. System Overview](#1-system-overview)
   - [2. Getting Started](#2-getting-started)
   - [3. User Roles and Permissions](#3-user-roles-and-permissions)
   - [4. Public Features](#4-public-features)
   - [5. User Features (Logged In)](#5-user-features-logged-in)
   - [6. Staff Features](#6-staff-features)
   - [7. Admin Features](#7-admin-features)
   - [8. Frequently Asked Questions](#8-frequently-asked-questions)
   - [9. Support](#9-support)
2. [CREDENTIALS](#credentials)

---

## USER MANUAL

### 1. System Overview
Foundly is a community-driven Lost and Found management system designed for PUP Taguig. The platform enables users to report lost or found items, claim ownership of items, and communicate securely with other users through an integrated messaging system.

**Core Features & Technical Additions**
* Secure authentication with strict ID verification (e.g., PUP ID regex `YYYY-XXXXX-TG-0`, National ID exact 12-digit requirement).
* **Smart Auto-Match Notifications:** Automatically alerts users via direct message if someone posts a "Found" item in a category where they previously reported a "Lost" item.
* Discovery feed with search and filter capabilities.
* Interactive map integration for location tracking (Latitude/Longitude support).
* Real-time messaging between users (polling every 3 seconds) with **Media Support** (Photo attachments up to 2MB).
* Claims management system.
* Report generation for administrators.
* User and role management for administrative staff.
* Audit logging for system accountability.
* **Similar Items Recommendation:** Actively recommends related items when viewing a specific post to help users find matches faster.
* **User-Friendly Self-Claiming:** Regular users can freely mark their own found postings as "Claimed" once they recover it, bypassing manual admin approval to generate an automated paper trail.
* **Automated Avatars:** System auto-generates sleek avatars using the first letter of a username if no profile picture is uploaded.

**APIs & Third-Party Integrations**
* **Leaflet.js:** Powers the interactive map integration. It provides an open-source, lightweight mapping solution to accurately pinpoint and display where items were lost or found using precise latitude and longitude coordinates.
* **AJAX Polling:** Utilized for the real-time chat system, ensuring messages are fetched and updated continuously (every 3 seconds) without requiring page reloads.

### 2. Getting Started

**System Access**
Open your web browser and navigate to the local installation URL:
`http://localhost/LF-web2/`
The landing page displays system statistics and provides navigation to all public features.

**Account Registration**
* Click the Register button located in the top-right corner of the page.
* Complete the registration form with the following required information:
  * First Name
  * Middle Initial (optional)
  * Last Name
  * Birthdate (age is calculated automatically)
  * Username (must be unique)
  * ID Type selection (PUP ID, National ID, Faculty ID, or Other)
  * ID Number (format validation is applied based on ID type)
  * Email Address (must be valid)
  * Password (minimum 6 characters)
  * Password Confirmation
* **Note on Duplicate Protection:** Accounts are rigorously verified against duplicate Usernames, Emails, or specific ID Type/Number combinations.
* Review the Terms and Conditions.
* Click I Agree to complete the registration process.
* You will be redirected to the login page upon successful registration.

**Login**
* Click the Login button in the top-right corner of the page.
* Enter your Username or Email address.
* Enter your Password.
* Click Sign In.

**Default System Accounts**
| Role | Username | Email Address | Password |
| :--- | :--- | :--- | :--- |
| Administrator | admin | admin@foundly.com | admin123 |
| Staff | jhun123 | jhun123@gmail.com | jhun123 |
| Staff | yuann123 | yuann123@gmail.com | yuann123 |
| Staff | john123 | john123@gmail.com | john123 |
| Regular User | testuser1 | testuser1@gmail.com | testuser1 |
| Regular User | testuser2 | testuser2@gmail.com | testuser2 |
| Regular User | yeriel123 | yeriel123@gmail.com | yeriel123 |
| Regular User | margie123 | margie123@gmail.com | margie123 |

### 3. User Roles and Permissions

| Feature | Regular User | Staff | Administrator |
| :--- | :--- | :--- | :--- |
| View Discovery Feed | Yes | Yes | Yes |
| Post Items | Yes | Yes | Yes |
| Edit Own Items | Yes | Yes | Yes |
| Delete Own Items | Yes | Yes | Yes |
| Claim Items | Yes | Yes | Yes |
| Send Messages | Yes | Yes | Yes |
| Receive Messages | Yes | Yes | Yes |
| Manage Profile | Yes | Yes | Yes |
| Manage User Accounts | No | No | Yes |
| Manage Categories | No | No | Yes |
| Manage All Items | No | No | Yes |
| Manage All Claims | No | Yes | Yes |
| View Audit Logs | No | No | Yes |
| Manage Issue Reports | No | Yes | Yes |
| Generate Reports | No | No | Yes |

### 4. Public Features

**Landing Page**
The landing page serves as the entry point for all visitors and displays:
* System-wide statistics including total claimed, lost, and found items.
* Search bar for locating items.
* How It Works section explaining the system process.
* Key features overview.
* Frequently Asked Questions section.
* Call-to-action buttons for registration and login.

**Search Functionality**
* The search bar is available on the landing page and the navigation bar.
* Search queries can be performed using item name, description, or location keywords.
* Search results are displayed in the Discovery Feed with matching items highlighted.

### 5. User Features (Logged In)

**Dashboard**
Access the dashboard by clicking the Dashboard button or the profile icon in the navigation bar.
The dashboard provides:
* Statistics cards displaying total lost and found items (both system-wide and personal).
* Pending claims count.
* Quick action shortcuts for common tasks.
* Recent user registrations (administrator only).

**Discovery Feed**
Access the Discovery Feed by clicking Home or the Feed link in the navigation.
* **Search Bar**: Enter keywords to find specific items by name, description, or location.
* **Category Filters**: Click category chips to filter items by category.
* **Status Filters**: Filter by Lost, Found, or Claimed status.
* **Sort Options**: Sort by Newest, Oldest, Name (A-Z or Z-A).
* **Map View**: View item locations on an interactive map.
* **Item Cards**: Each card displays: Photo thumbnail, Status badge, Item name, Category, Location details, Reporter name with avatar, Date and time reported, Action buttons.

**Posting an Item**
* Click the Post link in the navigation or the floating action button (+).
* Complete the four-step posting wizard:
  * **Step 1: Type** — Select I lost an item or I found an item.
  * **Step 2: Photos** — Upload a main photo and up to **4 additional photos** (Total: 5). *Note: Maximum file size is strictly 2MB per photo. Allowed formats: `jpg`, `jpeg`, `png`, `gif`.*
  * **Step 3: Details** — Enter item name, select category, provide description, and optionally set a reward.
  * **Step 4: Location** — Enter the location, specific location, date and time, and select the location on the interactive map.
* Click Publish Post to submit the item.
* *Note: Auto-save functionality preserves your progress. Leaving the page will save a draft that can be resumed later.*

**Viewing Item Details**
Click on any item card in the Discovery Feed. The item detail page displays:
* Full-size image with zoom capability.
* Thumbnail gallery for additional photos.
* Complete item information including name, category, status, location, and description.
* Location displayed on an interactive map.
* Action buttons for Contact, Claim, Edit, and Delete.
* **Similar items section** showing related posts (dynamically recommended based on category).

**Claiming an Item**
* Locate a Found item that you believe belongs to you.
* Click the Claim button on the item card or the item detail page.
* Complete the claim form with the following information: Your Full Name, ID Type and ID Number, Contact Number, Date of Claim, Proof Document.
  * *Note: Proof Documents must be `jpg`, `jpeg`, `png`, `gif`, or `pdf` and cannot exceed 2MB.*
* Click Submit Claim.
* The item finder will be notified and will review your claim.

**Messaging System**
* **Inbox**: Access via the Messages or Inbox link. Displays all conversations grouped by item. Unread messages are indicated with a badge.
* **Chat Interface**: Click Contact on an item card or item detail page. Send messages with optional **photo attachments (Max 2MB)**. Messages are updated in real-time (polling every 3 seconds). Quick reply buttons are available.

**Managing Personal Items**
* **Edit Lock Constraint**: An item cannot be edited once its status is marked as `claimed`.
* **Edit**: Click Edit on your own item to update details.
* **Delete**: Click Delete to remove your item from the system.
* **Mark Claimed (User-Friendly Self-Claiming)**: If you have recovered your own item, upload proof and mark it as claimed. This bypasses the admin approval pipeline and resolves the item immediately.

**My Claims**
* Access via the My Claims link in the navigation.
* View all claims submitted on your reported items.
* Update claim status for pending claims: Approve, Reject, or Claimed.

**Profile Management**
Click your profile icon and select Profile Management. Update your Profile Picture, Name, Birthdate, and Bio.
* *Note on Strict Profile Privacy:* The system separates public profile views from your private dashboard to ensure sensitive data (like exact ID numbers) remains hidden.

### 6. Staff Features

**Issue Reports Management**
* Access the Issues link in the navigation.
* View all submitted bug reports and feature suggestions.
* Mark reports as Resolved after addressing the reported issue.

### 7. Admin Features

**User Management**
Access through the User Management link in the Dashboard. Available actions include:
* Search Users by username, email, or ID number.
* Add User with assigned roles.
* Edit User role or account status (Active, Disabled).
* Delete User (self-deletion is prevented).
* Reset Password for any user account.

**Category Management**
Access through the Categories link.
* View Categories, Add Category, Edit Category, Delete Category (only if no items are currently using the category).

**Claims Management (Admin)**
Access through the All Claims link.
* Filter claims by status.
* Search claims by claimant name or ID number.
* Update any claim status.
* Delete claims.

**Audit Logs**
Access through the Audit link. View all system actions including:
* User logins and logouts.
* Item additions, edits, and deletions.
* Category changes, Claim updates, User management actions.

**Reports**
Access through the Reports link. Generate printable reports with Status, Category, and Date Range filters.

### 8. Frequently Asked Questions

**General Questions**
* **Q: Is Foundly free to use?** A: Yes, Foundly is completely free for all PUP Taguig students and staff.
* **Q: How do I report a bug or issue?** A: Navigate to Help and Support, then select Report an Issue, or click the Help link in the navigation.
* **Q: What if I cannot reach the person who found my item?** A: If no response is received within 48 hours, contact campus security or submit an issue report through the Help page.

**Claims Process**
* **Q: How do I claim an item?** A: Locate the item in the Discovery Feed, click the Claim button, and complete the claim form with your details and proof document.
* **Q: What if I found my own item?** A: On the item detail page, click Mark Claimed and upload proof of possession.
* **Q: Why is my claim still pending?** A: The item finder must review and approve your claim. You may send a message through the chat feature for follow-up.

**Item Management**
* **Q: Can I edit my post after publishing?** A: Yes, you can edit your own items by clicking the Edit button.
* **Q: How long do items remain visible?** A: Items remain visible until marked as claimed or deleted.
* **Q: What happens when an item is claimed?** A: The item receives a Claimed badge and is removed from the main feed to prevent duplicate claims.

**Privacy and Security**
* **Q: Who can view my personal information?** A: Only your username, profile picture, and bio are visible to other users. Email addresses and ID numbers are private.
* **Q: Are messages private?** A: Yes, messages are private between the two participants. Administrators may view messages for moderation purposes only.

### 9. Support

For additional assistance, please use the following channels:
* **Email Support:** support@foundly.ph
* **Issue Reporting:** Use the Help link in the navigation
* **Campus Office:** Visit the PUP Taguig Administration Office

---

## CREDENTIALS

**Login**
* Click the Login button in the top-right corner of the page.
* Enter your Username or Email address.
* Enter your Password.
* Click Sign In.

**Default System Accounts**
| Role | Username | Email Address | Password |
| :--- | :--- | :--- | :--- |
| Administrator | admin | admin@foundly.com | admin123 |
| Staff | jhun123 | jhun123@gmail.com | jhun123 |
| Staff | yuann123 | yuann123@gmail.com | yuann123 |
| Staff | john123 | john123@gmail.com | john123 |
| Regular User | testuser1 | testuser1@gmail.com | testuser1 |
| Regular User | testuser2 | testuser2@gmail.com | testuser2 |
| Regular User | yeriel123 | yeriel123@gmail.com | yeriel123 |
| Regular User | margie123 | margie123@gmail.com | margie123 |


