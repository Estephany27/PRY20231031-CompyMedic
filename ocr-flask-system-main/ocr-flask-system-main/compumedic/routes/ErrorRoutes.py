from flask import render_template

# Define las funciones manejadoras de error
def not_found(error):
    return render_template('error/error_404.jinja2'), 404

def method_not_allowed(error):
    return render_template('error/error_405.jinja2'), 405

def internal_server_error(error):
    return render_template('error/error_500.jinja2'), 500
