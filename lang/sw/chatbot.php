<?php

return [
    'welcome' => '👋 Karibu Kibo Auto! 🚗',
    'select_language' => 'Tafadhali chagua lugha unayopendelea:',
    'reply_with_number' => 'Tafadhali jibu kwa nambari ya chaguo lako.',

    'submenu_back_hint' => 'Jibu *99* au *menu* kwa menyu kuu.',
    'invalid_selection' => '❌ Chaguo si sahihi. Tafadhali jaribu tena.',
    'wrong_language_selection' => 'Chaguo si sahihi. Tafadhali chagua 1 kwa Kiingereza au 2 kwa Kiswahili.',
    'start_over' => 'Anza upya',

    'main_menu_title' => '🏠 *Menyu Kuu* - Tunaweza kukusaidia nini leo?',

    'main_menu' => [
        'kibo_services' => '🏢 Huduma za Kibo (magari, malori, ukodishaji na zingine)',
    ],

    'main_menu_zero_hint' => '*0* hapa = anza upya (chagua lugha tena). Kwenye skrini nyingine, jibu *99* kurudi menyu hii.',

    'service_help' => '💡 *Unahitaji msaada zaidi?* Jibu *99* au "rudi" / "menu" kurudi menyu kuu, "anza" au "anza upya" kuanza tena.',
    'termination_keywords' => 'Unaweza kuanza upya wakati wowote kwa kuandika: anza, anza upya, rudi, au menyu',
    'visit_website' => '🌐 Tembelea tovuti yetu',

    'service' => [
        'cars' => '🚗 Magari',
        'cars_description' => 'Vinjari na utafute maelfu ya magari bora. Pata magari mapya au yaliyotumika kutoka kwa wauzaji wa kuaminika.',

        'trucks' => '🚛 Malori',
        'trucks_description' => 'Pata lori kamili kwa mahitaji yako ya biashara. Vinjari magari ya kibiashara na malori makubwa.',

        'spare_parts' => '🔧 Sehemu Za Spare',
        'spare_parts_description' => 'Pata sehemu za spare za kweli kwa gari lako. Tafuta kwa aina, modeli, au jamii ya sehemu.',

        'garage' => '🛠️ Huduma Za Karakana',
        'garage_description' => 'Hudumia huduma za karakana za kitaalamu karibu nawe. Pata fundi wa kuaminika na vituo vya huduma.',

        'tracking' => '📍 Fuatilia agizo la spare',
        'tracking_description' => 'Angalia hali ya agizo lako la sehemu za spare kwa nambari ya agizo au kiungo cha ufuatiliaji.',

        'leasing' => '📋 Kukodisha Gari',
        'leasing_description' => 'Kodisha gari kwa masharti rahisi. Hakuna ada za siri, bei wazi.',

        'financing' => '💰 Ufadhili Wa Gari',
        'financing_description' => 'Pata ufadhili kwa ununuzi wako wa gari. Mipango ya malipo rahisi inapatikana.',

        'valuation' => '📊 Kuthamini Gari',
        'valuation_description' => 'Pata uthamini sahihi wa gari lako. Huduma ya tathmini ya kitaalamu.',

        'sell' => '💵 Uza Gari Lako',
        'sell_description' => 'Orodhesha gari lako kwa kuuza. Fikia maelfu ya wanunuzi wanaoweza kununua kwenye Kibo Auto.',

        'faq' => '❓ Maswali & mawasiliano',
        'faq_description' => 'Maswali yanayoulizwa mara kwa mara kuhusu Kibo Auto na jinsi ya kuwasiliana nasi.',
    ],

    'kibo_submenu' => [
        'title' => '🏢 *Huduma za Kibo* — chagua mada',
        'back' => '← Menyu kuu',
    ],

    'tracking' => [
        'prompt' => 'Ingiza *nambari ya agizo* (mfano SPO-20260404-9E2D2A), bandika *kiungo cha ufuatiliaji* kutoka SMS, au *msimbo wa herufi 40*.'."\n\n".'Jibu *99* au *menu* kurudi menyu kuu.',
        'prompt_again' => 'Tuma nambari nyingine kufuatilia, au jibu *99* / *menu* kurudi menyu kuu.',
        'not_found' => 'Hatukuipata agizo kwa kumbukumbu hiyo. Angalia muundo na jaribu tena.',
        'summary_title' => 'Hali ya agizo',
        'label_order' => 'Agizo:',
        'label_status' => 'Hali:',
        'label_part' => 'Sehemu:',
        'label_vehicle' => 'Gari:',
        'label_delivery' => 'Uwasilishaji:',
        'label_quote' => 'Bei iliyotajwa:',
        'label_estimated_delivery' => 'Makadirio ya uwasilishaji:',
        'label_shipment_tracking' => 'Nambari ya usafirishaji:',
        'label_view_online' => 'Maelezo kamili:',
    ],

    'order_status' => [
        'pending' => 'Inasubiri uhakiki',
        'processing' => 'Inachakatwa',
        'quoted' => 'Bei imetolewa — inasubiri jibu lako',
        'accepted' => 'Imekubaliwa',
        'rejected' => 'Imekataliwa',
        'awaiting_payment' => 'Inasubiri malipo',
        'payment_submitted' => 'Uthibitisho wa malipo umewasilishwa',
        'payment_verified' => 'Malipo yamethibitishwa',
        'preparing' => 'Inaandaliwa',
        'shipped' => 'Imetumwa',
        'delivered' => 'Imewasilishwa',
        'completed' => 'Imekamilika',
        'cancelled' => 'Imeghairiwa',
    ],

    'faq' => [
        'full' => "❓ *Maswali yanayoulizwa mara kwa mara — Kibo Auto*\n\n"
            ."*Kibo Auto ni nini?*\n"
            ."Soko la magari Tanzania: magari na malori, sehemu za spare, huduma za karakana, ufadhili, Agiza/Import, ubadilishaji wa gari, ukodishaji, na mengine — yote mahali pamoja.\n\n"
            ."*Ninanunuaje gari?*\n"
            ."Vinjari magari mapya au yaliyotumika kwenye tovuti, linganisha, kisha wasiliana na muuzaji au Kibo Auto kwa msaada.\n\n"
            ."*Ninauzaje au ninathamini gari langu?*\n"
            ."Tumia ukurasa wa “Uza gari” na uthamini kwenye tovuti, au tuulize hapa tutakuongoza.\n\n"
            ."*Kuagiza sehemu za spare*\n"
            ."Unaweza kuagiza kwenye tovuti au kupitia WhatsApp hii. Tunathibitisha nambari yako kwa SMS ikiwa nambari ya mawasiliano ni tofauti na WhatsApp yako.\n\n"
            ."*Ufadhili na uagizaji wa nje*\n"
            ."Angalia Ufadhili wa Import na Agiza/Import kwenye :url.\n\n"
            ."*Karakana na huduma*\n"
            ."Vinjari huduma za karakana kwenye tovuti kupata vituo vinavyoaminika.\n\n"
            ."*Malalamiko au kufuatilia agizo*\n"
            ."Tumia Malalamiko na Fuatilia agizo kwenye tovuti ikiwa unahitaji msaada kwa agizo lililopo.\n\n"
            ."*Mawasiliano na Kibo Auto*\n"
            ."📧 :email\n"
            ."📞 :phone\n"
            ."📍 :location\n"
            .'🌐 :url',
    ],

    'cars' => [
        'menu_title' => 'Ungependa kufanya nini?',
        'search' => 'Tafuta Magari',
        'browse_new' => 'Vinjari Magari Mapya',
        'browse_used' => 'Vinjari Magari Yaliyotumika',
        'sell_car' => 'Uza Gari Lako',
        'value_car' => 'Thamini Gari Lako',
        'insurance' => 'Bima Ya Gari',
        'search_prompt' => 'Unaweza kutafuta magari kwenye tovuti yetu. Tembelea ukurasa wa utafutaji kupata gari lako kamili.',
        'browse_new_prompt' => 'Vinjari mkusanyiko wetu wa magari mapya. Tembelea tovuti yetu kuona mifano ya hivi karibuni.',
        'browse_used_prompt' => 'Vinjari mkusanyiko wetu wa magari yaliyotumika. Pata makubaliano mazuri kwenye magari bora.',
    ],
];
