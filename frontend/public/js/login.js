document.addEventListener('DOMContentLoaded', function() {
  // Elementos del DOM
  const form = document.getElementById('form');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const togglePassword = document.getElementById('togglePassword');
  const toggleCircle = document.getElementById('toggleCircle');
  const submitButton = form.querySelector('button[type="submit"]');
  const preloader = document.getElementById('preloader');
  const message = document.getElementById('message');
  const messageText = document.getElementById('message-text');
  const closeMessage = document.getElementById('close-message');

  // Estado para controlar visibilidad de contraseña
  let passwordVisible = false;

  // =============================================
  // TOGGLE PARA MOSTRAR/OCULTAR CONTRASEÑA
  // =============================================
  togglePassword.addEventListener('click', function(e) {
    e.preventDefault();
    passwordVisible = !passwordVisible;
    
    if (passwordVisible) {
      passwordInput.type = 'text';
      togglePassword.classList.remove('bg-gray-300');
      togglePassword.classList.add('bg-blue-600');
      toggleCircle.style.transform = 'translateX(26px)';
    } else {
      passwordInput.type = 'password';
      togglePassword.classList.remove('bg-blue-600');
      togglePassword.classList.add('bg-gray-300');
      toggleCircle.style.transform = 'translateX(0)';
    }
  });

  // =============================================
  // CERRAR MENSAJE DE ALERTA
  // =============================================
  closeMessage.addEventListener('click', function() {
    hideMessage();
  });

  // =============================================
  // VALIDACIÓN Y ENVÍO DEL FORMULARIO
  // =============================================
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Validar campos
    if (!emailInput.value || !passwordInput.value) {
      showMessage('Por favor completa todos los campos', 'error');
      return;
    }
    
    // Validar formato de email
    if (!validateEmail(emailInput.value)) {
      showMessage('Por favor ingresa un correo electrónico válido', 'error');
      return;
    }
    
    // Deshabilitar botón y mostrar estado de carga
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Procesando...';
    
    // Mostrar preloader
    preloader.classList.remove('opacity-0', 'pointer-events-none');

    try {
      // Simular llamada a API (reemplazar con tu endpoint real)
      const response = await fetch('http://api.enersolar.com/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: emailInput.value,
          password: passwordInput.value
        })
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Error al iniciar sesión');
      }

      // Mostrar mensaje de éxito
      showMessage('Inicio de sesión exitoso. Redirigiendo...', 'success');
      
      // Redirigir después de 1.5 segundos
      setTimeout(() => {
        window.location.href = 'http://dashboard.enersolar.com/';
      }, 1500);

    } catch (error) {
      console.error('Error:', error);
      showMessage(error.message || 'Hubo un error al conectar con el servidor', 'error');
      
      // Re-enable button
      submitButton.disabled = false;
      submitButton.textContent = 'Iniciar sesión';
      
      // Ocultar preloader
      preloader.classList.add('opacity-0', 'pointer-events-none');
    }
  });

  // =============================================
  // FUNCIONES AUXILIARES
  // =============================================
  
  // Validar formato de email
  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  // Mostrar mensajes
  function showMessage(text, type = 'error') {
    messageText.textContent = text;
    
    // Resetear clases
    message.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-50 px-6 py-3 flex items-center rounded shadow-lg text-base font-medium transition-opacity duration-500';
    
    // Añadir clases de visibilidad
    message.classList.remove('opacity-0', 'pointer-events-none');
    
    // Añadir clases según tipo
    if (type === 'success') {
      message.classList.add('bg-green-100', 'text-green-700');
    } else {
      message.classList.add('bg-red-100', 'text-red-700');
    }
    
    // Ocultar automáticamente
    const timeoutDuration = type === 'success' ? 1500 : 4000;
    setTimeout(hideMessage, timeoutDuration);
  }
  
  // Ocultar mensaje
  function hideMessage() {
    message.classList.add('opacity-0', 'pointer-events-none');
  }
});