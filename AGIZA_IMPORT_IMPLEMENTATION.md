# Agiza/Import Feature Implementation

## Overview
The Agiza/Import feature allows customers to request assistance importing vehicles from outside Tanzania. Customers can either provide a link to a car listing or indicate they've already contacted a dealer and need help with the import process.

## Features Implemented

### Customer Features
1. **Submit Import Request** (`/agiza-import`)
   - Two request types:
     - **With Link**: Customer provides a URL to a car listing
     - **Already Contacted**: Customer has spoken to a dealer and needs import assistance
   - Form fields:
     - Contact information (auto-filled for logged-in users)
     - Vehicle details (make, model, year, condition)
     - Source country
     - Car listing link OR dealer contact info
     - Estimated price (optional)
     - Special requirements (optional)
     - Additional notes (optional)
     - Vehicle photos upload (optional)
     - Documents upload (optional)

2. **My Import Requests** (`/agiza-import/requests`)
   - View all submitted import requests
   - Filter by status
   - Search by request number, make, or model
   - Track request status

### Admin Features
1. **Agiza/Import Requests Dashboard** (`/admin/agiza-import`)
   - View all customer import requests
   - Statistics cards showing:
     - Total requests
     - Pending requests
     - Under review
     - Quote provided
     - In progress
   - Filter by:
     - Status (pending, under_review, quote_provided, accepted, in_progress, completed, cancelled)
     - Request type (with_link, already_contacted)
   - Search functionality

2. **Request Detail View** (`/admin/agiza-import/{id}`)
   - View complete request details
   - Update request status
   - Assign agent to handle the request
   - Provide quote (import cost, total cost, currency)
   - Add admin notes
   - View customer information
   - View vehicle details and links
   - View uploaded documents and images

## Database Schema

### Table: `agiza_import_requests`
- `id` - Primary key
- `request_number` - Unique request identifier (format: AGZ-YYYYMMDD-####)
- `user_id` - Foreign key to users table
- Customer information (name, email, phone)
- Vehicle information (make, model, year, condition, link, source_country)
- Request details (type, dealer_contact_info, estimated_price, special_requirements, notes)
- Documents and images (JSON arrays)
- Status tracking (pending → under_review → quote_provided → accepted → in_progress → completed/cancelled)
- Admin fields (assigned_to, admin_notes, quoted costs, timestamps)

## Files Created/Modified

### New Files
1. **Migration**: `database/migrations/2026_03_18_125537_create_agiza_import_requests_table.php`
2. **Model**: `app/Models/AgizaImportRequest.php`
3. **Livewire Components**:
   - `app/Livewire/Customer/AgizaImport.php`
   - `app/Livewire/Customer/AgizaImportRequests.php`
   - `app/Livewire/Admin/AgizaImportRequests.php`
   - `app/Livewire/Admin/AgizaImportRequestDetail.php`
4. **Views**:
   - `resources/views/livewire/customer/agiza-import.blade.php`
   - `resources/views/livewire/customer/agiza-import-requests.blade.php`
   - `resources/views/livewire/admin/agiza-import-requests.blade.php`
   - `resources/views/livewire/admin/agiza-import-request-detail.blade.php`
   - `resources/views/components/customer/navigation/agiza-import.blade.php`
5. **Test**: `tests/Feature/AgizaImportTest.php`
6. **Factory**: `database/factories/AgizaImportRequestFactory.php`
7. **Seeder**: `database/seeders/AgizaImportRequestSeeder.php`

### Modified Files
1. `routes/web.php` - Added Agiza/Import routes for customer and admin
2. `resources/views/components/customer/header.blade.php` - Added Agiza/Import menu item and submenu
3. `resources/views/components/admin/sidebar.blade.php` - Added Agiza/Import menu item in admin panel
4. `resources/views/components/customer/navigation/financing.blade.php` - Reordered submenu items

## Routes

### Customer Routes
- `GET /agiza-import` - Submit import request form
- `GET /agiza-import/requests` - View my import requests (auth required)

### Admin Routes
- `GET /admin/agiza-import` - View all import requests
- `GET /admin/agiza-import/{id}` - View/manage specific request

## Status Flow
1. **Pending** - Initial status when request is submitted
2. **Under Review** - Admin is reviewing the request
3. **Quote Provided** - Admin has provided a quote to the customer
4. **Accepted** - Customer has accepted the quote
5. **In Progress** - Import process is underway
6. **Completed** - Vehicle successfully imported
7. **Cancelled** - Request cancelled

## Testing
All tests passing (7 tests, 12 assertions):
- Guest cannot submit request (must login)
- Authenticated user can submit request with link
- Authenticated user can submit request with dealer contact
- Request number generation works correctly
- Admin can view all requests
- Admin can update request status
- Admin can provide quotes

## Usage

### For Customers
1. Navigate to "Agiza/Import" in the main menu
2. Choose request type (with link or already contacted dealer)
3. Fill in vehicle details and contact information
4. Upload photos/documents (optional)
5. Submit request
6. Track request status in "My Import Requests"

### For Admins
1. Access "Agiza/Import Requests" from admin sidebar
2. View all requests with filtering and search
3. Click on a request to view details
4. Update status, assign agents, provide quotes
5. Add internal notes for team reference

## Next Steps (Optional Enhancements)
- Email notifications when status changes
- Customer chat/messaging with assigned agent
- Quote acceptance workflow
- Payment integration
- Shipping tracking integration
- Document verification workflow
