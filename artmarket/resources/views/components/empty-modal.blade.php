<div x-data="{ open: false }" @keydown.window.escape="open = false"
     x-show="open" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" x-ref="dialog"
     style="display: none;"
     aria-modal="true"
        {{$attributes->merge()}}>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div x-show="open" x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-description="Background overlay, show/hide based on modal state."
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"
             aria-hidden="true"></div>


        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

        <div x-show="open" x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-description="Modal panel, show/hide based on modal state."
             class="inline-block align-bottom bg-white rounded-lg {{$attributes->has('no-padding') ? '' : 'px-4 pt-5 pb-4 sm:my-8 sm:p-6'}} text-left overflow-hidden shadow-xl transform transition-all sm:align-middle sm:max-w-lg sm:w-full">
            <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                <button type="button"
                        class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        @click="open = false">
                    <span class="sr-only">@lang('app.buttons.close')</span>
                    <svg class="h-6 w-6" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            {{$slot}}
            {{$footer ?? ''}}
        </div>

    </div>
</div>
