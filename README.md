# Internship Management System (IMS)

The **Internship Management System (IMS)** is an enterprise-grade, web-based platform designed to automate and streamline the entire lifecycle of intern management within an organization. From HR-driven pre-registration and department-based onboarding to daily digital logbook auditing, task lifecycle tracking, competency evaluations, and formal PDF performance reporting, IMS delivers an end-to-end operational workspace.

Built on **Laravel 13** with a **Teal & Sapphire** design system, full dark mode capability, and role-based security, IMS bridges the gap between interns, company supervisors, and human resources.

---

## 1. System Architecture & Technical Stack

```
 ┌─────────────────────────────────────────────────────────────────────────┐
 │                            CLIENT LAYER                                 │
 │     Blade Templates  •  TailwindCSS (v3.1/v4)  •  Alpine.js (v3.4)       │
 │                Chart.js Visuals  •  Teal & Sapphire UI                   │
 └────────────────────────────────────┬────────────────────────────────────┘
                                      │  Vite Asset Bundler / HMR
 ┌────────────────────────────────────▼────────────────────────────────────┐
 │                           APPLICATION LAYER                             │
 │    Laravel 13.8 (PHP 8.3+)  •  Breeze Auth  •  Role Middleware (RBAC)  │
 │     Services (OnboardingService)  •  Policies & Custom Controllers      │
 └───────────────────┬────────────────────┬────────────────────┬───────────┘
                     │                    │                    │
 ┌───────────────────▼──────┐ ┌───────────▼──────────┐ ┌───────▼───────────┐
 │      DATABASE LAYER      │ │   REPORTING ENGINE   │ │ NOTIFICATIONS/MAIL│
 │  SQLite / MySQL / PgSQL  │ │ DomPDF (v3.1) Export │ │  Mailtrap / SMTP  │
 │  t_ Prefixed Schema      │ │ Completion Certificates│ Db Notifications│
 └──────────────────────────┘ └──────────────────────┘ └───────────────────┘
```

### Core Technologies & Dependencies

| Layer | Technology / Package | Version / Detail | Description |
| :--- | :--- | :--- | :--- |
| **Framework** | PHP | `^8.3` | Backend execution runtime |
| **Framework** | Laravel Framework | `^13.8` | Core MVC framework with Breeze authentication scaffolding |
| **Database** | SQLite / MySQL / PostgreSQL | Default: `database.sqlite` | Relational database storage with custom `t_` prefixed schema |
| **Frontend** | Vite | `^8.0.0` | Next-generation frontend build tool and asset bundler |
| **Styling** | TailwindCSS | `^3.1.0` / `@tailwindcss/vite ^4.0.0` | Utility-first CSS framework with custom design tokens |
| **Interactivity** | Alpine.js | `^3.4.2` | Lightweight JavaScript framework for reactive Blade UI components |
| **Data Viz** | Chart.js | Integrated CDN | Interactive doughnut charts for intern task completion analytics |
| **Reporting** | DomPDF (`barryvdh/laravel-dompdf`) | `^3.1` | Server-side HTML-to-PDF report generation engine |
| **Concurrency** | Concurrently | `^9.0.1` | Multi-process runner for Artisan, Queue, Pail, and Vite |
| **Testing** | PHPUnit / Collision | `^12.5` / `^8.6` | Comprehensive automated test suite runner & error formatter |
| **Code Style** | Laravel Pint | `^1.27` | Zero-configuration PHP code style fixer |

---

## 2. Humanised RBAC Ecosystem & System Roles

IMS enforces strict Role-Based Access Control (RBAC) powered by custom `RoleMiddleware` (`role:admin`, `role:supervisor`, `role:intern`), granular policies, and dedicated controller namespaces.

```
                  ┌────────────────────────────────────────┐
                  │          SYSTEM ACCESS MATRIX          │
                  └───────────────────┬────────────────────┘
                                      │
         ┌────────────────────────────┼────────────────────────────┐
         │                            │                            │
 ┌───────▼──────────┐         ┌───────▼──────────┐         ┌───────▼──────────┐
 │ HR ADMINISTRATOR │         │    SUPERVISOR    │         │      INTERN      │
 │  (Neema Wacuka)  │         │  (Jane Wairimu)  │         │   (John Intern)  │
 └───────┬──────────┘         └───────┬──────────┘         └───────┬──────────┘
         │                            │                            │
 ├─ Dept & User CRUD          ├─ Onboarding Review         ├─ Account Activation
 ├─ Global Checklists         ├─ Task Allocation & Close   ├─ Daily Logbook Submission
 ├─ Supervisor Grouping       ├─ Logbook Auditing          ├─ Same-day Lock & Streak
 └─ PDF Export Access         └─ Guest Token Generation    └─ Task Progress Stats
```

