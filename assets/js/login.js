

(function() {
  
  
   

    const preloader = document.querySelector('#preloader');
    if (preloader) {
      window.addEventListener('load', () => {
        preloader.remove();
      });
    }
  
   
  
  
    
  
  
  
   
  
  })();

  function entrar() {
    const email = document.getElementById('typeEmailX-2').value;
    const password = document.getElementById('typePasswordX-2').value;

    if (email === "aluno@gmail.com" && password === "12345678") {
      window.location.href = "portal_do_aluno/index.html";
    } else if (email === "admin@gmail.com" && password === "12345678") {
      window.location.href = "home.html";
    } else {
      alert("Email ou palavra passe incorreta. Tente novamente.");
    }
  }