@if(session('success') || session('error'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3500)"
     x-show="show" x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     x-cloak
     class="fixed bottom-6 right-6 z-[100] max-w-sm">
    @if(session('success'))
        <div class="flex items-start gap-3 bg-emerald-600 text-white px-5 py-4 rounded-xl shadow-2xl">
            <i class="fa-solid fa-circle-check text-lg mt-0.5"></i>
            <p class="text-sm font-medium">{{ session('success') }}</p>
            <button @click="show = false" class="ml-auto text-white/70 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @elseif(session('error'))
        <div class="flex items-start gap-3 bg-rose-600 text-white px-5 py-4 rounded-xl shadow-2xl">
            <i class="fa-solid fa-circle-exclamation text-lg mt-0.5"></i>
            <p class="text-sm font-medium">{{ session('error') }}</p>
            <button @click="show = false" class="ml-auto text-white/70 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif
</div>
@endif