<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel</title>
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
        .navbar { background-color: #fff; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        .hero-bg {
            background: linear-gradient(rgba(30, 58, 95, 0.8), rgba(30, 58, 95, 0.8)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920');
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
    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/"><i class="fas fa-hotel me-2"></i>Luxury Hotel</a>
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
                    <button class="btn btn-link dropdown-toggle text-decoration-none text-dark d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        @if(Auth::user()->is_premium)
                            <i class="fas fa-crown me-2 {{ Auth::user()->premium_tier === 'gold' ? 'text-warning' : 'text-secondary' }}"></i>
                        @else
                            <i class="fas fa-user-circle me-2"></i>
                        @endif
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                        @elseif(Auth::user()->isManager())
                            <li><a class="dropdown-item" href="{{ route('manager.dashboard') }}">Manager Panel</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('customer.bookings.index') }}">My Bookings</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.premium') }}">Premium Membership</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <div style="margin-top: 80px;">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3"><h5>Luxury Hotel</h5><p>Experience world-class hospitality</p></div>
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
                        <li><i class="fas fa-map-marker-alt me-2 text-primary"></i>123 Hotel Street, City</li>
                        <li><i class="fas fa-phone me-2 text-primary"></i>+1 234 567 890</li>
                        <li><i class="fas fa-envelope me-2 text-primary"></i>info@luxuryhotel.com</li>
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
            <div class="text-center"><p class="small mb-0">&copy; 2024 Luxury Hotel. All rights reserved.</p></div>
        </div>
    </footer>

    @guest
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Login</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" id="loginEmail" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" id="loginPassword" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Register</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form id="registerForm">
                        <div class="mb-3"><label class="form-label">Name</label><input type="text" id="registerName" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Email</label><input type="email" id="registerEmail" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Password</label><input type="password" id="registerPassword" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Confirm Password</label><input type="password" id="registerPasswordConfirmation" class="form-control" required></div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
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

        @auth
        document.addEventListener('DOMContentLoaded', async function() {
            if (!localStorage.getItem('auth_token')) {
                try {
                    const response = await fetch('/get-token');
                    if (response.ok) {
                        const data = await response.json();
                        localStorage.setItem('auth_token', data.token);
                        console.log('Chat Widget: Token restored from session.');
                        if (typeof checkUnread === 'function') checkUnread();
                    }
                } catch (e) { console.error('Token recovery failed:', e); }
            }
        });
        @endauth

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
    </script>
    @yield('scripts')
</body>
</html>