### 🔑 HR Administrator Portal (Personified by Neema Wacuka)
Human Resources acts as the System Administrator in IMS, personified by **Neema Wacuka** (*HR Administrator*), retaining full administrative rights (`role:admin`):
* **Department Management:** Create, update, list, and toggle active/inactive states for organizational departments.
* **Intern Pre-Registration:** Pre-register new interns with metadata validation:
  * Internship placement start and end dates (restricted to current date or future start dates).
  * Automated supervisor filtering based on selected department.
  * Institution, academic programme, and registration details.
* **Checklist Template Management:** Manage global onboarding templates (required vs. optional items) per department.
* **Supervisor Overview Grouping:** Monitor registered interns dynamically grouped under collapsible toggles by their assigned supervisor.
* **PDF Performance Report Export:** Download verified intern performance and completion reports.

### 👤 Company Supervisor Portal
Supervisors oversee assigned interns within their department (`role:supervisor`):
* **Onboarding Tracking:** Monitor and mark off onboarding checklist items as interns complete mandatory steps.
* **Task Allocation & Lifecycle:** Create, describe, and assign tasks with defined priorities (`High`, `Medium`, `Low`). Review completed tasks and officially close/resolve them.
* **Logbook Auditing:** Review daily digital logbook entries submitted by supervisees.
* **Competency Evaluation:** Conduct formal performance evaluations against structured competency criteria.
* **Guest Token Generation:** Generate cryptographically secure, time-limited tokens (`t_guest_tokens`) allowing external auditors or university liaisons to view an intern's logbook.

### 🎓 Intern Dashboard
Interns manage their day-to-day activities (`role:intern`):
* **Account Activation:** Activate pre-registered accounts via secure email activation links and set custom credentials.
* **Collapsible Onboarding Checklist:** Track onboarding items under structured, collapsible category sections.
* **Daily Digital Logbook:** Record daily logbook entries detailing activities performed, skills acquired, and challenges faced.
  * **Same-Day Edit Constraints:** Interns can only edit logbook entries created on the current calendar date; past entries are locked to read-only mode for audit integrity.
  * **Dynamic Logging Streak:** Calculates consecutive daily log submissions, powered by a visual streak badge.
* **Interactive Task Manager & Analytics:** View assigned tasks, mark tasks complete with deliverable notes, and analyze work progress using a Chart.js doughnut chart.

### 🌐 Public Guest Viewer
* Token-authenticated, read-only view (`/guest/logbooks/{token}`) for third-party auditors and institution supervisors without requiring a system login.

---

## 3. Database Schema & Data Models

All custom domain models map to database tables prefixed with `t_` for clean organizational separation:

```
  t_roles ──────────┐
                    ▼
  t_departments ──► t_users ───────┬──► t_supervisors ──┐
                    ▲              │                    │ (assigns)
                    │              └──► t_interns ◄─────┘
                    │                      │
  t_checklist_templates                    ├──► t_onboarding_checklists
                                           ├──► t_tasks
                                           ├──► t_logbook_entries
                                           ├──► t_guest_tokens
                                           └──► t_evaluations ──► t_evaluation_scores
```

### Table Breakdown

