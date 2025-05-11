const routes = {
    "home": "/frontend/views/home.html",
    "about": "/frontend/views/about.html",
    "books": "/frontend/views/books.html",
    "cart": "/frontend/views/cart.html",
    "checkout": "/frontend/views/checkout.html",
    "login": "/frontend/views/login.html",
    "profile": "/frontend/views/profile.html",
    "register": "/frontend/views/register.html",
    "book": "/frontend/views/book.html",
    "admin-dashboard" : "/frontend/views/admin-dashboard.html"
};

function loadPage(page) {
    const content = document.getElementById('main-content');

    if (content) {
        fetch(routes[page])
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                content.innerHTML = data;

                const scripts = content.querySelectorAll("script");

                const loadScripts = async (scripts) => {
                    for (const oldScript of scripts) {
                        const newScript = document.createElement("script");
                        if (oldScript.src) {
                            await new Promise((resolve, reject) => {
                                newScript.src = oldScript.src;
                                newScript.onload = resolve;
                                newScript.onerror = reject;
                                document.body.appendChild(newScript);
                            });
                        } else {
                            newScript.textContent = oldScript.textContent;
                            document.body.appendChild(newScript);
                        }
                        oldScript.remove();
                    }
                };

                loadScripts(scripts).then(() => {
                    window.history.pushState({ page: page }, page, `#${page}`);
                    attachEventListeners();
                }).catch(error => {
                    console.error('Error loading scripts:', error);
                });

            })
            .catch(error => console.error('Error loading page:', error));
    } else {
        console.error("Container with id 'main-content' not found.");
    }
}



function attachEventListeners() {
    //  navigation links 
    document.querySelectorAll('[data-link]').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const page = e.target.getAttribute('data-page');
            if (routes[page]) {
                loadPage(page);
            }
        });
    });

    //  login navigation
    document.querySelectorAll('.login-link a').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            loadPage('login');
        });
    });

    // register navigation
    document.querySelectorAll('.register-link a').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            loadPage('register');
        });
    });
}

// load the correct page on window load
window.addEventListener('load', () => {
    const page = window.location.hash.replace('#', '') || 'home';
    loadPage(page);
});

// back/forward navigation
window.addEventListener('popstate', (event) => {
    if (event.state && event.state.page) {
        loadPage(event.state.page);
    }
});



