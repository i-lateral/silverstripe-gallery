var thumbnails = document.getElementsByClassName("gallery-thumbnail");

for (var i = 0; i < thumbnails.length; i++) {
    var element = thumbnails[i];

    element.addEventListener("click", function(event) {
        // get URL of main image
        var url = event.closest('span').dataset.url;
        var img = document.createElement("IMG"); 
        img.src = url;

        // instanciate new modal
        var modal = new tingle.modal({
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['gallery-modal']
        });

        // set content
        modal.setContent(img);
        modal.open();
    });
}