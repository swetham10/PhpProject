import React, { useState } from 'react';
import axios from 'axios';

const RegisterForm = () => {
  const [form, setForm] = useState({
    firstName: '', lastName: '', username: '', email: '', dob: '',
    languages: [], country: '', state: '', city: ''
  });

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleLanguages = (e) => {
    const { value, checked } = e.target;
    setForm((prev) => ({
      ...prev,
      languages: checked
        ? [...prev.languages, value]
        : prev.languages.filter((lang) => lang !== value),
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://localhost/server/register.php', form);
      alert(response.data.message);
    } catch (err) {
      console.error(err);
      alert('Error during registration');
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>Register</h2>
      <input name="firstName" placeholder="First Name" onChange={handleChange} required />
      <input name="lastName" placeholder="Last Name" onChange={handleChange} />
      <input name="username" placeholder="Username" onChange={handleChange} />
      <input name="email" placeholder="Email" type="email" onChange={handleChange} required />
      <input name="dob" type="date" onChange={handleChange} />
      
      <div>
        <label><input type="checkbox" value="English" onChange={handleLanguages} /> English</label>
        <label><input type="checkbox" value="Tamil" onChange={handleLanguages} /> Tamil</label>
      </div>

      <select name="country" onChange={handleChange}>
        <option value="">Select Country</option>
        <option value="India">India</option>
      </select>

      <select name="state" onChange={handleChange}>
        <option value="">Select State</option>
        <option value="Tamil Nadu">Tamil Nadu</option>
      </select>

      <select name="city" onChange={handleChange}>
        <option value="">Select City</option>
        <option value="Chennai">Chennai</option>
        <option value="Tiruvallur">Tiruvallur</option>
      </select>

      <button type="submit">Submit</button>
      <button type="button" onClick={() => window.location.href = '/login'}>Go to Login</button>
    </form>
  );
};

export default RegisterForm;
