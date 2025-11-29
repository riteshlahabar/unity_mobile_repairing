<script>
(function() {
  // Apply theme BEFORE app.js runs
  const theme = localStorage.getItem('theme') || 'light';
  if (theme === 'dark') document.body.classList.add('dark-mode');
  
  // Update HTML attributes for Bootstrap theme
  document.documentElement.setAttribute('data-bs-theme', theme);
  document.documentElement.setAttribute('data-startbar', theme);
  
  document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('light-dark-mode');
    if (!toggle) return;
    
    // Set icons
    toggle.querySelector('.dark-mode').style.display = theme === 'dark' ? 'none' : 'inline';
    toggle.querySelector('.light-mode').style.display = theme === 'dark' ? 'inline' : 'none';
    
    toggle.onclick = () => {
      const isDark = document.body.classList.contains('dark-mode');
      const newTheme = isDark ? 'light' : 'dark';
      
      // Toggle body class
      document.body.classList.toggle('dark-mode');
      
      // Update HTML attributes
      document.documentElement.setAttribute('data-bs-theme', newTheme);
      document.documentElement.setAttribute('data-startbar', newTheme);
      
      // Save & update icons
      localStorage.setItem('theme', newTheme);
      toggle.querySelector('.dark-mode').style.display = newTheme === 'dark' ? 'none' : 'inline';
      toggle.querySelector('.light-mode').style.display = newTheme === 'dark' ? 'inline' : 'none';
    };
  });
})();
</script>
