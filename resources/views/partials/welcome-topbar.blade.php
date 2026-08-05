@auth
    <div class="hidden md:flex items-center mx-auto whitespace-nowrap">
        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200" style="padding-left: 2rem;">
            Welcome ! {{ trim(auth()->user()->front_title . ' ' . auth()->user()->name . ' ' . auth()->user()->back_title) }}
        </span>
    </div>
@endauth
