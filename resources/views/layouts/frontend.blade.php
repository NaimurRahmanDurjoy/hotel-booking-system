<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Grand Azure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-navy: #1E3A5F;
            --accent-gold: #F5A623;
        }
        body { font-family: 'Outfit', sans-serif; }
        
        /* Hide scrollbars but allow scrolling */
        * {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        *::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        .navbar { 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 20px 0;
            background: transparent !important;
        }
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(15px);
            padding: 12px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            font-size: 1.5rem;
            letter-spacing: -0.5px;
            transition: color 0.3s ease;
        }
        .navbar.scrolled .navbar-brand { color: var(--primary-navy) !important; }
        .nav-link {
            font-weight: 500;
            color: #fff !important;
            margin: 0 12px;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s ease;
            opacity: 0.9;
        }
        .navbar.scrolled .nav-link { color: #333 !important; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--accent-gold);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover { opacity: 1; transform: translateY(-1px); }
        .nav-link:hover::after { width: 20px; }
        
        .user-dropdown-btn {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            padding: 8px 20px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .navbar.scrolled .user-dropdown-btn {
            background: var(--primary-navy);
            color: #fff;
            border: none;
        }
        .user-dropdown-btn:hover { background: var(--accent-gold); border-color: var(--accent-gold); color: #fff; }
        
        /* Glassmorphism Dropdown */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 10px;
            margin-top: 15px !important;
            min-width: 220px;
            animation: dropdownFade 0.3s ease;
        }
        @keyframes dropdownFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .dropdown-item {
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #444;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }
        .dropdown-item i { width: 25px; font-size: 1.1rem; opacity: 0.7; }
        .dropdown-item:hover { background: var(--primary-navy); color: #fff; transform: translateX(5px); }
        .dropdown-item:hover i { opacity: 1; }
        .dropdown-item.text-danger:hover { background: #fee2e2; color: #dc2626 !important; }

        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920');
            background-size: cover;
            background-position: center;
        }
        .footer { background-color: #111; color: rgba(255,255,255,0.8); }
        .footer h5 { color: #fff; font-weight: 700; margin-bottom: 25px; }
        .footer a { color: rgba(255,255,255,0.6); transition: all 0.3s ease; }
        .footer a:hover { color: var(--accent-gold); transform: translateX(5px); }
        .footer .text-muted { color: rgba(255,255,255,0.5) !important; }
        .footer hr { border-color: rgba(255,255,255,0.1); }

        /* Floating Chat Widget */
        .chat-widget-btn { position: fixed; bottom: 30px; right: 30px; width: 65px; height: 65px; border-radius: 20px; background: var(--primary-navy); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; cursor: pointer; z-index: 1000; box-shadow: 0 15px 35px rgba(30, 58, 95, 0.3); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .chat-widget-btn:hover { transform: scale(1.1) rotate(5deg); background: #162a45; }
        .chat-widget-btn .unread-badge { position: absolute; top: -5px; right: -5px; background: #ff4757; color: #fff; font-size: 0.7rem; padding: 4px 8px; border-radius: 10px; border: 2px solid #fff; }
        
        .chat-window { position: fixed; bottom: 110px; right: 30px; width: 380px; height: 500px; background: #fff; border-radius: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 999; display: none; flex-direction: column; overflow: hidden; border: 1px solid rgba(0,0,0,0.05); transform-origin: bottom right; transition: all 0.3s ease; }
        .chat-window.active { display: flex; animation: chatOpen 0.4s ease forwards; }
        @keyframes chatOpen { from { opacity: 0; transform: translateY(20px) scale(0.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
        
        .chat-window-header { background: var(--primary-navy); color: #fff; padding: 20px 25px; display: flex; justify-content: space-between; align-items: center; }
        .chat-window-messages { flex: 1; overflow-y: auto; padding: 20px; background: #f8f9fa; }
        .chat-window-input { padding: 15px 20px; background: #fff; border-top: 1px solid #eee; }
        @yield('styles')
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="/"><i class="fas fa-hotel me-2"></i>The Grand Azure</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/#rooms">Rooms</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="/#contact">Contact</a></li>
                </ul>
                
                @guest
                <div class="d-flex ms-3">
                    <button onclick="showLoginModal()" class="btn btn-primary">Login</button>
                    <button onclick="showRegisterModal()" class="btn btn-outline-primary ms-2">Register</button>
                </div>
                @endguest

                @auth
                <div class="dropdown ms-3">
                    <button class="user-dropdown-btn dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        @if(Auth::user()->is_premium)
                            <i class="fas fa-crown me-2 {{ Auth::user()->premium_tier === 'gold' ? 'text-warning' : 'text-secondary' }}"></i>
                        @else
                            <i class="fas fa-user-circle me-2"></i>
                        @endif
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if(Auth::user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i>Admin Dashboard</a></li>
                        @elseif(Auth::user()->isManager())
                            <li><a class="dropdown-item" href="{{ route('manager.dashboard') }}"><i class="fas fa-tasks me-2"></i>Manager Panel</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('customer.bookings.index') }}"><i class="fas fa-calendar-check me-2"></i>My Bookings</a></li>
                        @endif
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form id="logout-form" method="POST" action="{{ route('logout') }}" onsubmit="localStorage.removeItem('auth_token');">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger w-100 border-0 bg-transparent"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <div>
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3"><h5>The Grand Azure</h5><p>Experience world-class hospitality</p></div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/#rooms" class="text-decoration-none">Rooms</a></li>
                        <li><a href="/#services" class="text-decoration-none">Services</a></li>
                        <li><a href="/#about" class="text-decoration-none">About</a></li>
                        <li><a href="/#contact" class="text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2 text-primary"></i>House 12, Road 5, Dhanmondi, Dhaka</li>
                        <li><i class="fas fa-phone me-2 text-primary"></i>+880 1712 345678</li>
                        <li><i class="fas fa-envelope me-2 text-primary"></i>info@luxuryhotel.com.bd</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Follow Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center"><p class="small mb-0">&copy; 2026 The Grand Azure. All rights reserved.</p></div>
        </div>
    </footer>

    @guest
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header bg-navy-gradient text-white py-4 px-4 border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-key me-2 text-warning"></i>Guest Login</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 p-md-5">
                    <form id="loginForm">
                        <div class="mb-4 position-relative">
                            <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary"><i class="fas fa-envelope"></i></span>
                                <input type="email" id="loginEmail" class="form-control bg-light border-0 py-3" placeholder="Enter your email" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary"><i class="fas fa-lock"></i></span>
                                <input type="password" id="loginPassword" class="form-control bg-light border-0 py-3" placeholder="••••••••" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-navy w-100 py-3 rounded-pill fw-bold shadow-lg mb-4">SIGN IN TO ACCOUNT</button>
                        <div class="text-center">
                            <p class="small text-muted mb-0">New to our resort? <a href="#" onclick="loginModal.hide(); registerModal.show(); return false;" class="text-primary fw-bold text-decoration-none">Create an Account</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header bg-navy-gradient text-white py-4 px-4 border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-crown me-2 text-warning"></i>Join Elite Club</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 p-md-5">
                    <form id="registerForm">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary"><i class="fas fa-user"></i></span>
                                <input type="text" id="registerName" class="form-control bg-light border-0 py-3" placeholder="Your Name" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary"><i class="fas fa-envelope"></i></span>
                                <input type="email" id="registerEmail" class="form-control bg-light border-0 py-3" placeholder="your@email.com" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Password</label>
                                    <input type="password" id="registerPassword" class="form-control bg-light border-0 py-3" placeholder="••••••••" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 1px;">Confirm</label>
                                    <input type="password" id="registerPasswordConfirmation" class="form-control bg-light border-0 py-3" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-navy w-100 py-3 rounded-pill fw-bold shadow-lg mb-4">JOIN ELITE CLUB</button>
                        <div class="text-center">
                            <p class="small text-muted mb-0">Already a member? <a href="#" onclick="registerModal.hide(); loginModal.show(); return false;" class="text-primary fw-bold text-decoration-none">Sign In Instead</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endguest

    <!-- Floating Chat Widget -->
    @auth
        @if(Auth::user()->role === 'customer')
    <div class="chat-widget-btn" id="chatWidgetBtn" onclick="toggleChatWindow()">
        <i class="fas fa-comment-dots"></i>
        <div id="globalUnreadBadge" class="unread-badge d-none">0</div>
    </div>
    
    <div class="chat-window" id="chatWindow">
        <div class="chat-window-header">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3"><i class="fas fa-headset text-white"></i></div>
                <div><h6 class="mb-0 fw-bold">Concierge Chat</h6><small class="text-white-50">Online</small></div>
            </div>
            <button onclick="toggleChatWindow()" class="btn btn-link text-white p-0"><i class="fas fa-times"></i></button>
        </div>
        <div class="chat-window-messages" id="widgetMessages" style="height: 350px;">
            <div class="text-center py-5 opacity-50"><p class="small">How can we help you today?</p></div>
        </div>
        <div class="chat-window-input">
            <div class="input-group">
                <input type="text" id="widgetInput" class="form-control border-0 bg-light rounded-pill px-3" placeholder="Type a message...">
                <button onclick="sendWidgetMessage()" class="btn btn-primary rounded-circle ms-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>
        @endif
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @guest
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
        function showLoginModal() { loginModal.show(); }
        function showRegisterModal() { registerModal.show(); }

        document.getElementById('loginForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const response = await fetch('/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ email, password })
            });
            
            if (response.ok) { 
                const data = await response.json();
                if (data.token) {
                    localStorage.setItem('auth_token', data.token);
                }
                window.location.href = data.redirect || '/';
            } else { 
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: 'Please check your credentials and try again.'
                });
            }
        });

        document.getElementById('registerForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const name = document.getElementById('registerName').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;
            const password_confirmation = document.getElementById('registerPasswordConfirmation').value;
            const response = await fetch('/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ name, email, password, password_confirmation })
            });
            
            if (response.ok) { 
                const data = await response.json();
                if (data.token) {
                    localStorage.setItem('auth_token', data.token);
                }
                location.reload(); 
            } else { 
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed',
                    text: 'Please check your information and try again.'
                });
            }
        });
        @endguest

        @guest
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('auth_token')) {
                localStorage.removeItem('auth_token');
                console.log('Guest detected: Stale auth token cleared.');
            }
        });
        @endguest

        @auth
        @if(Auth::user()->role === 'customer')
        let chatPollInterval;
        async function toggleChatWindow() {
            const win = document.getElementById('chatWindow');
            win.classList.toggle('active');
            if (win.classList.contains('active')) {
                loadWidgetMessages();
                chatPollInterval = setInterval(loadWidgetMessages, 4000);
            } else {
                clearInterval(chatPollInterval);
            }
        }

        async function loadWidgetMessages() {
            const token = localStorage.getItem('auth_token');
            if (!token) {
                console.warn('Chat Widget: No auth token found.');
                return;
            }
            try {
                const msgRes = await fetch(`/api/chat/messages/support`, { 
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } 
                });
                const msgs = await msgRes.json();
                
                if (!Array.isArray(msgs)) {
                    console.error('Chat Widget: Expected array of messages, got:', msgs);
                    return;
                }

                const container = document.getElementById('widgetMessages');
                const currentUserId = {{ Auth::id() ?? 'null' }};
                
                container.innerHTML = msgs.map(m => `
                    <div class="mb-2 ${m.sender_id == currentUserId ? 'text-end' : ''}">
                        <div class="d-inline-block p-2 rounded-3 small ${m.sender_id == currentUserId ? 'bg-primary text-white' : 'bg-white shadow-sm'}" style="max-width: 85%">
                            ${m.message}
                        </div>
                    </div>
                `).join('');
                container.scrollTop = container.scrollHeight;
                window.widgetRecipientId = 'support';
            } catch (e) { console.error('Widget Error:', e); }
        }

        async function sendWidgetMessage() {
            const input = document.getElementById('widgetInput');
            const message = input.value.trim();
            if (!message) return;
            const token = localStorage.getItem('auth_token');
            await fetch('/api/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' },
                body: JSON.stringify({ receiver_id: 'support', message })
            });
            input.value = '';
            loadWidgetMessages();
        }

        async function checkUnread() {
            const token = localStorage.getItem('auth_token');
            if(!token) return;
            const res = await fetch('/api/chat/unread', { headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } });
            const data = await res.json();
            const badge = document.getElementById('globalUnreadBadge');
            if (data.unread_count > 0) {
                badge.textContent = data.unread_count;
                badge.classList.remove('d-none');
            } else { badge.classList.add('d-none'); }
        }
        setInterval(checkUnread, 10000);
        checkUnread();
        @endif
        @endauth

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
