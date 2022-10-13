let chatContainer = document.getElementById("chat-container");
if (chatContainer) {
    let chatId = chatContainer.getAttribute('data-chat-id');
    console.log(`chat.${chatId}`);
    Echo.private(`chat.${chatId}`)
        .listen('new-message', (e) => {
            console.log(e);
        });
}