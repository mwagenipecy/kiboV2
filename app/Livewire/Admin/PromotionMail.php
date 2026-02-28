<?php

namespace App\Livewire\Admin;

use App\Jobs\SendPromotionEmailJob;
use App\Models\Agent;
use App\Models\Cfc;
use App\Models\EmailLog;
use App\Models\PromotionCampaign;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PromotionMail extends Component
{
    /** 'list' | 'view' | 'create' */
    public string $screen = 'list';

    public ?int $viewCampaignId = null;

    public array $recipientGroups = [];

    public string $subject = '';

    public string $message = '';

    /** Optional image URLs to include in the email body */
    public array $imageUrls = [''];

    public bool $sending = false;

    public function mount(): void
    {
        if (auth()->user()?->role !== 'admin') {
            abort(403, 'Only administrators can access Promotion.');
        }
    }

    public static array $groupLabels = [
        'spare_part_agents' => 'Spare Part Agents',
        'cfc' => 'CFC',
        'garage_owner' => 'Garage Owner',
        'lubricant_shop' => 'Lubricant Shop',
        'admins' => 'Admins',
        'dealers' => 'Dealers',
        'lenders' => 'Lenders',
        'customers' => 'Customers',
    ];

    public static array $groupDescriptions = [
        'spare_part_agents' => 'Agents who supply spare parts',
        'cfc' => 'CFC partners',
        'garage_owner' => 'Garage and workshop owners',
        'lubricant_shop' => 'Lubricant and oil shop owners',
        'admins' => 'System administrators',
        'dealers' => 'Vehicle dealers',
        'lenders' => 'Financing and lending partners',
        'customers' => 'Registered customers',
    ];

    public static string $sampleMessage = "We have exciting news to share with you!\n\nNew features and offers are now available on Kibo. Log in to explore the latest vehicles, spare parts, and services.\n\nThank you for being part of our community.\n\n— The Kibo Team";

    public function showList(): void
    {
        $this->screen = 'list';
        $this->viewCampaignId = null;
    }

    public function showCreate(): void
    {
        $this->screen = 'create';
        $this->viewCampaignId = null;
        $this->recipientGroups = [];
        $this->subject = '';
        $this->message = '';
        $this->imageUrls = [''];
    }

    public function viewCampaign(int $id): void
    {
        $this->screen = 'view';
        $this->viewCampaignId = $id;
    }

    public function addImageUrl(): void
    {
        $this->imageUrls[] = '';
    }

    public function removeImageUrl(int $index): void
    {
        if (count($this->imageUrls) > 1) {
            array_splice($this->imageUrls, $index, 1);
        }
    }

    public function getRecipientsByGroup(): array
    {
        $recipients = [];
        $seen = [];

        foreach ($this->recipientGroups as $group) {
            $list = match ($group) {
                'spare_part_agents' => Agent::where('agent_type', 'spare_part')
                    ->where('status', 'active')
                    ->get()
                    ->map(fn ($a) => ['email' => $a->email, 'name' => $a->name ?? 'Spare Part Agent', 'type' => 'spare_part_agent']),
                'cfc' => Cfc::all()
                    ->map(fn ($c) => ['email' => $c->email, 'name' => $c->name ?? 'CFC', 'type' => 'cfc']),
                'garage_owner' => Agent::where('agent_type', 'garage_owner')
                    ->where('status', 'active')
                    ->get()
                    ->map(fn ($a) => ['email' => $a->email, 'name' => $a->name ?? 'Garage Owner', 'type' => 'garage_owner']),
                'lubricant_shop' => Agent::where('agent_type', 'lubricant_shop')
                    ->where('status', 'active')
                    ->get()
                    ->map(fn ($a) => ['email' => $a->email, 'name' => $a->name ?? 'Lubricant Shop', 'type' => 'lubricant_shop']),
                'admins' => User::where('role', 'admin')->get()
                    ->map(fn ($u) => ['email' => $u->email, 'name' => $u->name ?? 'Admin', 'type' => 'admin']),
                'dealers' => User::where('role', 'dealer')->get()
                    ->map(fn ($u) => ['email' => $u->email, 'name' => $u->name ?? 'Dealer', 'type' => 'dealer']),
                'lenders' => User::where('role', 'lender')->get()
                    ->map(fn ($u) => ['email' => $u->email, 'name' => $u->name ?? 'Lender', 'type' => 'lender']),
                'customers' => User::where('role', 'customer')->get()
                    ->map(fn ($u) => ['email' => $u->email, 'name' => $u->name ?? 'Customer', 'type' => 'customer']),
                default => collect(),
            };

            foreach ($list as $r) {
                $email = strtolower(trim($r['email']));
                if ($email && empty($seen[$email])) {
                    $seen[$email] = true;
                    $recipients[] = $r;
                }
            }
        }

        return $recipients;
    }

    public static function messageToHtml(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '<p></p>';
        }
        $paragraphs = preg_split('/\n\s*\n/', $text);
        $html = [];
        foreach ($paragraphs as $p) {
            $p = trim($p);
            if ($p === '') {
                continue;
            }
            $html[] = '<p>' . nl2br(e($p)) . '</p>';
        }
        return implode("\n", $html) ?: '<p></p>';
    }

    /** Build full body HTML from message + image URLs */
    public static function buildBodyHtml(string $message, array $imageUrls): string
    {
        $html = self::messageToHtml($message);
        $validUrls = array_filter(array_map('trim', $imageUrls));
        foreach ($validUrls as $url) {
            if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
                $html .= '<p style="margin:1em 0;"><img src="' . e($url) . '" alt="Image" style="max-width:100%; height:auto; border-radius:8px;" /></p>';
            }
        }
        return $html;
    }

    public function useSampleMessage(): void
    {
        $this->message = self::$sampleMessage;
    }

    public function sendPromotion(): void
    {
        $rules = [
            'recipientGroups' => 'required|array|min:1',
            'recipientGroups.*' => 'in:' . implode(',', array_keys(self::$groupLabels)),
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ];
        $messages = [
            'recipientGroups.required' => 'Please select who should receive this email.',
            'subject.required' => 'Please enter an email subject.',
            'message.required' => 'Please write your message.',
        ];
        foreach ($this->imageUrls as $i => $url) {
            $u = trim($url);
            if ($u !== '') {
                $rules["imageUrls.{$i}"] = 'url';
                $messages["imageUrls.{$i}.url"] = 'Image URL ' . ($i + 1) . ' must be a valid URL.';
            }
        }
        $this->validate($rules, $messages);

        $recipients = $this->getRecipientsByGroup();
        if (empty($recipients)) {
            $this->addError('recipientGroups', 'No recipients found for the selected groups. Try choosing different groups.');
            return;
        }

        $bodyHtml = self::buildBodyHtml($this->message, $this->imageUrls);
        $userId = auth()->id();

        $campaign = PromotionCampaign::create([
            'subject' => $this->subject,
            'body_html' => $bodyHtml,
            'sent_by_user_id' => $userId,
        ]);

        $this->sending = true;
        foreach ($recipients as $r) {
            $log = EmailLog::create([
                'type' => 'promotion',
                'promotion_campaign_id' => $campaign->id,
                'recipient_email' => $r['email'],
                'recipient_name' => $r['name'],
                'recipient_type' => $r['type'],
                'subject' => $this->subject,
                'sent_by_user_id' => $userId,
                'status' => 'pending',
                'metadata' => [],
            ]);
            SendPromotionEmailJob::dispatch(
                $r['email'],
                $r['name'],
                $r['type'],
                $this->subject,
                $bodyHtml,
                $userId,
                $campaign->id,
                $log->id
            );
        }
        $this->sending = false;

        $this->showList();
        session()->flash('promotion_success', 'Done! ' . count($recipients) . ' email(s) are being sent. View this message below to see each recipient.');
    }

    public function getRecipientCounts(): array
    {
        $counts = [];
        foreach ($this->recipientGroups as $group) {
            $list = match ($group) {
                'spare_part_agents' => Agent::where('agent_type', 'spare_part')->where('status', 'active')->get(),
                'cfc' => Cfc::all(),
                'garage_owner' => Agent::where('agent_type', 'garage_owner')->where('status', 'active')->get(),
                'lubricant_shop' => Agent::where('agent_type', 'lubricant_shop')->where('status', 'active')->get(),
                'admins' => User::where('role', 'admin')->get(),
                'dealers' => User::where('role', 'dealer')->get(),
                'lenders' => User::where('role', 'lender')->get(),
                'customers' => User::where('role', 'customer')->get(),
                default => collect(),
            };
            $emails = $list->pluck('email')->map(fn ($e) => strtolower(trim($e)))->filter()->unique();
            $counts[$group] = $emails->count();
        }
        return $counts;
    }

    public function render()
    {
        $campaigns = PromotionCampaign::with('sentBy:id,name')
            ->withCount('emailLogs')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        $viewCampaign = null;
        if ($this->screen === 'view' && $this->viewCampaignId) {
            $viewCampaign = PromotionCampaign::with(['sentBy:id,name', 'emailLogs'])
                ->find($this->viewCampaignId);
        }

        $recipientCounts = $this->getRecipientCounts();
        $recipients = $this->getRecipientsByGroup();
        $totalRecipients = count($recipients);

        return view('livewire.admin.promotion-mail', [
            'campaigns' => $campaigns,
            'viewCampaign' => $viewCampaign,
            'groupLabels' => self::$groupLabels,
            'groupDescriptions' => self::$groupDescriptions,
            'recipientCounts' => $recipientCounts,
            'totalRecipients' => $totalRecipients,
        ]);
    }
}
