<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test Vue.js</title>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>
  <div id="app">
    <h1>@{{ message }}</h1>
    <button @click="count++">Count: @{{ count }}</button>
  </div>

  <script>
    const { createApp, ref } = Vue;
    
    createApp({
      setup() {
        const message = ref('Vue.js berfungsi!');
        const count = ref(0);
        
        return {
          message,
          count
        };
      }
    }).mount('#app');
  </script>
</body>
</html>
