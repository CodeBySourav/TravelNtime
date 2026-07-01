<header class="header" style="background: linear-gradient(to bottom, #e4eff8 0%, #fef6f6 100%);">
  <div class="top-bar">
    <div class="logo">
      <img src="{{ asset('frontend/assets/img/Trave-N-Time-Logo.png') }}" alt="Travel N Time">
    </div>

    <!-- Toggle Button for Mobile -->
    <button class="menu-toggle" id="menuToggle">&#9776;</button>
    <nav class="navbar" id="navbarMenu">
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('about-us') }}">About</a></li>
        <li><a href="{{ route('front.tourbooking.services') }}">Tours</a></li>
        <!--<li><a href="#">Visa</a></li>-->
        <li><a href="#">News</a></li>
        <li><a href="{{ route('contact-us') }}">Contact</a></li>
      </ul>
    </nav>

    <div class="whatsapp-btn">
      <a href="#"><button><i class="fab fa-whatsapp"></i> WhatsApp</button></a>
    </div>
  </div>
</header>
