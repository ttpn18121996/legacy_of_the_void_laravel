<div class="user-info dropdown">
    <div class="user-info__trigger space-x-2 dropdown-trigger" data-target="#user-info__items">
        <p>{{ auth()->user()->name }}</p>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sm">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </div>
    <div class="user-info__items dropdown-content" id="user-info__items">
        <div class="user-info__item">
            <a href="#">Settings</a>
        </div>
        <div class="user-info__item">
            <form action="{{  route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="">Logout</button>
            </form>
        </div>
    </div>
</div>
