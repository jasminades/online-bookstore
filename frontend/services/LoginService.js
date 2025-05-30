document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const data = {
      email: document.getElementById('email').value.trim(),
      password: document.getElementById('password').value
    };

    try {
      const res = await fetch('http://localhost:8000/backend/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      const responseText = document.getElementById('response');

      if (!res.ok) {
        const errText = await res.text();
        responseText.innerText = 'Error: ' + errText;
        responseText.style.color = 'red';
      } else {
        const result = await res.json();

        localStorage.setItem('token', result.data.token);

        responseText.innerText = 'Success: ' + result.message;
        responseText.style.color = 'green';

        const role = result.data.role || 'customer'; 

        setTimeout(() => {
          if (role === 'admin') {
            window.location.href = 'admin-dashboard-books.html';
          } else {
            window.location.href = 'index.html'; 
          }
        }, 1000);
      }
    } catch (err) {
      document.getElementById('response').innerText = 'Error: ' + err.message;
      document.getElementById('response').style.color = 'red';
    }
  });