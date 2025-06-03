function showBookDetails(title, author, price, image, description) {
  document.getElementById('bookDetailsModalLabel').textContent = title;
  document.getElementById('modalBookAuthor').textContent = author;
  document.getElementById('modalBookPrice').textContent = price;
  document.getElementById('modalBookImage').src = image;
  document.getElementById('modalBookDescription').innerHTML = description;

  const modal = new bootstrap.Modal(document.getElementById('bookDetailsModal'));
  modal.show();
}
