    </main>
    <footer class="site-footer">
        <p>&copy; 2024 Simple Blog</p>
    </footer>
    <script>
        document.querySelectorAll('[data-confirm]').forEach((link) => {
            link.addEventListener('click', (event) => {
                if (!confirm(link.dataset.confirm)) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
