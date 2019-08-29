var thumbnails = document.getElementsByClassName("gallery-thumbnail");

for (var i = 0; i < thumbnails.length; i++) {
    var element = thumbnails[i];

    element.addEventListener("click", function(event) {
        // get URL of main image
        var url = this.parentElement.getAttribute('data-url');
        var img = document.createElement("IMG"); 
        img.setAttribute('src', url);

        // instanciate new modal
        var modal = new tingle.modal({
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            cssClass: ['gallery-modal'],
            onOpen: function() {
                modal.checkOverflow()	
            },
            onClose: function() {
                modal.destroy();
            }
        });

        // set content
        modal.setContent(img);
        modal.open();
    });
}