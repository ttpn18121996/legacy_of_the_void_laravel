<header class="px-4">
    <div class="toggle-sidebar">
        <button type="button" class="btn-toggle-sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
    </div>

    <div class="user-info">
        <form action="{{  route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-sm btn-primary">Logout</button>
        </form>
    </div>
</header>
