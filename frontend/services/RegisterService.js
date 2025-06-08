document.getElementById('register-form').addEventListener('submit', async function (e) {
      e.preventDefault();

      const firstName = document.getElementById('first_name').value.trim();
      const lastName = document.getElementById('last_name').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;

      const responseText = document.getElementById('response');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;



      if (!firstName || !lastName || !email || !password) {
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

       const data = {
        first_name: firstName,
        last_name: lastName,
        email: email,
        password: password
      };

      try {
        const res = await fetch('http://localhost:8000/backend/auth/register', {
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
          responseText.innerText = 'Success: ' + result.message;
          responseText.style.color = 'green';
        }

      } catch (err) {
        document.getElementById('response').innerText = 'Error: ' + err.message;
        document.getElementById('response').style.color = 'red';
      }
    });