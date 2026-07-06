<?php
/**
 * views/messages/chat.php – Real-time Chat Interface with avatars
 * 
 * Displays a full chat window with:
 * - Avatar for other user in header
 * - Avatars on messages from other user
 * - AJAX polling for new messages (every 3 seconds)
 * - Send messages with optional photo
 */
include __DIR__ . '/../../includes/header.php';
?>

<style>
.chat-container {
    background: var(--surface-container-lowest);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 70vh;
    border: 0.5px solid rgba(197, 198, 205, 0.2);
}
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: var(--surface);
}
.message {
    display: flex;
    max-width: 75%;
    animation: fadeIn 0.3s ease;
}
.message-mine {
    align-self: flex-end;
    background: var(--primary);
    color: var(--on-primary);
    border-radius: 18px 18px 4px 18px;
}
.message-other {
    align-self: flex-start;
    background: var(--surface-container);
    color: var(--on-surface);
    border-radius: 18px 18px 18px 4px;
}
.message-content {
    padding: 12px 16px;
}
.message-meta {
    font-size: 0.65rem;
    margin-top: 4px;
    opacity: 0.7;
}
.message-photo {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    margin-bottom: 4px;
    cursor: pointer;
}
.chat-input {
    padding: 16px;
    border-top: 0.5px solid var(--outline-variant);
    display: flex;
    gap: 10px;
    background: var(--surface-container-lowest);
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="max-w-4xl mx-auto px-margin-mobile md:px-0">
    <!-- Chat Header -->
    <div class="glass-card rounded-t-2xl p-4 border-b border-outline-variant/20">
        <div class="flex items-center gap-3">
            <a href="index.php?action=inbox" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <!-- Avatar using first letter -->
            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm flex-shrink-0">
                <?php echo $other_initial ?? '?'; ?>
            </div>
            <div>
                <h3 class="font-display font-semibold text-on-surface"><?php echo htmlspecialchars($item['item_name']); ?></h3>
                <p class="text-sm text-on-surface-variant">Chat with <?php echo htmlspecialchars($other_user['username']); ?></p>
            </div>
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="chat-container">
        <div class="chat-messages" id="chatMessages">
            <div class="text-center text-on-surface-variant text-sm py-4">Loading messages...</div>
        </div>

        <!-- Chat Input -->
        <div class="chat-input">
            <?php $prefill_msg = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : ''; ?>
            <textarea id="messageInput" class="flex-1 px-4 py-3 rounded-xl border border-outline-variant bg-surface/50 focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all outline-none resize-none" rows="2" placeholder="Type your message..."><?php echo $prefill_msg; ?></textarea>
            <input type="file" id="photoInput" accept="image/*" style="display: none;">
            <button id="attachBtn" class="px-4 py-2 bg-surface-variant hover:bg-outline-variant rounded-xl transition-colors">
                <span class="material-symbols-outlined">attach_file</span>
            </button>
            <button id="sendBtn" class="px-5 py-2 bg-primary text-on-primary rounded-xl hover:bg-primary/90 transition-all active:scale-95">
                <span class="material-symbols-outlined">send</span>
            </button>
        </div>
    </div>
</div>

<script>
const itemId = <?php echo $item['id']; ?>;
const otherUserId = <?php echo $other_user_id; ?>;
let lastMsgId = 0;
let isPolling = true;

/**
 * Load messages from server via AJAX
 */
function loadMessages() {
    if (!isPolling) return;
    
    fetch(`index.php?action=fetch_messages&item_id=${itemId}&other_user_id=${otherUserId}&last_id=${lastMsgId}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                const container = document.getElementById('chatMessages');
                if (container.children.length === 1 && container.children[0].innerText === 'Loading messages...') {
                    container.innerHTML = '';
                }
                data.forEach(msg => {
                    const div = document.createElement('div');
                    div.className = `message ${msg.is_mine ? 'message-mine' : 'message-other'}`;
                    
                    let content = '<div class="message-content">';
                    
                    // Show avatar for other user's messages
                    if (!msg.is_mine) {
                        content += `<div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm inline-block mr-2 flex-shrink-0">${msg.avatar}</div>`;
                    }
                    
                    if (msg.photo) {
                        content += `<img src="${msg.photo}" alt="Message photo" class="message-photo" onclick="window.open('${msg.photo}', '_blank')">`;
                    }
                    if (msg.message) {
                        content += `<div>${msg.message}</div>`;
                    }
                    content += `<div class="message-meta">${msg.created_at}</div>`;
                    content += `</div>`;
                    div.innerHTML = content;
                    container.appendChild(div);
                    lastMsgId = msg.id;
                });
                container.scrollTop = container.scrollHeight;
            }
        })
        .catch(err => console.error('Error fetching messages:', err));
}

/**
 * Send a message
 */
function sendMessage() {
    const message = document.getElementById('messageInput').value.trim();
    const photoFile = document.getElementById('photoInput').files[0];
    if (!message && !photoFile) return;

    const formData = new FormData();
    formData.append('to_user_id', otherUserId);
    formData.append('item_id', itemId);
    if (message) formData.append('message', message);
    if (photoFile) formData.append('photo', photoFile);

    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';

    fetch('index.php?action=send_message', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('messageInput').value = '';
            document.getElementById('photoInput').value = '';
            loadMessages();
        } else {
            alert(data.error || 'Failed to send message');
        }
    })
    .catch(err => {
        console.error('Error sending message:', err);
        alert('Failed to send message. Please try again.');
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<span class="material-symbols-outlined">send</span>';
    });
}

// ===== Event Listeners =====

document.getElementById('sendBtn').addEventListener('click', sendMessage);
document.getElementById('attachBtn').addEventListener('click', () => {
    document.getElementById('photoInput').click();
});
document.getElementById('photoInput').addEventListener('change', () => {
    if (document.getElementById('photoInput').files.length > 0) {
        sendMessage();
    }
});
document.getElementById('messageInput').addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

// ===== Poll for new messages every 3 seconds =====
setInterval(loadMessages, 3000);

// ===== Initial load =====
loadMessages();

// ===== Cleanup on page leave =====
window.addEventListener('beforeunload', () => {
    isPolling = false;
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
