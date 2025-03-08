document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/your_project/books") // Adjust URL to match your API
        .then(response => response.json())
        .then(data => {
            const booksTable = document.getElementById("books-list");
            booksTable.innerHTML = ""; // Clear existing content

            data.forEach(book => {
                booksTable.innerHTML += `
                    <tr>
                        <td>${book.id}</td>
                        <td>${book.title}</td>
                        <td>${book.author}</td>
                        <td>${book.price}€</td>
                    </tr>`;
            });
        })
        .catch(error => console.error("Error fetching books:", error));
});
