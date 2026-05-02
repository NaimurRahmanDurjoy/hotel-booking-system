@extends('layouts.admin')

@section('styles')
<style>
    .chat-card-integrated { 
        height: calc(100vh - 160px); 
        background: var(--white); 
        border-radius: 12px; 
        overflow: hidden; 
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .chat-sidebar-admin { 
        width: 320px; 
        background: var(--white); 
        border-right: 1px solid var(--bg-light); 
        display: flex;
        flex-direction: column;
    }
    
    .chat-sidebar-header {
        padding: 20px;
        border-bottom: 1px solid var(--bg-light);
        font-weight: 700;
        color: var(--primary);
    }

    .guest-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .guest-item { 
        padding: 15px; 
        border-radius: 10px; 
        margin-bottom: 5px; 
        transition: all 0.2s ease; 
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    
    .guest-item:hover { background: var(--bg-light); }
    .guest-item.active { 
        background: rgba(30, 58, 95, 0.05); 
        border-left: 4px solid var(--primary);
    }
    
    .guest-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .chat-main-admin {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--white);
    }

    .chat-main-header {
        padding: 15px 25px;
        border-bottom: 1px solid var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 25px;
        background: #fdfdfd;
        display: flex;
        flex-direction: column;
    }

    .msg-wrapper {
        margin-bottom: 15px;
        max-width: 75%;
    }

    .msg-wrapper.sent { align-self: flex-end; }
    .msg-wrapper.received { align-self: flex-start; }

    .msg-bubble {
        padding: 12px 18px;
        border-radius: 15px;
        font-size: 0.95rem;
        line-height: 1.4;
        position: relative;
    }

    .sent .msg-bubble {
        background: var(--primary);
        color: white;
        border-bottom-right-radius: 2px;
    }

    .received .msg-bubble {
        background: var(--bg-light);
        color: var(--text-dark);
        border-bottom-left-radius: 2px;
    }

    .msg-time {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 5px;
        display: block;
    }
    .sent .msg-time { text-align: right; }

    .chat-input-admin {
        padding: 20px 25px;
        border-top: 1px solid var(--bg-light);
    }

    .input-box-wrapper {
        display: flex;
        background: var(--bg-light);
        border-radius: 30px;
        padding: 5px 5px 5px 15px;
        align-items: center;
    }

    .input-box-wrapper input {
        flex: 1;
        background: transparent;
        border: none;
        padding: 10px;
        outline: none;
    }

    .send-btn-admin {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .send-btn-admin:hover { transform: scale(1.1); }

    .unread-dot {
        width: 10px;
        height: 10px;
        background: var(--secondary);
        border-radius: 50%;
        margin-left: auto;
    }
</style>
@endsection

@section('header_title', 'Concierge Message Center')

@section('content')
<div class="chat-card-integrated">
    <!-- Guests Sidebar -->
    <div class="chat-sidebar-admin">
        <div class="chat-sidebar-header">
            <i class="fas fa-users me-2"></i> Active Guests
        </div>
        <div class="guest-list" id="conversationsList">
            <!-- Loaded via JS -->
        </div>
    </div>

    <!-- Main Chat View -->
    <div class="chat-main-admin">
        <div class="chat-main-header">
            <div class="d-flex align-items-center">
                <div class="guest-avatar" id="chatAvatar">?</div>
                <div>
                    <h4 class="mb-0 fw-bold" id="chatTitle" style="font-size: 1.1rem;">Select a Guest</h4>
                    <span class="text-success small d-none" id="onlineStatus"><i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i> Connected</span>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-sm btn-light text-muted"><i class="fas fa-ellipsis-v"></i></button>
            </div>
        </div>

        <div class="chat-messages-container" id="chatMessages">
            <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                <div class="text-center opacity-50">
                    <i class="fas fa-comment-medical fa-4x mb-3"></i>
                    <h5>Guest Support Center</h5>
                    <p class="small">Open a conversation to begin luxury assistance</p>
                </div>
            </div>
        </div>

        <div class="chat-input-admin">
            <div class="input-box-wrapper">
                <input type="text" id="messageInput" placeholder="Type your response here...">
                <button onclick="sendMessage()" class="send-btn-admin">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const authToken = localStorage.getItem('auth_token');
    let currentConversation = null;
    let currentUserId = null;
    
    document.addEventListener('DOMContentLoaded', async function() {
        await fetchUser();
        loadConversations();
        setInterval(loadMessages, 3000);
        setInterval(loadConversations, 10000);
    });
    
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
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
            const conversations = await response.json();
            const list = document.getElementById('conversationsList');
            
            if (conversations.length === 0) {
                list.innerHTML = '<div class="p-4 text-center text-muted small">No active guests</div>';
                return;
            }
            
            list.innerHTML = conversations.map(conv => {
                const initial = conv.user_name.charAt(0);
                return `
                <div onclick="selectConversation('${conv.user_id}', '${conv.user_name}')" 
                    class="guest-item ${currentConversation === conv.user_id ? 'active' : ''}">
                    <div class="guest-avatar">${initial}</div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-truncate" style="font-size: 0.95rem;">${conv.user_name}</span>
                            <small class="text-muted" style="font-size: 0.7rem;">${conv.last_message_time || ''}</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted text-truncate" style="max-width: 85%">${conv.last_message || 'New Inquiry'}</small>
                            ${conv.unread > 0 ? `<div class="unread-dot"></div>` : ''}
                        </div>
                    </div>
                </div>
            `}).join('');

            if (conversations.length > 0 && !currentConversation) {
                selectConversation(conversations[0].user_id, conversations[0].user_name);
            }
        } catch (error) { console.error('Error:', error); }
    }
    
    function selectConversation(userId, userName) {
        currentConversation = userId;
        document.getElementById('chatTitle').textContent = userName;
        document.getElementById('chatAvatar').textContent = userName.charAt(0);
        document.getElementById('onlineStatus').classList.remove('d-none');
        loadMessages();
        loadConversations();
    }
    
    async function loadMessages() {
        if (!currentConversation) return;
        try {
            const response = await fetch(`/api/chat/messages/${currentConversation}`, {
                headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
            });
            const messages = await response.json();
            const container = document.getElementById('chatMessages');
            
            if (messages.length === 0) {
                container.innerHTML = `
                    <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                        <div class="text-center opacity-50">
                            <i class="fas fa-comment-dots fa-3x mb-3"></i>
                            <p>No message history with this guest</p>
                        </div>
                    </div>`;
                return;
            }

            container.innerHTML = messages.map(msg => `
                <div class="msg-wrapper ${msg.sender_id === currentUserId ? 'sent' : 'received'}">
                    <div class="msg-bubble">
                        <p class="mb-0">${msg.message}</p>
                    </div>
                    <small class="msg-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</small>
                </div>
            `).join('');
            
            container.scrollTop = container.scrollHeight;
        } catch (error) { console.error('Error:', error); }
    }
    
    async function sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim();
        if (!message || !currentConversation) return;
        
        try {
            await fetch('/api/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' },
                body: JSON.stringify({ receiver_id: currentConversation, message })
            });
            input.value = '';
            loadMessages();
            loadConversations();
        } catch (error) { console.error('Error:', error); }
    }
</script>
@endsection