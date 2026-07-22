# Internship Management System (IMS)

The **Internship Management System (IMS)** is a modern, responsive enterprise portal designed to manage and automate the onboarding, logbook tracking, and task lifecycle of interns within an organization. It features role-based access control, interactive analytics, same-day editing constraints, dynamic logging streaks, and a beautiful custom-engineered **Teal & Sapphire** interface with complete dark mode support.

---

## 1. System Roles & Functionalities

The system supports three user roles, each with a tailored workspace and custom modules:

### 🔑 HR Administrator Portal (Neema Wacuka)
* **Humanised System Administration:** Human Resources (HR) acts as the System Administrator in IMS, personified by **Neema Wacuka** (HR Administrator), retaining full system admin privileges.
* **Department Management:** Create, list, edit, and toggle active/inactive states for organizational departments.
* **Intern Registration:** Register new interns with detailed metadata, including:
  * Internship Start & End Dates (restricted to current date or future start dates).
  * Assigned Supervisor (automatically filtered by department).
  * University, Course of Study, and registration details.
* **Checklist Templates:** Manage global onboarding checklist templates, including required and optional items.
* **Supervisor Overview Grouping:** View registered interns dynamically grouped under collapsable toggles by their assigned supervisor.

### 👤 Supervisor Portal
* **Onboarding Checklist Management:** Monitor and mark off checklist items as interns complete their required onboarding steps.
* **Task Allocation:** Create, describe, and allocate tasks to assigned interns with defined priority levels (`High`, `Medium`, `Low`).
* **Task Closure & Review:** Receive real-time indicators when an intern marks a task as "Complete". Review and officially close/resolve tasks from the supervisor end (reflecting as "Closed / Resolved" on the intern view).
* **Logbook Auditing:** Review daily digital logbook entries submitted by assigned interns.

### 🎓 Intern Dashboard
* **Collapsible Checklist Overview:** Track onboarding items under structured, collapsable dropdown sections.
* **Daily Digital Logbook:** Record daily logbook entries detailing tasks performed.
  * **Same-Day Constraints:** Interns can only edit/update logbooks created on the current calendar date; historical entries are locked to read-only for audit integrity.
  * **Dynamic Logging Streak:** Tracks and displays consecutive daily logging days. Gaps of one day break the streak. Powered by a vibrant, pulsing gradient streak card.
* **Interactive Task Manager:** 
  * View active and completed tasks history.
  * Mark assigned tasks as "Complete" to submit them to supervisors for review.
  * **Work Statistics Chart:** A custom-styled Chart.js doughnut wheel showing real-time task breakdowns (Completed, In Progress, Pending) with segment-spacing, hover offset dimensions, and matching status card readouts.

---

## 2. Design System Architecture

The portal is styled using a custom **Teal & Sapphire** visual weight layout, implementing a strict contrast architecture:

### Color Palette (60-30-10 Rule)
* **60% Dominant Neutral (`#F8FAFC`):** Soft slate workspace canvas background.
* **30% Structural Frames:** Mellow Slate Sapphire (`#1E293B`) sidebar background and content cards.
* **10% Accent Identifiers:** Vibrant Teal (`#2DD4BF` active lines, `#0D9488` buttons) for core interactive components.

### Dark Mode Toggle
* A persistent **Sun/Moon Toggle** located in the top navbar enables system-wide midnight theme toggling.
* Leverages non-flicker initialization and persists user selection across page reloads via `localStorage`.
* **Universal Text Visibility:** Automatically maps all text nodes to high-contrast slate-white (`#CBD5E1`) and headings to pure white (`#FFFFFF`) under dark theme.
* **Action Buttons Protection:** Converts soft action badges (Edit, Delete, Deactivate, Report) to deep glow containers in dark mode for maximum accessibility.

---

## 3. Installation & Technical Setup

Follow these steps to run the IMS portal locally:

### Prerequisites
* PHP >= 8.1
* Composer
* Node.js & NPM
* SQLite / MySQL Database

### Setup Instructions

1. **Clone the repository and install dependencies:**
   ```bash
   composer install
   npm install
   ```

2. **Configure environment variables:**
   ```bash
   cp .env.example .env
   # Update DB_DATABASE or database parameters inside .env
   ```

3. **Initialize encryption keys and database:**
   ```bash
   php artisan key:generate
   touch database/database.sqlite  # if using SQLite
   php artisan migrate:fresh --seed
   ```

4. **Compile assets & start local servers:**
   * Build the CSS/JS pipelines:
     ```bash
     npm run dev
     ```
   * Start the Laravel dev server:
     ```bash
     php artisan serve
     ```

### Testing
Verify all system constraints and role behaviors by running PHPUnit tests:
```bash
php artisan test
```
