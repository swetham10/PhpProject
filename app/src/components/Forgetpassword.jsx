import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

function ForgetPassword() {
  const [step, setStep] = useState(1);
  const [form, setForm] = useState({
    email: '',
    otp: '',
    password: '',
    confirmPassword: '',
  });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSendOTP = async (e) => {
    e.preventDefault();
    const res = await fetch('http://localhost/server/send_otp.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: form.email }),
    });
    const data = await res.json();
    if (data.success) {
      alert('OTP sent to email');
      setStep(2);
    } else {
      setError(data.message);
    }
  };

  const handleResetPassword = async (e) => {
    e.preventDefault();
    if (form.password !== form.confirmPassword)
      return setError('Passwords do not match');
    if (form.password.length < 8)
      return setError('Password must be at least 8 characters');

    const res = await fetch('http://localhost/server/reset_password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form),
    });
    const data = await res.json();
    if (data.success) {
      alert('Password reset successfully');
      navigate('/login');
    } else {
      setError(data.message);
    }
  };

  return (
    <form onSubmit={step === 1 ? handleSendOTP : handleResetPassword}>
      <h2>Forget Password</h2>
      {error && <p style={{ color: 'red' }}>{error}</p>}

      <input
        type="email"
        name="email"
        placeholder="Email"
        value={form.email}
        onChange={handleChange}
        required
        disabled={step === 2}
      />

      {step === 2 && (
        <>
          <input
            type="text"
            name="otp"
            placeholder="Enter OTP"
            value={form.otp}
            onChange={handleChange}
            required
          />
          <input
            type="password"
            name="password"
            placeholder="New Password"
            value={form.password}
            onChange={handleChange}
            required
          />
          <input
            type="password"
            name="confirmPassword"
            placeholder="Confirm Password"
            value={form.confirmPassword}
            onChange={handleChange}
            required
          />
        </>
      )}

      <button type="submit">{step === 1 ? 'Send OTP' : 'Submit'}</button>
      <button type="button" onClick={() => navigate('/login')}>
        Go to Login
      </button>
    </form>
  );
}

export default ForgetPassword;
