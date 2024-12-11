// слайдер
document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".slide");
    const sliderContainer = document.querySelector(".sliders");
    const currentSlideContainer = document.querySelector(".current_slide");
    let currentIndex = 0;
    let autoSlideInterval;
    let autoSliding = true; // Флаг для автопереключения слайдов

    // Функция для смены слайда
    function changeSlide(index) {
        const newImage = slides[index].querySelector("img").src;

        // Создаём новый тег <img> для текущего слайда
        const currentSlideImg = document.createElement("img");
        currentSlideImg.src = newImage;
        currentSlideImg.alt = "Current Image";

        // Очищаем текущий контейнер и добавляем новое изображение без анимации
        currentSlideContainer.innerHTML = ''; // Очищаем текущий контейнер
        currentSlideContainer.appendChild(currentSlideImg);
    }

    // Функция для перемещения слайдов при клике
    function moveSlides(index) {
        const slideHeight = slides[0].offsetHeight; // Получаем высоту одного слайда
        const offset = index * slideHeight; // Рассчитываем, на сколько сдвигать контейнер

        sliderContainer.style.transition = "transform 0.5s ease"; // Добавляем плавную анимацию
        sliderContainer.style.transform = `translateY(-${offset}px)`; // Сдвигаем контейнер
    }

    // Обработчик клика на мини-слайд
    slides.forEach((slide, index) => {
        slide.addEventListener("click", () => {
            changeSlide(index);
            moveSlides(index); // Сдвигаем слайды так, чтобы выбранный стал первым
            autoSliding = false; // Останавливаем автопрокрутку
            resetAutoSlide(); // Перезапускаем автопрокрутку, если она будет включена позже
        });
    });

    // Функция для автоматической прокрутки слайдов с цикличностью
    function startAutoSlide() {
        if (autoSliding) {
            autoSlideInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % slides.length;  // Переход к следующему слайду с цикличностью
                changeSlide(currentIndex);
                moveSlides(currentIndex); // Перемещаем слайды
            }, 3000);  // Переключение каждые 3 секунды
        }
    }

    // Останавливаем автопрокрутку и перезапускаем
    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        startAutoSlide();
    }

    // Запуск автопрокрутки
    startAutoSlide();

    // Устанавливаем первый слайд как текущий сразу после загрузки страницы
    changeSlide(0);  // Подставляем первый слайд
    moveSlides(0);   // Перемещаем контейнер слайдов на первый слайд

    // Прокрутка слайдов с помощью колесика мыши или свайп на мобильном устройстве
    let startTouchY = 0;

    // Для мобильных устройств (свайпы)
    sliderContainer.addEventListener("touchstart", function (event) {
        startTouchY = event.touches[0].clientY;
    });

    sliderContainer.addEventListener("touchmove", function (event) {
        const touchMoveY = event.touches[0].clientY;
        const moveDistance = startTouchY - touchMoveY;

        if (Math.abs(moveDistance) > 50) {
            if (moveDistance > 0) {
                // Прокрутка вниз
                // Добавляем первый слайд в конец
                sliderContainer.appendChild(slides[0]);

                // Сдвигаем контейнер
                currentIndex = (currentIndex + 1) % slides.length; // Зацикливаем
                changeSlide(currentIndex);
                moveSlides(currentIndex);

                // Отключаем анимацию на момент перемещения
                slides[0].style.transition = "none"; 
            } else {
                // Прокрутка вверх
                // Перемещаем последний слайд в начало
                sliderContainer.insertBefore(slides[slides.length - 1], slides[0]);

                // Сдвигаем контейнер
                currentIndex = (currentIndex - 1 + slides.length) % slides.length; // Зацикливаем
                changeSlide(currentIndex);
                moveSlides(currentIndex);

                // Отключаем анимацию на момент перемещения
                slides[slides.length - 1].style.transition = "none"; 
            }

            startTouchY = touchMoveY; // Обновляем начальную точку
        }
    });

    // Для десктопа (колесико мыши)
    sliderContainer.addEventListener("wheel", function (event) {
        if (event.deltaY > 0) {
            // Прокрутка вниз
            // Добавляем первый слайд в конец
            sliderContainer.appendChild(slides[0]);

            // Сдвигаем контейнер
            currentIndex = (currentIndex + 1) % slides.length; // Зацикливаем
            changeSlide(currentIndex);
            moveSlides(currentIndex);

            // Отключаем анимацию на момент перемещения
            slides[0].style.transition = "none"; 
        } else {
            // Прокрутка вверх
            // Перемещаем последний слайд в начало
            sliderContainer.insertBefore(slides[slides.length - 1], slides[0]);

            // Сдвигаем контейнер
            currentIndex = (currentIndex - 1 + slides.length) % slides.length; // Зацикливаем
            changeSlide(currentIndex);
            moveSlides(currentIndex);

            // Отключаем анимацию на момент перемещения
            slides[slides.length - 1].style.transition = "none"; 
        }
    });
});



