@auth
    <div class="hidden md:flex items-center whitespace-nowrap ms-4">
        <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            Welcome ! {{ trim(auth()->user()->front_title . ' ' . auth()->user()->name . ' ' . auth()->user()->back_title) }}
        </span>
    </div>
@endauth
