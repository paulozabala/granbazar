// Variables globales
let cartItems = [];

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    initializeSideMenu();
    initializeIcons();
    initializeCategoryButtons();
});

// Inicializar el menú lateral
function initializeSideMenu() {
    const menuToggle = document.getElementById('menu-toggle');
    const sideMenu = document.getElementById('side-menu');
    const overlay = document.getElementById('overlay');
    
    menuToggle.addEventListener('click', function() {
        sideMenu.classList.toggle('active');
        overlay.classList.toggle('active');
    });
    
    overlay.addEventListener('click', function() {
        sideMenu.classList.remove('active');
        overlay.classList.remove('active');
    });
    
    // Menu items
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            sideMenu.classList.remove('active');
            overlay.classList.remove('active');
            showToast(`Categoría seleccionada: ${this.textContent.trim().split('\n')[0]}`);
        });
    });
}

// Inicializar iconos
function initializeIcons() {
    // Cart icon click
    document.getElementById('cart-icon').addEventListener('click', function() {
        if (cartItems.length === 0) {
            showToast('El carrito está vacío');
        } else {
            let total = 0;
            cartItems.forEach(item => {
                total += item.price;
            });
            showToast(`${cartItems.length} productos en el carrito. Total: $${total.toLocaleString()}`);
        }
    });
    
    // Notifications icon click
    document.getElementById('notifications-icon').addEventListener('click', function() {
        showToast('No tienes notificaciones nuevas');
    });
}

// Inicializar botones de categoría
function initializeCategoryButtons() {
    const categoryButtons = document.querySelectorAll('.category-button');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            showToast(`Categoría seleccionada: ${this.textContent}`);
        });
    });
}

// Función para agregar productos al carrito
function addToCart(productName, price, id) {
    cartItems.push({
        name: productName,
        price: price
    });
    
    showToast(`${productName} agregado al carrito`);
    updateCartIcon();
    window.open(
      `shoppingcart/addProduct.php?id=${id}`,     // URL
      'popup',                       // Nombre de la ventana
      'width=600,height=400,top=100,left=200,resizable=yes,scrollbars=yes' // Parámetros
    );

}

// Actualizar el icono del carrito
function updateCartIcon() {
    const cartIcon = document.getElementById('cart-icon');
    if (cartItems.length > 0) {
        cartIcon.style.position = 'relative';
        
        // Check if badge already exists
        let badge = cartIcon.querySelector('.badge');
        if (!badge) {
            badge = document.createElement('div');
            badge.className = 'badge';
            badge.style.position = 'absolute';
            badge.style.top = '-5px';
            badge.style.right = '-5px';
            badge.style.backgroundColor = '#FF5722';
            badge.style.color = 'white';
            badge.style.borderRadius = '50%';
            badge.style.width = '18px';
            badge.style.height = '18px';
            badge.style.display = 'flex';
            badge.style.justifyContent = 'center';
            badge.style.alignItems = 'center';
            badge.style.fontSize = '12px';
            cartIcon.appendChild(badge);
        }
        
        badge.textContent = cartItems.length;
    }
}

// Mostrar notificaciones toast
function showToast(message) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast show';
    
    setTimeout(() => {
        toast.className = 'toast';
    }, 3000);
}