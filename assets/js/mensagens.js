function deleteMessage(button) {
    const messageItem = button.closest('.message-item');
    messageItem.remove();
}