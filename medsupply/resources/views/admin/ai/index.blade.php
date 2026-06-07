@extends('layouts.admin')
@section('title', 'Assistant IA')
@section('page-title', 'Assistant IA')
@section('page-subtitle')
MedSupply · Votre assistant intelligent
@endsection

@section('content')
<style>
.ai-wrap { display: grid; grid-template-columns: 1fr 280px; gap: 1.4rem; height: calc(100vh - 160px); }
.chat-box { background: var(--card-bg); border-radius: 18px; box-shadow: var(--shadow); border: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden; animation: fadeUp 0.4s ease both; }
.chat-header { padding: 1.2rem 1.4rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
.ai-avatar { width: 42px; height: 42px; border-radius: 12px; background: linear-gradient(135deg, var(--blue), var(--lavender-dark)); display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; flex-shrink: 0; }
.ai-status { width: 8px; height: 8px; background: var(--mint-dark); border-radius: 50%; display: inline-block; margin-right: 5px; animation: pulse 2s infinite; }
.chat-messages { flex: 1; overflow-y: auto; padding: 1.4rem; display: flex; flex-direction: column; gap: 14px; }
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
.msg { display: flex; gap: 10px; animation: fadeUp 0.3s ease both; }
.msg.user { flex-direction: row-reverse; }
.msg-avatar { width: 32px; height: 32px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; flex-shrink: 0; }
.msg.bot .msg-avatar { background: linear-gradient(135deg, var(--blue), var(--lavender-dark)); color: white; }
.msg.user .msg-avatar { background: linear-gradient(135deg, var(--mint-dark), var(--blue)); color: white; }
.msg-bubble { max-width: 75%; padding: 10px 14px; border-radius: 14px; font-size: 0.86rem; line-height: 1.5; }
.msg.bot .msg-bubble { background: var(--bg); color: var(--text); border-radius: 4px 14px 14px 14px; }
.msg.user .msg-bubble { background: linear-gradient(135deg, var(--blue), var(--blue-dark)); color: white; border-radius: 14px 4px 14px 14px; }
.msg-time { font-size: 0.68rem; color: var(--text3); margin-top: 4px; }
.typing-indicator { display: flex; gap: 4px; padding: 10px 14px; background: var(--bg); border-radius: 4px 14px 14px 14px; width: fit-content; }
.typing-dot { width: 7px; height: 7px; background: var(--text3); border-radius: 50%; animation: typing 1.2s infinite; }
.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes typing { 0%,60%,100%{transform:translateY(0);}30%{transform:translateY(-6px);} }
.chat-input-area { padding: 1rem 1.4rem; border-top: 1px solid var(--border); display: flex; gap: 10px; align-items: flex-end; }
.chat-input { flex: 1; border: 1.5px solid var(--border); border-radius: 12px; padding: 10px 14px; font-size: 0.86rem; font-family: 'DM Sans', sans-serif; color: var(--text); background: var(--bg); outline: none; resize: none; max-height: 100px; transition: border-color 0.2s; }
.chat-input:focus { border-color: var(--blue); background: var(--card-bg); }
.send-btn { width: 42px; height: 42px; border-radius: 11px; background: linear-gradient(135deg, var(--blue), var(--blue-dark)); border: none; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0; }
.send-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(91,155,213,0.4); }
.send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* Sidebar suggestions */
.side-panel { display: flex; flex-direction: column; gap: 1rem; }
.side-card { background: var(--card-bg); border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--border); padding: 1.2rem; animation: fadeUp 0.4s ease both; }
.side-title { font-size: 0.82rem; font-weight: 700; color: var(--text); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
.suggestion-btn { display: block; width: 100%; text-align: left; padding: 8px 10px; border-radius: 9px; border: 1px solid var(--border); background: var(--bg); color: var(--text2); font-size: 0.78rem; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; margin-bottom: 6px; }
.suggestion-btn:hover { background: var(--blue-soft); border-color: var(--blue); color: var(--blue-dark); }
.suggestion-btn:last-child { margin-bottom: 0; }
.clear-btn { display: block; width: 100%; padding: 8px; border-radius: 9px; border: 1.5px solid var(--border); background: none; color: var(--text3); font-size: 0.78rem; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: all 0.2s; text-align: center; }
.clear-btn:hover { background: var(--peach); border-color: var(--peach-dark); color: var(--peach-dark); }
@keyframes fadeUp { from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);} }
@keyframes pulse { 0%,100%{transform:scale(1);}50%{transform:scale(1.3);} }
</style>

