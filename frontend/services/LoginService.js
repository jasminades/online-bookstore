document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const responseText = document.getElementById('response');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email || !password) {
      responseText.innerText = 'All fields are required.';
      responseText.style.color = 'red';
      return;
    }

    if (!emailRegex.test(email)) {
      responseText.innerText = 'Please enter a valid email.';
      responseText.style.color = 'red';
      return;
    }

    if (password.length < 6) {
      responseText.innerText = 'Password must be at least 6 characters.';
      responseText.style.color = 'red';
      return;
    }

    const data = { email, password };

    try {
      const res = await fetch('http://localhost:8000/backend/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

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