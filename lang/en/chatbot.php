<?php

return [
    'welcome' => '👋 Welcome to Kibo Auto! 🚗',
    'select_language' => 'Please select your preferred language:',
    'reply_with_number' => 'Please reply with the number of your choice.',

    'submenu_back_hint' => 'Reply *99* or *menu* for the main menu.',
    'invalid_selection' => '❌ Invalid selection. Please try again.',
    'wrong_language_selection' => 'Wrong selection. Please choose 1 for English or 2 for Swahili.',
    'start_over' => 'Start over',

    'main_menu_title' => '🏠 *Main Menu* - What can we help you with today?',

    'main_menu' => [
        'kibo_services' => '🏢 Kibo services (cars, trucks, leasing & more)',
    ],

    'main_menu_zero_hint' => '*0* here = start over (pick language again). From other screens, reply *99* to come back to this menu.',

    'service_help' => '💡 *Need more help?* Reply *99* or "back" / "menu" to return to the main menu, "reset" or "start" to begin again.',
    'termination_keywords' => 'You can reset the session anytime by typing: reset, restart, new, start over, or menu',
    'visit_website' => '🌐 Visit our website',

    'service' => [
        'cars' => '🚗 Cars',
        'cars_description' => 'Browse and search through thousands of quality cars. Find new or used vehicles from trusted dealers.',

        'trucks' => '🚛 Trucks',
        'trucks_description' => 'Find the perfect truck for your business needs. Browse commercial vehicles and heavy-duty trucks.',

        'spare_parts' => '🔧 Spare Parts',
        'spare_parts_description' => 'Find genuine spare parts for your vehicle. Search by make, model, or part category.',

        'garage' => '🛠️ Garage Services',
        'garage_description' => 'Book professional garage services near you. Find trusted mechanics and service centers.',

        'tracking' => '📍 Track spare part order',
        'tracking_description' => 'Check the status of your spare part order with your order number or tracking link.',

        'leasing' => '📋 Vehicle Leasing',
        'leasing_description' => 'Lease a vehicle with flexible terms. No hidden fees, transparent pricing.',

        'financing' => '💰 Vehicle Financing',
        'financing_description' => 'Get financing for your vehicle purchase. Flexible payment plans available.',

        'valuation' => '📊 Vehicle Valuation',
        'valuation_description' => 'Get an accurate valuation for your vehicle. Professional assessment service.',

        'sell' => '💵 Sell Your Vehicle',
        'sell_description' => 'List your vehicle for sale. Reach thousands of potential buyers on Kibo Auto.',

        'faq' => '❓ FAQ & contact',
        'faq_description' => 'Common questions about Kibo Auto and how to reach us.',
    ],

    'kibo_submenu' => [
        'title' => '🏢 *Kibo services* — choose a topic',
        'back' => '← Main menu',
    ],

    'tracking' => [
        'prompt' => 'Enter your *order number* (e.g. SPO-20260404-9E2D2A), paste the *tracking link* from SMS, or the *40-character code*.'."\n\n".'Reply *99* or *menu* to return to the main menu.',
        'prompt_again' => 'Send another number to track, or reply *99* / *menu* to return to the main menu.',
        'not_found' => 'We could not find an order with that reference. Check the format and try again.',
        'summary_title' => 'Order status',
        'label_order' => 'Order:',
        'label_status' => 'Status:',
        'label_part' => 'Part:',
        'label_vehicle' => 'Vehicle:',
        'label_delivery' => 'Delivery:',
        'label_quote' => 'Quoted price:',
        'label_estimated_delivery' => 'Est. delivery:',
        'label_shipment_tracking' => 'Shipment tracking:',
        'label_view_online' => 'Full details:',
    ],

    'order_status' => [
        'pending' => 'Pending review',
        'processing' => 'Processing',
        'quoted' => 'Quoted — awaiting your response',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        'awaiting_payment' => 'Awaiting payment',
        'payment_submitted' => 'Payment submitted',
        'payment_verified' => 'Payment verified',
        'preparing' => 'Preparing your order',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'faq' => [
        'full' => "❓ *Frequently asked questions — Kibo Auto*\n\n"
            ."*What is Kibo Auto?*\n"
            ."We are a Tanzania vehicle marketplace: cars and trucks, spare parts, garage services, financing, import (Agiza), car exchange, leasing, insurance links, and more — all in one place.\n\n"
            ."*How do I buy a vehicle?*\n"
            ."Browse new or used listings on our website, compare options, and contact the seller or Kibo Auto for support.\n\n"
            ."*How do I sell or value my car?*\n"
            ."Use “Sell your car” and valuation tools on the website, or ask us here and we will guide you.\n\n"
            ."*Spare parts orders*\n"
            ."You can order via the Spare Parts section on the site or through this WhatsApp chat. We verify your phone by SMS when your contact number is different from your WhatsApp number.\n\n"
            ."*Financing and import*\n"
            ."See Import Financing and Agiza/Import on :url for loans and import assistance.\n\n"
            ."*Garage and servicing*\n"
            ."Browse garage services on the website to find trusted workshops.\n\n"
            ."*Complaints or tracking*\n"
            ."Use Complaints and Track order on the website if you need help with an existing order.\n\n"
            ."*Contact Kibo Auto*\n"
            ."📧 :email\n"
            ."📞 :phone\n"
            ."📍 :location\n"
            .'🌐 :url',
    ],

    'cars' => [
        'menu_title' => 'What would you like to do?',
        'search' => 'Search Cars',
        'browse_new' => 'Browse New Cars',
        'browse_used' => 'Browse Used Cars',
        'sell_car' => 'Sell Your Car',
        'value_car' => 'Value Your Car',
        'insurance' => 'Car Insurance',
        'search_prompt' => 'You can search for cars on our website. Visit the search page to find your perfect car.',
        'browse_new_prompt' => 'Browse our collection of new cars. Visit our website to see the latest models.',
        'browse_used_prompt' => 'Browse our collection of used cars. Find great deals on quality vehicles.',
    ],
];
