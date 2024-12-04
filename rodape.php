<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rodapé</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos gerais */
        body {
            padding-bottom: 100px; /* Espaço para o rodapé fixo */
            margin: 0;
            font-family: Arial, sans-serif;
        }

        #footer {
            background-color: #343a40;
            color: white;
            font-size: 18px;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            z-index: 9999;
            transition: opacity 0.5s;
        }

        #footer .social-icons {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        #footer .social-icons a {
            color: white;
            font-size: 30px;
            margin-right: 15px;
        }

        #footer .social-icons a:hover {
            color: #007bff; /* Mudando a cor ao passar o mouse */
        }

        #footer .privacy-link {
            font-size: 18px;
            text-decoration: none;
            color: white;
            margin-top: 10px;
        }

        #footer .privacy-link:hover {
            color: #007bff;
        }

        #footer p {
            font-size: 16px;
            margin-top: 10px;
        }

        /* Ajustes para telas menores */
        @media (max-width: 768px) {
            #footer .social-icons a {
                font-size: 25px; /* Reduzir o tamanho dos ícones para dispositivos menores */
                margin-right: 10px; /* Menor distância entre os ícones */
            }

            #footer .privacy-link {
                font-size: 14px; /* Reduzir o tamanho do texto */
            }

            #footer p {
                font-size: 14px; /* Reduzir o tamanho do texto */
            }
        }
    </style>
</head>
<body>
    <!-- Rodapé -->
    <footer id="footer">
        <div class="social-icons">
            <a href="https://www.facebook.com" target="_blank">
                <i class="fab fa-facebook-f"></i> <!-- Ícone do Facebook -->
            </a>
            <a href="https://twitter.com" target="_blank">
                <i class="fab fa-twitter"></i> <!-- Ícone do Twitter -->
            </a>
            <a href="https://www.instagram.com/marciel_rossetto" target="_blank">
                <i class="fab fa-instagram"></i> <!-- Ícone do Instagram -->
            </a>
            <a href="https://www.linkedin.com/in/marciel-rossetto-383b182b3/" target="_blank">
                <i class="fab fa-linkedin-in"></i> <!-- Ícone do LinkedIn -->
            </a>
        </div>
        
        <div>
            <a href="politica_de_privacidade.php" class="privacy-link">Política de Privacidade</a>
        </div>

        <div>
            <p>&copy; <?php echo date("Y"); ?> RossetoTi. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Função para esconder o rodapé após alguns segundos
        setTimeout(function() {
            document.getElementById('footer').style.opacity = '0';
        }, 5000); // 5000ms = 5 segundos

        // Detectando quando o usuário rola a página para cima
        let lastScrollTop = 0;
        window.addEventListener('scroll', function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll < lastScrollTop) {
                // Quando o usuário sobe a página, mostra o rodapé
                document.getElementById('footer').style.opacity = '1';
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        });
    </script>
</body>
</html>
