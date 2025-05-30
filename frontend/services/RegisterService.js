document.getElementById('register-form').addEventListener('submit', async function (e) {
      e.preventDefault();

      const data = {
        first_name: document.getElementById('first_name').value.trim(),
        last_name: document.getElementById('last_name').value.trim(),
        email: document.getElementById('email').value.trim(),
        password: document.getElementById('password').value
      };

      try {
        const res = await fetch('http://localhost:8000/backend/auth/register', {
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
          responseText.innerText = 'Success: ' + result.message;
          responseText.style.color = 'green';
        }

      } catch (err) {
        document.getElementById('response').innerText = 'Error: ' + err.message;
        document.getElementById('response').style.color = 'red';
      }
    });