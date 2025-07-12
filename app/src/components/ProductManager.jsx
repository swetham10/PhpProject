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

  return (
    <div>
      <h2>{editingId ? 'Edit Product' : 'Add Product'}</h2>
      <form onSubmit={handleSubmit}>
        <input name="title" placeholder="Title" value={form.title} onChange={handleChange} required />
        <input name="description" placeholder="Description" value={form.description} onChange={handleChange} required />
        <input name="price" type="number" step="0.01" placeholder="Price" value={form.price} onChange={handleChange} required />
        <button type="submit">{editingId ? 'Update' : 'Add'}</button>
      </form>

      <h2>Product List</h2>
      <div style={{ display: 'flex', flexWrap: 'wrap' }}>
        {products.map((prod) => (
          <div key={prod.id} style={{ border: '1px solid gray', padding: '10px', margin: '10px' }}>
            <h3>{prod.title}</h3>
            <p>{prod.description}</p>
            <p><b>Price:</b> ₹{prod.price}</p>
            <button onClick={() => handleEdit(prod)}>Edit</button>
            <button onClick={() => handleDelete(prod.id)}>Delete</button>
          </div>
        ))}
      </div>
    </div>
  );
}

export default ProductManager;
