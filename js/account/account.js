document.addEventListener('DOMContentLoaded', () => {
    // Переменные для переключения вкладок
    const loginTab = document.getElementById('loginTab');
    const signupTab = document.getElementById('signupTab');
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');

    // Функция для переключения вкладок
    function switchTab(activeTab, inactiveTab, activeForm, inactiveForm) {
        activeTab.classList.add('active');
        inactiveTab.classList.remove('active');
        activeForm.style.display = 'flex';
        inactiveForm.style.display = 'none';
    }

    // Слушаем события на кнопки
    loginTab.addEventListener('click', () => {
        switchTab(loginTab, signupTab, loginForm, signupForm);
    });

    signupTab.addEventListener('click', () => {
        switchTab(signupTab, loginTab, signupForm, loginForm);
    });

    // Устанавливаем начальную вкладку
    switchTab(loginTab, signupTab, loginForm, signupForm);

    // Переменные для загрузки файлов
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const fileStatus = document.getElementById('fileStatus');
    const fileText = document.getElementById('fileText');
    const fileIcon = document.getElementById('fileIcon');

    // Обработчик перетаскивания файлов
    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });

    dropArea.addEventListener('dragenter', (e) => {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('dragover');
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showFileStatus();
        }
    });

    dropArea.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) {
            showFileStatus();
        }
    });

    function showFileStatus() {
        fileText.textContent = 'Файл загружен!';
        fileIcon.classList.add('show');
    }

    // Обработка отправки форм
    const handleFormSubmit = async (form) => {
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
            });
            const result = await response.json();

            alert(result.message);
            if (result.success) {
                form.reset();
            }
        } catch (error) {
            console.error('Ошибка при отправке данных:', error);
            alert('Произошла ошибка, попробуйте позже.');
        }
    };

    loginForm.addEventListener('submit', (event) => {
        event.preventDefault();
        handleFormSubmit(loginForm);
    });

    signupForm.addEventListener('submit', (event) => {
        event.preventDefault();
        handleFormSubmit(signupForm);
    });
});
