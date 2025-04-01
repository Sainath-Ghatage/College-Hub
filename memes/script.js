$(document).ready(function() {
    function loadMemes() {
        $.ajax({
            url: "fetch_memes.php",
            method: "GET",
            success: function(data) {
                $("#memeFeed").html(data);
            }
        });
    }

    loadMemes();

    // Upload Meme
    $("#uploadMemeForm").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: "upload_meme.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                loadMemes();
            },
            error: function(xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });

    // Like Meme
    $(document).on("click", ".like-btn", function() {
        var memeId = $(this).data("id");
        var button = $(this);

        $.ajax({
            url: "like_meme.php",
            method: "POST",
            data: { meme_id: memeId },
            success: function(response) {
                if (response.trim() === "Liked") {
                    button.text("✅ Liked");
                } else {
                    alert(response);
                }
            }
        });
    });

    // Toggle Comments
    $(document).on("click", ".comment-toggle", function() {
        var memeId = $(this).data("id");
        $("#comments-" + memeId).toggle();
    });

    // Post Comment
    $(document).on("submit", ".comment-form", function(e) {
        e.preventDefault();
        var memeId = $(this).data("id");
        var comment = $(this).find(".comment-input").val();
        var commentBox = $(this).closest(".comment-section").find(".comment-list");

        $.ajax({
            url: "comment_meme.php",
            method: "POST",
            data: { meme_id: memeId, comment: comment },
            success: function(response) {
                if (response.trim() === "Comment added!") {
                    commentBox.append("<p><strong>You</strong>: " + comment + "</p>");
                    $(".comment-input").val("");
                } else {
                    alert(response);
                }
            }
        });
    });

    // Edit Caption
    $(document).on("click", ".edit-btn", function() {
        var memeId = $(this).data("id");
        var captionElement = $(this).closest(".card-body").find("p");
        var currentCaption = captionElement.text().trim();
        var newCaption = prompt("Edit your caption:", currentCaption);

        if (newCaption !== null && newCaption.trim() !== "") {
            $.ajax({
                url: "edit_caption.php",
                method: "POST",
                data: { meme_id: memeId, new_caption: newCaption },
                success: function(response) {
                    if (response.trim() === "Caption updated!") {
                        captionElement.text(newCaption);
                    } else {
                        alert(response);
                    }
                }
            });
        }
    });

    // Delete Meme
    $(document).on("click", ".delete-btn", function() {
        var memeId = $(this).data("id");
        var cardElement = $(this).closest(".card");

        if (confirm("Are you sure you want to delete this meme?")) {
            $.ajax({
                url: "delete_meme.php",
                method: "POST",
                data: { meme_id: memeId },
                success: function(response) {
                    if (response.trim() === "Meme deleted!") {
                        cardElement.fadeOut("slow", function() {
                            $(this).remove();
                        });
                    } else {
                        alert(response);
                    }
                }
            });
        }
    });
});
