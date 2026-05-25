# Week 8: Testing & Quality Assurance (Unit Testing)

## 1. Unit Test Cases

The following test cases have been executed to ensure logic correctness, proper code organization, and error handling for the PorciTrack Backend.

| Test ID | Module | Scenario | Expected Result | Status |
|---|---|---|---|---|
| `TC_AUTH_01` | Authentication | Login with valid Admin credentials | Redirect to Admin Dashboard | Passed |
| `TC_AUTH_02` | Authentication | Login with valid Worker credentials | Redirect to Worker Portal | Passed |
| `TC_SWINE_01` | Swine Management | Add new pig to registry with valid data | Database record created, success message | Passed |
| `TC_SWINE_02` | Swine Management | Attempt to add pig without required RFID | Validation error returned | Passed |
| `TC_SWINE_03` | Swine Management | Update pig health status (CRUD) | Database updated, UI reflects change | Passed |
| `TC_PENS_01` | Pen Management | Expand pen details in "Pens & Pigs Report" | Load associated pigs correctly | Passed |
| `TC_ALERT_01` | Notifications | Worker triggers medical emergency alert | Alert sent to Admin database table | Passed |
| `TC_REPORT_01` | PDF Export | Admin generates farm report | PDF file generated and downloaded | Passed |
| `TC_BIO_01` | Biosecurity AI | Fetch location-specific disease data | API returns JSON threat data | Passed |

## 2. Bug List & Issues Tracked

| Bug ID | Component | Description | Severity | Status |
|---|---|---|---|---|
| `BUG_01` | Pens & Pigs Report | `ReferenceError` for missing functions `openModal` and `toggleAccordion` causing JS execution failure. | High | Fixed |
| `BUG_02` | Swine Details | Redundant modal footer buttons persisting in the UI. | Low | Fixed |
| `BUG_03` | Admin Portal | Worker-to-admin alerts not reflecting in real-time (requiring page reload). | Medium | Fixed |
| `BUG_04` | PDF Export | Export function fails when inline data scraping is used; fragile DOM dependency. | High | Fixed |
| `BUG_05` | Worker Portal | PWA/Browser caching aggressive, preventing UI updates. | Low | Fixed |

## 3. Rubric Evaluation (Self-Assessment)
*   **Test Case Completeness (40%)**: All major CRUD operations, authentication, and core modules are covered by the unit test plan.
*   **Accuracy of Results (40%)**: Test scenarios match actual business logic constraints. Real database seeding (`DatabaseSeeder.php`) is used to ensure accurate evaluation environments.
*   **Documentation (20%)**: Test cases and bugs are properly logged, categorized by severity, and tracked for resolution.
# Week 9: Testing & QA (UAT & Fixes)

## 1. User Acceptance Testing (UAT) Results

UAT was conducted with representatives acting as Farm Administrators and Farm Workers to ensure the system met real-world operational requirements.

| UAT Scenario | User Role | Action | Result / Feedback | Status |
|---|---|---|---|---|
| Initial Deployment Setup | Admin | Clone repository and login. | Access failed due to missing default roles (Admin/Worker). | Failed initially, then Fixed |
| Emergency Alerting | Worker | Submit medical emergency for a pig. | Alert submitted, but Admin was not notified immediately. | Failed initially, then Fixed |
| View Swine Records | Worker/Admin | Open Swine Details modal. | UI was cluttered with redundant footer elements. | Failed initially, then Fixed |
| Generate Farm Report | Admin | Export PDF from Pens & Pigs view. | PDF failed to generate due to DOM-scraping errors. | Failed initially, then Fixed |
| AI Biosecurity | Admin | View Threat Alerts. | Real-time AI processing was successful. High satisfaction with glassmorphism UI. | Passed |

## 2. Revised System Overview (Fixes Implemented)

Based on UAT feedback, the following critical revisions were made to the system architecture and UI:

1. **Deployment Standardization**: 
   - Updated `DatabaseSeeder.php` to automatically seed critical system roles (Admin/Worker), resolving authentication failures for new clones.
2. **Real-time Synchronization**:
   - Implemented a real-time notification polling mechanism in the admin portal to receive emergency alerts without page refreshes.
3. **UI/UX Cleanup**:
   - Removed redundant footer section from the swine record card modal in `pigCard.blade.php`.
   - Eliminated visual clutter ("Quick Action" manual entry bars) in the Worker Portal for a cleaner interface.
4. **Reporting System Refactoring**:
   - Replaced fragile inline data passing (`json_encode`) with structured Blade `@json` directives.
   - Refactored reporting logic into the `PT_APP` global object, restoring functional integrity to the "Pens & Pigs Report" and PDF download features.

## 3. Rubric Evaluation (Self-Assessment)
*   **Issue Resolution (40%)**: All critical bugs identified during Unit Testing and initial UAT have been resolved (JS `ReferenceError`, PDF generation, authentication access).
*   **System Reliability (40%)**: Refactored logic to utilize the global `PT_APP` object and robust Blade directives prevents future DOM dependency errors.
*   **User Feedback Incorporation (20%)**: UI clutter was removed and real-time polling was added directly addressing user feedback regarding workflow efficiency.
# Week 11: System Refinement

## 1. Enhanced Features

In this phase, advanced functionalities were implemented to elevate the PorciTrack system from a basic CRUD application to an intelligent farm management platform.

*   **AI Biosecurity Intelligence Module**: 
    *   Integrated Google Gemini AI to retrieve location-specific disease data and dynamically update risk assessments.
    *   Deployed a "Threat Alert" card interface to the dashboard with optimized glassmorphism styling for both light and dark modes.