| Table Name | Associated Model | Key Fields / Description |
| :--- | :--- | :--- |
| `t_roles` | `Role` | `id`, `name` (*HR Administrator, Supervisor, Intern*), `slug` (*admin, supervisor, intern*) |
| `t_departments` | `Department` | `id`, `name`, `code` (*IT, HR, FIN, OPS*), `is_active` |
| `t_users` | `User` | `id`, `role_id`, `name`, `email`, `password`, `is_active`, `email_verified_at` |
| `t_supervisors` | `Supervisor` | `id`, `user_id`, `dept_id`, `employee_number` |
| `t_interns` | `Intern` | `id`, `user_id`, `dept_id`, `supervisor_id`, `institution`, `programme`, `registration_number`, `start_date`, `end_date` |
| `t_checklist_templates` | `ChecklistTemplate` | `id`, `dept_id`, `title`, `description`, `is_required` |
| `t_onboarding_checklists`| `OnboardingChecklist` | `id`, `intern_id`, `title`, `description`, `is_required`, `is_completed`, `completed_at` |
| `t_tasks` | `Task` | `id`, `created_by`, `assigned_to`, `title`, `description`, `priority`, `due_date`, `deliverable`, `status` (*pending, in_progress, completed, closed*), `completed_at`, `closed_at` |
| `t_logbook_entries` | `LogbookEntry` | `id`, `intern_id`, `entry_date`, `entry_type` (*daily, weekly*), `activities_performed`, `skills_acquired`, `challenges_faced` |
| `t_guest_tokens` | `GuestToken` | `id`, `user_id`, `intern_id`, `token`, `expires_at` |
| `t_notifications` | `Notification` | `id`, `user_id`, `type`, `data`, `read_at` |
| `t_evaluations` | `Evaluation` | `id`, `intern_id`, `supervisor_id`, `evaluation_date`, `comments`, `overall_score` |
| `t_competency_criteria` | `CompetencyCriteria` | `id`, `category`, `title`, `description`, `max_score` |
| `t_evaluation_scores` | `EvaluationScore` | `id`, `evaluation_id`, `criteria_id`, `score` |

---

## 4. Key Technical Features & Business Rules

### 🔐 1. Pre-Registration & Account Activation Workflow
Public self-registration is deliberately disabled (`Route::get('register')` commented out). Instead:
1. **HR Administrator (Neema Wacuka)** registers the intern via `/admin/interns/create`.
2. System creates the user account (`is_active = true`) and triggers `InternActivationNotification`.
3. Intern receives an email notification containing a secure, signed activation URL (`/activate/set-password/{email}`).
4. Intern sets their account password and verifies their email address (`email_verified_at = now()`).

### 📅 2. Same-Day Logbook Edit Constraints
To ensure audit compliance and prevent retrospective entry tampering:
- Logbook entries check `$entry->entry_date->isToday()`.
- If the entry was created on a previous date, the update endpoint throws a validation exception or HTTP 403, locking past entries to read-only status.

### 🔥 3. Dynamic Logging Streak Engine
The system calculates active logging streaks for interns by evaluating consecutive daily submissions (`entry_type = daily`):
- Measures consecutive calendar days with submitted logbook records.
- Displayed prominently on the Intern Dashboard via an animated gradient streak card.

### 📋 4. Task Lifecycle Management
Tasks transition through explicit status states:
`Pending` ➔ `In Progress` ➔ `Completed` (Intern marks with deliverable notes) ➔ `Closed / Resolved` (Supervisor reviews and closes).

### 📄 5. PDF Completion Report Engine
Powered by **DomPDF**, supervisors and HR Administrators can generate formal PDF completion reports (`/interns/{intern}/report`) containing:
- Intern details, institution, placement period, and department.
- Onboarding checklist completion rates.
- Aggregated task completion statistics and logbook entry summaries.
- Official digital signature placeholders for **Company Supervisor** and **HR Administrator (Neema Wacuka)**.

---

## 5. Design System Architecture

The UI adheres to a custom **Teal & Sapphire** theme following the **60-30-10 visual weight balance**:

```
 ┌─────────────────────────────────────────────────────────────────────────┐
 │ 60% DOMINANT NEUTRAL: Slate Canvas (#F8FAFC / Dark: #0F172A)           │
 ├─────────────────────────────────────────────────────────────────────────┤
 │ 30% STRUCTURAL FRAMES: Mellow Sapphire Containers (#1E293B)             │
 ├─────────────────────────────────────────────────────────────────────────┤
 │ 10% ACCENT IDENTIFIERS: Vibrant Teal (#2DD4BF / #0D9488 Buttons/Badges) │
 └─────────────────────────────────────────────────────────────────────────┘
```

### Dark Mode Toggle
- Persistent Sun/Moon theme toggle in the top navigation header.
- Uses inline non-flicker script execution and persists state across page loads in `localStorage`.
- High-contrast slate-white mapping (`#CBD5E1`) for body text and pure white (`#FFFFFF`) for headers in dark mode.

