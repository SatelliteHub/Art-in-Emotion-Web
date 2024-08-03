// loadContent.js
document.addEventListener("DOMContentLoaded", function() {
    // Load the common head content and header/footer
    fetch('header-footer.html')
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');

            // Inject common head content (excluding <title>)
            const headContent = doc.head.innerHTML; // Keep the <title> tag
            document.head.innerHTML = headContent;

            // Inject header and footer content
            document.getElementById('header-container').innerHTML = doc.querySelector('#header').outerHTML;
            document.getElementById('footer-container').innerHTML = doc.querySelector('#footer').outerHTML;
        })
        .catch(error => console.error('Error loading HTML:', error));
});

function highlightCurrentPage() {
    const currentPath = window.location.pathname;
    const links = document.querySelectorAll('.nav-link');

    links.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
}
