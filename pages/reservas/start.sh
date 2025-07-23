#!/bin/bash
# Inicia o Flask (app.py) em background
python3 app.py &
FLASK_PID=$!

# Inicia o servidor PHP embutido
php -S 127.0.0.1:8080

# Quando o PHP parar, mata o Flask
kill $FLASK_PID
