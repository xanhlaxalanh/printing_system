var modal = document.getElementById('analysisModal');

// Get the button that opens the modal
var btn = document.getElementsByName('Save')[0];


// When the user clicks the button, open the modal 
btn.onclick = function(event) {
  event.preventDefault(); // Prevent form from submitting
  modal.style.display = "block"; // Display the overlay
  window.scrollTo({ top: window.innerHeight / 2, behavior: 'smooth' }); // Scroll smoothly to the center of the viewport height
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}