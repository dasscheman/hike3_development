var popupmodal = document.getElementById('image-popup-modal')

var modalImg = document.getElementById("image-popup-image");
var captionText = document.getElementById("image-popup- caption");
var close = document.getElementById("image-popup-close");

var routebook = document.getElementsByClassName('routebook-tekst')
for (let item of routebook) {
    // Get the image and insert it inside the modal - use its "alt" text as a captionimage-popup-modal
    var images = item.getElementsByTagName('img');
    for (i = 0; i < images.length; i++) {
      images[i].onclick = function(){
        popupmodal.style.display = "block";
        modalImg.src = this.src;
        // captionText.innerHTML = this.alt;
      }
      // When the user clicks on <span> (x), close the modal
      close.onclick = function() {
        popupmodal.style.display = "none";
      }
    };
}
