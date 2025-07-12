// About.jsx
import React from 'react';
import { useNavigate } from 'react-router-dom';

function About() {
  const navigate = useNavigate();

  return (
    <div style={{ padding: "20px" }}>
      <div style={{ padding: '30px' }}>
        <h1>About Us</h1>
        <p>Welcome to My Shop!</p>
        <p>This app helps you manage your products – add, edit, or delete items easily.</p>
        <p>Built using React and PHP backend with MySQL.</p>
      </div>
      <button onClick={() => navigate("/products")} style={{
        marginTop: "20px",
        padding: "10px 20px",
        backgroundColor: "#007bff",
        color: "white",
        border: "none",
        borderRadius: "5px",
        cursor: "pointer"
      }}>
        Back to Products
      </button>
    </div>
  );
}

export default About;
