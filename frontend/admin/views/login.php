<?php include 'includes/header.php' ?>

<div class="grid grid-cols-1 md:grid-cols-2 h-full text-center relative">

  <!-- ============================================= -->
  <!--PANEL IZQUIERDO (FONDO)-->
  <!-- ============================================= -->
  <div class="flex items-center flex-col gap-4 justify-center text-white text-center px-6 container">
    <h2 class="text-3xl md:text-4xl font-bold uppercase">
      Panel Administrativo <strong class="text-600">Enersolar</strong>
    </h2>
    <p class="text-white text-lg md:text-xl">
      Administra y controla tus productos, gestiona contenidos y mantén tu plataforma siempre actualizada.
    </p>
  </div>

  <!-- ============================================= -->
  <!--PANEL DERECHO (FORMULARIO) -->
  <!-- ============================================= -->
  <div class="flex items-center justify-center bg-gray-200 w-full container__form">
    <form id="form" class="p-8 rounded-xl  w-full bg-white max-w-md form">
      <h2 class="text-2xl font-semibold mb-9 text-gray-500 md:text-xl">Ingresa con tu correo y contraseña:</h2>

      <!-- ============================================= -->
      <!--CORREO ELECTRONICO-->
      <!-- ============================================= -->
      <div class="mb-7">
        <label class="block text-left text-gray-700 mb-2" for="email" aria-label="Correo electrónico">Correo electrónico:</label>
        <div class="relative w-full">
          <i class="fa-solid fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input type="email" id="email" name="email"
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Introduce tu correo electrónico">
        </div>
      </div>

      <!-- ============================================= -->
      <!-- CONTRASEÑA -->
      <!-- ============================================= -->
      <div class="mb-7">
        <label class="block text-left text-gray-700 mb-2" for="password">Contraseña:</label>
        <div class="relative w-full">
          <i class="fa-solid fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
          <input type="password" id="password" name="password"
            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Introduce tu contraseña">
        </div>
      </div>

      <!-- ============================================= -->
      <!-- MOSTRAR CONTRASEÑA -->
      <!-- ============================================= -->
      <div class="mb-7 flex items-center gap-3">
        <button id="togglePassword"
          class="relative flex items-center cursor-pointer  w-11 h-5 bg-gray-300 rounded-full p-1 transition-colors duration-300">
          <div id="toggleCircle"
            class="absolute left-1 w-3 h-3 bg-white rounded-full transition-transform duration-300">
          </div>
        </button>
        <p>Mostar Contraseña</p>
      </div>

      <!-- ============================================= -->
      <!-- INICIAR SESION -->
      <!-- ============================================= -->
     <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg cursor-pointer hover:bg-blue-700 transition duration-300">
        Iniciar sesión
      </button>
     </div>
    </form>

  <!-- ============================================= -->
  <!--PRELOADER -->
  <!-- ============================================= -->
  <div id="preloader" class="absolute inset-0 bg-white bg-opacity-75 z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="w-12 h-12 border-4 border-blue-500 border-dashed rounded-full animate-spin"></div>
  </div>
</div>

<!-- ============================================= -->
<!-- ALERTAS -->
<!-- ============================================= -->
<div id="message" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 px-6 py-3 flex items-center rounded shadow-lg text-base font-medium transition-opacity duration-500 opacity-0 pointer-events-none">
  <div class="flex items-center justify-between gap-4">
    <span id="message-text"></span>
    <button id="close-message" class="text--300 cursor-pointer hover:text-gray-700 focus:outline-none">
      <i class="fa fa-close"></i>
    </button>
  </div>
</div>

<?php include 'includes/footer.php' ?>