// кнопки + и - в контролах товара 
document.addEventListener("DOMContentLoaded", function() {
    const increaseButton = document.querySelector('.increase');
    const decreaseButton = document.querySelector('.decrease');
    const inputField = document.getElementById('quantity_product');
    
    // Получаем минимальное и максимальное значение
    const minValue = parseInt(inputField.min);
    const maxValue = parseInt(inputField.max);
    
    // Увеличение значения
    increaseButton.addEventListener('click', function() {
        let currentValue = parseInt(inputField.value);
        if (currentValue < maxValue) {
            inputField.value = currentValue + 1;
        }
    });
    
    // Уменьшение значения
    decreaseButton.addEventListener('click', function() {
        let currentValue = parseInt(inputField.value);
        if (currentValue > minValue) {
            inputField.value = currentValue - 1;
        }
    });
});


// переключение кнопок
// Функция для добавления активности в соответствующие группы
function setActiveButtons(selector, buttonClass) {
    // Получаем все контейнеры для кнопок, относящихся к группе
    const controlElements = document.querySelectorAll(selector);

    controlElements.forEach(container => {
        // Находим все кнопки внутри текущего контейнера
        const buttons = container.querySelectorAll(buttonClass);

        buttons.forEach(button => {
            // Добавляем обработчик клика на каждую кнопку
            button.addEventListener('click', function() {
                // Убираем класс 'active' у всех кнопок внутри контейнера
                buttons.forEach(btn => btn.classList.remove('active'));

                // Добавляем класс 'active' к текущей кнопке, если она не имеет класс 'disenable'
                if (!button.classList.contains('disenable')) {
                    button.classList.add('active');
                }
            });
        });
    });
}

// Для группы цветов
setActiveButtons('.control-element.color .controls-container', '.color-btn');

// Для группы размеров
setActiveButtons('.control-element.size .controls-container', '.size-btn');




  function restrictInputValue() {
    const inputs = document.querySelectorAll('input[type="number"]'); // находим все инпуты с типом number
  
    inputs.forEach(input => {
      input.addEventListener('input', function () {
        const min = parseFloat(input.min); // получаем минимальное значение
        const max = parseFloat(input.max); // получаем максимальное значение
  
        let value = parseFloat(input.value); // текущий ввод пользователя
  
        // Проверяем, если значение меньше минимального, то устанавливаем минимальное значение
        if (value < min) {
          input.value = min;
        }
        // Проверяем, если значение больше максимального, то устанавливаем максимальное значение
        else if (value > max) {
          input.value = max;
        }
      });
    });
  }
  
  // Вызываем функцию после загрузки страницы, чтобы она работала с уже существующими инпутами
  document.addEventListener('DOMContentLoaded', restrictInputValue);
  
  document.addEventListener("DOMContentLoaded", function() {
    let selectedColorId = '';
    let selectedSizeId = '';

    // Выбираем первый доступный цвет и устанавливаем его как активный
    const colorButtons = document.querySelectorAll('.color-btn');
    if (colorButtons.length > 0) {
        selectedColorId = colorButtons[0].getAttribute('data-color-id');
        colorButtons[0].classList.add('active'); // Делаем кнопку активной
        document.getElementById('selected_color').value = selectedColorId;
    }

    // Выбираем первый доступный размер и устанавливаем его как активный
    const sizeButtons = document.querySelectorAll('.size-btn');
    if (sizeButtons.length > 0) {
        selectedSizeId = sizeButtons[0].getAttribute('data-size-id');
        sizeButtons[0].classList.add('active'); // Делаем кнопку активной
        document.getElementById('selected_size').value = selectedSizeId;
    }

    // Устанавливаем количество по умолчанию равным 1
    const quantityInput = document.getElementById('quantity_product');
    quantityInput.value = 1;


    // Обработчик выбора цвета
    colorButtons.forEach(button => {
        button.addEventListener('click', function() {
            selectedColorId = this.getAttribute('data-color-id'); // Получаем ID цвета
            document.getElementById('selected_color').value = selectedColorId; // Устанавливаем ID цвета в скрытое поле
            colorButtons.forEach(btn => btn.classList.remove('active')); // Убираем класс active у всех
            this.classList.add('active'); // Добавляем класс active текущей кнопке
        });
    });

    // Обработчик выбора размера
    sizeButtons.forEach(button => {
        button.addEventListener('click', function() {
            selectedSizeId = this.getAttribute('data-size-id'); // Получаем ID размера
            document.getElementById('selected_size').value = selectedSizeId; // Устанавливаем ID размера в скрытое поле
            sizeButtons.forEach(btn => btn.classList.remove('active')); // Убираем класс active у всех
            this.classList.add('active'); // Добавляем класс active текущей кнопке
        });
    });

    // Обработчик кнопки добавления в корзину
    const addToCartButton = document.getElementById('add_to_cart');
    addToCartButton.addEventListener('click', function() {
        // Получаем количество товара
        const quantity = quantityInput.value;
        document.getElementById('selected_quantity').value = quantity;

        // Подготовка данных для отправки на сервер
        const formData = new FormData();
        formData.append('product_id', document.querySelector('[name="product_id"]').value);
        formData.append('user_id', document.querySelector('[name="user_id"]').value);
        formData.append('color', selectedColorId); // Теперь передаем ID цвета
        formData.append('size', selectedSizeId); // Теперь передаем ID размера
        formData.append('quantity', quantity);

        // Отправка данных с использованием fetch
        fetch('../php/template_product/process_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Ожидаем, что сервер вернет JSON
        .then(data => {
            if (data.success) {
                alert('Товар добавлен в корзину!');
            } else {
                alert('Не удалось добавить товар в корзину.');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при добавлении товара в корзину.');
        });
    });
});
