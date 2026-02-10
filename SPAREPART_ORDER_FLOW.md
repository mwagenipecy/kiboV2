# Spare Part Order Flow - Complete Documentation

## Flow Overview: From Language Selection to Order Completion

### Step 1: Language Selection
- **User Action**: Selects language (1 for English, 2 for Swahili)
- **System Response**: Shows main menu with available services
- **Next Step**: Main menu

### Step 2: Main Menu
- **User Action**: Selects "Spare Parts" service (by number or name)
- **System Response**: Shows spare parts service details
- **User Action**: Types "order", "buy", "yes", or similar keywords
- **Next Step**: Order flow starts

### Step 3: Email Request
- **System Prompt**: "Please provide your email address"
- **User Action**: Enters email address
- **Validation**: Email format validation
- **System Action**: 
  - Generates 4-digit OTP
  - Stores OTP in conversation context
  - Dispatches OTP email via Job
- **System Response**: "We've sent a verification code to your email. Please enter the verification code sent on your email (4 digits). If you didn't receive it, type 'resend'."
- **Navigation**: User can type "back" to return to main menu
- **Next Step**: OTP verification

### Step 4: OTP Verification
- **User Action**: Enters 4-digit OTP code
- **System Validation**:
  - Checks if OTP matches stored code
  - Checks if OTP has expired (10 minutes)
- **If Invalid**: Shows error message with option to resend
- **If Expired**: Prompts user to resend OTP
- **If Valid**: 
  - Clears OTP from context
  - Moves to vehicle make selection
- **Navigation**: 
  - User can type "resend" to get new OTP
  - User can type "back" to re-enter email
- **Next Step**: Vehicle make selection

### Step 5: Vehicle Make Selection
- **System Prompt**: Shows list of available vehicle makes (numbered list)
- **User Action**: Selects make by number or name
- **System Validation**: 
  - Checks if selection is valid
  - If not found, re-shows list with error message
- **System Response**: "✓ Vehicle make: [Make Name]. Please select the vehicle model:"
- **Navigation**: User can type "back" to return to OTP step
- **Next Step**: Vehicle model selection

### Step 6: Vehicle Model Selection
- **System Prompt**: Shows list of models for selected make (numbered list)
- **User Action**: Selects model by number or name
- **System Validation**: 
  - Checks if selection is valid
  - If not found, re-shows list with error message
- **System Response**: "✓ Model: [Model Name]. Please enter the name of the spare part you need:"
- **Navigation**: User can type "back" to return to make selection
- **Next Step**: Part name input

### Step 7: Part Name Input
- **System Prompt**: "Please enter the name of the spare part you need:"
- **User Action**: Enters spare part name
- **System Validation**: Checks if part name is not empty
- **System Response**: "✓ Part name: [Part Name]. Do you have an image of this part? (Reply 'yes' or 'no')"
- **Navigation**: User can type "back" to return to model selection
- **Next Step**: Image question

### Step 8: Image Question
- **System Prompt**: "Do you have an image of this part? (Reply 'yes' or 'no')"
- **User Action**: Replies "yes" or "no"
- **If Yes**:
  - System: "Please send the image(s) of the spare part. You can send one or more images. After sending images, type 'done' or 'ready'."
  - Next Step: Image upload
- **If No**:
  - System: "Okay. Please provide delivery description (full delivery address):"
  - Next Step: Delivery description

### Step 9: Image Upload (If Yes)
- **User Action**: Sends image(s) via WhatsApp
- **System Response**: "Image received. You can continue sending more images, or type 'done' to continue."
- **User Action**: Types "done" or "ready" when finished
- **System Response**: "Thank you for the images. Please provide delivery description (full delivery address):"
- **Navigation**: User can type "back" to return to image question
- **Next Step**: Delivery description

### Step 10: Delivery Description
- **System Prompt**: "Please provide delivery description (full delivery address):"
- **User Action**: Enters delivery address
- **System Validation**: Checks if address is not empty
- **System Response**: "✓ Delivery description: [Address]. Please provide any additional explanation about this part (or type 'none' if you don't have additional details):"
- **Navigation**: User can type "back" to return to image step
- **Next Step**: Part explanation

### Step 11: Part Explanation
- **System Prompt**: "Please provide any additional explanation about this part (or type 'none' if you don't have additional details):"
- **User Action**: Enters explanation or types "none"
- **System Action**: 
  - Saves current order to orders array
  - Clears current order context
- **System Response**: "✓ Spare part added to your order! Would you like to add another spare part? (Reply 'yes' or 'no')"
- **Next Step**: Add more question

### Step 12: Add More Question
- **System Prompt**: "Would you like to add another spare part? (Reply 'yes' or 'no')"
- **User Action**: Replies "yes" or "no"
- **If Yes**:
  - System: Shows vehicle makes list again
  - Next Step: Vehicle make selection (repeat from Step 5)
- **If No**:
  - System: Shows order review
  - Next Step: Review orders

### Step 13: Review Orders
- **System Display**: Shows all orders in formatted review:
  ```
  📋 REVIEW YOUR ORDER:
  
  ━━━━━━━━━━━━━━━━━━━━━━━━━━━
  Order #1
  Vehicle Make: [Make]
  Model: [Model]
  Part Name: [Part Name]
  Image: Yes/No
  Delivery: [Address]
  Explanation: [Details]
  
  [More orders if any]
  
  Would you like to confirm this order? (Reply 'yes' or 'no')
  Or type 'back' to add another part.
  ```
- **User Action**: 
  - Types "yes" to confirm
  - Types "no" to cancel
  - Types "back" to add more parts
- **Next Step**: Confirm orders (if yes)

### Step 14: Confirm Orders
- **System Action**: 
  - Validates all orders have required fields
  - Creates orders in database
  - Generates unique order numbers
  - Dispatches confirmation emails for each order
  - Clears order context
  - Returns to main menu
- **System Response**: 
  ```
  ✅ Thank you! Your order has been created successfully!
  
  📦 Order Number: [ORDER_NUMBER]
  
  📧 We've sent a confirmation email to: [EMAIL]
  
  📱 You can check your order status anytime by visiting:
  [WEBSITE_URL]/spare-parts/orders
  
  Or search by order number: [ORDER_NUMBER]
  
  How else can we help you?
  ```
- **Next Step**: Main menu (ready for new requests)

## Key Features

### Navigation Options
- **Back/Cancel**: Available at every step
- **Resend OTP**: Available during OTP verification
- **Skip Optional Fields**: Can type "none" for optional explanations

### Error Handling
- Invalid email format → Shows example and allows retry
- Invalid OTP → Shows error with resend option
- Expired OTP → Prompts to resend
- Invalid vehicle make/model → Re-shows list with error
- Empty required fields → Prompts to enter again

### User-Friendly Features
- Clear step-by-step guidance
- Confirmation messages at each step (✓)
- Bilingual support (English/Swahili)
- Multiple spare parts in one session
- Order review before confirmation
- Order numbers provided after creation
- Direct link to check order status

## Order Status Check

After order creation, users can:
1. Visit: `[WEBSITE_URL]/spare-parts/orders` (requires login)
2. Search by order number
3. Receive email confirmation with order details
4. Check status anytime through the website

## Technical Details

- **OTP Expiration**: 10 minutes
- **OTP Delivery**: Asynchronous via Job queue
- **Order Status**: Stored in database, accessible via web interface
- **Email Confirmation**: Sent for each order created
- **Session Management**: Conversation context stored in database

