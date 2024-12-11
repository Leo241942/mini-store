const addToCartButton = document.getElementById('add_to_cart');
const quantityInput = document.getElementById('quantity_product');

let selectedColor = null;
let selectedSize = null;

// Обработчики для выбора цвета и размера
const colorButtons = document.querySelectorAll('.color-btn');
colorButtons.forEach(button => {
    button.addEventListener('click', function() {
        selectedColor = button.textContent;
        document.getElementById('selected_color').value = selectedColor;
    });
});

const sizeButtons = document.querySelectorAll('.size-btn');
sizeButtons.forEach(button => {
    button.addEventListener('click', function() {
        selectedSize = button.textContent;
        document.getElementById('selected_size').value = selectedSize;
    });
});

// Обработчик для добавления в корзину
addToCartButton.addEventListener('click', function() {
    const productId = document.getElementById('product_id').value;
    const userId = document.getElementById('user_id').value;
    const quantity = quantityInput.value;
    const color = selectedColor;
    const size = selectedSize;

    // Проверка на выбор цвета и размера
    if (!color || !size) {
        alert('Please select both color and size.');
        return;
    }

    // Отправка данных в обработчик через fetch
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            user_id: userId,
            quantity: quantity,
            color: color,
            size: size
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Успешное добавление в корзину
            alert('Product added to cart successfully!');
        } else {
            // Ошибка при добавлении
            alert('Failed to add product to cart: ' + (data.message || 'Unknown error.'));
        }
    })
    .catch(error => {
        // Ошибка соединения или другой сбой
        console.error('Error:', error);
        alert('There was an error adding the product to the cart. Please try again.');
    });
});