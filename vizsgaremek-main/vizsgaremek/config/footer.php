<footer class="footer bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 text-center text-md-start">
                <span class="footer-logo">AB MASSZÁZS</span>
            </div>
            <div class="col-md-4 text-center">
                <div class="footer-socials">
                    <a href="https://www.instagram.com/abmasszazs?igsh=bnpyMzAzZGphMXN1" target="blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.facebook.com/share/1KrAZEAPKc/?mibextid=wwXIfr" target="blank"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <p class="footer-copyright">
                    &copy; 2026 AB MASSZÁZS <br>
                    <small>A NYUGALOM SZIGETE</small>
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>

<script>
    // Központi Téma Kezelő
    function toggleTheme() {
        const isDark = document.body.classList.toggle('dark-theme');
        document.cookie = "theme=" + (isDark ? 'dark' : 'light') + ";path=/;max-age=31536000";
        
        const icons = document.querySelectorAll('#themeToggle i, .fa-circle-half-stroke');
        icons.forEach(icon => {
            if (isDark) icon.classList.replace('fa-moon', 'fa-sun');
            else icon.classList.replace('fa-sun', 'fa-moon');
        });
    }

    // Oldal betöltési animációk és preloader
// Oldal betöltési animációk
window.addEventListener('load', () => {
    // Scroll Reveal - Biztosítjuk, hogy akkor is látszódjon, ha hiba van
    const reveals = document.querySelectorAll('.reveal');
    
    const obs = new IntersectionObserver(ents => {
        ents.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    
    reveals.forEach(r => obs.observe(r));

    // Kényszerített láthatóság, ha az observer nem indulna el 3 másodpercen belül
    setTimeout(() => {
        reveals.forEach(r => r.classList.add('visible'));
    }, 3000);
});
</script>