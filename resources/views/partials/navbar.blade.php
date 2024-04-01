<nav class="bg-white border-gray-200 dark:bg-gray-900">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
        <img src="{{asset('images/logo_no_bg.png')}}" class="h-8" alt="Bazaar Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">{!!__('Bazaar')!!}</span>
    </a>
    <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
        <span class="sr-only">{!!__('Open main menu')!!}</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-default">
      <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="/" class="block py-2 px-3 text-{{ Request::is('/') ? 'blue' : 'black' }}-700 rounded md:bg-transparent md:p-0">{!!__('Home')!!}</a>
        </li>
        @if (auth()->check() && (auth()->user()->user_type === 'zakelijke_verkoper' || auth()->user()->user_type === 'particuliere_verkoper'))
        <li>
          <a href="/listings" class="block py-2 px-3 text-{{ Request::is('listings') ? 'blue' : 'black' }}-700 rounded md:bg-transparent md:p-0">{!!__('My listings')!!}</a>
        </li>
        @endif
        @if (auth()->check() && auth()->user()->user_type === 'admin')
        <li>
          <a href="/companies" class="block py-2 px-3 text-{{ Request::is('companies') ? 'blue' : 'black' }}-700 rounded md:bg-transparent md:p-0">{!!__('Companies')!!}</a>
        </li>
        @endif
        @if (auth()->check())
        <li>
          <a href="/agenda" class="block py-2 px-3 text-{{ Request::is('agenda') ? 'blue' : 'black' }}-700 rounded md:bg-transparent md:p-0">{!!__('Calendar')!!}</a>
        </li>
        @endif
        <li>
          <a href="/account" class="block py-2 px-3 text-{{ Request::is('account') ? 'blue' : 'black' }}-700 rounded md:bg-transparent md:p-0">{!!__('My account')!!}</a>
        </li>
        @if (auth()->check() && (auth()->user()->user_type === 'gebruiker'))
        <li>
          <a href="/cart" class="block py-2 px-3 text-{{ Request::is('cart') ? 'blue' : 'black' }}-700 rounded md:bg-transparent md:p-0"><i class="fas fa-shopping-cart text-xl"></i></a>
        </li>
        @endif
        <li>
        <div class="relative inline-block text-left">
          <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="language-menu" aria-expanded="false" aria-haspopup="true">
              @php
                  $locale = App::getLocale();
                  $language = '';
                  switch ($locale) {
                      case 'en':
                          $language = 'English';
                          break;
                      case 'nl':
                          $language = 'Dutch';
                          break;
                  }
              @endphp
              {!!__($language)!!}
              <span class="font-bold ml-2">⌵</span>
          </button>

          <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" style="z-index: 1;" role="menu" aria-orientation="vertical" aria-labelledby="language-menu" id="language-dropdown">
              <form action="{{ route('setLocale') }}" method="POST">
                  @csrf
                  <div class="py-1" role="none">
                      <button type="submit" name="locale" value="en" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none w-full text-left">English</button>
                      <button type="submit" name="locale" value="nl" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none w-full text-left">Nederlands</button>
                  </div>
              </form>
          </div>   
        </div>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var languageMenu = document.getElementById("language-menu");
                var languageDropdown = document.getElementById("language-dropdown");
        
                languageMenu.addEventListener("click", function() {
                    var expanded = this.getAttribute("aria-expanded") === "true" || false;
                    this.setAttribute("aria-expanded", !expanded);
                    languageDropdown.classList.toggle("hidden");
                });
        
                // Close the dropdown when clicking outside of it
                document.addEventListener("click", function(event) {
                    if (!languageMenu.contains(event.target)) {
                        languageMenu.setAttribute("aria-expanded", "false");
                        languageDropdown.classList.add("hidden");
                    }
                });
            });
        </script>
        
        </li>
      </ul>
    </div>
  </div>
</nav>