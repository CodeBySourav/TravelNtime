    <!-- offCanvas-menu -->
    <div class="offCanvas__info">
        <div class="offCanvas__close-icon menu-close">
            <button><i class="fa-sharp fa-regular fa-xmark"></i></button>
        </div>
        <div class="offCanvas__logo mb-30">
            <a href="{{ route('home') }}"><img src="{{ asset($general_setting?->secondary_logo) }}"
                    alt="Logo"></a>
        </div>
        <div class="offCanvas__side-info mb-30">

    <!-- Office Address -->
    <div class="contact-list mb-30">
        <h4>Office Address</h4>
        <p>{{ $footer->address }}</p>
    </div>

    <!-- Main Contact Number -->
    <div class="contact-list mb-30">
        <h4>Contact Us</h4>
        <p>{{ $footer->phone }}</p>
    </div>

    <!-- Department Numbers -->
    <div class="contact-list mb-30">
        <h4>Department Contacts</h4>
        <p><strong>Ticketing:</strong> 8448993285</p>
        <p><strong>Hotels:</strong> 9711208099</p>
        <p><strong>Packages:</strong> 8448993284</p>
        <p><strong>Accounts:</strong> 8920132709</p>
        <p><strong>Groups:</strong> 9891108099</p>
        <p><strong>Visas:</strong> 9911993438</p>
    </div>

    <!-- Email -->
    <div class="contact-list mb-30">
        <h4>Email Address</h4>
        <p>{{ $footer->email }}</p>
    </div>

</div>
        <div class="offCanvas__social-icon mt-30">
            @if ($footer->facebook)
                <a href="{{ $footer->facebook }}"><i class="fab fa-facebook-f"></i></a>
            @endif
            @if ($footer->twitter)
                <a href="{{ $footer->twitter }}"><i class="fab fa-twitter"></i></a>
            @endif
            @if ($footer->instagram)
                <a href="{{ $footer->instagram }}"><i class="fab fa-instagram"></i></a>
            @endif
            @if ($footer->linkedin)
                <a href="{{ $footer->linkedin }}"><i class="fab fa-linkedin-in"></i></a>
            @endif
            @if ($footer->youtube)
                <a href="{{ $footer->youtube }}"><i class="fab fa-youtube"></i></a>
            @endif
        </div>
    </div>
    <div class="offCanvas__overly"></div>
    <!-- offCanvas-menu-end -->
