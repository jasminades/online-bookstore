let FeaturedBooksService = {
    init: function () {
        console.log("Featured Books Service Initialized");
        FeaturedBooksService.getFeaturedBooks();
    },

    getFeaturedBooks: function () {
        console.log("Fetching Featured Books...");
        RestClient.get("/featured-books", function (data) {
            FeaturedBooksService.renderFeaturedBooks(data);
        }, function (error) {
            console.error("Failed to fetch featured books:", error);
            toastr.error("Failed to load featured books.");
        });
    },

    renderFeaturedBooks: function (books) {
        console.log("Rendering Featured Books...");
        let container = $("#featured-books-container");
        container.empty();

        if (books && books.length > 0) {
            books.forEach(function (book) {
                let card = `
                    <div class="book-card">
                         <img id="book-image" src="${book.image_url || '../static/images/book1.jpg'}" alt="Book Cover">
                        <h3 class="book-title">${book.title}</h3>
                        <p class="book-author">by ${book.author}</p>
                        <p class="book-category">Category: ${book.category_name || 'Unknown'}</p>
                        <p class="book-price">$${parseFloat(book.price).toFixed(2)}</p>
                        <button class="btn view-details" onclick="window.location.href='index.html#book-${book.id}'">View Details</button>
                    </div>
                `;
                container.append(card);
            });
        } else {
            container.append('<p>No featured books available.</p>');
        }
    }
};
