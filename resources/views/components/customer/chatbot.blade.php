<!-- Chatbot Widget -->
<div id="chatbot-widget" class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50">
    <!-- Chat Window -->
    <div id="chatbot-window" class="hidden bg-white rounded-2xl shadow-2xl w-[calc(100vw-2rem)] md:w-96 h-[600px] max-h-[calc(100vh-8rem)] flex flex-col border border-gray-200 mb-4 transition-all duration-300 ease-in-out">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-4 rounded-t-2xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-lg">Kibo Auto Support</h3>
                    <p class="text-xs text-white/80">We're here to help</p>
                </div>
            </div>
            <button onclick="toggleChatbot()" class="text-white hover:text-gray-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Chat Messages Area -->
        <div id="chatbot-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            <!-- Welcome Message -->
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-200">
                        <p class="text-sm text-gray-800">Hello! 👋 Welcome to Kibo Auto. How can I assist you today?</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-1">Just now</p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap gap-2 ml-10">
                <button onclick="sendQuickMessage('Find a vehicle')" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-full hover:bg-green-700 transition-colors">
                    Find a vehicle
                </button>
                <button onclick="sendQuickMessage('Sell my vehicle')" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-full hover:bg-green-700 transition-colors">
                    Sell my vehicle
                </button>
                <button onclick="sendQuickMessage('Spare parts')" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-full hover:bg-green-700 transition-colors">
                    Spare parts
                </button>
                <button onclick="sendQuickMessage('Garage services')" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-full hover:bg-green-700 transition-colors">
                    Garage services
                </button>
            </div>
        </div>

        <!-- Chat Input Area -->
        <div class="border-t border-gray-200 p-4 bg-white rounded-b-2xl">
            <form id="chatbot-form" onsubmit="sendMessage(event)" class="flex items-center gap-2">
                <input 
                    type="text" 
                    id="chatbot-input" 
                    placeholder="Type your message..." 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                    autocomplete="off"
                >
                <button 
                    type="submit" 
                    class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center hover:bg-green-700 transition-colors flex-shrink-0"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            <p class="text-xs text-gray-500 text-center mt-2">Powered by AI • Typically replies within seconds</p>
        </div>
    </div>

    <!-- Chatbot Toggle Button -->
    <button 
        id="chatbot-toggle" 
        onclick="toggleChatbot()" 
        class="w-16 h-16 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 flex items-center justify-center group"
    >
        <svg id="chatbot-icon" class="w-7 h-7 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg id="chatbot-close-icon" class="w-7 h-7 hidden transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <!-- Notification Badge -->
        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
        </span>
    </button>
</div>

