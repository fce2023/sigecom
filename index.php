<style>
    #carga {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: 'Lato', sans-serif;
    }
    #carga .progress-bar {
        width: 250px;
        height: 25px;
        border-radius: 15px;
        background-color: #333;
        overflow: hidden;
        position: relative;
    }
    #carga .progress-bar .barra-progreso {
        width: 100%;
        height: 100%;
        background-color: #76c7c0;
        position: absolute;
        top: 0;
        left: 0;
        animation: progreso 2s linear forwards;
    }
    #carga .contador {
        color: #76c7c0;
        font-size: 26px;
        margin-top: 25px;
        text-shadow: 2px 2px 4px #000;
    }
    @keyframes progreso {
        0% { width: 0%; }
        100% { width: 100%; }
    }
</style>

<div id="carga">
    <div class="progress-bar">
        <div class="barra-progreso"></div>
    </div>
    <div class="contador">Cargando sistema SIGECOM...</div>
</div>

<script>
    setTimeout(function(){
        window.location.href = "inicio.php";
    }, 2000);
</script>

