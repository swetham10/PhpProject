import React, { useState } from 'react';
import './RegisterForm.css'; // optional: for styling

const RegistrationForm = () => {
  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    username: '',
    email: '',
    dob: '',
    languages: [],
    country: '',
    state: '',
    city: ''
  });

  const languagesList = ['English', 'Tamil', 'Hindi', 'Telugu'];
  const countries = ['INDIA', 'USA' , 'UK']; // can be dynamic
  const states = ['Tamil Nadu', 'Kerala', 'Telungana'];
  const cities = ['Chennai', 'Coimbatore', 'vellore' , 'Trichy'];

  const handleChange = (e) => {
    const { name, value, type, selectedOptions } = e.target;
    if (type === 'select-multiple') {
      const values = Array.from(selectedOptions, option => option.value);
      setFormData({ ...formData, [name]: values });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    // Basic validation (you can enhance this)
    if (!formData.email || !formData.firstName) {
      alert("Please fill all fields");
      return;
    }

    try {
      const response = await fetch('http://localhost/server/register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });

      const result = await response.json();
      if (result.success) {
        alert('Registration successful! Check your email.');
      } else {
        alert(result.message || 'Registration failed');
      }
    } catch (err) {
      console.error(err);
      alert('Error connecting to server.');
    }
  };

  return (
    <div className="form-container">
    <h2 className="form-title">Register</h2>
    <form onSubmit={handleSubmit}>
      <input name="firstName" type="text" placeholder="First Name" onChange={handleChange} required />
      <input name="lastName" type="text" placeholder="Last Name" onChange={handleChange} required />
      <input name="username" type="text" placeholder="Username" onChange={handleChange} required />
      <input name="email" type="email" placeholder="Email ID" onChange={handleChange} required />
      <input name="dob" type="date" onChange={handleChange} required />

      <label>Known Languages</label>
      {/* <select name="languages" multiple onChange={handleChange} required>
        {languagesList.map(lang => <option key={lang} value={lang}>{lang}</option>)}
      </select> */}
      <div className="checkbox-group">
        {languagesList.map((lang) => (
          <label key={lang}>
            <input
              type="checkbox"
              name="languages"
              value={lang}
              checked={formData.languages.includes(lang)}
              onChange={(e) => {
                const { value, checked } = e.target;
                if (checked) {
                  setFormData({ ...formData, languages: [...formData.languages, value] });
                } else {
                  setFormData({
                    ...formData,
                    languages: formData.languages.filter((l) => l !== value),
                  });
                }
              }}
            />
            {lang}
          </label>
        ))}
      </div>

      <select name="country" onChange={handleChange} required>
        <option value="">Select Country</option>
        {countries.map(c => <option key={c} value={c}>{c}</option>)}
      </select>

      <select name="state" onChange={handleChange} required>
        <option value="">Select State</option>
        {states.map(s => <option key={s} value={s}>{s}</option>)}
      </select>

      <select name="city" onChange={handleChange} required>
        <option value="">Select City</option>
        {cities.map(c => <option key={c} value={c}>{c}</option>)}
      </select>

      <button type="submit">Submit</button>
      <button type="button" onClick={() => window.location.href = '/login'}>Go to Login</button>
    </form>
    </div>
  );
};

export default RegistrationForm;
// import React, { useState } from 'react';
// import axios from 'axios';

// const RegisterForm = () => {
//   const [form, setForm] = useState({
//     firstName: '', lastName: '', username: '', email: '', dob: '',
//     languages: [], country: '', state: '', city: ''
//   });

//   const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

//   const handleLanguages = (e) => {
//     const { value, checked } = e.target;
//     setForm((prev) => ({
//       ...prev,
//       languages: checked
//         ? [...prev.languages, value]
//         : prev.languages.filter((lang) => lang !== value),
//     }));
//   };

//   const handleSubmit = async (e) => {
//     e.preventDefault();
//     try {
//       const response = await axios.post('http://localhost/server/register.php', form);
//       alert(response.data.message);
//     } catch (err) {
//       console.error(err);
//       alert('Error during registration');
//     }
//   };

//   return (
//     <form onSubmit={handleSubmit}>
//       <h2>Register</h2>
//       <input name="firstName" placeholder="First Name" onChange={handleChange} required />
//       <input name="lastName" placeholder="Last Name" onChange={handleChange} />
//       <input name="username" placeholder="Username" onChange={handleChange} />
//       <input name="email" placeholder="Email" type="email" onChange={handleChange} required />
//       <input name="dob" type="date" onChange={handleChange} />
      
//       <div>
//         <label><input type="checkbox" value="English" onChange={handleLanguages} /> English</label>
//         <label><input type="checkbox" value="Tamil" onChange={handleLanguages} /> Tamil</label>
//       </div>

//       <select name="country" onChange={handleChange}>
//         <option value="">Select Country</option>
//         <option value="India">India</option>
//       </select>

//       <select name="state" onChange={handleChange}>
//         <option value="">Select State</option>
//         <option value="Tamil Nadu">Tamil Nadu</option>
//       </select>

//       <select name="city" onChange={handleChange}>
//         <option value="">Select City</option>
//         <option value="Chennai">Chennai</option>
//         <option value="Tiruvallur">Tiruvallur</option>
//       </select>

//       <button type="submit">Submit</button>
//       <button type="button" onClick={() => window.location.href = '/login'}>Go to Login</button>
//     </form>
//   );
// };

// export default RegisterForm;
