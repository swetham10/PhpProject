import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';

import RegisterForm from './components/RegisterForm';
import SetPassword from './components/SetPassword';
import Login from './components/login';
import ForgetPassword from './components/Forgetpassword';
import ProductManager from './components/ProductManager';
import About from './About';

function App() {
  return (
    <Router>
      <div className="App">
        <Routes>
          <Route path="/" element={<RegisterForm />} />
          <Route path="/set-password/:uid/:token" element={<SetPassword />} />
          <Route path="/login" element={<Login />} />
          <Route path="/forget-password" element={<ForgetPassword />} />
          <Route path="/products" element={<ProductManager />} />
          <Route path="/about" element={<About />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
