<?php

namespace App\Livewire\Customer;

use App\Models\SparePartOrder;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SparePartOrderDetail extends Component
{
    public $order;
    public $orderId;
    public $messages = [];
    public $newMessage = '';

    public function mount($id)
    {
        $this->orderId = $id;
        $this->loadOrder();
        $this->loadMessages();
    }

    public function loadOrder()
    {
        $this->order = SparePartOrder::with(['vehicleMake', 'vehicleModel', 'user', 'assignedTo'])
            ->where('id', $this->orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    public function loadMessages()
    {
        // Load messages - using array cast from model
        $this->messages = $this->order->chat_messages ?? [];
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000',
        ]);

        $message = [
            'id' => uniqid(),
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'message' => $this->newMessage,
            'created_at' => now()->toDateTimeString(),
        ];

        $currentMessages = $this->order->chat_messages ?? [];
        $currentMessages[] = $message;

        $this->order->update([
            'chat_messages' => $currentMessages
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('message-sent');
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'quoted' => 'bg-purple-100 text-purple-800',
            'accepted' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function render()
    {
        return view('livewire.customer.spare-part-order-detail');
    }
}

