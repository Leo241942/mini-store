document.addEventListener('DOMContentLoaded', () => {
    const loginTab = document.getElementById('loginTab');
    const signupTab = document.getElementById('signupTab');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

    // Функция для переключения вкладок
    function switchTab(activeTab, inactiveTab, activeForm, inactiveForm) {
        activeTab.classList.add('active');
        inactiveTab.classList.remove('active');
        activeForm.classList.add('show');
        inactiveForm.classList.remove('show');
    }

    // Слушаем события на кнопки
    loginTab.addEventListener('click', () => {
        switchTab(loginTab, signupTab, loginForm, signupForm);
    });

    signupTab.addEventListener('click', () => {
        switchTab(signupTab, loginTab, signupForm, loginForm);
    });

    // Начальная установка: показываем форму "Log In"
    switchTab(loginTab, signupTab, loginForm, signupForm);
});




document.addEventListener('DOMContentLoaded', () => {
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const fileStatus = document.getElementById('fileStatus');
    const fileText = document.getElementById('fileText');
    const fileIcon = document.getElementById('fileIcon');

    // Обработчик перетаскивания файлов
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault(); // Разрешаем перетаскивание
        dropArea.classList.add('dragover'); // Стиль при перетаскивании
    });

    dropArea.addEventListener('dragenter', (e) => {
        e.preventDefault();
        dropArea.classList.add('dragover'); // Стиль при заходе файла в область
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('dragover'); // Убираем стиль, когда файл уходит
    });

    // Обработчик события drop (перетаскивание)
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault(); // Отменяем стандартное поведение
        dropArea.classList.remove('dragover'); // Убираем стиль после сброса

        const files = e.dataTransfer.files; // Получаем перетащенные файлы
        if (files.length > 0) {
            fileInput.files = files;  // Передаем файлы в скрытый input
            showFileStatus(); // Показываем анимацию галочки и изменяем текст
        }
    });

    // Включаем выбор файла при клике на область перетаскивания
    dropArea.addEventListener('click', () => {
        fileInput.click(); // Открываем стандартный диалог выбора файлов
    });

    // Обработчик выбора файла через диалоговое окно
    fileInput.addEventListener('change', () => {
        const selectedFile = fileInput.files[0]; // Получаем выбранный файл
        if (selectedFile) {
            showFileStatus(); // Показываем анимацию галочки и изменяем текст
        }
    });

    // Функция для отображения галочки и изменения текста
    function showFileStatus() {
        // Меняем текст
        fileText.textContent = 'Файл загружен!';

        // Показываем анимацию рисования галочки
        fileIcon.classList.add('show');
    }
});
