<div class="mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Lubricant Shop Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Quick overview for your lubricant shop account.</p>
    <p class="mt-1 text-xs text-gray-500">Use My Shop Profile to update shop name and location details.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <p class="text-sm font-medium text-gray-600">Account Type</p>
        <p class="mt-2 text-xl font-bold text-gray-900">Lubricant Shop Agent</p>
        <p class="mt-2 text-sm text-gray-500">Focused workspace with profile management only.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">Profile Management</p>
            <p class="mt-2 text-sm text-gray-700">Update shop name, company details, address, and location coordinates.</p>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.agent.profile') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Open My Shop Profile
            </a>
        </div>
    </div>
</div>