<script>
    function toggleChatbot() {
        const window = document.getElementById('chatbot-window');
        const toggle = document.getElementById('chatbot-toggle');
        const icon = document.getElementById('chatbot-icon');
        const closeIcon = document.getElementById('chatbot-close-icon');
        
        if (window.classList.contains('hidden')) {
            window.classList.remove('hidden');
            icon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            // Focus on input when opening
            setTimeout(() => {
                document.getElementById('chatbot-input').focus();
            }, 100);
        } else {
            window.classList.add('hidden');
            icon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    }

    // Store conversation history
    let conversationHistory = [];

    function sendMessage(event) {
        event.preventDefault();
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();
        
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        conversationHistory.push({ role: 'user', content: message });
        input.value = '';
        
        // Show loading indicator
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'chatbot-loading';
        loadingDiv.className = 'flex items-start gap-2';
        loadingDiv.innerHTML = `
            <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
                    <div class="flex gap-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        `;
        const messagesContainer = document.getElementById('chatbot-messages');
        messagesContainer.appendChild(loadingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Call OpenAI API
        fetch('{{ route("chatbot.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                message: message,
                conversation_history: conversationHistory.slice(-10) // Last 10 messages for context
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading indicator
            const loading = document.getElementById('chatbot-loading');
            if (loading) loading.remove();
            
            if (data.error) {
                addMessage('I apologize, but I\'m having trouble connecting right now. Please try again in a moment.', 'bot');
            } else {
                addMessage(data.response, 'bot');
                conversationHistory.push({ role: 'bot', content: data.response });
            }
        })
        .catch(error => {
            console.error('Chatbot error:', error);
            const loading = document.getElementById('chatbot-loading');
            if (loading) loading.remove();
            addMessage('I apologize, but I\'m having trouble connecting right now. Please try again in a moment.', 'bot');
        });
    }

    function sendQuickMessage(message) {
        const input = document.getElementById('chatbot-input');
        input.value = message;
        sendMessage(new Event('submit'));
    }

    function addMessage(text, sender) {
        const messagesContainer = document.getElementById('chatbot-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex items-start gap-2 ${sender === 'user' ? 'flex-row-reverse' : ''}`;
        
        const avatar = sender === 'user' 
            ? '<div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div>'
            : '<div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg></div>';
        
        const bgColor = sender === 'user' ? 'bg-green-600 text-white' : 'bg-white border border-gray-200';
        const textColor = sender === 'user' ? 'text-white' : 'text-gray-800';
        
        messageDiv.innerHTML = `
            ${avatar}
            <div class="flex-1 ${sender === 'user' ? 'flex flex-col items-end' : ''}">
                <div class="${bgColor} rounded-lg p-3 shadow-sm max-w-[80%] ${sender === 'user' ? 'ml-auto' : ''}">
                    <p class="text-sm ${textColor}">${escapeHtml(text)}</p>
                </div>
                <p class="text-xs text-gray-500 mt-1 ${sender === 'user' ? 'mr-1' : 'ml-1'}">Just now</p>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function getBotResponse(message) {
        const lowerMessage = message.toLowerCase();
        
        if (lowerMessage.includes('vehicle') || lowerMessage.includes('car') || lowerMessage.includes('truck')) {
            return "Great! I can help you find the perfect vehicle. You can browse our extensive inventory of cars and trucks. Would you like to search by make, model, price range, or location?";
        } else if (lowerMessage.includes('sell')) {
            return "Selling your vehicle with Kibo Auto is easy! We have millions of visitors each month. You can create a listing with photos and details. Would you like me to guide you through the process?";
        } else if (lowerMessage.includes('spare part')) {
            return "I can help you find spare parts! You can browse our spare parts directory or use our sourcing service where we'll help you find specific parts. What part are you looking for?";
        } else if (lowerMessage.includes('garage') || lowerMessage.includes('service')) {
            return "We have a network of trusted garages! You can search by location or vehicle make. Our garages offer various services including repairs, maintenance, and more. What service do you need?";
        } else if (lowerMessage.includes('price') || lowerMessage.includes('cost')) {
            return "Pricing varies depending on the vehicle, condition, and location. I'd recommend browsing our listings or using our search filters to see current prices. Is there a specific vehicle you're interested in?";
        } else if (lowerMessage.includes('contact') || lowerMessage.includes('phone') || lowerMessage.includes('email')) {
            return "You can reach our support team through this chat, or visit our Contact Us page for more options. We're here to help! Is there something specific you'd like assistance with?";
        } else {
            return "Thank you for your message! I'm here to help with finding vehicles, selling your car, spare parts, garage services, and more. How can I assist you today?";
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close chatbot when clicking outside
    document.addEventListener('click', function(event) {
        const widget = document.getElementById('chatbot-widget');
        const window = document.getElementById('chatbot-window');
        const toggle = document.getElementById('chatbot-toggle');
        
        if (!widget.contains(event.target) && !window.classList.contains('hidden')) {
            toggleChatbot();
        }
    });
</script>

<style>
    #chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    #chatbot-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    #chatbot-messages::-webkit-scrollbar-thumb {
        background: #10b981;
        border-radius: 10px;
    }
    
    #chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: #059669;
    }
</style>

