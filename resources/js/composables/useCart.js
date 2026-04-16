import { ref, watch } from 'vue';

const cart = ref([]);
const STORAGE_KEY = 'recarstore_cart';

export function useCart() {
    const loadCart = () => {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored) {
            cart.value = JSON.parse(stored);
        }
    };

    const saveCart = () => {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(cart.value));
    };

    const addToCart = (product, quantity = 1) => {
        const existing = cart.value.find(item => item.id === product.id);
        
        if (existing) {
            existing.quantity += quantity;
        } else {
            cart.value.push({
                id: product.id,
                marca: product.marca,
                modelo: product.modelo,
                precio: product.precio,
                imagen: product.imagen,
                quantity: quantity
            });
        }
        
        saveCart();
    };

    const removeFromCart = (productId) => {
        cart.value = cart.value.filter(item => item.id !== productId);
        saveCart();
    };

    const updateQuantity = (productId, quantity) => {
        const item = cart.value.find(item => item.id === productId);
        if (item) {
            if (quantity <= 0) {
                removeFromCart(productId);
            } else {
                item.quantity = quantity;
                saveCart();
            }
        }
    };

    const clearCart = () => {
        cart.value = [];
        saveCart();
    };

    const cartTotal = () => {
        return cart.value.reduce((total, item) => {
            return total + (item.precio * item.quantity);
        }, 0);
    };

    const cartCount = () => {
        return cart.value.reduce((count, item) => count + item.quantity, 0);
    };

    const getItemTotal = (item) => {
        return item.precio * item.quantity;
    };

    loadCart();

    return {
        cart,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
        cartTotal,
        cartCount,
        getItemTotal
    };
}