    // For the Slider at the Landing Page
    var slideIndex = 0;
    var slides = document.querySelectorAll('.slider img');

    function showSlide(n) {
        slideIndex = n >= slides.length ? 0 : n < 0 ? slides.length - 1 : n;

        for (var i = 0; i < slides.length; i++) {
            slides[i].classList.remove('active');
        }

        slides[slideIndex].classList.add('active');
    }

    function nextSlide() {
        showSlide(slideIndex + 1);
    }

    function prevSlide() {
        showSlide(slideIndex - 1);
    }

    setInterval(nextSlide, 3000); // Change slide every 3 seconds

    showSlide(slideIndex); // Show initial slide