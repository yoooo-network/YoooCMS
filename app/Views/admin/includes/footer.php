        </div>
    </div>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (!sidebar || !overlay) return;
    const isClosed = sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !isClosed);
    overlay.classList.toggle('hidden', !isClosed);
    document.body.classList.toggle('overflow-hidden', isClosed);
}
</script>
</body>
</html>
