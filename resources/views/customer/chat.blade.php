@extends('layouts.frontend')

@section('styles')
<style>
    .chat-card { height: 600px; display: flex; flex-direction: column; }
    .chat-messages { flex: 1; overflow-y: auto; padding: 20px; background: #f8f9fa; }
    .message { max-width: 75%; padding: 12px 18px; border-radius: 20px; margin-bottom: 15px; position: relative; }
    .message.sent { background: #1E3A5F; color: white; margin-left: auto; border-bottom-right-radius: 4px; }
    .message.received { background: white; color: #333; margin-right: auto; border-bottom-left-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .message small { display: block; font-size: 0.7rem; margin-top: 5px; opacity: 0.7; }
    .conv-item { cursor: pointer; transition: all 0.2s; border-left: 4px solid transparent; }
    .conv-item:hover { background: #f8f9fa; }
    .conv-item.active { background: #eef2f7; border-left-color: #1E3A5F; }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Messages</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-4"><i class="fas fa-comments text-primary me-2"></i>My Messages</h2>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Conversations</h5>
                        </div>
                        <div class="list-group list-group-flush" id="conversationsList" style="max-height: 500px; overflow-y: auto;">
                            <!-- Loaded via JS -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm chat-card overflow-hidden">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold" id="chatTitle">Select a contact</h5>
                            <span class="badge bg-success rounded-pill d-none" id="onlineStatus">Online</span>
                        </div>
                        <div class="chat-messages" id="chatMessages">
                            <div class="h-100 d-flex align-items-center justify-content-center text-muted">
                                <div class="text-center">
                                    <i class="fas fa-comment-dots fa-3x mb-3 opacity-25"></i>
                                    <p>Click on a conversation to start chatting with our staff.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-top bg-white">
                            <div class="input-group">
                                <input type="text" id="messageInput" class="form-control border-0 bg-light" placeholder="Type your message here..." style="padding: 12px 20px;">
                                <button onclick="sendMessage()" class="btn btn-primary px-4"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
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
                list.innerHTML = '<div class="p-4 text-center text-muted small">No conversations yet</div>';
                return;
            }
            
            list.innerHTML = conversations.map(conv => `
                <div onclick="selectConversation(${conv.user_id}, '${conv.user_name}')" 
                    class="list-group-item list-group-item-action conv-item p-3 ${currentConversation === conv.user_id ? 'active' : ''}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-1 fw-bold">${conv.user_name}</h6>
                        <small class="text-muted">${conv.last_message_time || ''}</small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted text-truncate" style="max-width: 80%">${conv.last_message || 'Start a conversation'}</small>
                        ${conv.unread_count > 0 ? `<span class="badge bg-primary rounded-pill">${conv.unread_count}</span>` : ''}
                    </div>
                </div>
            `).join('');
        } catch (error) { console.error('Error:', error); }
    }
    
    function selectConversation(userId, userName) {
        currentConversation = userId;
        document.getElementById('chatTitle').textContent = userName;
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
            
            container.innerHTML = messages.map(msg => `
                <div class="message ${msg.sender_id === currentUserId ? 'sent' : 'received'}">
                    <p class="mb-0">${msg.message}</p>
                    <small>${msg.created_at}</small>
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