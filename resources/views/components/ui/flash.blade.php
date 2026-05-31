@if (session('status'))
    @php
        $messages = [
            'client-created' => 'Client created successfully.',
            'client-updated' => 'Client updated successfully.',
            'client-deleted' => 'Client deleted successfully.',
            'job-created' => 'Job created successfully.',
            'job-updated' => 'Job updated successfully.',
            'job-deleted' => 'Job deleted successfully.',
            'quote-created' => 'Quote created successfully.',
            'quote-updated' => 'Quote updated successfully.',
            'quote-deleted' => 'Quote deleted successfully.',
            'invoice-created' => 'Invoice created successfully.',
            'invoice-updated' => 'Invoice updated successfully.',
            'invoice-deleted' => 'Invoice deleted successfully.',
            'profile-updated' => 'Profile updated successfully.',
            'business-profile-updated' => 'Business profile saved successfully.',
        ];
        $message = $messages[session('status')] ?? session('status');
    @endphp
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200/80 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
        <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ __($message) }}
    </div>
@endif