<div class="ai-wrap">
  <!-- CHAT BOX -->
  <div class="chat-box">
    <div class="chat-header">
      <div class="ai-avatar"><i class="fa-solid fa-robot"></i></div>
      <div>
        <div style="font-size:0.92rem;font-weight:700;color:var(--text)">MedBot</div>
        <div style="font-size:0.74rem;color:var(--text3)"><span class="ai-status"></span>En ligne · Assistant MedSupply</div>
      </div>
    </div>

    <div class="chat-messages" id="chatMessages">
      <!-- Message de bienvenue -->
      <div class="msg bot">
        <div class="msg-avatar"><i class="fa-solid fa-robot"></i></div>
        <div>
          <div class="msg-bubble">
            👋 Bonjour ! Je suis <strong>MedBot</strong>, votre assistant IA pour la gestion des fournitures médicales.<br><br>
            Je peux vous aider avec :<br>
            • Gestion des commandes et stocks<br>
            • Suivi des fournisseurs<br>
            • Analyse des données<br>
            • Conseils sur les procédures
          </div>
          <div class="msg-time">Maintenant</div>
        </div>
      </div>
    </div>

    <div class="chat-input-area">
      <textarea class="chat-input" id="chatInput" placeholder="Posez votre question..." rows="1"
        onkeydown="handleKey(event)" oninput="autoResize(this)"></textarea>
      <button class="send-btn" id="sendBtn" onclick="sendMessage()">
        <i class="fa-solid fa-paper-plane" style="font-size:14px;"></i>
      </button>
    </div>
  </div>

  <!-- SIDE PANEL -->
  <div class="side-panel">
    <div class="side-card">
      <div class="side-title"><i class="fa-solid fa-lightbulb" style="color:var(--blue);"></i> Suggestions</div>
      <button class="suggestion-btn" onclick="useSuggestion(this)">Comment optimiser la gestion des stocks ?</button>
      <button class="suggestion-btn" onclick="useSuggestion(this)">Quels sont les indicateurs KPI importants ?</button>
      <button class="suggestion-btn" onclick="useSuggestion(this)">Comment évaluer un fournisseur médical ?</button>
      <button class="suggestion-btn" onclick="useSuggestion(this)">Procédure de commande urgente</button>
      <button class="suggestion-btn" onclick="useSuggestion(this)">Meilleures pratiques de gestion hospitalière</button>
    </div>

    <div class="side-card">
      <div class="side-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--text3);"></i> Session</div>
      <div style="font-size:0.78rem;color:var(--text3);margin-bottom:10px;" id="msgCount">0 message(s)</div>
      <button class="clear-btn" onclick="clearChat()">
        <i class="fa-solid fa-trash" style="font-size:11px;margin-right:5px;"></i> Effacer la conversation
      </button>
    </div>

    <div class="side-card">
      <div class="side-title"><i class="fa-solid fa-circle-info" style="color:var(--lavender-dark);"></i> À propos</div>
      <div style="font-size:0.76rem;color:var(--text3);line-height:1.5;">
        MedBot est alimenté par <strong style="color:var(--text);">GPT-3.5</strong>. Il est spécialisé dans la gestion médicale et répond en français.
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
let conversationHistory = [];
let messageCount = 0;

function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 100) + 'px';
}

function getTime() {
    return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
}

function addMessage(role, content) {
    const container = document.getElementById('chatMessages');
    const isUser = role === 'user';

    const msg = document.createElement('div');
    msg.className = `msg ${isUser ? 'user' : 'bot'}`;
    msg.innerHTML = `
        <div class="msg-avatar">
            ${isUser ? '<i class="fa-solid fa-user" style="font-size:12px;"></i>' : '<i class="fa-solid fa-robot"></i>'}
        </div>
        <div>
            <div class="msg-bubble">${content.replace(/\n/g, '<br>')}</div>
            <div class="msg-time">${getTime()}</div>
        </div>
    `;
    container.appendChild(msg);
    container.scrollTop = container.scrollHeight;

    messageCount++;
    document.getElementById('msgCount').textContent = messageCount + ' message(s)';
}

function showTyping() {
    const container = document.getElementById('chatMessages');
    const typing = document.createElement('div');
    typing.className = 'msg bot';
    typing.id = 'typingIndicator';
    typing.innerHTML = `
        <div class="msg-avatar"><i class="fa-solid fa-robot"></i></div>
        <div class="typing-indicator">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    `;
    container.appendChild(typing);
    container.scrollTop = container.scrollHeight;
}

function removeTyping() {
    const typing = document.getElementById('typingIndicator');
    if (typing) typing.remove();
}

async function sendMessage() {
    const input = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');
    const message = input.value.trim();

    if (!message) return;

    // Afficher le message user
    addMessage('user', message);
    conversationHistory.push({ role: 'user', content: message });

    // Reset input
    input.value = '';
    input.style.height = 'auto';
    sendBtn.disabled = true;

    // Afficher typing
    showTyping();

    try {
        const response = await fetch('{{ route("admin.ai.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: message,
                history: conversationHistory.slice(-10) // Garder les 10 derniers messages
            })
        });

        const data = await response.json();
        removeTyping();

        if (data.success) {
            addMessage('bot', data.reply);
            conversationHistory.push({ role: 'assistant', content: data.reply });
        } else {
            addMessage('bot', '⚠️ ' + data.reply);
        }

    } catch (error) {
        removeTyping();
        addMessage('bot', '⚠️ Erreur de connexion. Vérifiez votre clé API.');
    }

    sendBtn.disabled = false;
    input.focus();
}

function useSuggestion(btn) {
    document.getElementById('chatInput').value = btn.textContent;
    sendMessage();
}

function clearChat() {
    if (!confirm('Effacer toute la conversation ?')) return;
    const container = document.getElementById('chatMessages');
    container.innerHTML = '';
    conversationHistory = [];
    messageCount = 0;
    document.getElementById('msgCount').textContent = '0 message(s)';

    // Remettre le message de bienvenue
    addMessage('bot', '👋 Conversation effacée. Comment puis-je vous aider ?');
    messageCount = 0;
    document.getElementById('msgCount').textContent = '0 message(s)';
}
</script>
@endsection