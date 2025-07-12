import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';


function Login() {
  const [form, setForm] = useState({ emailOrUsername: '', password: '' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const res = await fetch("http://localhost/server/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(form),
    });

    const data = await res.json();
    if (data.success) {
      alert("Login successful!");
      navigate('/products');
      // navigate to home/dashboard if needed
    } else {
      setError(data.message || "Login failed");
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Login</h2>
      {error && <p style={{ color: 'red' }}>{error}</p>}
      <input
        type="text"
        name="emailOrUsername"
        placeholder="Email or Username"
        value={form.emailOrUsername}
        onChange={handleChange}
        required
      />
      <input
        type="password"
        name="password"
        placeholder="Password"
        value={form.password}
        onChange={handleChange}
        required
      />
      <button type="submit">Login</button>
      <button type="button" onClick={() => navigate('/register')}>Register</button>
      <button type="button" onClick={() => navigate('/forget-password')}>Forget Password</button>
    </form>
  );
}

export default Login;
