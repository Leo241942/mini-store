// слайдер
document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll(".slide");
    const sliderContainer = document.querySelector(".sliders");
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
        document.querySelector(".current_slide").innerHTML = ''; // Очищаем текущий контейнер
        document.querySelector(".current_slide").appendChild(currentSlideImg);
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

    // Функция для автоматической прокрутки слайдов
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
        if (startTouchY - touchMoveY > 50) {
            // Вниз
            sliderContainer.scrollBy({ top: 150, behavior: 'smooth' });
            startTouchY = touchMoveY; // Обновляем начальную точку
        } else if (touchMoveY - startTouchY > 50) {
            // Вверх
            sliderContainer.scrollBy({ top: -150, behavior: 'smooth' });
            startTouchY = touchMoveY; // Обновляем начальную точку
        }
    });

    // Для десктопа (колесико мыши)
    sliderContainer.addEventListener("wheel", function (event) {
        if (event.deltaY > 0) {
            // Прокрутка вниз
            sliderContainer.scrollBy({ top: 150, behavior: 'smooth' });
        } else {
            // Прокрутка вверх
            sliderContainer.scrollBy({ top: -150, behavior: 'smooth' });
        }
    });
});


// кнопки + и - в контролах товара 
document.querySelector('.increase').addEventListener('click', function() {
    const input = document.querySelector('input[type="number"]');
    input.value = parseInt(input.value) + 1; // Увеличиваем на 1
});

document.querySelector('.decrease').addEventListener('click', function() {
    const input = document.querySelector('input[type="number"]');
    input.value = Math.max(0, parseInt(input.value) - 1); // Уменьшаем на 1, не даем значения ниже 0
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


document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.tabs_controls button');
    const tabs = document.querySelectorAll('.tab_content');
  
    buttons.forEach(button => {
      button.addEventListener('click', () => {
        // Удаляем активный класс со всех кнопок и вкладок
        buttons.forEach(btn => btn.classList.remove('active'));
        tabs.forEach(tab => tab.classList.remove('active'));
  
        // Добавляем активный класс к текущей кнопке и соответствующей вкладке
        button.classList.add('active');
        document.querySelector(`.tab_${button.dataset.tab}`).classList.add('active');
      });
    });
  });
  