
// cart open\close 
document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cart-icon');
    const cart_open = document.getElementById('cart_open'); // Исправлено
    const blackout = document.getElementById('blackout');

    // Открытие корзины
    cartIcon.addEventListener('click', (e) => {
        e.preventDefault();
        cart_open.classList.add('active');
        blackout.classList.add('active');
    });

    // Закрытие корзины при клике на затемнение
    blackout.addEventListener('click', () => {
        cart_open.classList.remove('active');
        blackout.classList.remove('active');
    });

    // Закрытие корзины при клике вне её области
    document.addEventListener('click', (e) => {
        if (!cart_open.contains(e.target) && !cartIcon.contains(e.target)) {
            cart_open.classList.remove('active');
            blackout.classList.remove('active');
        }
    });
});

function reloadBlock(url, blockId) {
    // Делаем запрос на сервер для получения HTML
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка загрузки: ' + response.statusText);
            }
            return response.text();
        })
        .then(html => {
            // Вставляем полученный HTML в указанный блок
            document.getElementById(blockId).innerHTML = html;
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
}