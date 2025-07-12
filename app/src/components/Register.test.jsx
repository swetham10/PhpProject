import { render, screen } from '@testing-library/react';
import Register from './Register';

test('renders registration form', () => {
  render(<Register />);
  expect(screen.getByPlaceholderText(/First Name/i)).toBeInTheDocument();
});
