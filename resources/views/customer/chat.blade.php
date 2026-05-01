<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Hotel Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar { min-height: 100vh; background: #1E3A5F; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: #fff; }
        .chat-container { height: 500px; display: flex; flex-direction: column; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 15px; }
        .message { max-width: 70%; padding: 10px 15px; border-radius: 15px; margin-bottom: 10px; }
        .message.sent { background: #1E3A5F; color: white; margin-left: auto; }
        .message.received { background: #f8f9fa; color: #333; }
        .message-input { padding: 15px; border-top: 1px solid #dee2e6; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar p-3">
                <h4 class="text-white mb-4"><i class="fas fa-hotel me-2"></i>Luxury Hotel</h4>
                <nav>
                    <a href="/dashboard"><i class="fas fa-home me-2"></i>Dashboard</a>
                    <a href="/bookings"><i class="fas fa-calendar me-2"></i>My Bookings</a>
                    <a href="/rooms"><i class="fas fa-bed me-2"></i>Rooms</a>
                    <a href="/chat" class="active"><i class="fas fa-comments me-2"></i>Messages</a>
                    <a href="/premium"><i class="fas fa-crown me-2"></i>Premium</a>
                    <a href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </nav>
            </div>
            <div class="col-md-9 col-lg-10 p-4">
                <h2 class="mb-4">Messages</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Conversations</h5>
                            </div>
                            <div class="list-group list-group-flush" id="conversationsList"></div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card chat-container">
                            <div class="card-header bg-white">
                                <h5 class="mb-0" id="chatTitle">Select a conversation</h5>
                            </div>
                            <div class="chat-messages" id="chatMessages"></div>
                            <div class="message-input">
                                <div class="input-group">
                                    <input type="text" id="messageInput" class="form-control" placeholder="Type a message...">
                                    <button onclick="sendMessage()" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const authToken = localStorage.getItem('auth_token');
        let currentConversation = null;
        
        if (!authToken) window.location.href = '/';
        
        document.addEventListener('DOMContentLoaded', function() {
            loadConversations();
            setInterval(loadMessages, 5000);
        });
        
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') sendMessage();
        });
        
        async function loadConversations() {
            try {
                const response = await fetch('/api/chat/conversations', {
                    headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' }
                });
                const conversations = await response.json();
                const list = document.getElementById('conversationsList');
                
                if (conversations.length === 0) {
                    list.innerHTML = '<div class="list-group-item text-muted">No conversations yet</div>';
                    return;
                }
                
                list.innerHTML = conversations.map(conv => `
                    <button onclick="selectConversation(${conv.user_id}, '${conv.user_name}')" 
                        class="list-group-item list-group-item-action ${currentConversation === conv.user_id ? 'active' : ''}">
                        <div class="d-flex justify-content-between">
                            <strong>${conv.user_name}</strong>
                            <small class="text-muted">${conv.unread || ''}</small>
                        </div>
                        <small class="text-muted">${conv.last_message || 'No messages'}</small>
                    </button>
                `).join('');
            } catch (error) { console.error('Error:', error); }
        }
        
        function selectConversation(userId, userName) {
            currentConversation = userId;
            document.getElementById('chatTitle').textContent = userName;
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
                    <div class="message ${msg.sender_id === ${authToken} ? 'sent' : 'received'}">
                        <p class="mb-0">${msg.message}</p>
                        <small class="${msg.sender_id === ${authToken} ? 'text-white-50' : 'text-muted'}">${msg.created_at}</small>
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
        
        function logout() {
            fetch('/api/logout', { method: 'POST', headers: { 'Authorization': `Bearer ${authToken}`, 'Accept': 'application/json' } })
            .then(() => { localStorage.removeItem('auth_token'); window.location.href = '/'; });
        }
    </script>
</body>
</html>