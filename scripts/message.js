$(document).ready(function() {
    function loadMessages() {
        $.ajax({
            url: 'handlers/fetch_messages.php',
            type: 'GET',
            data: {
                friend_id: friendId
            },
            success: function(data) {
                $('#chatBox').html(data);
                $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight); // Scroll to bottom
            }
        });
    }

    loadMessages(); // Initial load
    setInterval(loadMessages, 3000); // Refresh messages every 3 seconds

    $('#messageForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'handlers/send_message.php',
            type: 'POST',
            data: {
                friend_id: friendId,
                message: $('#message').val()
            },
            success: function() {
                $('#message').val(''); // Clear the input
                loadMessages(); // Reload messages
            }
        });
    });
});