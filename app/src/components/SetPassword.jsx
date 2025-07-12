import { useParams, useNavigate } from 'react-router-dom';
import { useEffect, useState } from 'react';

function SetPassword() {
  const { uid, token } = useParams();
  const navigate = useNavigate();

  const [valid, setValid] = useState(false);
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [error, setError] = useState('');

  useEffect(() => {
    fetch(`http://localhost/server/validate_token.php?uid=${uid}&token=${token}`)
      .then(res => res.json())
      .then(data => setValid(data.valid))
      .catch(() => setError("Error validating token"));
  }, [uid, token]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (password !== confirmPassword) return setError("Passwords do not match");
    if (password.length < 8) return setError("Password must be at least 8 characters");

    const res = await fetch("http://localhost/server/set_password.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ uid, token, password }),
    });
    const data = await res.json();
    if (data.success) {
      alert("Password set successfully");
      navigate('/login');
    } else {
      setError(data.message || "Failed to set password");
    }
  };

  const handleResend = async () => {
    const res = await fetch("http://localhost/server/resend_email.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ uid }),
    });
    const data = await res.json();
    alert(data.message);
  };

  if (!valid) return <p>Invalid or expired link</p>;

  return (
    <form onSubmit={handleSubmit}>
      <h2>Set Your Password</h2>
      {error && <p style={{ color: "red" }}>{error}</p>}
      <input
        type="password"
        placeholder="New Password"
        value={password}
        onChange={e => setPassword(e.target.value)}
        required
      />
      <input
        type="password"
        placeholder="Confirm Password"
        value={confirmPassword}
        onChange={e => setConfirmPassword(e.target.value)}
        required
      />
      <button type="submit">Submit</button>
      <button type="button" onClick={handleResend}>Resend Email</button>
    </form>
  );
}

export default SetPassword;