*   **Real-Time Admin Dashboard**:
    *   Replaced static alert panels with a dynamic Worker-To-Admin Alert System featuring polling and auditory feedback for medical emergencies.

## 2. Performance & Security Improvements

*   **Caching & State Management**: 
    *   Bypassed aggressive PWA and local browser caching to force interface updates, stabilizing the Farm Worker Portal UI.
*   **Data Structure Integrity**:
    *   Migrated from fragile inline JSON rendering to secure Laravel Blade `@json` directives, ensuring proper script loading order via `@push('scripts')`.
*   **Security Configurations**:
    *   Secured sensitive API credentials (e.g., `GEMINI_API_KEY`) within environment files.
    *   Verified database migration status to ensure the schema includes `role` and `status` columns for robust authorization.

## 3. Rubric Evaluation (Self-Assessment)
*   **Improvement Relevance (40%)**: Refinements directly addressed performance bottlenecks (caching, DOM loading) and expanded business value (Biosecurity AI).
*   **System Performance (40%)**: Removing inline DOM-scraping and moving logic to the `PT_APP` object drastically reduced JS execution errors and UI latency.
*   **Stability (20%)**: Test suites and final deployments confirm the system is stable and fully functional for end users.
# Week 12: Final User Manual

*Note: This is a textual framework. For the official visual manual, please reference the compiled PDF containing application screenshots.*

## 1. System Overview
PorciTrack is an advanced Farm Management System built with Laravel, designed to digitize livestock registries, automate biosecurity risk assessments via AI, and facilitate real-time communication between Farm Workers and Administrators.

## 2. Administrator Guide

### 2.1 Dashboard & Analytics
*   **Accessing the Dashboard**: Log in using Admin credentials. The dashboard displays overall farm metrics, active biosecurity threats, and pending medical alerts.
*   **Biosecurity AI**: The "Threat Alert" card automatically fetches and displays location-specific disease data using Gemini AI. 

### 2.2 Farm Management
*   **Pens & Pigs Report**: Navigate to the Reports section. Here you can expand individual pens to view associated swine records. 
*   **Generating Reports**: Click "Generate & View Report" to compile economic metrics, animal health status, and logs. Use the "Export PDF" button to download a hard copy.

### 2.3 User Management & Real-Time Alerts
*   **Emergency Monitoring**: The system automatically polls for medical emergencies flagged by workers. Auditory feedback will sound when a critical flag is raised.

## 3. Farm Worker Portal Guide

### 3.1 Livestock Registry
*   **Viewing Swine Details**: Use the simplified, minimalist Worker Portal to access the Swine Details page. Click on a specific pig's card to view its health status.
*   **Adding Records**: Ensure you input the mandatory RFID tag when adding new livestock to the registry.

### 3.2 Reporting Emergencies
*   **Flagging Medical Issues**: If a pig exhibits symptoms, open the pig's record card and click the "Flag Medical Emergency" button. This will instantly send a notification to the Admin Dashboard.

## 4. Rubric Evaluation (Self-Assessment)
*   **Professional Quality (40%)**: The manual is structured, role-based, and reference-grade.
*   **Accuracy (40%)**: All workflows reflect the latest UI changes (e.g., removed redundant footers, added AI integration).
*   **Formatting (20%)**: Clean markdown structure allows for easy conversion to PDF with screenshot placeholders.
# Week 15: Final Evaluation & Submission

## 1. System Architecture & Final Configuration
PorciTrack (SADII) has reached v1.0 Production Readiness. The final system encapsulates a dual-portal farm management environment utilizing the Laravel framework, optimized for modern agricultural requirements.

**Core Technology Stack:**
*   **Backend**: Laravel (PHP), MySQL Database
*   **Frontend**: Blade Templating, Tailwind CSS/Custom Glassmorphism styling, Vanilla JS/PT_APP global scope
*   **Integrations**: Google Gemini AI (Biosecurity), DOMPDF (Reporting)

## 2. Complete Documentation Directory

This documentation suite fulfills the final evaluation requirements for the Systems Analysis and Design II (SADII) project deliverables.

| Document Title | Reference File | Content Overview |
|---|---|---|
| **Unit Testing & Bug Tracker** | `Week_08_Testing_QA.md` | Verification of core CRUD operations, authentication logic, and tracking of resolved software defects. |
| **UAT & Revision Logs** | `Week_09_UAT_Fixes.md` | Stakeholder testing feedback and architectural fixes implemented based on user experience. |
| **System Refinement Report** | `Week_11_System_Refinement.md` | Details on the Biosecurity AI integration, caching optimizations, and real-time polling configurations. |
| **Final User Manual** | `Week_12_Final_User_Manual.md` | Role-based guide for Administrators and Farm Workers, detailing workflows for reporting, alerts, and registry management. |
| **Deployment Configuration** | `README.md` (Root) | Database seeding instructions (`DatabaseSeeder.php`), environment variable setups (`GEMINI_API_KEY`), and server spin-up guidelines. |

## 3. Rubric Evaluation (Self-Assessment)
*   **Overall System Quality (50%)**: The application demonstrates high stability, responsive UI design (dark/light mode glassmorphism), and robust error handling. Complex features like AI integrations and real-time notifications operate smoothly.
*   **Completeness of Outputs (30%)**: All required modules (Backend, Auth, Reporting, Alerts) are fully functional and properly seeded.
*   **Professionalism (20%)**: The codebase is cleanly organized, commits are properly attributed to team members, and the documentation adheres to professional formatting standards.
