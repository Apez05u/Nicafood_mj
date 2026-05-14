<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold"><i class="fas fa-utensils me-2"></i>NicaFood ERP</h5>
                <p class="small mb-0">Sistema de gestión integral para restaurantes.</p>
            </div>
            <div class="col-md-3">
                <h6 class="fw-bold">Enlaces</h6>
                <ul class="list-unstyled small">
                    <li><a href="/nicafood/index.php" class="text-white-50 text-decoration-none">Menú Público</a></li>
                    <li><a href="/nicafood/modules/dashboard/index.php" class="text-white-50 text-decoration-none">Panel Admin</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="fw-bold">Soporte</h6>
                <ul class="list-unstyled small">
                    <li><i class="fas fa-envelope me-1"></i> soporte@nicafood.ni</li>
                    <li><i class="fas fa-phone me-1"></i> +505 8888-9999</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary my-3">
        <p class="text-center small mb-0 text-white-50">
            &copy; <?= date('Y') ?> NicaFood ERP. Todos los derechos reservados.
        </p>
    </div>
</footer>

<!-- Bootstrap JS Bundle (Incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS Personalizado -->
 
<!-- <script src="/nicafood/assets/js/app.js"></script> -->

<!-- Toasts dinámicos -->
<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1100;"></div>

<script>
// Función global para mostrar notificaciones
function showToast(mensaje, tipo = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${tipo === 'error' ? 'danger' : 'success'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${tipo === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
                ${mensaje}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    container.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast, { delay: 4000 });
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => toast.remove());
}
</script>

</body>
</html>