---

## 6. Installation & Local Setup

### Prerequisites
- **PHP**: `>= 8.3` (with `pdo_sqlite` / `pdo_mysql`, `mbstring`, `openssl`, `gd`, `xml` extensions enabled)
- **Composer**: `>= 2.0`
- **Node.js**: `>= 18.0` & **NPM**
- **Database**: SQLite (default) or MySQL/PostgreSQL

### Step-by-Step Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/your-org/ims.git
   cd ims
   ```

2. **Run automated composer setup script:**
   ```bash
   composer run setup
   ```
   *This command installs PHP dependencies, copies `.env.example` to `.env` if missing, generates app key, runs database migrations, installs NPM packages, and builds frontend production assets.*

3. **Configure Environment Variables (`.env`):**
   ```ini
   APP_NAME="Internship Management System"
   APP_ENV=local
   APP_KEY=base64:...
   APP_DEBUG=true
   APP_URL=http://localhost:8000

   # Database Configuration (SQLite default)
   DB_CONNECTION=sqlite
   # For MySQL:
   # DB_CONNECTION=mysql
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=ims
   # DB_USERNAME=root
   # DB_PASSWORD=

   # Mail Configuration (Mailtrap / Local Log)
   MAIL_MAILER=log
   MAIL_FROM_ADDRESS="admin@ims.test"
   MAIL_FROM_NAME="IMS HR Portal"
   ```

4. **Seed Database with Initial Data:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Seeds default roles, departments (IT, HR, Finance, Operations), HR Administrator (Neema Wacuka), supervisors, interns, checklist templates, and realistic logbook/task sample data.*

5. **Start Local Development Environment:**
   Run all development processes (Artisan Server, Queue Listener, Pail Log Monitor, and Vite HMR) concurrently:
   ```bash
   composer run dev
   ```
   Or launch separately:
   ```bash
   php artisan serve   # Server running at http://localhost:8000
   npm run dev         # Vite HMR asset server
   ```

---

## 7. Testing & Quality Assurance

IMS includes a robust automated test suite written in **PHPUnit** covering models, authentication, role middleware, policies, views, reports, and feature workflows.

### Running Tests

Run the complete test suite:
```bash
composer test
# or
php artisan test
```

### Test Suite Structure (`tests/`)

```
tests/
├── Feature/
│   ├── AdminInternManagementTest.php          # Intern CRUD & pre-registration
│   ├── AdminOnboardingDashboardTest.php        # Admin dashboard & supervisor grouping
│   ├── ChecklistTemplateManagementTest.php     # Global checklist template management
│   ├── InternActivationTest.php                # Email activation & password setup
│   ├── InternDashboardTest.php                 # Intern view & streak card
│   ├── InternLogEditAndStreakTest.php          # Same-day log edit lock & streak engine
│   ├── OnboardingChecklistCompletionTest.php   # Onboarding completion logic
│   ├── OnboardingChecklistPolicyTest.php       # RBAC authorization policy checks
│   ├── Phase1ModelsTest.php                    # Domain models & t_ table relationships
│   ├── Phase2RoutesTest.php                    # Route permissions & role middleware
│   ├── Phase3ViewsTest.php                    # UI layout rendering & dark mode tokens
│   ├── Phase4ReportingTest.php                 # DomPDF generation & downloads
│   ├── Phase5VerificationTest.php              # Comprehensive system verification
│   └── Auth/                                   # Authentication & password reset tests
└── Unit/
    └── ExampleTest.php
```

---

## 8. Default Credentials for Testing

| Role | Name | Email | Default Password | Role Access |
| :--- | :--- | :--- | :--- | :--- |
| **HR Administrator** | Neema Wacuka | `admin@ims.test` | `Admin@1234` | `/admin/dashboard` |
| **Company Supervisor** | Jane Wairimu | `warugurumundia593@gmail` | `Super@1234` | `/supervisor/dashboard` |
| **Intern** | John Intern | `intern@ims.test` | `Intern@1234` | `/intern/dashboard` |

---

## 9. License & Maintainers

- **Project**: Internship Management System (IMS)
- **Framework**: Built with [Laravel Framework](https://laravel.com)
- **License**: Open-source software licensed under the [MIT License](LICENSE).
