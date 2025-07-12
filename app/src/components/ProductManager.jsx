import React, { useEffect, useState } from 'react';

function ProductManager() {
  const [form, setForm] = useState({ title: '', description: '', price: '' });
  const [products, setProducts] = useState([]);
  const [editingId, setEditingId] = useState(null);

  const fetchProducts = async () => {
    const res = await fetch("http://localhost/server/get_products.php");
    const data = await res.json();
    setProducts(data);
  };

  useEffect(() => {
    fetchProducts();
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const endpoint = editingId ? 'update_product.php' : 'add_product.php';
    const payload = editingId ? { ...form, id: editingId } : form;

    await fetch(`http://localhost/server/${endpoint}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    setForm({ title: '', description: '', price: '' });
    setEditingId(null);
    fetchProducts();
  };

  const handleEdit = (product) => {
    setForm({ title: product.title, description: product.description, price: product.price });
    setEditingId(product.id);
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Delete this product?")) return;

    await fetch("http://localhost/server/delete_product.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });

    fetchProducts();
  };

  const handleLogout = () => {
    localStorage.clear();
    window.location.href = "/login";
  };

  return (
    <div>
      {/* NAVIGATION BAR */}
      <nav style={navStyle}>
        <div style={{ fontWeight: "bold", fontSize: "22px" }}>AbsProducts</div>
        <div style={{ display: "flex", gap: "10px" }}>
          <button onClick={() => window.location.href = "/products"} style={navBtnStyle}>Home</button>
          <button onClick={() => window.location.href = "/about"} style={navBtnStyle}>About Us</button>
          <button onClick={handleLogout} style={navBtnStyle}>Logout</button>
        </div>
      </nav>

      <h2>{editingId ? 'Edit Product' : 'Add Product'}</h2>
      <form onSubmit={handleSubmit}>
        <input name="title" placeholder="Title" value={form.title} onChange={handleChange} required />
        <input name="description" placeholder="Description" value={form.description} onChange={handleChange} required />
        <input name="price" type="number" step="0.01" placeholder="Price" value={form.price} onChange={handleChange} required />
        <button type="submit">{editingId ? 'Update' : 'Add'}</button>
      </form>

      <h2>Product List</h2>
      <div style={{ display: 'flex', flexWrap: 'wrap', gap: '20px' }}>
        {products.map((prod) => (
          <div key={prod.id} style={productCardStyle}>
            <h3>{prod.title}</h3>
            <p>{prod.description}</p>
            <p><b>Price:</b> ₹{prod.price}</p>
            <div style={{ display: 'flex', justifyContent: 'space-between', gap: '10px' }}>
              <button onClick={() => handleEdit(prod)} style={btnStyle}>Edit</button>
              <button onClick={() => handleDelete(prod.id)} style={{ ...btnStyle, backgroundColor: "red" }}>Delete</button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

const navStyle = {
  backgroundColor: "#222",
  color: "white",
  padding: "10px 20px",
  display: "flex",
  justifyContent: "space-between",
  alignItems: "center"
};

const navBtnStyle = {
  backgroundColor: "#007bff",
  color: "white",
  border: "none",
  padding: "6px 12px",
  borderRadius: "4px",
  cursor: "pointer"
};

const productCardStyle = {
  border: '1px solid #ccc',
  borderRadius: '10px',
  padding: '15px',
  width: '220px',
  boxShadow: '0 4px 8px rgba(0, 0, 0, 0.1)',
  backgroundColor: '#fff'
};

const btnStyle = {
  backgroundColor: '#007bff',
  color: '#fff',
  padding: '6px 12px',
  border: 'none',
  borderRadius: '5px',
  cursor: 'pointer'
};

export default ProductManager;
