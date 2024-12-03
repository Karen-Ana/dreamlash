<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetraedro 3D Interactivo</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #4c77ff, #6e34e8);
            overflow: hidden;
            perspective: 1000px; /* Da profundidad a la escena */
        }

        .tetrahedron-container {
            position: relative;
            width: 200px;
            height: 200px;
            transform-style: preserve-3d;
            animation: spin 10s linear infinite; /* Rotación automática */
        }

        /* Animación para una rotación suave */
        @keyframes spin {
            0% { transform: rotateX(0deg) rotateY(0deg); }
            100% { transform: rotateX(360deg) rotateY(360deg); }
        }

        /* Cada cara del tetraedro */
        .face {
            position: absolute;
            width: 0;
            height: 0;
            border-style: solid;
            transform-origin: center;
            cursor: pointer;
        }

        /* Caras con bordes para formar el tetraedro */
        .front {
            border-width: 0 100px 173px 100px;
            border-color: transparent transparent rgba(255, 255, 255, 0.8) transparent;
            transform: rotateY(0deg) rotateX(-60deg) translateZ(86px);
        }

        .right {
            border-width: 0 100px 173px 100px;
            border-color: transparent transparent rgba(255, 200, 200, 0.8) transparent;
            transform: rotateY(120deg) rotateX(-60deg) translateZ(86px);
        }

        .left {
            border-width: 0 100px 173px 100px;
            border-color: transparent transparent rgba(200, 255, 200, 0.8) transparent;
            transform: rotateY(-120deg) rotateX(-60deg) translateZ(86px);
        }

        .bottom {
            border-width: 173px 100px 0 100px;
            border-color: rgba(200, 200, 255, 0.8) transparent transparent transparent;
            transform: rotateX(60deg) translateY(86px);
        }

        /* Caja de datos */
        .info-box {
            margin-top: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            display: none; /* Oculto por defecto */
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        .info-box.active {
            display: block; /* Mostrar cuando esté activo */
        }
    </style>
</head>
<body>
    <div class="tetrahedron-container" id="tetrahedron">
        <div class="face front" data-info="Datos de la cara frontal: Esta es la cara principal."></div>
        <div class="face right" data-info="Datos de la cara derecha: Es un lado lateral."></div>
        <div class="face left" data-info="Datos de la cara izquierda: Es el otro lado lateral."></div>
        <div class="face bottom" data-info="Datos de la cara inferior: Es la base del tetraedro."></div>
    </div>
    <div class="info-box" id="infoBox">Haz clic en una cara para ver sus datos.</div>

    <script>
        const tetrahedron = document.getElementById("tetrahedron");
        const infoBox = document.getElementById("infoBox");

        // Rotar el tetraedro con el mouse
        let isSpinning = true; // Control de animación automática
        document.addEventListener("mousemove", (e) => {
            const x = (window.innerWidth / 2 - e.clientX) / 10;
            const y = (window.innerHeight / 2 - e.clientY) / 10;
            if (!isSpinning) {
                tetrahedron.style.transform = `rotateX(${y}deg) rotateY(${-x}deg)`;
            }
        });

        // Detener la animación automática al interactuar
        tetrahedron.addEventListener("mouseenter", () => {
            isSpinning = false;
            tetrahedron.style.animation = "none";
        });

        tetrahedron.addEventListener("mouseleave", () => {
            isSpinning = true;
            tetrahedron.style.animation = "spin 10s linear infinite";
        });

        // Mostrar datos al hacer clic en una cara
        const faces = document.querySelectorAll(".face");
        faces.forEach(face => {
            face.addEventListener("click", () => {
                const info = face.getAttribute("data-info");
                infoBox.textContent = info; // Cambiar el texto de la caja de información
                infoBox.classList.add("active"); // Mostrar la caja de información
            });
        });
    </script>
</body>
</html>
