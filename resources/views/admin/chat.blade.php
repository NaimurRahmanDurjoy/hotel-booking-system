@extends('layouts.admin')

@section('title', 'Concierge Center')
@section('header_title', 'Guest Concierge Center')

@section('styles')
<style>
    :root {
        --chat-bg: #f8fafc;
        --sidebar-bg: #ffffff;
        --accent-gold: #f5a623;
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    .concierge-wrapper {
        height: calc(100vh - 140px);
        display: flex;
        background: var(--sidebar-bg);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.05);
        margin: 10px;
    }

    /* Column 1: Sidebar */
    .sidebar-pane {
        width: 350px;
        border-right: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        background: #fff;
    }
    .sidebar-top {
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
    }
    .sidebar-top h5 { font-weight: 800; color: var(--text-main); margin-bottom: 15px; letter-spacing: -0.5px; }
    
    .search-box {
        position: relative;
    }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 0.8rem;
    }
    .search-box input {
        width: 100%;
        background: #f1f5f9;
        border: none;
        padding: 12px 15px 12px 40px;
        border-radius: 14px;
        font-size: 0.85rem;
        transition: all 0.3s;
    }
    .search-box input:focus { background: #fff; box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1); outline: none; }

    .guest-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }
    .guest-card {
        padding: 16px;
        border-radius: 18px;
        display: flex;
        gap: 14px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 4px;
        position: relative;
    }
    .guest-card:hover { background: #f8fafc; transform: translateX(5px); }
    .guest-card.active { background: #f1f5f9; }
    .guest-card.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 20%;
        height: 60%;
        width: 4px;
        background: var(--primary);
        border-radius: 0 4px 4px 0;
    }

    .avatar-wrapper { position: relative; }
    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: linear-gradient(135deg, var(--primary) 0%, #1e3a5f 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 8px 16px rgba(30, 58, 95, 0.15);
    }
    .online-indicator {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 14px;
        height: 14px;
        background: #10b981;
        border: 3px solid #fff;
        border-radius: 50%;
    }

    .guest-meta h6 { margin: 0 0 2px 0; font-weight: 700; color: var(--text-main); font-size: 0.95rem; }
    .guest-meta p { margin: 0; font-size: 0.8rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
    .time-stamp { font-size: 0.7rem; color: var(--text-muted); font-weight: 500; }

    /* Column 2: Chat */
    .chat-pane {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        position: relative;
    }
    .chat-nav {
        padding: 16px 30px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 10;
    }
    
    .msg-container {
        flex: 1;
        padding: 30px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    /* Empty State Fix */
    .empty-chat {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 40px;
    }
    .empty-chat-icon {
        width: 120px;
        height: 120px;
        background: #fff;
        border-radius: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
        margin-bottom: 24px;
        color: var(--primary);
        font-size: 3rem;
    }

    .bubble-wrap { display: flex; flex-direction: column; max-width: 65%; }
    .bubble-wrap.sent { align-self: flex-end; align-items: flex-end; }
    .bubble-wrap.received { align-self: flex-start; align-items: flex-start; }
    
    .bubble {
        padding: 14px 20px;
        border-radius: 20px;
        font-size: 0.95rem;
        line-height: 1.5;
        position: relative;
    }
    .sent .bubble {
        background: var(--primary);
        color: #fff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 10px 20px rgba(30, 58, 95, 0.1);
    }
    .received .bubble {
        background: #fff;
        color: var(--text-main);
        border-bottom-left-radius: 4px;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    .bubble-time { font-size: 0.7rem; color: var(--text-muted); margin-top: 6px; font-weight: 500; }

    .bottom-bar {
        padding: 20px 30px;
        background: transparent;
    }
    .input-pill {
        background: #fff;
        border-radius: 20px;
        padding: 8px 10px 8px 24px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .input-pill input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 0.95rem;
        color: var(--text-main);
    }
    .send-pill-btn {
        width: 44px;
        height: 44px;
        border-radius: 15px;
        background: var(--primary);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(30, 58, 95, 0.2);
    }
    .send-pill-btn:hover { transform: scale(1.05) rotate(-5deg); background: #1e3a5f; }

    /* Column 3: Profile */
    .info-pane {
        width: 320px;
        background: #fff;
        border-left: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 24px;
    }
    .big-avatar {
        width: 110px;
        height: 110px;
        border-radius: 35px;
        background: #f8fafc;
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 20px;
        position: relative;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    }
    .premium-star {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 36px;
        height: 36px;
        background: var(--accent-gold);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.9rem;
        border: 4px solid #fff;
        box-shadow: 0 4px 10px rgba(245, 166, 35, 0.3);
    }
    .user-title { font-weight: 800; font-size: 1.3rem; color: var(--text-main); margin-bottom: 4px; text-align: center; }
    .user-status-label {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 5px 14px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 30px;
    }
    .label-gold { background: #fff8e6; color: #d97706; }
    .label-silver { background: #f1f5f9; color: #475569; }

    .detail-grid { width: 100%; border-top: 1px solid #f1f5f9; padding-top: 30px; }
    .detail-row { margin-bottom: 20px; }
    .detail-row label { display: block; font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; margin-bottom: 6px; letter-spacing: 0.5px; }
    .detail-row p { margin: 0; font-size: 0.95rem; color: var(--text-main); font-weight: 600; }
</style>
@endsection

@section('content')
<div class="concierge-wrapper">
    <!-- Sidebar -->
    <div class="sidebar-pane">
        <div class="sidebar-top">
            <h5>Concierge</h5>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="guestSearch" placeholder="Find a guest...">
            </div>
        </div>
        <div class="guest-scroll" id="conversationsList">
            <!-- Loading -->
            <div class="p-5 text-center opacity-50">
                <div class="spinner-border text-primary mb-3"></div>
                <p class="small fw-bold">Connecting to guests...</p>
            </div>
        </div>
    </div>

    <!-- Main Chat -->
    <div class="chat-pane">
        <div class="chat-nav" id="chatHeader" style="display: none;">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-wrapper">
                    <div class="avatar-circle" style="width: 40px; height: 40px; font-size: 1rem; border-radius: 12px;" id="headerAvatar">?</div>
                    <div class="online-indicator" style="width: 10px; height: 10px; border-width: 2px;"></div>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold" id="headerName" style="font-size: 1rem;">Guest Name</h6>
                    <small class="text-success fw-bold" style="font-size: 0.65rem;">ONLINE ASSISTANCE</small>
                </div>
            </div>
            <div class="nav-actions">
                <!-- Actions removed -->
            </div>
        </div>

        <div class="msg-container" id="chatMessages">
            <div class="empty-chat">
                <div class="empty-chat-icon">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <h4 class="fw-bold text-dark">Luxury Support Center</h4>
                <p class="text-muted mx-auto" style="max-width: 320px;">Open a conversation from the left to start providing world-class assistance to our valued guests.</p>
            </div>
        </div>

        <div class="bottom-bar" id="inputArea" style="display: none;">
            <div class="input-pill">
                <input type="text" id="messageInput" placeholder="Type your elegant response...">
                <button onclick="sendMessage()" class="send-pill-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Profile -->
    <div class="info-pane" id="profileSidebar" style="display: none;">
        <div class="big-avatar">
            <span id="profileAvatar">G</span>
            <div class="premium-star" id="premiumStar" style="display: none;">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <h3 class="user-title" id="profileName">Guest Name</h3>
        <span id="profileBadge" class="user-status-label label-silver">Regular Customer</span>

        <div class="detail-grid">
            <div class="detail-row">
                <label>Direct Email</label>
                <p id="profileEmail">guest@example.com</p>
            </div>
            <div class="detail-row">
                <label>Contact Number</label>
                <p id="profilePhone">Not provided</p>
            </div>
            <div class="detail-row">
                <label>Membership Status</label>
                <p id="profileTier" class="text-capitalize">Standard</p>
            </div>
            <div class="detail-row">
                <label>Arrival History</label>
                <p id="profileJoined">--</p>
            </div>
        </div>
        
        <div class="mt-auto w-100 p-3 bg-light rounded-4 text-center">
            <p class="small text-muted mb-0 fw-bold">Assisting since May 2026</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const authToken = localStorage.getItem('auth_token');
    let currentConversationId = null;
    let currentUserId = null;
    let conversations = [];

    document.addEventListener('DOMContentLoaded', async () => {
        await fetchUser();
        loadConversations();
        
        setInterval(loadMessages, 3000);
        setInterval(loadConversations, 10000);
    });

    document.getElementById('messageInput').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    document.getElementById('guestSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        renderConversations(conversations.filter(c => c.user_name.toLowerCase().includes(term)));
    });

    async function fetchUser() {
        const response = await fetch('/api/user', {
            headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
        });
        if (response.ok) {
            const user = await response.json();
            currentUserId = user.id;
        }
    }

    async function loadConversations() {
        try {
            const response = await fetch('/api/chat/conversations', {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            conversations = await response.json();
            renderConversations(conversations);
        } catch (error) { console.error('Error:', error); }
    }

    function renderConversations(list) {
        const container = document.getElementById('conversationsList');
        if (list.length === 0) {
            container.innerHTML = '<div class="p-5 text-center text-muted small fw-bold">No active conversations</div>';
            return;
        }

        container.innerHTML = list.map(conv => `
            <div onclick="selectConversation('${conv.user_id}', '${conv.user_name}')" 
                 class="guest-card ${currentConversationId === conv.user_id ? 'active' : ''}">
                <div class="avatar-wrapper">
                    <div class="avatar-circle">${conv.user_name.charAt(0)}</div>
                </div>
                <div class="guest-meta flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6>${conv.user_name}</h6>
                        <span class="time-stamp">${conv.last_message_time || ''}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p>${conv.last_message || 'Inquiry pending...'}</p>
                        ${conv.unread > 0 ? '<div style="width: 8px; height: 8px; background: var(--primary); border-radius: 50%;"></div>' : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }

    async function selectConversation(userId, userName) {
        currentConversationId = userId;
        
        document.getElementById('chatHeader').style.display = 'flex';
        document.getElementById('inputArea').style.display = 'block';
        document.getElementById('profileSidebar').style.display = 'flex';
        
        document.getElementById('headerName').textContent = userName;
        document.getElementById('headerAvatar').textContent = userName.charAt(0);
        
        fetchGuestDetails(userId);
        loadMessages();
        renderConversations(conversations);
    }

    async function fetchGuestDetails(userId) {
        const response = await fetch(`/api/admin/users/${userId}`, {
            headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
        });
        if (response.ok) {
            const guest = await response.json();
            document.getElementById('profileName').textContent = guest.name;
            document.getElementById('profileAvatar').textContent = guest.name.charAt(0);
            document.getElementById('profileEmail').textContent = guest.email;
            document.getElementById('profilePhone').textContent = guest.phone || 'Not provided';
            document.getElementById('profileTier').textContent = guest.premium_tier || 'Standard';
            document.getElementById('profileJoined').textContent = new Date(guest.created_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            
            const badge = document.getElementById('profileBadge');
            const star = document.getElementById('premiumStar');
            
            if (guest.is_premium) {
                badge.textContent = `${guest.premium_tier} MEMBER`;
                badge.className = `user-status-label label-gold`;
                star.style.display = 'flex';
            } else {
                badge.textContent = 'REGULAR CUSTOMER';
                badge.className = `user-status-label label-silver`;
                star.style.display = 'none';
            }
        }
    }

    async function loadMessages() {
        if (!currentConversationId) return;
        try {
            const response = await fetch(`/api/chat/messages/${currentConversationId}`, {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            const messages = await response.json();
            const container = document.getElementById('chatMessages');
            
            if (messages.length === 0) {
                container.innerHTML = '<div class="h-100 d-flex align-items-center justify-content-center text-muted small fw-bold">Conversation started</div>';
                return;
            }

            container.innerHTML = messages.map(msg => `
                <div class="bubble-wrap ${msg.sender_id === currentUserId ? 'sent' : 'received'}">
                    <div class="bubble">
                        ${msg.message}
                    </div>
                    <span class="bubble-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                </div>
            `).join('');
            
            container.scrollTop = container.scrollHeight;
        } catch (error) { console.error('Error:', error); }
    }

    async function sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        if (!message || !currentConversationId) return;
        
        try {
            await fetch('/api/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
                body: JSON.stringify({ receiver_id: currentConversationId, message })
            });
            input.value = '';
            loadMessages();
            loadConversations();
        } catch (error) { console.error('Error:', error); }
    }
</script>
@endsection