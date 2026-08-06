1.

php /home/u598407524/domains/sunoyaar.com/public_html/midnightclub/artisan schedule:run >> /dev/null 2>&1

2. 
Add in htaccess.
# ----------------------------------------------------------------
# GZIP COMPRESSION (Optimize asset delivery)
# ----------------------------------------------------------------
<IfModule mod_deflate.c>
    # Compress HTML, CSS, JavaScript, Text, XML and fonts
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE font/woff
    AddOutputFilterByType DEFLATE font/woff2
</IfModule>

# ----------------------------------------------------------------
# 6. BROWSER CACHING (mod_expires)
# ----------------------------------------------------------------
<IfModule mod_expires.c>
    ExpiresActive On
    
    # Default expiry
    ExpiresDefault "access plus 1 month"

    # Cache CSS and JavaScript for 1 year
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType application/x-javascript "access plus 1 year"
    
    # Cache Fonts for 1 year
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
    
    # Cache Images and SVG for 1 year
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    
    # Do NOT cache HTML (ensures the browser always checks for new file versions)
    ExpiresByType text/html "access plus 0 seconds"
</IfModule>